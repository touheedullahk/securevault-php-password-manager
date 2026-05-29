<?php
class User
{
    private PDO $database;
    private EncryptionService $encryption;

    public function __construct(PDO $database)
    {
        $this->database = $database;
        $this->encryption = new EncryptionService();
    }

    public function register(string $username, string $password): string
    {
        $username = trim($username);

        $check = $this->database->prepare('SELECT id FROM users WHERE username = ?');
        $check->execute([$username]);

        if ($check->fetch()) {
            return 'This username is already in use.';
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $vaultKey = $this->encryption->createVaultKey();
        $encryptedVaultKey = $this->encryption->encryptVaultKey($vaultKey, $password);

        $statement = $this->database->prepare(
            'INSERT INTO users (username, password_hash, encrypted_vault_key)
             VALUES (?, ?, ?)'
        );
        $statement->execute([$username, $passwordHash, $encryptedVaultKey]);

        return '';
    }

    public function login(string $username, string $password): bool
    {
        $statement = $this->database->prepare(
            'SELECT id, username, password_hash, encrypted_vault_key
             FROM users WHERE username = ?'
        );
        $statement->execute([trim($username)]);
        $user = $statement->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        /*
         * Compatibility for accounts made before encrypted storage was added:
         * the first successful login creates their permanent vault key.
         */
        if (empty($user['encrypted_vault_key'])) {
            $vaultKey = $this->encryption->createVaultKey();
            $encryptedVaultKey = $this->encryption->encryptVaultKey($vaultKey, $password);

            $update = $this->database->prepare(
                'UPDATE users SET encrypted_vault_key = ? WHERE id = ?'
            );
            $update->execute([$encryptedVaultKey, $user['id']]);
        } else {
            $vaultKey = $this->encryption->decryptVaultKey(
                $user['encrypted_vault_key'],
                $password
            );
        }

        if ($vaultKey === '') {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['vault_key'] = base64_encode($vaultKey);

        return true;
    }

    public function changePassword(
        int $userId,
        string $currentPassword,
        string $newPassword,
        string $confirmPassword
    ): string {
        if (strlen($newPassword) < 8) {
            return 'New password must contain at least 8 characters.';
        }

        if (!preg_match('/[A-Za-z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword)) {
            return 'New password must contain at least one letter and one number.';
        }

        if ($newPassword !== $confirmPassword) {
            return 'New passwords do not match.';
        }

        $statement = $this->database->prepare(
            'SELECT password_hash, encrypted_vault_key FROM users WHERE id = ?'
        );
        $statement->execute([$userId]);
        $user = $statement->fetch();

        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            return 'Current password is incorrect.';
        }

        $vaultKey = $this->encryption->decryptVaultKey(
            $user['encrypted_vault_key'],
            $currentPassword
        );

        if ($vaultKey === '') {
            return 'The vault key could not be unlocked.';
        }

        /*
         * The vault key does not change. Only its encrypted wrapper changes,
         * so existing stored passwords still work after changing login password.
         */
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $reEncryptedVaultKey = $this->encryption->encryptVaultKey($vaultKey, $newPassword);

        $update = $this->database->prepare(
            'UPDATE users SET password_hash = ?, encrypted_vault_key = ? WHERE id = ?'
        );
        $update->execute([$newPasswordHash, $reEncryptedVaultKey, $userId]);

        $_SESSION['vault_key'] = base64_encode($vaultKey);

        return '';
    }
}
