<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['vault_key'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Password | SecureVault</title>
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
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="card form-card">
        <h1>Add existing password</h1>
        <p>Store a desired password for a website or program using the encrypted vault.</p>

        <form action="save_password.php" method="post">
            <label>Website or program name
                <input type="text" name="service_name" maxlength="60" required>
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <button type="submit">Save encrypted password</button>
        </form>
    </section>
</main>
</body>
</html>
