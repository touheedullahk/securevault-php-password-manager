<?php
class PasswordStrength
{
    public function evaluate(string $password): array
    {
        $score = 0;
        $tips = [];

        if (strlen($password) >= 12) {
            $score++;
        } else {
            $tips[] = 'Use at least 12 characters.';
        }

        if (preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password)) {
            $score++;
        } else {
            $tips[] = 'Include both lowercase and uppercase letters.';
        }

        if (preg_match('/[0-9]/', $password)) {
            $score++;
        } else {
            $tips[] = 'Include at least one number.';
        }

        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            $score++;
        } else {
            $tips[] = 'Include at least one special character.';
        }

        if ($score <= 1) {
            $label = 'Weak';
            $cssClass = 'weak';
        } elseif ($score <= 3) {
            $label = 'Medium';
            $cssClass = 'medium';
        } else {
            $label = 'Strong';
            $cssClass = 'strong';
        }

        return [
            'label' => $label,
            'css_class' => $cssClass,
            'tips' => $tips
        ];
    }
}
