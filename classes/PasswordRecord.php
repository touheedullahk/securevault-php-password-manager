<?php
class PasswordRecord
{
    private PDO $database;
    private EncryptionService $encryption;

    public function __construct(PDO $database)
    {
        $this->database = $database;
        $this->encryption = new EncryptionService();
    }

    public function save(
        int $userId,
        string $serviceName,
        string $password,
        string $vaultKey
    ): string {
        $serviceName = trim($serviceName);

        if ($serviceName === '') {
            return 'Enter a website or program name.';
        }

        if (strlen($serviceName) > 60) {
            return 'Website or program name must be shorter than 60 characters.';
        }

        if ($password === '') {
            return 'Generate or enter a password first.';
        }

        $encryptedPassword = $this->encryption->encryptPassword($password, $vaultKey);

        $statement = $this->database->prepare(
            'INSERT INTO password_records (user_id, service_name, encrypted_password)
             VALUES (?, ?, ?)'
        );
        $statement->execute([$userId, $serviceName, $encryptedPassword]);

        return '';
    }

    public function findAllForUser(int $userId, string $vaultKey): array
    {
        $statement = $this->database->prepare(
            'SELECT id, service_name, encrypted_password, created_at
             FROM password_records
             WHERE user_id = ?
             ORDER BY created_at DESC'
        );
        $statement->execute([$userId]);
        $records = $statement->fetchAll();

        foreach ($records as &$record) {
            $record['password'] = $this->encryption->decryptPassword(
                $record['encrypted_password'],
                $vaultKey
            );
        }

        return $records;
    }

    public function remove(int $recordId, int $userId): void
    {
        $statement = $this->database->prepare(
            'DELETE FROM password_records WHERE id = ? AND user_id = ?'
        );
        $statement->execute([$recordId, $userId]);
    }
}
