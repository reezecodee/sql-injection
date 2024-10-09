<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success" role="alert">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); // Hapus pesan setelah ditampilkan 
            ?>
        </div>
    <?php endif; ?>

    <h1>Selamat Datang di Dashboard!</h1>
</body>

</html>