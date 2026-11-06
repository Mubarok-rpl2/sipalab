<?php
session_start();
require_once __DIR__ . "/../config.php";

/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$nis_nip = $_POST['nis_nip'] ?? '';
$nama = $_POST['nama'] ?? '';
$kelas_jabatan = $_POST['kelas_jabatan'] ?? '';
$no_hp = $_POST['no_hp'] ?? '';

$query = mysqli_query($conn, "
    INSERT INTO peminjam
    (nis_nip, nama, kelas_jabatan, no_hp)
    VALUES
    ('$nis_nip', '$nama', '$kelas_jabatan', '$no_hp')
");

if ($query) {
    header("Location: index.php");
    exit;
}

echo "Data gagal disimpan: " . mysqli_error($conn);
?>