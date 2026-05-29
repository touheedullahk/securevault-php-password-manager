<?php
session_start();

require_once '../classes/Database.php';
require_once '../classes/EncryptionService.php';
require_once '../classes/User.php';
require_once '../classes/Security.php';

$config = require '../config/config.php';
$message = '';
$username = '';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

try {
    $database = new Database($config);
    $user = new User($database->getConnection());

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!Security::checkCsrfToken($_POST['csrf_token'] ?? '')) {
            $message = 'Invalid form request. Please try again.';
        } elseif ($user->login($username, $password)) {
            header('Location: dashboard.php');
            exit;
        } else {
            $message = 'Incorrect username or password.';
        }
    }
} catch (PDOException $error) {
    $message = 'Database connection failed. Import database/schema.sql first.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | SecureVault</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
    <div class="container navigation">
        <a class="brand" href="index.php">SecureVault</a>
        <nav><a href="login.php">Login</a><a href="register.php">Register</a></nav>
    </div>
</header>

<main class="container">
    <section class="card form-card">
        <h1>Login</h1>
        <p>Sign in to unlock your encrypted saved-password vault.</p>

        <?php if ($message !== ''): ?>
            <div class="message error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Security::createCsrfToken()) ?>">
            <label>Username
                <input type="text" name="username" required value="<?= htmlspecialchars($username) ?>">
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <button type="submit">Login</button>
        </form>
        <p class="link-line">No account yet? <a href="register.php">Create account</a></p>
    </section>
</main>
</body>
</html>
