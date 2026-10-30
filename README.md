# 🌿 Diesel Parts — Sistem Manajemen Inventaris, Penjualan & Keuangan

Aplikasi web profesional berbasis **Laravel 12** yang dirancang khusus untuk manajemen persediaan suku cadang mesin diesel (*Diesel Engine Spareparts & Heavy Equipment*). Sistem ini mengintegrasikan pencatatan produk, kategori, supplier, stok masuk/pembelian, pembayaran utang supplier, kasir penjualan, **laporan keuangan resmi (khusus Admin)**, dan **kelola akun pengguna (khusus Admin)** dengan antarmuka modern bertema **Hijau Matcha (Matcha Green)**.

---

## 🎨 Tampilan & Desain Tema Hijau Matcha

Antarmuka sistem telah diperbarui dengan estetika **Matcha Green** yang profesional, bersih, dan nyaman di mata:
* **Sidebar**: Nuansa *Deep Ceremonial Matcha* (`#15291a` s/d `#102014`) dengan indikator menu aktif beraksen daun matcha.
* **Komponen & Tombol**: *Matcha Green* elegan (`#3d6f46`, hover `#315a39`, soft `#eaf3ed`).
* **Latar Belakang**: *Matcha Mist* lembut (`#f3f7f4`).
* **Tipografi**: Menggunakan font modern **Plus Jakarta Sans**.
* **Indikator Status (Badges)**:
  * Lunas / Selesai: Hijau Matcha Segar (`#e6f6eb` / text `#166534`).
  * Belum Lunas / Sebagian: *Golden Amber* (`#fef7e0` / text `#925f05`).
  * Belum Dibayar / Stok Kritis: *Soft Rose* (`#fde8e8` / text `#b81c1c`).
* **Responsif**: Mendukung tampilan Desktop, Tablet, dan Mobile dengan animasi transisi yang mulus.

---

## 🚀 Fitur-Fitur Utama Sistem

### 1. 📊 Dashboard Eksekutif
* Ringkasan metrik instan: Total Produk, Total Supplier, Total Transaksi Penjualan, dan Total Omzet.
* Peringatan **Stok Kritis**: Menampilkan produk dengan stok di bawah batas minimum (*low stock warning*).
* Tabel **Penjualan Terbaru**: 5 transaksi penjualan terakhir beserta kasir dan nominal.
* **Akses Cepat (Quick Actions)**: Pintasan langsung ke modul-modul penting.

---

### 2. 🏷️ Kategori Produk (Terhubung Langsung dengan Produk)
* **Kategori Terintegrasi**: Setiap produk dapat dikaitkan dengan kelompok kategori (misal: *Filter & Penyaring*, *Sistem Bahan Bakar*, *Sistem Pendingin & Radiator*, *Pelumas & Oli*, dll).
* **Input Otomatis Produk**: Saat menginput produk baru, pengguna dapat memilih kategori yang ada atau **mengetik kategori baru** — sistem akan **otomatis membuat kategori tersebut** di database jika belum pernah ada.
* **Fitur Pencarian Kategori**: Memudahkan pencarian kategori berdasarkan nama kategori, kode acuan (seperti `FLT`, `OIL`, `RAD`), maupun deskripsi.
* **Rincian Produk per Kategori**: Halaman detail kategori menampilkan seluruh produk yang tergabung di dalamnya.
* **Pengaturan Hak Akses**:
  * **Admin**: Dapat Menambah, Mengedit, Menghapus, dan Melihat kategori.
  * **Staff**: **Hanya Bisa Melihat (Read-Only)** daftar kategori dan produk di dalamnya.

---

### 3. 📦 Manajemen Produk
* Pencatatan SKU unik, nama barang, supplier, kategori, harga beli (modal), harga jual, stok fisik, dan batas minimum stok.
* Pencarian cepat berdasarkan nama produk, SKU, kategori, atau nama supplier.
* Form penambahan & pengubahan produk interaktif via sliding drawer modern.
* Validasi pencegahan penghapusan jika produk sudah pernah digunakan dalam transaksi pembelian atau penjualan.

---

### 4. 🏢 Manajemen Supplier
* Pendataan informasi pemasok barang: nama perusahaan/supplier, nomor telepon, alamat email, dan alamat fisik.
* Riwayat barang yang dipasok oleh supplier bersangkutan.

---

