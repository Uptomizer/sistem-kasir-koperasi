# Sistem Kasir & Inventaris Koperasi

Aplikasi kasir dan inventaris koperasi sekolah berbasis **Laravel 12**,  
dirancang untuk membantu pencatatan transaksi, pengelolaan stok,  
dan pembuatan laporan penjualan secara efisien.

## 🚀 Fitur Utama
- **Login Admin & Kasir** (Role-based Authentication, tanpa Breeze/Jetstream)
- **Manajemen Kategori & Barang**
- **Manajemen Stok Real-time**
- **Kasir Pintar (POS)** dengan dukungan Scan Barcode
- **Cetak Struk & Barcode Generator** (Support Printer Thermal & Label)
- **Laporan Penjualan Detail** & Analitik Grafik
- **Export Laporan ke Excel dan Cetak ke PDF**
- **Keamanan Transaksi** (Limit Input & Anti Overflow)
- **UI Modern (Tailwind CSS v4)** dengan Animasi Interaktif

## 🛠 Teknologi
- **PHP 8.2+**
- **Laravel 12**
- **SQLite / MySQL / PostgreSQL** (Fleksibel, Default: SQLite)
- **Blade Template**
- **Tailwind CSS v4**
- **JavaScript (Vanilla)**
- **Vite**
- **Chart.js** (Visualisasi Data)
- **Maatwebsite Excel** (Export Data)
- **Dompdf PDF** (Cetak PDF)

## ⚙️ Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/Uptomizer/sistem-kasir-koperasi.git
cd sistem-kasir-koperasi
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup Database
Secara default menggunakan SQLite. Jika ingin menggunakan MySQL/PostgreSQL, ubah konfigurasi DB di `.env`.

```bash
php artisan migrate
php artisan db:seed UserSeeder
```

### 5. Jalankan Aplikasi
```bash
npm run dev
# Buka terminal baru untuk serve
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`
