<?php
session_start();
require_once __DIR__ . "/../config.php";
/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT alat.*, kategori_alat.nama_kategori
    FROM alat
    JOIN kategori_alat ON alat.kategori_id = kategori_alat.id
    ORDER BY alat.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Alat - SIPALAB</title>

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
            font-size: 30px;
            margin-bottom: 5px;
        }

        header p {
            opacity: .9;
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
            transition: .3s;
        }

        .tambah:hover {
            background: #146c43;
            transform: translateY(-2px);
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
            min-width: 950px;
        }

        th {
            background: #198754;
            color: white;
            padding: 14px 10px;
        }

        td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e5e5;
            text-align: center;
        }

        tr:hover td {
            background: #f1f8f4;
        }

        .gambar-alat {
            width: 100px;
            height: 70px;
            object-fit: contain;
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 3px;
            background: white;
        }

        .kondisi {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .baik {
            background: #d1e7dd;
            color: #0f5132;
        }

        .rusak {
            background: #fff3cd;
            color: #856404;
        }

        .aksi a {
            text-decoration: none;
            font-weight: bold;
            margin: 0 4px;
        }

        .edit {
            color: #198754;
        }

        .hapus {
            color: #dc3545;
        }

        .kembali {
            display: inline-block;
            margin-top: 20px;
            color: #198754;
            text-decoration: none;
            font-weight: bold;
        }

        .kembali:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            header {
                padding: 20px;
            }

            .container {
                padding: 0 15px;
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
        <h2>Data Alat Laboratorium</h2>

        <a class="tambah" href="tambah.php">
            + Tambah Alat
        </a>
    </div>

    <div class="table-box">

        <table>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Kode</th>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th>Kondisi</th>
                <th>Stok</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>

            <?php
            $no = 1;

            while ($data = mysqli_fetch_assoc($query)) {
            ?>

            <tr>

                <td><?php echo $no++; ?></td>

                <td>
                    <?php
                    $nama_file = strtolower(
                        str_replace(" ", "_", $data['nama_alat'])
                    );

                    $path_gambar =
                        "../../assets/processed/" .
                        $nama_file .
                        ".png";
                    ?>

                    <?php if (file_exists($path_gambar)) { ?>

                        <img
                            src="<?php echo $path_gambar; ?>"
                            class="gambar-alat"
                            alt="<?php echo $data['nama_alat']; ?>"
                        >

                    <?php } else { ?>

                        Tidak ada gambar

                    <?php } ?>
                </td>

                <td><?php echo $data['kode_alat']; ?></td>

                <td><strong><?php echo $data['nama_alat']; ?></strong></td>

                <td><?php echo $data['nama_kategori']; ?></td>

                <td>
                    <?php
                    $class = ($data['kondisi'] == 'baik')
                        ? 'baik'
                        : 'rusak';
                    ?>

                    <span class="kondisi <?php echo $class; ?>">
                        <?php echo $data['kondisi']; ?>
                    </span>
                </td>

                <td><strong><?php echo $data['jumlah_stok']; ?></strong></td>

                <td><?php echo $data['deskripsi']; ?></td>

                <td class="aksi">

                    <a
                        class="edit"
                        href="edit.php?id=<?php echo $data['id']; ?>"
                    >
                        Edit
                    </a>

                    |

                    <a
                        class="hapus"
                        href="hapus.php?id=<?php echo $data['id']; ?>"
                        onclick="return confirm('Yakin ingin menghapus alat ini?')"
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