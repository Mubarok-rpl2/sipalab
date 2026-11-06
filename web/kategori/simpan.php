<?php
session_start();
require_once __DIR__ . "/../config.php";

/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$nama_kategori = $_POST['nama_kategori'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';

$query = mysqli_query($conn, "
    INSERT INTO kategori_alat
    (nama_kategori, deskripsi)
    VALUES
    ('$nama_kategori', '$deskripsi')
");

if ($query) {
    header("Location: index.php");
    exit;
}

echo "Data gagal disimpan: " . mysqli_error($conn);
?>