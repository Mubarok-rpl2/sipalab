<?php
session_start();
require_once __DIR__ . "/config.php";

/** @var mysqli $conn */

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// ==========================
// TOTAL ALAT
// ==========================
$query_alat = mysqli_query($conn, "
    SELECT SUM(jumlah_stok) AS total
    FROM alat
");

$data_alat = mysqli_fetch_assoc($query_alat);
$total_alat = $data_alat['total'] ?? 0;


// ==========================
// TOTAL ALAT YANG DIPINJAM
// ==========================
$query_pinjam = mysqli_query($conn, "
    SELECT SUM(dp.jumlah) AS total
    FROM detail_peminjaman dp
    JOIN peminjaman p ON dp.peminjaman_id = p.id
    WHERE p.status IN ('dipinjam', 'terlambat')
");

$data_pinjam = mysqli_fetch_assoc($query_pinjam);
$total_pinjam = $data_pinjam['total'] ?? 0;


// ==========================
// TOTAL ALAT RUSAK
// ==========================
$query_rusak = mysqli_query($conn, "
    SELECT SUM(jumlah_stok) AS total
    FROM alat
    WHERE kondisi != 'baik'
");

$data_rusak = mysqli_fetch_assoc($query_rusak);
$total_rusak = $data_rusak['total'] ?? 0;


// ==========================
// TOTAL PEMINJAM
// ==========================
$query_peminjam = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM peminjam
");

$data_peminjam = mysqli_fetch_assoc($query_peminjam);
$total_peminjam = $data_peminjam['total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - SIPALAB</title>

    <style>

        /* ==========================
           RESET
        ========================== */

        * {
            box-sizing: border-box;
        }


        /* ==========================
           BODY
        ========================== */

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f7f4;
            color: #26352b;
        }


        /* ==========================
           HEADER
        ========================== */

        header {
            background: linear-gradient(
                135deg,
                #1b5e20,
                #2e7d32
            );

            color: white;

            padding: 25px 50px;

            box-shadow:
                0 3px 12px rgba(0, 0, 0, 0.15);
        }

        header h1 {
            margin: 0;
            font-size: 30px;
            letter-spacing: 1px;
        }

        header p {
            margin: 8px 0 0;
            font-size: 15px;
            opacity: 0.9;
        }


        /* ==========================
           CONTAINER
        ========================== */

        .container {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 35px 40px;
        }


        /* ==========================
           WELCOME
        ========================== */

        .welcome {
            background: white;

            padding: 25px 30px;

            border-radius: 15px;

            margin-bottom: 25px;

            box-shadow:
                0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .welcome h2 {
            margin: 0 0 15px;

            color: #2e7d32;

            font-size: 26px;
        }

        .welcome p {
            margin: 7px 0;

            color: #555;
        }

        .welcome strong {
            color: #2e7d32;
        }


        /* ==========================
           CARDS
        ========================== */

        .cards {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 20px;
        }

        .card {
            background: white;

            padding: 25px;

            border-radius: 15px;

            border-top: 4px solid #43a047;

            box-shadow:
                0 4px 12px rgba(0, 0, 0, 0.07);

            transition: 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);

            box-shadow:
                0 8px 20px rgba(0, 0, 0, 0.10);
        }

        .card h3 {
            margin: 0 0 15px;

            font-size: 16px;

            color: #53635a;
        }

        .number {
            font-size: 34px;

            font-weight: bold;

            color: #2e7d32;
        }


        /* ==========================
           GRAFIK
        ========================== */

        .grafik {
            margin-top: 30px;

            background: white;

            padding: 25px;

            border-radius: 15px;

            box-shadow:
                0 4px 12px rgba(0, 0, 0, 0.07);
        }

        .grafik h2 {
            margin: 0 0 20px;

            color: #2e7d32;

            font-size: 22px;
        }

        .grafik img {
            width: 100%;

            max-width: 700px;

            height: auto;

            display: block;

            margin: auto;

            border-radius: 8px;
        }


        /* ==========================
           MENU
        ========================== */

        .menu {
            margin-top: 30px;

            background: white;

            padding: 25px;

            border-radius: 15px;

            box-shadow:
                0 4px 12px rgba(0, 0, 0, 0.07);
        }

        .menu h3 {
            margin-top: 0;

            margin-bottom: 18px;

            color: #2e7d32;

            font-size: 21px;
        }

        .menu a {
            display: inline-block;

            margin-right: 8px;

            margin-bottom: 8px;

            padding: 11px 18px;

            background: #2e7d32;

            color: white;

            text-decoration: none;

            border-radius: 8px;

            font-size: 14px;

            transition: 0.2s;
        }

        .menu a:hover {
            background: #1b5e20;

            transform: translateY(-2px);
        }


        /* ==========================
           LOGOUT
        ========================== */

        .logout {
            display: inline-block;

            margin-top: 25px;

            padding: 11px 22px;

            background: #d9534f;

            color: white;

            text-decoration: none;

            border-radius: 8px;

            font-size: 14px;

            transition: 0.2s;
        }

        .logout:hover {
            background: #b52b27;

            transform: translateY(-2px);
        }


        /* ==========================
           RESPONSIVE TABLET
        ========================== */

        @media (max-width: 900px) {

            .cards {
                grid-template-columns:
                    repeat(2, 1fr);
            }

        }


        /* ==========================
           RESPONSIVE HP
        ========================== */

        @media (max-width: 600px) {

            header {
                padding: 20px 25px;
            }

            header h1 {
                font-size: 25px;
            }

            .container {
                padding: 25px 20px;
            }

            .cards {
                grid-template-columns: 1fr;
            }

            .menu a {
                display: block;

                width: 100%;

                margin-right: 0;

                text-align: center;
            }

        }

    </style>

</head>


<body>


<!-- ==========================
     HEADER
========================== -->

<header>

    <h1>SIPALAB</h1>

    <p>
        Sistem Informasi Peminjaman Alat Laboratorium Komputer
    </p>

</header>


<div class="container">


    <!-- ==========================
         WELCOME
    ========================== -->

    <div class="welcome">

        <h2>Dashboard</h2>

        <p>
            Selamat datang,
            <strong>
                <?php echo $_SESSION['nama_lengkap']; ?>
            </strong>
        </p>

        <p>
            Role:
            <strong>
                <?php echo $_SESSION['role']; ?>
            </strong>
        </p>

    </div>


    <!-- ==========================
         STATISTIK
    ========================== -->

    <div class="cards">


        <div class="card">

            <h3>Total Alat</h3>

            <div class="number">
                <?php echo $total_alat; ?>
            </div>

        </div>


        <div class="card">

            <h3>Sedang Dipinjam</h3>

            <div class="number">
                <?php echo $total_pinjam; ?>
            </div>

        </div>


        <div class="card">

            <h3>Alat Rusak</h3>

            <div class="number">
                <?php echo $total_rusak; ?>
            </div>

        </div>


        <div class="card">

            <h3>Total Peminjam</h3>

            <div class="number">
                <?php echo $total_peminjam; ?>
            </div>

        </div>


    </div>


    <!-- ==========================
         GRAFIK
    ========================== -->

    <div class="grafik">

        <h2>Grafik Kondisi Alat</h2>

        <img
            src="../assets/processed/grafik_kondisi_alat.png"
            alt="Grafik Kondisi Alat"
        >

    </div>


    <!-- ==========================
         MENU
    ========================== -->

    <div class="menu">

        <h3>Menu SIPALAB</h3>


        <a href="kategori/index.php">
            Kategori Alat
        </a>


        <a href="alat/index.php">
            Data Alat
        </a>


        <a href="peminjam/index.php">
            Data Peminjam
        </a>


        <a href="peminjaman/index.php">
            Data Peminjaman
        </a>

    </div>


    <!-- ==========================
         LOGOUT
    ========================== -->

    <a
        class="logout"
        href="logout.php"
    >
        Logout
    </a>


</div>


</body>

</html>