<?php
class PasswordGenerator
{
    private string $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    private string $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private string $numbers = '0123456789';
    private string $specialCharacters = '!@#$%&*?';

    public function generate(
        int $length,
        bool $useLowercase,
        bool $useUppercase,
        bool $useNumbers,
        bool $useSpecial
    ): string {
        if ($length < 6 || $length > 40) {
            return '';
        }

        $characters = '';

        if ($useLowercase) {
            $characters .= $this->lowercase;
        }

        if ($useUppercase) {
            $characters .= $this->uppercase;
        }

        if ($useNumbers) {
            $characters .= $this->numbers;
        }

        if ($useSpecial) {
            $characters .= $this->specialCharacters;
        }

        if ($characters === '') {
            return '';
        }

        $password = '';
        $lastIndex = strlen($characters) - 1;

        for ($position = 0; $position < $length; $position++) {
            $randomPosition = random_int(0, $lastIndex);
            $password .= $characters[$randomPosition];
        }

        return $password;
    }
}
