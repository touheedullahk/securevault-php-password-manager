<?php
session_start();

require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Validator.php';

$config = require '../config/config.php';
$message = '';
$success = false;
$username = '';

try {
    $database = new Database($config);
    $user = new User($database->getConnection());
    $validator = new Validator();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $message = $validator->validateRegistration($username, $password, $confirmPassword);

        if ($message === '') {
            $message = $user->register($username, $password);

            if ($message === '') {
                $success = true;
                $message = 'Account created successfully. You may now log in.';
                $username = '';
            }
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
    <title>Register | SecureVault</title>
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
        <h1>Create account</h1>
        <p>Version 2: improved validation and safer login session handling.</p>

        <?php if ($message !== ''): ?>
            <div class="message <?= $success ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <label>Username
                <input type="text" name="username" required maxlength="30"
                       value="<?= htmlspecialchars($username) ?>">
            </label>

            <label>Password
                <input type="password" name="password" required minlength="8">
            </label>

            <label>Confirm password
                <input type="password" name="confirm_password" required minlength="8">
            </label>

            <button type="submit">Register</button>
        </form>

        <p class="hint">Username: letters, numbers or underscore. Password: at least 8 characters with a letter and a number.</p>
        <p class="link-line">Already registered? <a href="login.php">Login here</a></p>
    </section>
</main>
</body>
</html>
