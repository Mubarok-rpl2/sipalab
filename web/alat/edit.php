<?php
session_start();
require_once __DIR__ . "/../config.php";
/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'];

$query = mysqli_query($conn, "
    SELECT * FROM alat
    WHERE id = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data alat tidak ditemukan.");
}

$query_kategori = mysqli_query($conn, "
    SELECT * FROM kategori_alat
    ORDER BY nama_kategori ASC
");

// Menentukan gambar lama
$nama_file_lama = strtolower(
    str_replace(
        " ",
        "_",
        $data['nama_alat']
    )
);

$gambar_lama = "../../assets/processed/" . $nama_file_lama . ".png";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Alat - SIPALAB</title>

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

        .gambar-lama {
            margin-top: 10px;
            padding: 15px;
            background-color: #f1f8f3;
            border-radius: 10px;
            text-align: center;
        }

        .gambar-lama img {
            width: 180px;
            height: 130px;
            object-fit: contain;
            border-radius: 10px;
        }

        .gambar-lama p {
            margin-bottom: 10px;
            color: #555;
            font-size: 14px;
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

        .info {
            margin-top: 8px;
            font-size: 13px;
            color: #666;
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
    <p>Edit Data Alat Laboratorium</p>
</header>

<div class="container">

    <div class="card">

        <h2>Edit Alat</h2>

        <form
            action="update.php"
            method="POST"
            enctype="multipart/form-data"
        >

            <input
                type="hidden"
                name="id"
                value="<?php echo $data['id']; ?>"
            >

            <label>Kode Alat</label>

            <input
                type="text"
                name="kode_alat"
                value="<?php echo $data['kode_alat']; ?>"
                required
            >

            <label>Nama Alat</label>

            <input
                type="text"
                name="nama_alat"
                value="<?php echo $data['nama_alat']; ?>"
                required
            >

            <label>Kategori</label>

            <select name="kategori_id" required>

                <option value="">
                    -- Pilih Kategori --
                </option>

                <?php while ($kategori = mysqli_fetch_assoc($query_kategori)) { ?>

                    <option
                        value="<?php echo $kategori['id']; ?>"
                        <?php
                        if ($kategori['id'] == $data['kategori_id']) {
                            echo "selected";
                        }
                        ?>
                    >
                        <?php echo $kategori['nama_kategori']; ?>
                    </option>

                <?php } ?>

            </select>

            <label>Kondisi</label>

            <select name="kondisi" required>

                <option
                    value="baik"
                    <?php if ($data['kondisi'] == 'baik') echo 'selected'; ?>
                >
                    Baik
                </option>

                <option
                    value="rusak ringan"
                    <?php if ($data['kondisi'] == 'rusak ringan') echo 'selected'; ?>
                >
                    Rusak Ringan
                </option>

                <option
                    value="rusak berat"
                    <?php if ($data['kondisi'] == 'rusak berat') echo 'selected'; ?>
                >
                    Rusak Berat
                </option>

            </select>

            <label>Jumlah Stok</label>

            <input
                type="number"
                name="jumlah_stok"
                value="<?php echo $data['jumlah_stok']; ?>"
                min="1"
                required
            >

            <label>Gambar Alat</label>

            <div class="gambar-lama">

                <p>Gambar saat ini:</p>

                <?php if (file_exists($gambar_lama)) { ?>

                    <img
                        src="<?php echo $gambar_lama; ?>"
                        alt="Gambar <?php echo $data['nama_alat']; ?>"
                    >

                <?php } else { ?>

                    <p>Tidak ada gambar</p>

                <?php } ?>

            </div>

            <br>

            <input
                type="file"
                name="gambar_alat"
                accept=".jpg,.jpeg,.png"
            >

            <p class="info">
                Kosongkan jika tidak ingin mengganti gambar.
            </p>

            <label>Deskripsi</label>

            <textarea
                name="deskripsi"
                placeholder="Masukkan deskripsi alat..."
            ><?php echo $data['deskripsi']; ?></textarea>

            <div class="button-group">

                <button type="submit">
                    Simpan Perubahan
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