<?php
class Validator
{
    public function validateRegistration(
        string $username,
        string $password,
        string $confirmPassword
    ): string {
        $username = trim($username);

        if (strlen($username) < 3 || strlen($username) > 30) {
            return 'Username must contain between 3 and 30 characters.';
        }

        if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
            return 'Username may contain only letters, numbers and underscore.';
        }

        if (strlen($password) < 8) {
            return 'Password must contain at least 8 characters.';
        }

        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            return 'Password must contain at least one letter and one number.';
        }

        if ($password !== $confirmPassword) {
            return 'Passwords do not match.';
        }

        return '';
    }
}
