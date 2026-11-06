<?php
session_start();
require_once __DIR__ . "/../config.php";

/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_POST['id'] ?? '';
$nama_kategori = $_POST['nama_kategori'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';

$query = mysqli_query($conn, "
    UPDATE kategori_alat
    SET
        nama_kategori = '$nama_kategori',
        deskripsi = '$deskripsi'
    WHERE id='$id'
");

if ($query) {
    header("Location: index.php");
    exit;
}

echo "Data gagal diupdate: " . mysqli_error($conn);
?>