### 5. 📥 Stok Masuk (Pembelian dari Supplier)
* Pencatatan nota pembelian (*Purchase Order*) barang dari supplier.
* Penambahan stok produk secara otomatis setelah transaksi pembelian disimpan.
* Pelacakan tagihan utang: total faktur pembelian, jumlah yang sudah dibayar, tanggal jatuh tempo, dan sisa utang yang belum lunas.
* **Pencatatan Pembayaran Utang**: Fitur input cicilan/pelunasan pembayaran utang ke supplier dengan berbagai metode (Tunai, Transfer Bank, dll).

---

### 6. 💰 Transaksi Penjualan (Kasir / Point of Sale)
* Pencatatan transaksi penjualan ke pelanggan dengan nomor nota otomatis (`PJ-...`).
* Pemilihan barang, jumlah kuantitas, harga jual, perhitungan subtotal, total belanja, nominal bayar, dan uang kembalian.
* **Pengurangan Stok Otomatis**: Stok produk berkurang secara otomatis saat transaksi disimpan.
* Proteksi validasi stok: Transaksi ditolak jika stok barang tidak mencukupi untuk mencegah *overselling*.

---

### 7. 📈 Laporan Keuangan & Arus Kas (Eksklusif Khusus Admin)
> 🔒 **Keamanan Ketat:** Menu dan halaman ini **HANYA DAPAT DIAKSES OLEH ADMINISTRATOR**. Pengguna berlevel Staff tidak melihat menu ini dan otomatis diblokir (*403 Forbidden*) jika mencoba mengakses URL.

* **Ringkasan Finansial Eksekutif**:
  * **Total Omzet & Pendapatan**: Akumulasi nilai transaksi penjualan pada periode yang dipilih.
  * **Total HPP (Harga Pokok Penjualan)**: Dihitung secara akurat dari harga modal beli barang yang laku terjual.
  * **Estimasi Laba Kotor & Margin**: Menampilkan keuntungan kotor (`Omzet - HPP`) serta persentase margin keuntungan.
  * **Arus Kas (Cash Flow)**:
    * **Kas Masuk**: Penerimaan uang tunai dari penjualan.
    * **Kas Keluar**: Pengeluaran uang tunai untuk pembayaran faktur supplier.
    * **Arus Kas Bersih (*Net Cash Flow*)**: Selisih riil antara Kas Masuk dan Kas Keluar.
  * **Kewajiban Utang & Aset**: Sisa utang periode berjalan, total akumulasi seluruh sisa utang toko, dan estimasi nilai aset seluruh stok barang saat ini.
* **Filter Periode Lengkap**:
  * 📅 **Harian**: Memilih hari/tanggal tertentu.
  * 📆 **Mingguan**: Memilih acuan minggu (rentang 7 hari).
  * 🗓️ **Bulanan**: Memilih bulan dan tahun tertentu.
  * 📈 **Tahunan**: Memilih tahun tertentu.
  * ⚙️ **Rentang Tanggal Kustom**: Bebas menentukan tanggal awal (*Start Date*) hingga tanggal akhir (*End Date*).
* **Tabel Rincian Interaktif**: Tab Penjualan, Tab Pembelian Stok, Tab Pembayaran Kas Supplier, dan Tab Performa Produk Terlaris & Keuntungan.
* **🖨️ Cetak Laporan Resmi**:
  * Dokumen resmi ber-Kop Surat **DIESEL PARTS - INVENTORY MANAGEMENT**.
  * Dilengkapi informasi periode filter, tanggal cetak, dan identitas Admin pencetak.
  * Tabel ringkasan eksekutif laba rugi, rincian penjualan, rincian pembelian, serta kolom pengesahan tanda tangan (*Petugas Keuangan* dan *Pimpinan*).
  * Dioptimalkan dengan CSS `@media print` sehingga bersih saat dicetak ke printer fisik atau diekspor ke format PDF.

---

### 8. 👥 Kelola Akun Pengguna / User Management (Eksklusif Khusus Admin)
> 🔒 **Keamanan Ketat:** Pengelolaan akun login sistem **HANYA DAPAT DIAKSES OLEH ADMINISTRATOR**.

