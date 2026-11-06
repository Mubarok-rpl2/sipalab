<?php
session_start();
require_once __DIR__ . "/../config.php";

/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$peminjam_id = $_POST['peminjam_id'] ?? '';
$tanggal_pinjam = $_POST['tanggal_pinjam'] ?? '';
$tanggal_kembali_rencana = $_POST['tanggal_kembali_rencana'] ?? '';
$alat_id = $_POST['alat_id'] ?? '';
$jumlah = $_POST['jumlah'] ?? '';
$user_id = $_SESSION['id'];

/* Validasi data */

if (
    empty($peminjam_id) ||
    empty($tanggal_pinjam) ||
    empty($tanggal_kembali_rencana) ||
    empty($alat_id) ||
    empty($jumlah)
) {
    die("Semua data harus diisi.");
}

/* Validasi tanggal */

if ($tanggal_kembali_rencana < $tanggal_pinjam) {
    die("Tanggal kembali tidak boleh sebelum tanggal pinjam.");
}

/* Validasi jumlah */

if ($jumlah < 1) {
    die("Jumlah alat minimal 1.");
}

/* Cek alat */

$query_alat = mysqli_query($conn, "
    SELECT *
    FROM alat
    WHERE id = '$alat_id'
");

$data_alat = mysqli_fetch_assoc($query_alat);

if (!$data_alat) {
    die("Alat tidak ditemukan.");
}

/* Cek stok */

if ($data_alat['jumlah_stok'] < $jumlah) {
    die("Stok alat tidak mencukupi.");
}

/* Mulai transaksi */

mysqli_begin_transaction($conn);

try {

    /* Simpan peminjaman */

    $query_peminjaman = mysqli_query($conn, "
        INSERT INTO peminjaman
        (
            peminjam_id,
            user_id,
            tanggal_pinjam,
            tanggal_kembali_rencana,
            status
        )
        VALUES
        (
            '$peminjam_id',
            '$user_id',
            '$tanggal_pinjam',
            '$tanggal_kembali_rencana',
            'dipinjam'
        )
    ");

    if (!$query_peminjaman) {
        throw new Exception("Gagal menyimpan data peminjaman.");
    }

    /* Ambil ID peminjaman */

    $peminjaman_id = mysqli_insert_id($conn);

    /* Simpan detail peminjaman */

    $query_detail = mysqli_query($conn, "
        INSERT INTO detail_peminjaman
        (
            peminjaman_id,
            alat_id,
            jumlah
        )
        VALUES
        (
            '$peminjaman_id',
            '$alat_id',
            '$jumlah'
        )
    ");

    if (!$query_detail) {
        throw new Exception("Gagal menyimpan detail peminjaman.");
    }

    /* Kurangi stok */

    $query_stok = mysqli_query($conn, "
        UPDATE alat
        SET jumlah_stok = jumlah_stok - $jumlah
        WHERE id = '$alat_id'
    ");

    if (!$query_stok) {
        throw new Exception("Gagal mengurangi stok alat.");
    }

    /* Simpan transaksi */

    mysqli_commit($conn);

    header("Location: index.php");
    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);

    echo "Terjadi kesalahan: " . $e->getMessage();
}
?>