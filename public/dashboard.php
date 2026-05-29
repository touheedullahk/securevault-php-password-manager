<?php
session_start();

if (!isset($_SESSION['user_id'])) {
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
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<main class="container">
    <section class="welcome">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <p>The generator now supports exact quantities and simple strength feedback.</p>
        <a class="button-link" href="generator.php">Open password generator</a>
    </section>

    <section class="card placeholder">
        <h2>Stored Password Vault</h2>
        <p>Saving encrypted website or application passwords will be implemented in a later version.</p>
    </section>
</main>
</body>
</html>
