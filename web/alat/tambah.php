<?php
session_start();
require_once __DIR__ . "/../config.php";
/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$query_kategori = mysqli_query($conn, "
    SELECT * FROM kategori_alat
    ORDER BY nama_kategori ASC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Alat - SIPALAB</title>

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
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2e7d32;
        }

        .info {
            margin-top: 8px;
            font-size: 13px;
            color: #666;
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
    <p>Tambah Data Alat Laboratorium</p>
</header>

<div class="container">

    <div class="card">

        <h2>Tambah Alat</h2>

        <form
            action="simpan.php"
            method="POST"
            enctype="multipart/form-data"
        >

            <label>Kode Alat</label>

            <input
                type="text"
                name="kode_alat"
                placeholder="Contoh: ALT009"
                required
            >

            <label>Nama Alat</label>

            <input
                type="text"
                name="nama_alat"
                placeholder="Masukkan nama alat"
                required
            >

            <label>Kategori</label>

            <select name="kategori_id" required>

                <option value="">
                    -- Pilih Kategori --
                </option>

                <?php while ($kategori = mysqli_fetch_assoc($query_kategori)) { ?>

                    <option value="<?php echo $kategori['id']; ?>">
                        <?php echo $kategori['nama_kategori']; ?>
                    </option>

                <?php } ?>

            </select>

            <label>Kondisi</label>

            <select name="kondisi" required>

                <option value="baik">Baik</option>
                <option value="rusak ringan">Rusak Ringan</option>
                <option value="rusak berat">Rusak Berat</option>

            </select>

            <label>Jumlah Stok</label>

            <input
                type="number"
                name="jumlah_stok"
                min="1"
                value="1"
                required
            >

            <label>Gambar Alat</label>

            <input
                type="file"
                name="gambar_alat"
                accept=".jpg,.jpeg,.png"
            >

            <p class="info">
                Pilih gambar alat dengan format JPG, JPEG, atau PNG.
            </p>

            <label>Deskripsi</label>

            <textarea
                name="deskripsi"
                placeholder="Masukkan deskripsi alat..."
            ></textarea>

            <div class="button-group">

                <button type="submit">
                    Simpan Data
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