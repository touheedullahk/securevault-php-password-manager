<?php
class TemporaryPasswordList
{
    public function __construct()
    {
        if (!isset($_SESSION['temporary_passwords'])) {
            $_SESSION['temporary_passwords'] = [];
        }
    }

    public function add(string $serviceName, string $password): string
    {
        $serviceName = trim($serviceName);

        if ($serviceName === '') {
            return 'Enter a website or program name first.';
        }

        if (strlen($serviceName) > 60) {
            return 'Website or program name must be shorter than 60 characters.';
        }

        $_SESSION['temporary_passwords'][] = [
            'service_name' => $serviceName,
            'password' => $password,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return '';
    }

    public function getAll(): array
    {
        return array_reverse($_SESSION['temporary_passwords']);
    }

    public function remove(int $position): void
    {
        if (isset($_SESSION['temporary_passwords'][$position])) {
            unset($_SESSION['temporary_passwords'][$position]);
            $_SESSION['temporary_passwords'] = array_values($_SESSION['temporary_passwords']);
        }
    }

    public function clear(): void
    {
        $_SESSION['temporary_passwords'] = [];
    }

    public function count(): int
    {
        return count($_SESSION['temporary_passwords']);
    }
}
