# 📦 Sistem Stok Gudang Toko Plastik

Sistem Stok Gudang Toko Plastik adalah aplikasi manajemen inventaris yang dirancang khusus untuk mempermudah pencatatan, pemantauan, dan pengelolaan stok barang pada toko plastik atau kemasan. Aplikasi ini membantu mengotomatisasi pencatatan barang masuk, barang keluar, dan memberikan informasi sisa stok secara *real-time*.

---

## ✨ Fitur Utama
- **Dashboard Informatif:** Menampilkan ringkasan total barang, stok yang menipis, serta grafik transaksi.
- **Manajemen Barang:** Fitur CRUD (Create, Read, Update, Delete) untuk mendata berbagai jenis produk plastik (kresek, mika, gelas plastik, dll).
- **Transaksi Barang Masuk (Inbound):** Pencatatan pasokan barang dari *supplier* ke dalam gudang.
- **Transaksi Barang Keluar (Outbound):** Pencatatan pengeluaran barang untuk dijual ke toko atau pelanggan.
- **Manajemen Kategori:** Pengelompokan barang berdasarkan jenis, merk, atau ukuran.
- **Laporan (Reporting):** Cetak laporan mutasi stok berdasarkan rentang waktu tertentu.

---

## 📐 Desain Database
Berikut adalah struktur *Entity Relationship Diagram* (ERD) yang digunakan dalam sistem ini untuk memetakan hubungan antar tabel:

![Entity Relationship Diagram](documentation/ERD/erd-db.png)

---

## 📋 Daftar Endpoint API
Aplikasi ini menyediakan endpoint web yang mendukung respons JSON (jika menyertakan header `Accept: application/json`). Berikut adalah daftar rute yang tersedia beserta deskripsi dan batasan hak aksesnya:

| Modul | Method | Endpoint (Route) | Hak Akses | Deskripsi & Kegunaan |
| :--- | :---: | :--- | :--- | :--- |
| **Autentikasi** | `GET` | `/login` | Guest | Menampilkan halaman login |
| | `POST` | `/login` | Guest | Autentikasi masuk untuk Admin & Karyawan |
| | `ANY` | `/logout` | Auth | Keluar dari sistem dan menghapus sesi |
| **Dashboard** | `GET` | `/` | Auth | Menampilkan ringkasan total barang, stok tipis, & statistik |
| **Kategori** | `GET` | `/categories` | Auth | Menampilkan daftar kategori produk plastik |
| | `GET` | `/categories/create` | Admin Only | Form tambah kategori baru |
| | `POST` | `/categories` | Admin Only | Menyimpan kategori baru ke sistem |
| | `GET` | `/categories/{category}/edit` | Admin Only | Form edit kategori berdasarkan ID |
| | `PUT` | `/categories/{category}` | Admin Only | Mengubah data kategori berdasarkan ID |
| | `DELETE` | `/categories/{category}` | Admin Only | Menghapus data kategori dari database |
| **Supplier** | `GET` | `/suppliers` | Auth | Menampilkan daftar supplier produk plastik |
| | `GET` | `/suppliers/create` | Admin Only | Form tambah supplier baru |
| | `POST` | `/suppliers` | Admin Only | Menyimpan data supplier baru |
| | `GET` | `/suppliers/{supplier}/edit` | Admin Only | Form edit supplier berdasarkan ID |
| | `PUT` | `/suppliers/{supplier}` | Admin Only | Mengubah data supplier berdasarkan ID |
| | `DELETE` | `/suppliers/{supplier}` | Admin Only | Menghapus data supplier |
| **Produk** | `GET` | `/products` | Auth | Menampilkan produk (pencarian & filter kategori) |
| | `GET` | `/products/create` | Admin Only | Form tambah produk plastik baru |
| | `POST` | `/products` | Admin Only | Menyimpan data produk baru (termasuk unggah foto) |
| | `GET` | `/products/{product}/edit` | Admin Only | Form edit rincian produk |
| | `PUT` | `/products/{product}` | Admin Only | Mengubah rincian data produk berdasarkan ID |
| | `DELETE` | `/products/{product}` | Admin Only | Menghapus data produk dari database |
| **Barang Masuk** | `GET` | `/barang-masuk` | Auth | Menampilkan riwayat transaksi masuk (inbound) |
| | `GET` | `/barang-masuk/create` | Auth | Form tambah transaksi barang masuk |
| | `POST` | `/barang-masuk` | Auth | Mencatat transaksi barang masuk dan menambah stok produk |
| | `GET` | `/barang-masuk/{barangMasuk}/edit` | Auth | Form edit transaksi barang masuk |
| | `PUT` | `/barang-masuk/{barangMasuk}` | Auth | Memperbarui transaksi masuk dan menyesuaikan stok |
| | `DELETE` | `/barang-masuk/{barangMasuk}` | Admin Only | Menghapus riwayat transaksi masuk dan memotong stok |
| **Barang Keluar** | `GET` | `/barang-keluar` | Auth | Menampilkan riwayat transaksi keluar (outbound) |
| | `GET` | `/barang-keluar/create` | Auth | Form tambah transaksi barang keluar |
| | `POST` | `/barang-keluar` | Auth | Mencatat transaksi barang keluar dan mengurangi stok produk |
| | `GET` | `/barang-keluar/{barangKeluar}/edit` | Auth | Form edit transaksi barang keluar |
| | `PUT` | `/barang-keluar/{barangKeluar}` | Auth | Memperbarui transaksi keluar dan menyesuaikan stok |
| | `DELETE` | `/barang-keluar/{barangKeluar}` | Admin Only | Menghapus riwayat transaksi keluar dan menambah stok |
| **Laporan** | `GET` | `/reports` | Auth | Menampilkan halaman laporan mutasi stok barang |
| | `GET` | `/reports/print` | Auth | Mencetak laporan mutasi stok ke PDF / Cetak |
| | `GET` | `/reports/export` | Auth | Mengekspor laporan mutasi stok ke format file CSV |

