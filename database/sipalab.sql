CREATE DATABASE sipalab;
USE sipalab;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'petugas') NOT NULL DEFAULT 'petugas',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kategori_alat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL UNIQUE,
    deskripsi VARCHAR(255) NULL
);

-- dan seterusnya untuk tabel alat,
-- peminjam, peminjaman,
-- dan detail_peminjaman