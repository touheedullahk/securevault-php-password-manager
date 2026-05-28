<?php
class User
{
    private PDO $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
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

        $statement = $this->database->prepare(
            'INSERT INTO users (username, password_hash) VALUES (?, ?)'
        );
        $statement->execute([$username, $passwordHash]);

        return '';
    }

    public function login(string $username, string $password): bool
    {
        $statement = $this->database->prepare(
            'SELECT id, username, password_hash FROM users WHERE username = ?'
        );
        $statement->execute([trim($username)]);
        $user = $statement->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        return true;
    }
}
