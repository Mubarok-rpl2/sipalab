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
    SELECT * FROM peminjam
    WHERE id = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data peminjam tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Peminjam - SIPALAB</title>

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
            max-width: 700px;
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

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus {
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
    <p>Edit Data Peminjam</p>
</header>

<div class="container">

    <div class="card">

        <h2>Edit Peminjam</h2>

        <form action="update.php" method="POST">

            <input
                type="hidden"
                name="id"
                value="<?php echo $data['id']; ?>"
            >

            <label>NIS/NIP</label>

            <input
                type="text"
                name="nis_nip"
                value="<?php echo $data['nis_nip']; ?>"
                required
            >

            <label>Nama</label>

            <input
                type="text"
                name="nama"
                value="<?php echo $data['nama']; ?>"
                required
            >

            <label>Kelas/Jabatan</label>

            <input
                type="text"
                name="kelas_jabatan"
                value="<?php echo $data['kelas_jabatan']; ?>"
                required
            >

            <label>No HP</label>

            <input
                type="text"
                name="no_hp"
                value="<?php echo $data['no_hp']; ?>"
            >

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