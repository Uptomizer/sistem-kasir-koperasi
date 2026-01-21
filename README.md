# Sistem Kasir & Inventaris Koperasi SMK

Aplikasi kasir dan inventaris koperasi sekolah berbasis **Laravel 12**,  
dirancang untuk membantu pencatatan transaksi, pengelolaan stok,  
dan pembuatan laporan penjualan secara efisien.

## 🚀 Fitur Utama
- Login Admin & Kasir (tanpa Breeze/Jetstream)
- Manajemen Kategori & Barang
- Manajemen Stok
- Transaksi Kasir Otomatis
- Laporan Penjualan Detail
- Export Laporan ke Excel
- UI Modern (Tailwind CSS)
- Animasi & UX Enhancement

## 🛠 Teknologi
- PHP 8.4
- Laravel 12
- PostgreSQL
- Blade Template
- Tailwind CSS
- JavaScript (Vanilla)
- Vite

## ⚙️ Instalasi
```bash
git clone https://github.com/Uptomizer/sistem-kasir-koperasi.git
cd sistem-kasir-koperasi
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed UserSeeder
npm run dev
php artisan serve