* **Daftar Akun Pengguna**: Melihat seluruh akun login, avatar inisial, alamat email, badge peran (🛡️ Admin / 👤 Staff), dan tanggal pendaftaran.
* **Pencarian & Filter**: Pencarian berdasarkan nama/email serta filter dropdown peran pengguna.
* **Tambah Akun Baru (`/users/create`)**: Mendaftarkan akun login baru dengan menentukan nama, email, hak akses (Admin/Staff), dan kata sandi.
* **Edit Akun (`/users/{id}/edit`)**: Memperbarui profil akun pengguna, mengubah peran, atau mereset kata sandi (opsional).
* **Hapus Akun Pengguna**:
  * Menghapus akun yang sudah tidak bekerja.
  * **Perlindungan Keamanan**:
    * Admin tidak dapat menghapus akunnya sendiri yang sedang aktif digunakan login.
    * Sistem mencegah penghapusan jika akun bersangkutan adalah Admin terakhir di sistem.
    * Sistem mencegah penghapusan jika akun memiliki riwayat transaksi penjualan/pembelian untuk melindungi data pembukuan.

---

## 🛡️ Matriks Hak Akses (Role Access Matrix)

| Fitur / Modul | Administrator | Staff |
| :--- | :---: | :---: |
| **Login & Logout** | ✅ Ya | ✅ Ya |
| **Lihat Dashboard** | ✅ Ya | ✅ Ya |
| **Lihat Produk & Supplier** | ✅ Ya | ✅ Ya |
| **Tambah, Edit, Hapus Produk** | ✅ Ya | ❌ Tidak |
| **Tambah, Edit, Hapus Supplier** | ✅ Ya | ❌ Tidak |
| **Lihat Kategori Produk** | ✅ Ya | ✅ **Ya (Lihat & Cari)** |
| **Tambah, Edit, Hapus Kategori** | ✅ **Ya** | ❌ **Tidak (Read-Only)** |
| **Catat Pembelian Stok Masuk** | ✅ Ya | ✅ Ya |
| **Catat Pembayaran Utang Supplier**| ✅ Ya | ✅ Ya |
| **Edit & Hapus Pembelian Stok** | ✅ Ya | ❌ Tidak |
| **Catat Penjualan (Kasir POS)** | ✅ Ya | ✅ Ya |
| **Laporan Keuangan & Arus Kas** | ✅ **Ya (Akses Penuh)** | ❌ **Diblokir (403)** |
| **Cetak Laporan Keuangan Resmi** | ✅ **Ya** | ❌ **Diblokir (403)** |
| **Kelola Pengguna (User Management)**| ✅ **Ya (CRUD Akun)** | ❌ **Diblokir (403)** |

---

## 💻 Persyaratan Sistem

* **PHP**: Versi 8.2 atau lebih baru
* **Ekstensi PHP**: `OpenSSL`, `PDO`, `PDO_MySQL`, `Mbstring`, `Tokenizer`, `XML`, `Ctype`, `JSON`, `cURL`
* **Database**: MySQL 5.7+ atau MariaDB 10.3+ (misal via XAMPP)
* **Dependency Manager**: Composer 2.x
* **Web Server**: Apache (XAMPP) atau PHP Built-in Server

---

## 🛠️ Panduan Instalasi Lokal

### 1. Clone atau Buka Folder Project
Buka direktori project di terminal / Command Prompt / PowerShell:
```bash
cd c:\xampp\htdocs\inventory-uts
```

### 2. Pasang Dependency Composer
```bash
composer install
```

### 3. Konfigurasi File Environment (`.env`)
Salin file `.env.example` menjadi `.env` jika belum ada:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi database lokal pada file `.env`:
```dotenv
APP_NAME="Diesel Parts"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_uts
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Database Migration
Jalankan migrasi database untuk membuat seluruh tabel:
```bash
php artisan migrate
```

### 6. Jalankan Server Aplikasi
Anda dapat mengakses aplikasi melalui Apache XAMPP di:
```text
http://localhost/inventory-uts/public
```
Atau menggunakan server bawaan Artisan:
```bash
php artisan serve
```
Kemudian buka browser di:
```text
http://127.0.0.1:8000
```

---

## 🔑 Akun Demo Pengujian Bawaan

Sistem telah dilengkapi akun contoh bawaan untuk pengujian:

| Peran (Role) | Alamat Email | Kata Sandi | Keterangan Akses |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@dieselparts.test` | `password` | **Akses Penuh:** Laporan Keuangan, Kelola Pengguna, CRUD Produk, Kategori, Supplier |
| **Staff** | `staff@dieselparts.test` | `password` | **Akses Operasional:** Transaksi Kasir, Stok Masuk, Lihat Produk, Lihat Kategori |

