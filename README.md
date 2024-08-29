
## Tentang Aplikasi Todo List

Aplikasi Todo List ini dibuat menggunakan Laravel 10. Aplikasi ini memungkinkan pengguna untuk menambahkan, mengedit, menghapus, dan memfilter item todo. Fitur tambahan termasuk penghapusan item todo yang sudah selesai secara massal.

## Fitur

- **Tambah Todo**: Menambahkan item todo baru.
- **Edit Todo**: Mengubah judul item todo.
- **Hapus Todo**: Menghapus item todo individual.
- **Filter Todo**: Menampilkan semua, aktif, atau item todo yang sudah selesai.
- **Hapus Semua yang Selesai**: Menghapus semua item todo yang sudah selesai.

## Prerequisites

Sebelum menjalankan proyek ini, pastikan Anda telah menginstal:

- PHP >= 8.0
- Composer
- MySQL atau database lain yang didukung Laravel

## Instalasi

Ikuti langkah-langkah di bawah ini untuk meng-clone dan menjalankan proyek ini:

1. **Clone Repository**

   ```bash
   git clone https://github.com/Dimasnotfound/todos-laravel.git

2. **Masuk Kedalam repository**

   ```bash
   cd todos-laravel

3. **Install dependencies**

   ```bash
   composer install

4. **Konfigurasi Lingkungan**

   ```bash
   cp .env.example .env

5. **Generate Key**

   ```bash
   php artisan key:generate

6. **Migrasi Database**
   ```bash
   php artisan migrate

7. **Jalankan Aplikasi**
   ```bash
   php artisan serve



