# Inventory UTS — Sistem Inventaris dan Penjualan

Aplikasi web berbasis Laravel untuk membantu pencatatan produk, supplier, stok masuk/pembelian, pembayaran supplier, dan penjualan. Project ini dibuat untuk memenuhi tugas UTS Integrated Laravel Project.

## Fitur

- Login dan logout.
- Pembagian akses berdasarkan role `admin` dan `staff`.
- Dashboard ringkasan inventaris dan transaksi.
- Pengelolaan produk: tambah, lihat, ubah, hapus, pencarian, dan pagination.
- Pengelolaan supplier.
- Pencatatan pembelian/stok masuk beserta item pembelian.
- Pencatatan pembayaran untuk tagihan pembelian supplier.
- Pencatatan penjualan beserta item penjualan.
- Validasi input pada form dan pemeriksaan stok saat penjualan.
- Perlindungan agar produk yang telah digunakan dalam transaksi tidak dihapus begitu saja.

> Catatan: modul kategori sengaja tidak disertakan dalam versi project ini.

## Teknologi

- PHP 8.2 atau lebih baru yang kompatibel dengan project
- Laravel 12
- MySQL/MariaDB
- Composer
- Node.js dan npm
- Blade, CSS, dan JavaScript

## Persyaratan

Pastikan perangkat sudah memiliki:
- PHP 8.2+
- Composer
- MySQL atau MariaDB (misalnya melalui XAMPP)
- Node.js dan npm

Ekstensi PHP yang diperlukan mengikuti kebutuhan Laravel 12, termasuk `OpenSSL`, `PDO`, `Mbstring`, `Tokenizer`, `XML`, `Ctype`, `JSON`, dan `cURL`.

## Instalasi Lokal

### 1. Clone repository

```bash
git clone <URL_REPOSITORY_GITHUB>
cd inventory-uts
```

Jika project sudah berada di komputer, buka folder project tersebut di terminal.

### 2. Pasang dependency PHP

```bash
composer install
```

### 3. Buat file environment

Di Windows Command Prompt:

```bat
copy .env.example .env
```

Di PowerShell:

```powershell
Copy-Item .env.example .env
```

Jika file `.env` sudah ada, jangan menimpanya.

### 4. Atur database

Buat database baru melalui phpMyAdmin, misalnya bernama `inventory_uts`.

Buka `.env` dan sesuaikan konfigurasi database lokal:

```dotenv
APP_NAME="Inventory UTS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_uts
DB_USERNAME=root
DB_PASSWORD=
```

Nilai di atas merupakan contoh konfigurasi XAMPP lokal. Sesuaikan dengan konfigurasi MySQL di perangkat masing-masing. Jangan unggah file `.env` ke GitHub.

### 5. Buat application key

```bash
php artisan key:generate
```

### 6. Jalankan migration dan seeder

```bash
php artisan migrate --seed
```

Perintah ini membuat tabel database dan menjalankan seeder yang tersedia. Jalankan pada database pengembangan yang baru atau yang memang disiapkan untuk instalasi. Jangan gunakan `migrate:fresh` pada database yang berisi data penting karena perintah itu menghapus tabel.

**Akun pengguna:** seeder bawaan saat ini membuat pengguna contoh `test@example.com`. Periksa `database/seeders/DatabaseSeeder.php` dan `database/factories/UserFactory.php` untuk mengetahui konfigurasi pengguna contoh. Untuk penggunaan admin/staff, pastikan akun dan nilai `role` disiapkan sesuai kebutuhan project. Jangan mengandalkan akun contoh sebagai akun produksi.

### 7. Pasang dependency frontend dan build asset

```bash
npm install
npm run build
```

### 8. Jalankan aplikasi

```bash
php artisan serve
```

Buka alamat berikut di browser:

http://127.0.0.1:8000

Jika PHP belum terdaftar di PATH pada Windows XAMPP, jalankan Artisan menggunakan path PHP XAMPP, misalnya:

```powershell
C:\xampp\php\php.exe artisan serve
```

## Struktur Folder Penting

```text
app/
  Http/Controllers/   # Logika request aplikasi
  Http/Middleware/    # Middleware akses berdasarkan role
  Models/             # Model Eloquent
database/
  migrations/         # Struktur tabel
  seeders/            # Data awal
resources/views/      # Tampilan Blade
routes/web.php        # Route aplikasi
public/               # Entry point dan asset publik
```

## Pengujian

Jalankan test Laravel dengan:

```bash
php artisan test
```

Lakukan juga pemeriksaan manual untuk:
- Login dengan akun yang valid dan tidak valid.
- Akses halaman sesuai role admin/staff.
- Validasi SKU unik dan nilai input.
- Pembelian yang menambah stok.
- Penjualan yang mengurangi stok.
- Penjualan ketika stok tidak mencukupi.
- Pembayaran supplier dan perhitungan sisa tagihan.
- Pencarian, pagination, dan tampilan detail transaksi.

Hasil pengujian perlu dicatat dan dilengkapi screenshot sebagai bukti pengujian project.

## Keamanan dan Catatan

- File `.env`, password, token, dan kredensial pribadi tidak boleh diunggah ke repository.
- Folder `vendor/` dan `node_modules/` tidak perlu di-commit; dependency dapat dipasang kembali menggunakan Composer dan npm.
- Jangan gunakan data pelanggan atau data pribadi sungguhan untuk demo.
- Project ini ditujukan untuk pembelajaran dan demonstrasi lokal; lakukan pengujian keamanan dan konfigurasi tambahan sebelum digunakan di lingkungan produksi.

## Pengembang

Project UTS — Integrated Laravel Project.

Tambahkan nama pengembang, kelas, dan informasi mata kuliah di sini jika diperlukan oleh ketentuan pengumpulan.
