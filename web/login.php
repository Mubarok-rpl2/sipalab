<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPALAB</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0f5132, #198754, #75b798);
        }

        .login-container {
            width: 900px;
            max-width: 90%;
            display: flex;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .login-info {
            width: 50%;
            padding: 50px;
            background: linear-gradient(135deg, #0f5132, #198754);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-info h1 {
            font-size: 38px;
            margin-bottom: 15px;
        }

        .login-info h2 {
            font-size: 22px;
            margin-bottom: 15px;
        }

        .login-info p {
            line-height: 1.7;
            font-size: 15px;
        }

        .login-form {
            width: 50%;
            padding: 50px;
        }

        .login-form h2 {
            color: #198754;
            margin-bottom: 8px;
            font-size: 28px;
        }

        .login-form .subtitle {
            color: #777;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            outline: none;
            font-size: 15px;
        }

        .form-group input:focus {
            border-color: #198754;
            box-shadow: 0 0 5px rgba(25, 135, 84, 0.25);
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            background: #198754;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #146c43;
            transform: translateY(-2px);
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            color: #888;
            font-size: 13px;
        }

        @media (max-width: 700px) {
            .login-container {
                flex-direction: column;
            }

            .login-info,
            .login-form {
                width: 100%;
            }

            .login-info {
                padding: 35px;
                text-align: center;
            }

            .login-form {
                padding: 35px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">

        <div class="login-info">
            <h1>SIPALAB</h1>

            <h2>Sistem Informasi Peminjaman Alat Laboratorium</h2>

            <p>
                Selamat datang di SIPALAB.
                Silakan login untuk mengakses sistem
                peminjaman dan pengelolaan alat laboratorium komputer.
            </p>
        </div>

        <div class="login-form">

            <h2>Login</h2>

            <p class="subtitle">
                Masukkan akun untuk melanjutkan
            </p>

            <form action="proses_login.php" method="POST">

                <div class="form-group">
                    <label>Username</label>
                    <input
                        type="text"
                        name="username"
                        placeholder="Masukkan username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button type="submit" class="btn-login">
                    Login
                </button>

            </form>

            <div class="footer">
                SIPALAB © 2026
            </div>

        </div>

    </div>

</body>
</html>