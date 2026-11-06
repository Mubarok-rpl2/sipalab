<?php
session_start();
require_once __DIR__ . "/../config.php";
/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT * FROM peminjam
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Peminjam - SIPALAB</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f8f4;
            color: #26352c;
        }

        header {
            background: linear-gradient(135deg, #0f5132, #198754);
            color: white;
            padding: 25px 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,.12);
        }

        header h1 {
            margin-bottom: 5px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 25px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .top h2 {
            color: #0f5132;
        }

        .tambah {
            background: #198754;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 10px;
            font-weight: bold;
        }

        .tambah:hover {
            background: #146c43;
        }

        .table-box {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        th {
            background: #198754;
            color: white;
            padding: 14px;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #e5e5e5;
            text-align: center;
        }

        tr:hover td {
            background: #f1f8f4;
        }

        .edit {
            color: #198754;
            text-decoration: none;
            font-weight: bold;
        }

        .hapus {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }

        .kembali {
            display: inline-block;
            margin-top: 20px;
            color: #198754;
            text-decoration: none;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            header {
                padding: 20px;
            }

            .top {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>SIPALAB</h1>
    <p>Sistem Informasi Peminjaman Alat Laboratorium Komputer</p>
</header>

<div class="container">

    <div class="top">
        <h2>Data Peminjam</h2>

        <a class="tambah" href="tambah.php">
            + Tambah Peminjam
        </a>
    </div>

    <div class="table-box">

        <table>

            <tr>
                <th>No</th>
                <th>NIS/NIP</th>
                <th>Nama</th>
                <th>Kelas/Jabatan</th>
                <th>No HP</th>
                <th>Aksi</th>
            </tr>

            <?php
            $no = 1;

            while ($data = mysqli_fetch_assoc($query)) {
            ?>

            <tr>

                <td><?php echo $no++; ?></td>

                <td>
                    <strong>
                        <?php echo $data['nis_nip']; ?>
                    </strong>
                </td>

                <td><?php echo $data['nama']; ?></td>

                <td><?php echo $data['kelas_jabatan']; ?></td>

                <td><?php echo $data['no_hp']; ?></td>

                <td>

                    <a
                        class="edit"
                        href="edit.php?id=<?php echo $data['id']; ?>"
                    >
                        Edit
                    </a>

                    &nbsp; | &nbsp;

                    <a
                        class="hapus"
                        href="hapus.php?id=<?php echo $data['id']; ?>"
                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                    >
                        Hapus
                    </a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

    <a class="kembali" href="../dashboard.php">
        ← Kembali ke Dashboard
    </a>

</div>

</body>
</html>