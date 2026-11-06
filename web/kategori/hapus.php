<?php
session_start();
require_once __DIR__ . "/../config.php";

/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? '';

$query = mysqli_query($conn, "
    DELETE FROM kategori_alat
    WHERE id='$id'
");

if ($query) {
    header("Location: index.php");
    exit;
}

echo "Data gagal dihapus. Mungkin kategori masih digunakan oleh data alat.";
?>