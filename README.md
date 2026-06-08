# Sistem Informasi Manajemen Gudang Toko Plastik

Aplikasi berbasis web untuk mengelola dan memonitor persediaan barang, transaksi barang masuk, barang keluar, serta pembuatan laporan stok pada Gudang Toko Plastik. Sistem ini dibuat menggunakan framework **Laravel** dan **Vite** untuk performa yang optimal dan antarmuka pengguna yang responsif.

---

## 🚀 Fitur Utama

Sistem ini mencakup berbagai modul penting untuk pengelolaan stok dan inventaris gudang:

1. **Dashboard Ringkasan**
   - Menampilkan metrik utama seperti total produk, jumlah supplier, total transaksi barang masuk dan keluar, serta peringatan (*alert*) produk dengan stok menipis.
2. **Manajemen Kategori Produk**
   - Pengelompokan produk berdasarkan jenis/kategori plastik untuk mempermudah pencarian dan pengarsipan data.
3. **Manajemen Supplier (Pemasok)**
   - Menyimpan data kontak pemasok barang, termasuk kode supplier, nama, alamat, nomor telepon, dan email.
4. **Manajemen Produk & Stok**
   - Pencatatan data produk lengkap dengan kode unik, nama produk, satuan, harga beli, harga jual, jumlah stok saat ini, batas minimum stok, serta foto produk.
5. **Transaksi Barang Masuk**
   - Pencatatan stok barang yang masuk dari supplier. Secara otomatis menambah jumlah stok produk yang bersangkutan.
6. **Transaksi Barang Keluar**
   - Pencatatan pengeluaran barang dari gudang. Sistem secara otomatis memotong stok produk dan memvalidasi kecukupan stok sebelum diproses.
7. **Laporan & Cetak**
   - Fitur ekspor laporan transaksi barang masuk dan barang keluar ke format **CSV** dan fitur **Print/Cetak** langsung dari browser.
8. **Keamanan & Autentikasi**
   - Sistem login untuk melindungi data inventaris dan membagi akses berdasarkan hak pengguna (Role-based Access Control).

---

## 🔑 Hak Akses Pengguna (Role-based Access Control)

Aplikasi memiliki dua level pengguna dengan hak akses yang berbeda:

* **Admin Gudang**
  * Hak akses penuh (CRUD) ke semua modul: Kategori, Supplier, dan Produk.
  * Dapat melihat, menambah, mengedit, dan menghapus transaksi Barang Masuk & Barang Keluar.
  * Mengakses dan mencetak laporan transaksi.
* **Karyawan Gudang**
  * Dapat melihat daftar Kategori, Supplier, dan Produk (Read-only).
  * Dapat menginput (tambah) dan mengedit transaksi Barang Masuk & Barang Keluar.
  * *Tidak dapat menghapus* transaksi barang masuk/keluar demi menjaga integritas data stok.
  * Mengakses dan mencetak laporan transaksi.

---

## 🛠️ Persyaratan Sistem

Pastikan perangkat Anda sudah menginstal beberapa software berikut sebelum memulai instalasi:

* **PHP** >= 8.3
* **Composer** (untuk manajemen package PHP)
* **Node.js & npm** (untuk build tools CSS/JS menggunakan Vite)
* **Database Server** (MySQL/MariaDB, bisa menggunakan Laragon, XAMPP, atau Docker)

---

## 📦 Panduan Instalasi dan Setup

Ikuti langkah-langkah di bawah ini untuk menjalankan project ini di komputer lokal Anda:

### 1. Clone Repositori
```bash
git clone https://github.com/SyahrulHW/Sistem-Stok-Gudang-Toko-Plastik.git
cd Sistem-Stok-Gudang-Toko-Plastik
```

### 2. Instal Dependency PHP (Composer)
```bash
composer install
```

### 3. Buat File Konfigurasi `.env`
Duplikat file `.env.example` menjadi `.env` lalu sesuaikan konfigurasi koneksi database Anda:
```bash
cp .env.example .env
```
Sesuaikan bagian database berikut pada file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi Database dan Seeding Data
Buat database kosong terlebih dahulu di database server Anda (misal: MySQL) dengan nama yang sesuai di `.env`. Kemudian jalankan perintah berikut untuk membuat tabel dan mengisi data awal (seeding):
```bash
php artisan migrate --seed
```

### 6. Instal dan Build Asset Frontend
Instal dependensi JavaScript dan lakukan build aset menggunakan Vite:
```bash
npm install
npm run build
```

### 7. Jalankan Server Lokal
Untuk menjalankan server pengembangan:
```bash
php artisan serve
```
Aplikasi Anda akan siap diakses melalui browser di alamat [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 🔐 Kredensial Akun Default (Hasil Seeder)

Untuk login pertama kali setelah menjalankan `--seed`, gunakan akun bawaan berikut:

| Peran (Role) | Email Login | Password |
| :--- | :--- | :--- |
| **Admin Gudang** | `admin@plastik.com` | `password` |
| **Karyawan Gudang** | `karyawan@plastik.com` | `password` |

---

## 📊 Rancangan Database (ERD)

Rancangan database (Entity-Relationship Diagram) aplikasi ini didefinisikan menggunakan format DBML (Database Markup Language) dan dapat diimpor langsung ke website [dbdiagram.io](https://dbdiagram.io).

* File skema DBML: [database/database_schema.dbml](database/database_schema.dbml)
* Petunjuk cara impor dan visualisasi ERD: [db_erd_schema.md](C:/Users/Acer/.gemini/antigravity/brain/846e62e1-ee3c-4689-9002-042130a5c7cd/db_erd_schema.md) (tersedia di folder brain artifact)