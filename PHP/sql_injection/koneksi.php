<?php
session_start();

unset($_SESSION['login']);

$conn = mysqli_connect('localhost', 'root', '', 'sql_injection');

if (!$conn) {
    die("Koneksi terputus");
}

// Mengambil input email dan password
$email = $_POST['email'];
$password = $_POST['password'];

// Buat query SQL yang rentan
$sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
$query = mysqli_query($conn, $sql);

// Mengecek hasil query
if (mysqli_num_rows($query) > 0) {
    $_SESSION['success'] = 'Berhasil login ke dashboard';
    $_SESSION['login'] = 'success';
    header('Location: dashboard.php');
    exit;
} else {
    $_SESSION['error'] = 'Maaf email atau password Anda salah';
    header('Location: login.php');
    exit;
}

mysqli_close($conn);
?>
