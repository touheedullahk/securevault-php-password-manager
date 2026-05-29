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
    <title>Dashboard | SecureVault</title>
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
            <a href="change_password.php">Change Login Password</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="welcome">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <p>Your account uses an encrypted permanent vault key to protect saved passwords.</p>
    </section>

    <section class="dashboard-grid">
        <section class="card feature-card">
            <h2>Generate Password</h2>
            <p>Create a password with exact character quantities and strength feedback.</p>
            <a class="button-link" href="generator.php">Generate password</a>
        </section>

        <section class="card feature-card">
            <h2>Saved Vault</h2>
            <p>View generated or desired passwords stored as encrypted MySQL records.</p>
            <a class="button-link secondary-link" href="vault.php">View vault</a>
        </section>

        <section class="card feature-card">
            <h2>Account Security</h2>
            <p>Change your login password without replacing the permanent vault key.</p>
            <a class="button-link secondary-link" href="change_password.php">Change password</a>
        </section>
    </section>

    <section class="roadmap-card completed">
        <strong>Implemented:</strong>
        hashed login passwords, encrypted vault key, AES encrypted saved passwords, exact-quantity generator and key re-encryption after password change.
    </section>
</main>
</body>
</html>
