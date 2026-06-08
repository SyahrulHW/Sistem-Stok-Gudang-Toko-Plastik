# 📦 Sistem Stok Gudang Toko Plastik

Sistem Stok Gudang Toko Plastik adalah aplikasi manajemen inventaris yang dirancang khusus untuk mempermudah pencatatan, pemantauan, dan pengelolaan stok barang pada toko plastik atau kemasan. Aplikasi ini membantu mengotomatisasi pencatatan barang masuk, barang keluar, dan memberikan informasi sisa stok secara *real-time*.

## ✨ Fitur Utama
- **Dashboard Informatif:** Menampilkan ringkasan total barang, stok yang menipis, serta grafik transaksi.
- **Manajemen Barang:** Fitur CRUD (Create, Read, Update, Delete) untuk mendata berbagai jenis produk plastik (kresek, mika, gelas plastik, dll).
- **Transaksi Barang Masuk (Inbound):** Pencatatan pasokan barang dari *supplier* ke dalam gudang.
- **Transaksi Barang Keluar (Outbound):** Pencatatan pengeluaran barang untuk dijual ke toko atau pelanggan.
- **Manajemen Kategori:** Pengelompokan barang berdasarkan jenis, merk, atau ukuran.
- **Laporan (Reporting):** Cetak laporan mutasi stok berdasarkan rentang waktu tertentu.

## 📐 Desain Database
Berikut adalah struktur *Entity Relationship Diagram* (ERD) yang digunakan dalam sistem ini untuk memetakan hubungan antar tabel:

![Entity Relationship Diagram](documentation/ERD/erd-db.png)

Modul,Method,Endpoint (Route),Deskripsi & Akses
Autentikasi,POST,/login,Autentikasi masuk untuk Hak Akses Admin & Karyawan
Kategori Produk,POST,/categories,Menambahkan data kategori baru ke sistem
,PUT,/categories/{id},Mengubah data kategori berdasarkan ID
Supplier,POST,/suppliers,Menambahkan data supplier baru
,PUT,/suppliers/{id},Mengubah data supplier berdasarkan ID
Produk,POST,/products,Menambahkan data produk plastik baru
,PUT,/products/{id},Mengubah rincian data produk berdasarkan ID
Transaksi Masuk,POST,/barang-masuk,Mencatat transaksi barang masuk dan menambah stok
,PUT,/barang-masuk/{id},Memperbarui/merevisi transaksi barang masuk berdasarkan ID
Transaksi Keluar,POST,/barang-keluar,Mencatat transaksi barang keluar dan mengurangi stok
,PUT,/barang-keluar/{id},Memperbarui/merevisi transaksi barang keluar berdasarkan ID

## 💻 Teknologi yang Digunakan
- **Frontend:** HTML, CSS, JavaScript, Bootstrap / TailwindCSS
- **Backend:** PHP (Laravel / CodeIgniter) / Node.js
- **Database:** MySQL / PostgreSQL

## 📋 Persyaratan Sistem
Pastikan Anda telah menginstal perangkat lunak berikut sebelum menjalankan aplikasi:
- [Git](https://git-scm.com/)
- Web Server lokal (seperti [XAMPP](https://www.apachefriends.org/index.html) atau Laragon)

## 🚀 Instalasi & Cara Penggunaan

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek secara lokal:

1. **Clone Repositori**
   ```bash
   git clone [https://github.com/SyahrulHW/Sistem-Stok-Gudang-Toko-Plastik.git](https://github.com/SyahrulHW/Sistem-Stok-Gudang-Toko-Plastik.git)