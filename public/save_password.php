<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['vault_key'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/Database.php';
require_once '../classes/EncryptionService.php';
require_once '../classes/PasswordRecord.php';
require_once '../classes/Security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Security::checkCsrfToken($_POST['csrf_token'] ?? '')) {
    $_SESSION['vault_message'] = 'Invalid form request. Please try again.';
    $_SESSION['vault_message_type'] = 'error';
    header('Location: vault.php');
    exit;
}

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
