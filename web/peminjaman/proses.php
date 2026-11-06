<?php

session_start();

require_once __DIR__ . "/../config.php";

/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Ambil data dari form
|--------------------------------------------------------------------------
*/

$id = $_POST['id'] ?? '';
$tanggal_kembali_aktual = $_POST['tanggal_kembali_aktual'] ?? '';
$kondisi_saat_kembali = $_POST['kondisi_saat_kembali'] ?? '';

/*
|--------------------------------------------------------------------------
| Validasi data
|--------------------------------------------------------------------------
*/

if (
    $id == '' ||
    $tanggal_kembali_aktual == '' ||
    $kondisi_saat_kembali == ''
) {
    die("Semua data harus diisi.");
}

/*
|--------------------------------------------------------------------------
| Validasi kondisi
|--------------------------------------------------------------------------
*/

$kondisi_valid = [
    'baik',
    'rusak ringan',
    'rusak berat'
];

if (!in_array($kondisi_saat_kembali, $kondisi_valid)) {
    die("Kondisi alat tidak valid.");
}

/*
|--------------------------------------------------------------------------
| Ambil data peminjaman dan detail alat
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn, "
    SELECT 
        p.id,
        p.status,
        dp.alat_id,
        dp.jumlah
    FROM peminjaman p
    JOIN detail_peminjaman dp
        ON p.id = dp.peminjaman_id
    WHERE p.id = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data peminjaman tidak ditemukan.");
}

/*
|--------------------------------------------------------------------------
| Cek apakah sudah dikembalikan
|--------------------------------------------------------------------------
*/

if ($data['status'] == 'dikembalikan') {
    die("Alat ini sudah dikembalikan.");
}

/*
|--------------------------------------------------------------------------
| Mulai transaksi database
|--------------------------------------------------------------------------
*/

mysqli_begin_transaction($conn);

try {

    /*
    |--------------------------------------------------------------------------
    | Tambahkan stok alat kembali
    |--------------------------------------------------------------------------
    */

    $alat_id = $data['alat_id'];
    $jumlah = $data['jumlah'];

    $update_stok = mysqli_query($conn, "
        UPDATE alat
        SET 
            jumlah_stok = jumlah_stok + $jumlah,
            kondisi = '$kondisi_saat_kembali'
        WHERE id = '$alat_id'
    ");

    if (!$update_stok) {
        throw new Exception("Gagal mengupdate stok alat.");
    }

    /*
    |--------------------------------------------------------------------------
    | Update data peminjaman
    |--------------------------------------------------------------------------
    */

    $update_peminjaman = mysqli_query($conn, "
        UPDATE peminjaman
        SET
            tanggal_kembali_aktual = '$tanggal_kembali_aktual',
            status = 'dikembalikan'
        WHERE id = '$id'
    ");

    if (!$update_peminjaman) {
        throw new Exception("Gagal mengupdate data peminjaman.");
    }

    /*
    |--------------------------------------------------------------------------
    | Update kondisi alat pada detail peminjaman
    |--------------------------------------------------------------------------
    */

    $update_detail = mysqli_query($conn, "
        UPDATE detail_peminjaman
        SET
            kondisi_saat_kembali = '$kondisi_saat_kembali'
        WHERE peminjaman_id = '$id'
    ");

    if (!$update_detail) {
        throw new Exception("Gagal mengupdate detail peminjaman.");
    }

    /*
    |--------------------------------------------------------------------------
    | Simpan semua perubahan
    |--------------------------------------------------------------------------
    */

    mysqli_commit($conn);

    header("Location: index.php?pesan=pengembalian_berhasil");
    exit;

} catch (Exception $e) {

    /*
    |--------------------------------------------------------------------------
    | Batalkan jika terjadi error
    |--------------------------------------------------------------------------
    */

    mysqli_rollback($conn);

    die("Pengembalian gagal: " . $e->getMessage());
}
?>