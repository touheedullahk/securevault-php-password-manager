<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['vault_key'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/Database.php';
require_once '../classes/EncryptionService.php';
require_once '../classes/PasswordRecord.php';

$config = require '../config/config.php';
$database = new Database($config);
$passwordRecord = new PasswordRecord($database->getConnection());

$vaultKey = base64_decode($_SESSION['vault_key']);
$message = $passwordRecord->save(
    (int) $_SESSION['user_id'],
    $_POST['service_name'] ?? '',
    $_POST['password'] ?? '',
    $vaultKey
);

if ($message !== '') {
    $_SESSION['vault_message'] = $message;
    $_SESSION['vault_message_type'] = 'error';
} else {
    $_SESSION['vault_message'] = 'Password securely saved in the encrypted vault.';
    $_SESSION['vault_message_type'] = 'success';
}

header('Location: vault.php');
exit;
