<?php
class PasswordGenerator
{
    private string $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    private string $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private string $numbers = '0123456789';
    private string $specialCharacters = '!@#$%&*?';

    public function generate(
        int $lowercaseQuantity,
        int $uppercaseQuantity,
        int $numberQuantity,
        int $specialQuantity
    ): string {
        $characters = [];

        $characters = array_merge($characters, $this->selectCharacters($this->lowercase, $lowercaseQuantity));
        $characters = array_merge($characters, $this->selectCharacters($this->uppercase, $uppercaseQuantity));
        $characters = array_merge($characters, $this->selectCharacters($this->numbers, $numberQuantity));
        $characters = array_merge($characters, $this->selectCharacters($this->specialCharacters, $specialQuantity));

        $this->shuffleCharacters($characters);

        return implode('', $characters);
    }

    private function selectCharacters(string $group, int $quantity): array
    {
        $selected = [];
        $lastIndex = strlen($group) - 1;

        for ($counter = 0; $counter < $quantity; $counter++) {
            $selected[] = $group[random_int(0, $lastIndex)];
        }

        return $selected;
    }

    private function shuffleCharacters(array &$characters): void
    {
        for ($position = count($characters) - 1; $position > 0; $position--) {
            $randomPosition = random_int(0, $position);
            $temporary = $characters[$position];
            $characters[$position] = $characters[$randomPosition];
            $characters[$randomPosition] = $temporary;
        }
    }
}
