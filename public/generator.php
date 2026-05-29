<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['vault_key'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/PasswordGenerator.php';
require_once '../classes/PasswordStrength.php';
require_once '../classes/Security.php';

$message = '';
$generatedPassword = '';
$strengthResult = null;

$length = (int) ($_POST['length'] ?? 12);
$lowercaseQuantity = (int) ($_POST['lowercase_quantity'] ?? 3);
$uppercaseQuantity = (int) ($_POST['uppercase_quantity'] ?? 3);
$numberQuantity = (int) ($_POST['number_quantity'] ?? 3);
$specialQuantity = (int) ($_POST['special_quantity'] ?? 3);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantityTotal = $lowercaseQuantity + $uppercaseQuantity + $numberQuantity + $specialQuantity;

    if (!Security::checkCsrfToken($_POST['csrf_token'] ?? '')) {
        $message = 'Invalid form request. Please try again.';
    } elseif ($length < 6 || $length > 40) {
        $message = 'Password length must be between 6 and 40.';
    } elseif ($lowercaseQuantity < 0 || $uppercaseQuantity < 0 || $numberQuantity < 0 || $specialQuantity < 0) {
        $message = 'Character quantities cannot be negative.';
    } elseif ($quantityTotal !== $length) {
        $message = 'The four character quantities must add up to the total length.';
    } else {
        $generator = new PasswordGenerator();
        $generatedPassword = $generator->generate(
            $lowercaseQuantity,
            $uppercaseQuantity,
            $numberQuantity,
            $specialQuantity
        );

        $strengthChecker = new PasswordStrength();
        $strengthResult = $strengthChecker->evaluate($generatedPassword);
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
            <a href="vault.php">Saved Vault</a>
            <a href="change_password.php">Change Login Password</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="card generator-card">
        <h1>Password generator</h1>
        <p>Generate exact character quantities, review strength, then save securely.</p>

        <?php if ($message !== ''): ?>
            <div class="message error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Security::createCsrfToken()) ?>">
            <label>Password length
                <input type="number" name="length" min="6" max="40" value="<?= $length ?>" required>
            </label>

            <div class="quantity-grid">
                <label>Lowercase letters
                    <input type="number" name="lowercase_quantity" min="0" max="40" value="<?= $lowercaseQuantity ?>" required>
                </label>
                <label>Uppercase letters
                    <input type="number" name="uppercase_quantity" min="0" max="40" value="<?= $uppercaseQuantity ?>" required>
                </label>
                <label>Numbers
                    <input type="number" name="number_quantity" min="0" max="40" value="<?= $numberQuantity ?>" required>
                </label>
                <label>Special characters
                    <input type="number" name="special_quantity" min="0" max="40" value="<?= $specialQuantity ?>" required>
                </label>
            </div>

            <button type="submit">Generate password</button>
        </form>

        <?php if ($generatedPassword !== '' && $strengthResult !== null): ?>
            <div class="result">
                <p>Generated password:</p>
                <div class="password-output">
                    <strong id="generated-password"><?= htmlspecialchars($generatedPassword) ?></strong>
                    <button class="copy-button" type="button" onclick="copyGeneratedPassword()">Copy</button>
                </div>

                <div class="strength-box">
                    <p>Strength:
                        <span class="strength-badge <?= htmlspecialchars($strengthResult['css_class']) ?>">
                            <?= htmlspecialchars($strengthResult['label']) ?>
                        </span>
                    </p>
                </div>

                <form class="temporary-form" action="save_password.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Security::createCsrfToken()) ?>">
                    <input type="hidden" name="password" value="<?= htmlspecialchars($generatedPassword) ?>">
                    <label>Website or program name
                        <input type="text" name="service_name" maxlength="60" placeholder="Example: Gmail Demo" required>
                    </label>
                    <button class="secondary-button" type="submit">Save encrypted password</button>
                </form>
            </div>
        <?php endif; ?>
    </section>
</main>
<script src="assets/app.js"></script>
</body>
</html>
