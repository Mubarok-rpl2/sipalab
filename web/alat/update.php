<?php
session_start();
require_once __DIR__ . "/../config.php";

/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_POST['id'] ?? '';
$kode_alat = $_POST['kode_alat'] ?? '';
$nama_alat = $_POST['nama_alat'] ?? '';
$kategori_id = $_POST['kategori_id'] ?? '';
$kondisi = $_POST['kondisi'] ?? 'baik';
$jumlah_stok = $_POST['jumlah_stok'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';

if (
    empty($id) ||
    empty($kode_alat) ||
    empty($nama_alat) ||
    empty($kategori_id) ||
    $jumlah_stok === ''
) {
    die("Data wajib diisi.");
}

if ($jumlah_stok < 0) {
    die("Jumlah stok tidak boleh kurang dari 0.");
}

$query = mysqli_query($conn, "
    UPDATE alat
    SET
        kode_alat = '$kode_alat',
        nama_alat = '$nama_alat',
        kategori_id = '$kategori_id',
        kondisi = '$kondisi',
        jumlah_stok = '$jumlah_stok',
        deskripsi = '$deskripsi'
    WHERE id='$id'
");

if ($query) {
    header("Location: index.php");
    exit;
}

echo "Data gagal diupdate: " . mysqli_error($conn);
?>