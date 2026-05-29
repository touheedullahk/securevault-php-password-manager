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
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="welcome">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <p>Version 7 introduces encrypted MySQL storage for your generated or desired passwords.</p>
    </section>

    <section class="dashboard-grid">
        <section class="card feature-card">
            <h2>Generate Password</h2>
            <p>Create a password with exact character quantities and save it securely.</p>
            <a class="button-link" href="generator.php">Generate password</a>
        </section>

        <section class="card feature-card">
            <h2>Saved Vault</h2>
            <p>View passwords saved in the database as encrypted values.</p>
            <a class="button-link secondary-link" href="vault.php">View saved vault</a>
        </section>

        <section class="card feature-card">
            <h2>Add Existing</h2>
            <p>Store a password you already use for a website or program.</p>
            <a class="button-link secondary-link" href="add_password.php">Add password</a>
        </section>
    </section>

    <section class="roadmap-card">
        <strong>Next development step:</strong>
        allow the user to change the login password while safely re-encrypting the unchanged vault key.
    </section>
</main>
</body>
</html>
