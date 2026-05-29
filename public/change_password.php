<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['vault_key'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/Database.php';
require_once '../classes/EncryptionService.php';
require_once '../classes/User.php';
require_once '../classes/Security.php';

$config = require '../config/config.php';
$message = '';
$success = false;

try {
    $database = new Database($config);
    $user = new User($database->getConnection());

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!Security::checkCsrfToken($_POST['csrf_token'] ?? '')) {
            $message = 'Invalid form request. Please try again.';
        } else {
            $message = $user->changePassword(
                (int) $_SESSION['user_id'],
                $_POST['current_password'] ?? '',
                $_POST['new_password'] ?? '',
                $_POST['confirm_password'] ?? ''
            );

            if ($message === '') {
                $success = true;
                $message = 'Login password changed. Your unchanged vault key was safely re-encrypted.';
            }
        }
    }
} catch (PDOException $error) {
    $message = 'Database connection failed.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password | SecureVault</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
    <div class="container navigation">
        <a class="brand" href="dashboard.php">SecureVault</a>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="generator.php">Generator</a>
            <a href="vault.php">Saved Vault</a>
            <a href="change_password.php">Change Login Password</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="card form-card">
        <h1>Change login password</h1>
        <p>Your vault key stays unchanged and is protected again using your new login password.</p>

        <?php if ($message !== ''): ?>
            <div class="message <?= $success ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Security::createCsrfToken()) ?>">

            <label>Current password
                <input type="password" name="current_password" required>
            </label>

            <label>New password
                <input type="password" name="new_password" minlength="8" required>
            </label>

            <label>Confirm new password
                <input type="password" name="confirm_password" minlength="8" required>
            </label>

            <button type="submit">Change password</button>
        </form>
    </section>
</main>
</body>
</html>