> [!NOTE]
> File koleksi Postman tersedia di repositori ini dengan nama `Sistem_Gudang_Toko_Plastik.postman_collection.json`. Anda dapat langsung mengimpor file ini ke aplikasi Postman Anda untuk pengujian API.

---
## Testing & Dokumentasi API (Postman)
###   1. Autentikasi



---

## 💻 Teknologi yang Digunakan
- **Backend:** PHP ^8.3 & Laravel ^13.8 (dengan Laravel Tinker)
- **Frontend:** Blade Templates & Tailwind CSS ^4.0 (dijalankan menggunakan Vite ^8.0 & `@tailwindcss/vite`)
- **Database:** MySQL / SQLite
- **Pengujian API:** Postman Collection

---

## 📋 Persyaratan Sistem
Pastikan perangkat Anda telah menginstal komponen berikut sebelum menjalankan aplikasi:
- **PHP** (Versi >= 8.3)
- **Composer**
- **Node.js & NPM**
- **Web Server** seperti Laragon (sangat disarankan di Windows), XAMPP, atau server lokal lainnya.
- **Git**

---

## 🚀 Instalasi & Cara Penggunaan

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek secara lokal:

1. **Clone Repositori**
   ```bash
   git clone https://github.com/SyahrulHW/Sistem-Stok-Gudang-Toko-Plastik.git
   cd Sistem-Stok-Gudang-Toko-Plastik
   ```

2. **Jalankan Setup Otomatis**
   Proyek ini dilengkapi dengan skrip *setup* otomatis di `composer.json` yang akan melakukan instalasi dependensi backend, pembuatan file `.env`, pembuatan *application key*, migrasi database, instalasi modul NPM, serta build aset.
   ```bash
   composer setup
   ```

3. **Jalankan Seeder Database (Opsional)**
   Gunakan perintah berikut untuk mengisi database dengan data default (seperti data kategori, supplier, produk, transaksi, dan akun pengguna):
   ```bash
   php artisan db:seed
   ```

4. **Jalankan Server Pengembangan**
   Anda dapat menjalankan server Laravel dan compiler aset Vite secara bersamaan dengan menjalankan perintah berikut:
   ```bash
   composer dev
   ```
   Aplikasi akan berjalan secara lokal di [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 🔑 Akun Uji Coba (Default Credentials)
Setelah menjalankan `php artisan db:seed`, Anda dapat masuk menggunakan salah satu dari akun uji coba berikut:

* **Akun Admin**
  - **Email:** `admin@plastik.com`
  - **Password:** `password`
  - **Hak Akses:** Penuh (CRUD Produk, Supplier, Kategori, Hapus Transaksi)

* **Akun Karyawan**
  - **Email:** `karyawan@plastik.com`
  - **Password:** `password`
  - **Hak Akses:** Terbatas (Melihat data, CRUD Transaksi Masuk/Keluar tanpa hak hapus transaksi, tidak dapat menambah/mengedit Master Data Produk/Supplier/Kategori)