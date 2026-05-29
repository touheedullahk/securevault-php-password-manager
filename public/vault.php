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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
    $passwordRecord->remove((int) $_POST['record_id'], (int) $_SESSION['user_id']);
    $_SESSION['vault_message'] = 'Saved password removed.';
    $_SESSION['vault_message_type'] = 'success';
    header('Location: vault.php');
    exit;
}

$records = $passwordRecord->findAllForUser((int) $_SESSION['user_id'], $vaultKey);
$message = $_SESSION['vault_message'] ?? '';
$messageType = $_SESSION['vault_message_type'] ?? 'success';
unset($_SESSION['vault_message'], $_SESSION['vault_message_type']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saved Vault | SecureVault</title>
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
            <a href="add_password.php">Add Existing</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="list-heading">
        <div>
            <h1>Encrypted saved vault</h1>
            <p>Passwords listed here are stored in MySQL as AES-encrypted values.</p>
        </div>
        <a class="button-link" href="add_password.php">Add existing password</a>
    </section>

    <?php if ($message !== ''): ?>
        <div class="message <?= htmlspecialchars($messageType) ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($records === []): ?>
        <section class="card placeholder">
            <h2>No saved passwords</h2>
            <p>Generate and save a password, or add an existing password.</p>
            <a class="button-link" href="generator.php">Open generator</a>
        </section>
    <?php else: ?>
        <section class="card table-card">
            <table class="records-table">
                <thead>
                    <tr>
                        <th>Website / Program</th>
                        <th>Password</th>
                        <th>Saved date/time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($records as $position => $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['service_name']) ?></td>
                        <td>
                            <code id="saved-password-<?= $position ?>">••••••••</code>
                            <span class="hidden-password" id="real-password-<?= $position ?>"><?= htmlspecialchars($record['password']) ?></span>
                            <button class="tiny-button" type="button"
                                    onclick="showOrHidePassword('saved-password-<?= $position ?>', 'real-password-<?= $position ?>', this)">Show</button>
                            <button class="tiny-button" type="button"
                                    onclick="copyText('real-password-<?= $position ?>', this)">Copy</button>
                        </td>
                        <td><?= htmlspecialchars($record['created_at']) ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="record_id" value="<?= (int) $record['id'] ?>">
                                <button class="danger-button tiny-button" type="submit" name="remove" value="1">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>
</main>
<script src="assets/app.js"></script>
</body>
</html>
