-- ==========================================
-- DATABASE CREATION (DDL) & QUERY SAMPLES (DML)
-- Sistem Gudang Toko Plastik
-- ==========================================

-- -----------------------------------------------------
-- 1. DATA DEFINITION LANGUAGE (DDL) - TABEL DATA
-- -----------------------------------------------------

-- Membuat Database (jika belum ada)
CREATE DATABASE IF NOT EXISTS sistem_gudang_plastik;
USE sistem_gudang_plastik;

-- Tabel 1: Users
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) DEFAULT 'karyawan', -- 'admin' atau 'karyawan'
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel 2: Password Reset Tokens
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel 3: Sessions
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel 4: Categories
CREATE TABLE IF NOT EXISTS categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(255) NOT NULL,
    deskripsi TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel 5: Suppliers
CREATE TABLE IF NOT EXISTS suppliers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_supplier VARCHAR(255) UNIQUE NOT NULL,
    nama_supplier VARCHAR(255) NOT NULL,
    alamat TEXT NULL,
    telepon VARCHAR(255) NULL,
    email VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel 6: Products
CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_produk VARCHAR(255) UNIQUE NOT NULL,
    nama_produk VARCHAR(255) NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    supplier_id BIGINT UNSIGNED NOT NULL,
    satuan VARCHAR(255) NOT NULL,
    harga_beli DECIMAL(15, 2) NOT NULL,
    harga_jual DECIMAL(15, 2) NOT NULL,
    stok INT DEFAULT 0,
    minimum_stok INT DEFAULT 0,
    foto VARCHAR(255) NULL,
    deskripsi TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel 7: Barang Masuks
CREATE TABLE IF NOT EXISTS barang_masuks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nomor_transaksi VARCHAR(255) UNIQUE NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    supplier_id BIGINT UNSIGNED NOT NULL,
    tanggal DATE NOT NULL,
    jumlah INT NOT NULL,
    harga_beli DECIMAL(15, 2) NOT NULL,
    total_harga DECIMAL(15, 2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel 8: Barang Keluars
CREATE TABLE IF NOT EXISTS barang_keluars (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nomor_transaksi VARCHAR(255) UNIQUE NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    tanggal DATE NOT NULL,
    jumlah INT NOT NULL,
    keterangan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- 2. DATA MANIPULATION LANGUAGE (DML) - CONTOH QUERY
-- -----------------------------------------------------

-- A. QUERY UNTUK DASHBOARD / RINGKASAN
-- -----------------------------------------------------

-- Mengambil total seluruh produk unik
SELECT COUNT(*) AS total_produk FROM products;

-- Mengambil total supplier aktif
SELECT COUNT(*) AS total_supplier FROM suppliers;

-- Menghitung total jumlah barang masuk secara keseluruhan
SELECT SUM(jumlah) AS total_barang_masuk FROM barang_masuks;

-- Menghitung total jumlah barang keluar secara keseluruhan
SELECT SUM(jumlah) AS total_barang_keluar FROM barang_keluars;

-- Menampilkan produk yang stoknya di bawah batas minimum (Alert Stok Menipis)
SELECT kode_produk, nama_produk, stok, minimum_stok 
FROM products 
WHERE stok <= minimum_stok;


-- B. QUERY TRANSAKSI BARANG MASUK (Menambah Stok)
-- -----------------------------------------------------

-- 1. Insert ke tabel barang_masuks
INSERT INTO barang_masuks (nomor_transaksi, product_id, supplier_id, tanggal, jumlah, harga_beli, total_harga, created_at, updated_at)
VALUES ('BM-20260608-0001', 1, 1, '2026-06-08', 50, 8500.00, 425000.00, NOW(), NOW());

-- 2. Trigger pembaruan stok di tabel products (dilakukan otomatis via Controller)
UPDATE products 
SET stok = stok + 50 
WHERE id = 1;


-- C. QUERY TRANSAKSI BARANG KELUAR (Mengurangi Stok)
-- -----------------------------------------------------

-- 1. Sebelum memotong stok, periksa ketersediaan stok produk
SELECT stok, nama_produk FROM products WHERE id = 1;

-- 2. Jika stok cukup, masukkan ke tabel barang_keluars
INSERT INTO barang_keluars (nomor_transaksi, product_id, tanggal, jumlah, keterangan, created_at, updated_at)
VALUES ('BK-20260608-0001', 1, '2026-06-08', 12, 'Pengeluaran Toko Cabang A', NOW(), NOW());

-- 3. Kurangi stok di tabel products
UPDATE products 
SET stok = stok - 12 
WHERE id = 1;


-- D. QUERY LAPORAN TRANSAKSI (Gabungan Barang Masuk & Keluar)
-- -----------------------------------------------------

SELECT 
    'Barang Masuk' AS tipe_transaksi,
    bm.nomor_transaksi,
    p.nama_produk,
    bm.tanggal,
    bm.jumlah,
    bm.harga_beli AS harga_satuan,
    bm.total_harga,
    s.nama_supplier AS detail_info
FROM barang_masuks bm
JOIN products p ON bm.product_id = p.id
JOIN suppliers s ON bm.supplier_id = s.id

UNION ALL

SELECT 
    'Barang Keluar' AS tipe_transaksi,
    bk.nomor_transaksi,
    p.nama_produk,
    bk.tanggal,
    bk.jumlah,
    p.harga_jual AS harga_satuan,
    (bk.jumlah * p.harga_jual) AS total_harga,
    bk.keterangan AS detail_info
FROM barang_keluars bk
JOIN products p ON bk.product_id = p.id

ORDER BY tanggal DESC;
