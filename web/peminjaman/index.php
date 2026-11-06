<?php
session_start();
require_once __DIR__ . "/../config.php";
/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT
        peminjaman.*,
        peminjam.nama AS nama_peminjam,
        users.nama_lengkap AS nama_petugas
    FROM peminjaman
    JOIN peminjam
        ON peminjaman.peminjam_id = peminjam.id
    JOIN users
        ON peminjaman.user_id = users.id
    ORDER BY peminjaman.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Peminjaman - SIPALAB</title>

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
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 25px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
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
            min-width: 1000px;
        }

        th {
            background: #198754;
            color: white;
            padding: 14px 10px;
        }

        td {
            padding: 13px 10px;
            border-bottom: 1px solid #e5e5e5;
            text-align: center;
        }

        tr:hover td {
            background: #f1f8f4;
        }

        .status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .dipinjam {
            background: #cff4fc;
            color: #055160;
        }

        .dikembalikan {
            background: #d1e7dd;
            color: #0f5132;
        }

        .terlambat {
            background: #f8d7da;
            color: #842029;
        }

        .pengembalian {
            color: #198754;
            text-decoration: none;
            font-weight: bold;
        }

        .sudah {
            color: #777;
            font-size: 14px;
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
        <h2>Data Peminjaman Alat</h2>

        <a class="tambah" href="tambah.php">
            + Tambah Peminjaman
        </a>
    </div>

    <div class="table-box">

        <table>

            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Petugas</th>
                <th>Tanggal Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Kembali Aktual</th>
                <th>Status</th>
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
                        <?php echo $data['nama_peminjam']; ?>
                    </strong>
                </td>

                <td>
                    <?php echo $data['nama_petugas']; ?>
                </td>

                <td>
                    <?php echo $data['tanggal_pinjam']; ?>
                </td>

                <td>
                    <?php echo $data['tanggal_kembali_rencana']; ?>
                </td>

                <td>
                    <?php
                    echo $data['tanggal_kembali_aktual'] ?? '-';
                    ?>
                </td>

                <td>

                    <?php
                    $status_class = $data['status'];
                    ?>

                    <span class="status <?php echo $status_class; ?>">
                        <?php echo $data['status']; ?>
                    </span>

                </td>

                <td>

                    <?php if ($data['status'] != 'dikembalikan') { ?>

                        <a
                            class="pengembalian"
                            href="pengembalian.php?id=<?php echo $data['id']; ?>"
                        >
                            Pengembalian
                        </a>

                    <?php } else { ?>

                        <span class="sudah">
                            Sudah Dikembalikan
                        </span>

                    <?php } ?>

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