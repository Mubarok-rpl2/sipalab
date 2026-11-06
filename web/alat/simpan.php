<?php
session_start();
require_once __DIR__ . "/../config.php";

/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$kode_alat = $_POST['kode_alat'] ?? '';
$nama_alat = $_POST['nama_alat'] ?? '';
$kategori_id = $_POST['kategori_id'] ?? '';
$kondisi = $_POST['kondisi'] ?? 'baik';
$jumlah_stok = $_POST['jumlah_stok'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';

if (
    empty($kode_alat) ||
    empty($nama_alat) ||
    empty($kategori_id) ||
    empty($jumlah_stok)
) {
    die("Data wajib diisi.");
}

if ($jumlah_stok < 1) {
    die("Jumlah stok minimal 1.");
}

$query = mysqli_query($conn, "
    INSERT INTO alat
    (
        kode_alat,
        nama_alat,
        kategori_id,
        kondisi,
        jumlah_stok,
        deskripsi
    )
    VALUES
    (
        '$kode_alat',
        '$nama_alat',
        '$kategori_id',
        '$kondisi',
        '$jumlah_stok',
        '$deskripsi'
    )
");

if ($query) {
    header("Location: index.php");
    exit;
}

echo "Data gagal disimpan: " . mysqli_error($conn);
?>