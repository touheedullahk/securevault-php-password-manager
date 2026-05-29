<?php
class EncryptionService
{
    private string $method = 'AES-256-CBC';

    public function createVaultKey(): string
    {
        return random_bytes(32);
    }

    public function encryptVaultKey(string $vaultKey, string $loginPassword): string
    {
        $passwordKey = hash('sha256', $loginPassword, true);
        return $this->encrypt(base64_encode($vaultKey), $passwordKey);
    }

    public function decryptVaultKey(string $encryptedVaultKey, string $loginPassword): string
    {
        $passwordKey = hash('sha256', $loginPassword, true);
        $decodedKey = $this->decrypt($encryptedVaultKey, $passwordKey);

        return base64_decode($decodedKey);
    }

    public function encryptPassword(string $password, string $vaultKey): string
    {
        return $this->encrypt($password, $vaultKey);
    }

    public function decryptPassword(string $encryptedPassword, string $vaultKey): string
    {
        return $this->decrypt($encryptedPassword, $vaultKey);
    }

    private function encrypt(string $plainText, string $key): string
    {
        $ivLength = openssl_cipher_iv_length($this->method);
        $iv = random_bytes($ivLength);

        $encryptedText = openssl_encrypt(
            $plainText,
            $this->method,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_encode($iv . $encryptedText);
    }

    private function decrypt(string $encryptedText, string $key): string
    {
        $decodedText = base64_decode($encryptedText);
        $ivLength = openssl_cipher_iv_length($this->method);

        $iv = substr($decodedText, 0, $ivLength);
        $cipherText = substr($decodedText, $ivLength);

        $plainText = openssl_decrypt(
            $cipherText,
            $this->method,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return $plainText === false ? '' : $plainText;
    }
}
