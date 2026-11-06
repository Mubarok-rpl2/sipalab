<?php
session_start();
require_once __DIR__ . "/../config.php";
/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$query_peminjam = mysqli_query($conn, "
    SELECT * FROM peminjam
    ORDER BY nama ASC
");

$query_alat = mysqli_query($conn, "
    SELECT * FROM alat
    WHERE jumlah_stok > 0
    ORDER BY nama_alat ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Peminjaman - SIPALAB</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f1f8f3;
        }

        header {
            background: linear-gradient(135deg, #2e7d32, #43a047);
            color: white;
            padding: 25px 40px;
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        header p {
            margin: 5px 0 0;
        }

        .container {
            max-width: 750px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .card h2 {
            margin-top: 0;
            color: #2e7d32;
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
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2e7d32;
        }

        .button-group {
            margin-top: 25px;
            display: flex;
            gap: 10px;
        }

        button {
            border: none;
            padding: 12px 20px;
            background-color: #2e7d32;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #1b5e20;
        }

        .kembali {
            display: inline-block;
            padding: 12px 20px;
            background-color: #e8f5e9;
            color: #2e7d32;
            text-decoration: none;
            border-radius: 8px;
        }

        .kembali:hover {
            background-color: #c8e6c9;
        }

        @media (max-width: 600px) {
            header {
                padding: 20px;
            }

            .container {
                margin: 20px auto;
            }

            .card {
                padding: 20px;
            }

            .button-group {
                flex-direction: column;
            }

            button,
            .kembali {
                text-align: center;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>SIPALAB</h1>
    <p>Tambah Data Peminjaman Alat</p>
</header>

<div class="container">

    <div class="card">

        <h2>Tambah Peminjaman</h2>

        <form action="simpan.php" method="POST">

            <label>Peminjam</label>

            <select name="peminjam_id" required>

                <option value="">
                    -- Pilih Peminjam --
                </option>

                <?php while ($peminjam = mysqli_fetch_assoc($query_peminjam)) { ?>

                    <option value="<?php echo $peminjam['id']; ?>">
                        <?php echo $peminjam['nama']; ?>
                    </option>

                <?php } ?>

            </select>

            <label>Alat</label>

            <select name="alat_id" required>

                <option value="">
                    -- Pilih Alat --
                </option>

                <?php while ($alat = mysqli_fetch_assoc($query_alat)) { ?>

                    <option value="<?php echo $alat['id']; ?>">
                        <?php echo $alat['nama_alat']; ?>
                        - Stok: <?php echo $alat['jumlah_stok']; ?>
                    </option>

                <?php } ?>

            </select>

            <label>Jumlah</label>

            <input
                type="number"
                name="jumlah"
                min="1"
                value="1"
                required
            >

            <label>Tanggal Pinjam</label>

            <input
                type="date"
                name="tanggal_pinjam"
                required
            >

            <label>Tanggal Kembali Rencana</label>

            <input
                type="date"
                name="tanggal_kembali_rencana"
                required
            >

            <div class="button-group">

                <button type="submit">
                    Simpan Peminjaman
                </button>

                <a
                    class="kembali"
                    href="index.php"
                >
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>

</body>

</html>