---

## 🧪 Pengujian Otomatis (Automated Testing)

Project telah dilengkapi pengujian otomatis *Feature & Unit Testing* menggunakan PHPUnit untuk memastikan seluruh logika bisnis, relasi kategori, hak akses role, dan keamanan berjalan sempurna.

Jalankan seluruh test dengan perintah:
```bash
php artisan test
```

**Cakupan Uji Coba (21 Tests, 71+ Assertions):**
* `CategoryAndUserTest`:
  * Akses publik diblokir ke halaman login.
  * Staff hanya bisa melihat & mencari kategori (tidak bisa create/edit/delete).
  * Admin dapat membuat, mengedit, dan menghapus kategori.
  * Input produk otomatis menghubungkan dan membuat kategori baru di database.
  * Staff diblokir dari kelola pengguna (*403 Forbidden*).
  * Admin dapat membuat akun baru, mengedit, dan menghapus pengguna.
  * Proteksi admin tidak dapat menghapus akunnya sendiri.
* `FinancialReportTest`:
  * Staff diblokir dari laporan keuangan & cetak laporan (*403 Forbidden*).
  * Admin dapat membuka laporan keuangan dengan seluruh filter periode (Harian, Mingguan, Bulanan, Tahunan, Kustom).
  * Admin dapat membuka halaman cetak laporan resmi.
* `CoreWorkflowTest`:
  * Alur login, dashboard, produk, supplier, pembelian stok, dan transaksi penjualan.

---

## 📁 Struktur Direktori Utama

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php            # Logika login & logout
│   │   ├── CategoryController.php        # Modul Kategori Produk (CRUD Admin, Read-only Staff)
│   │   ├── DashboardController.php       # Dashboard metrik & ringkasan
│   │   ├── FinancialReportController.php # Laporan Keuangan, Arus Kas & Cetak Resmi (Khusus Admin)
│   │   ├── ProductController.php         # Modul Produk (terhubung ke Kategori & Supplier)
│   │   ├── PurchaseController.php        # Pembelian stok masuk dari supplier
│   │   ├── PurchasePaymentController.php # Pembayaran pelunasan utang supplier
│   │   ├── SaleController.php            # Kasir penjualan & pengurangan stok otomatis
│   │   ├── SupplierController.php        # Modul pemasok barang
│   │   └── UserController.php            # Kelola Akun Pengguna / User Management (Khusus Admin)
│   └── Middleware/
│       └── RoleMiddleware.php            # Pemeriksaan hak akses role (admin/staff)
└── Models/
    ├── Category.php                      # Model Kategori (relasi HasMany ke Product)
    ├── Product.php                       # Model Produk (relasi BelongsTo ke Category & Supplier)
    ├── Purchase.php                      # Model Pembelian
    ├── PurchaseItem.php                  # Model Item Pembelian
    ├── PurchasePayment.php               # Model Pembayaran Utang Supplier
    ├── Sale.php                          # Model Penjualan
    ├── SaleItem.php                      # Model Item Penjualan
    ├── Supplier.php                      # Model Supplier
    └── User.php                          # Model Pengguna & Role

resources/views/
├── auth/login.blade.php                  # Halaman Login bertema Matcha Green
├── categories/                           # Tampilan Kategori (Index, Create, Edit, Show)
├── layout/app.blade.php                  # Master layout profesional tema Matcha Green
├── products/                             # Tampilan Produk & Drawer Form
├── purchases/                            # Tampilan Pembelian Stok & Pelunasan Utang
├── reports/
│   ├── financial.blade.php               # Laporan Keuangan Interaktif & Filter Periode
│   └── print.blade.php                   # Format Cetak Laporan Keuangan Resmi
├── sales/                                # Tampilan Transaksi Kasir Penjualan
├── suppliers/                            # Tampilan Data Supplier
├── users/                                # Tampilan Kelola Akun Pengguna (Index, Create, Edit)
└── dashboard.blade.php                   # Tampilan Dashboard Utama
```

---

## 📄 Lisensi & Hak Cipta

Project ini dikembangkan sebagai solusi sistem informasi persediaan, kasir, dan keuangan suku cadang mesin diesel (*Diesel Parts Inventory System*).
