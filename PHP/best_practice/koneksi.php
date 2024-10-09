<?php
session_start();

// Hapus session login jika ada
unset($_SESSION['login']);

// Buat koneksi ke database
$conn = mysqli_connect('localhost', 'root', '', 'sql_injection');

if (!$conn) {
    die("Koneksi terputus");
}

// Mengambil input email dan password
$email = $_POST['email'];
$password = $_POST['password'];

// Persiapkan statement SQL
$sql = "SELECT * FROM users WHERE email = ? AND password = ?";
$stmt = mysqli_prepare($conn, $sql);

// Ikat parameter ke statement
mysqli_stmt_bind_param($stmt, "ss", $email, $password);

// Eksekusi statement
mysqli_stmt_execute($stmt);

// Ambil hasil dari eksekusi statement
$result = mysqli_stmt_get_result($stmt);

// Mengecek hasil query
if (mysqli_num_rows($result) > 0) {
    $_SESSION['success'] = 'Berhasil login ke dashboard';
    $_SESSION['login'] = 'success';
    header('Location: dashboard.php');
    exit;
} else {
    $_SESSION['error'] = 'Maaf email atau password Anda salah';
    header('Location: login.php');
    exit;
}

// Tutup statement dan koneksi
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
