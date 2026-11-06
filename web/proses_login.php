<?php

session_start();

require_once __DIR__ . "/config.php";
/** @var mysqli $conn */

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE username='$username'"
);

$data = mysqli_fetch_assoc($query);

if ($data && $password == $data['password']) {

    $_SESSION['login'] = true;
    $_SESSION['id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
    $_SESSION['role'] = $data['role'];

    header("Location: dashboard.php");
    exit;

} else {

    echo "Username atau password salah.";

}

?>