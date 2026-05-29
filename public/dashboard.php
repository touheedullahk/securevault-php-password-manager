<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/TemporaryPasswordList.php';
$temporaryList = new TemporaryPasswordList();
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
            <a href="temporary_vault.php">Temporary List (<?= $temporaryList->count() ?>)</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="welcome">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <p>The generator now supports exact quantities, strength feedback and a temporary vault preview.</p>
    </section>

    <section class="dashboard-grid">
        <section class="card feature-card">
            <h2>Password Generator</h2>
            <p>Create a new password with exact character quantities and see its strength.</p>
            <a class="button-link" href="generator.php">Generate password</a>
        </section>

        <section class="card feature-card">
            <h2>Temporary List</h2>
            <p>Preview saved records during this session only. Current temporary records: <strong><?= $temporaryList->count() ?></strong></p>
            <a class="button-link secondary-link" href="temporary_vault.php">View temporary list</a>
        </section>
    </section>

    <section class="roadmap-card">
        <strong>Development note:</strong>
        Temporary session records prepare the interface for the later encrypted MySQL password vault.
    </section>
</main>
</body>
</html>
