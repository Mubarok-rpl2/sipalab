<?php
session_start();
require_once __DIR__ . "/../config.php";

/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_POST['id'] ?? '';
$nis_nip = $_POST['nis_nip'] ?? '';
$nama = $_POST['nama'] ?? '';
$kelas_jabatan = $_POST['kelas_jabatan'] ?? '';
$no_hp = $_POST['no_hp'] ?? '';

$query = mysqli_query($conn, "
    UPDATE peminjam SET
        nis_nip = '$nis_nip',
        nama = '$nama',
        kelas_jabatan = '$kelas_jabatan',
        no_hp = '$no_hp'
    WHERE id = '$id'
");

if ($query) {
    header("Location: index.php");
    exit;
}

echo "Data gagal diupdate: " . mysqli_error($conn);
?>