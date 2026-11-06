<?php
session_start();
require_once __DIR__ . "/../config.php";

/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? '';

if ($id == '') {
    die("ID peminjaman tidak ditemukan.");
}

$query = mysqli_query($conn, "
    SELECT 
        p.*,
        pem.nama AS nama_peminjam,
        pem.nis_nip,
        a.nama_alat,
        a.kode_alat,
        dp.jumlah
    FROM peminjaman p
    JOIN peminjam pem ON p.peminjam_id = pem.id
    JOIN detail_peminjaman dp ON p.id = dp.peminjaman_id
    JOIN alat a ON dp.alat_id = a.id
    WHERE p.id = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data peminjaman tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pengembalian Alat - SIPALAB</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0fdf4;
            color: #1f2937;
        }

        header {
            background: linear-gradient(135deg, #166534, #22c55e);
            color: white;
            padding: 30px;
            text-align: center;
        }

        header h1 {
            margin-bottom: 8px;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        h2 {
            color: #166534;
            margin-bottom: 25px;
        }

        .info {
            background: #f0fdf4;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .info p {
            margin: 8px 0;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #22c55e;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #16a34a;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #15803d;
        }

        .kembali {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #166534;
            text-decoration: none;
        }

        .kembali:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<header>
    <h1>SIPALAB</h1>
    <p>Pengembalian Alat Laboratorium Komputer</p>
</header>

<div class="container">

    <div class="card">

        <h2>Form Pengembalian Alat</h2>

        <div class="info">
            <p>
                <strong>Peminjam:</strong>
                <?php echo htmlspecialchars($data['nama_peminjam']); ?>
            </p>

            <p>
                <strong>NIS/NIP:</strong>
                <?php echo htmlspecialchars($data['nis_nip']); ?>
            </p>

            <p>
                <strong>Alat:</strong>
                <?php echo htmlspecialchars($data['nama_alat']); ?>
            </p>

            <p>
                <strong>Kode Alat:</strong>
                <?php echo htmlspecialchars($data['kode_alat']); ?>
            </p>

            <p>
                <strong>Jumlah:</strong>
                <?php echo htmlspecialchars($data['jumlah']); ?>
            </p>

            <p>
                <strong>Tanggal Pinjam:</strong>
                <?php echo htmlspecialchars($data['tanggal_pinjam']); ?>
            </p>

            <p>
                <strong>Rencana Kembali:</strong>
                <?php echo htmlspecialchars($data['tanggal_kembali_rencana']); ?>
            </p>
        </div>

        <form action="proses.php" method="POST">

            <!-- ID PEMINJAMAN -->
            <input
                type="hidden"
                name="id"
                value="<?php echo htmlspecialchars($data['id']); ?>"
            >

            <label for="tanggal_kembali_aktual">
                Tanggal Kembali Aktual
            </label>

            <input
                type="date"
                id="tanggal_kembali_aktual"
                name="tanggal_kembali_aktual"
                required
            >

            <label for="kondisi_saat_kembali">
                Kondisi Saat Kembali
            </label>

            <select
                id="kondisi_saat_kembali"
                name="kondisi_saat_kembali"
                required
            >
                <option value="">-- Pilih Kondisi --</option>
                <option value="baik">Baik</option>
                <option value="rusak ringan">Rusak Ringan</option>
                <option value="rusak berat">Rusak Berat</option>
            </select>

            <button type="submit">
                Proses Pengembalian
            </button>

        </form>

        <a class="kembali" href="index.php">
            ← Kembali ke Data Peminjaman
        </a>

    </div>

</div>

</body>
</html>