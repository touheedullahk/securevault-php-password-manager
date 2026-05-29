<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../classes/TemporaryPasswordList.php';

$temporaryList = new TemporaryPasswordList();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        $temporaryList->remove((int) $_POST['position']);
        $message = 'Temporary record removed.';
    }

    if (isset($_POST['clear'])) {
        $temporaryList->clear();
        $message = 'Temporary list cleared.';
    }
}

$records = $temporaryList->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Temporary List | SecureVault</title>
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
    <section class="list-heading">
        <div>
            <h1>Temporary password list</h1>
            <p>Version 6 preview: these records are kept only during your current login session.</p>
        </div>

        <?php if ($records !== []): ?>
            <form method="post">
                <button class="danger-button" type="submit" name="clear" value="1">Clear all</button>
            </form>
        <?php endif; ?>
    </section>

    <?php if ($message !== ''): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="temporary-warning">
        Not final storage: these passwords are not stored in MySQL yet and will disappear after logout.
    </div>

    <?php if ($records === []): ?>
        <section class="card placeholder">
            <h2>No temporary records</h2>
            <p>Generate a password and add it to the temporary list to preview a future vault feature.</p>
            <a class="button-link" href="generator.php">Open generator</a>
        </section>
    <?php else: ?>
        <section class="card table-card">
            <table class="records-table">
                <thead>
                    <tr>
                        <th>Website / Program</th>
                        <th>Password</th>
                        <th>Date created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($records as $displayPosition => $record): ?>
                    <?php $realPosition = count($records) - 1 - $displayPosition; ?>
                    <tr>
                        <td><?= htmlspecialchars($record['service_name']) ?></td>
                        <td>
                            <code id="temp-password-<?= $displayPosition ?>"><?= htmlspecialchars($record['password']) ?></code>
                            <button class="tiny-button" type="button"
                                    onclick="copyText('temp-password-<?= $displayPosition ?>', this)">Copy</button>
                        </td>
                        <td><?= htmlspecialchars($record['created_at']) ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="position" value="<?= $realPosition ?>">
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
