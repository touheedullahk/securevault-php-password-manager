<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/PasswordGenerator.php';

$message = '';
$generatedPassword = '';
$length = 12;
$useLowercase = true;
$useUppercase = true;
$useNumbers = true;
$useSpecial = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $length = (int) ($_POST['length'] ?? 12);
    $useLowercase = isset($_POST['lowercase']);
    $useUppercase = isset($_POST['uppercase']);
    $useNumbers = isset($_POST['numbers']);
    $useSpecial = isset($_POST['special']);

    if (!$useLowercase && !$useUppercase && !$useNumbers && !$useSpecial) {
        $message = 'Select at least one character type.';
    } elseif ($length < 6 || $length > 40) {
        $message = 'Password length must be between 6 and 40.';
    } else {
        $generator = new PasswordGenerator();
        $generatedPassword = $generator->generate(
            $length,
            $useLowercase,
            $useUppercase,
            $useNumbers,
            $useSpecial
        );
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generator | SecureVault</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header>
    <div class="container navigation">
        <a class="brand" href="dashboard.php">SecureVault</a>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="generator.php">Generator</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="card form-card">
        <h1>Password generator</h1>
        <p>Version 3: generate a password using selected character groups.</p>

        <?php if ($message !== ''): ?>
            <div class="message error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Password length
                <input type="number" name="length" min="6" max="40" value="<?= $length ?>" required>
            </label>

            <fieldset>
                <legend>Include characters</legend>

                <label class="checkbox">
                    <input type="checkbox" name="lowercase" <?= $useLowercase ? 'checked' : '' ?>>
                    Lowercase letters
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="uppercase" <?= $useUppercase ? 'checked' : '' ?>>
                    Uppercase letters
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="numbers" <?= $useNumbers ? 'checked' : '' ?>>
                    Numbers
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="special" <?= $useSpecial ? 'checked' : '' ?>>
                    Special characters
                </label>
            </fieldset>

            <button type="submit">Generate password</button>
        </form>

        <?php if ($generatedPassword !== ''): ?>
            <div class="result">
                <p>Generated password:</p>
                <strong><?= htmlspecialchars($generatedPassword) ?></strong>
            </div>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
