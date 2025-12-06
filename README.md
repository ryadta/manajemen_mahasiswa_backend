# Manajemen Mahasiswa Backend

Aplikasi backend REST API untuk manajemen data mahasiswa berbasis Laravel. Sistem ini menerapkan autentikasi **Basic Auth** dengan **Role-Based Access Control (RBAC)** untuk memisahkan hak akses antara Admin dan Mahasiswa.

## 📋 Prasyarat

Pastikan lingkungan pengembangan Anda memiliki:
* **PHP** >= 8.2
* **Composer**
* **SQLite** (Database default yang dikonfigurasi)

## 🛠️ Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di local:

1.  **Clone Repository**
    ```bash
    git clone <url-repository-anda>
    cd manajemen_mahasiswa_backend
    ```

2.  **Instal Dependensi**
    ```bash
    composer install
    ```

3.  **Konfigurasi Environment**
    Salin file konfigurasi contoh `.env`.
    ```bash
    cp .env.example .env
    ```

4.  **Setup Database (SQLite)**
    Buat file database kosong untuk SQLite.

    *Untuk Mac/Linux:*
    ```bash
    touch database/database.sqlite
    ```
    
    *Untuk Windows (CMD):*
    ```cmd
    type nul > database\database.sqlite
    ```
    
    *Untuk Windows (PowerShell):*
    ```powershell
    New-Item database/database.sqlite -ItemType File
    ```

5.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

6.  **Migrasi dan Seeding Data**
    Jalankan migrasi database dan isi data awal (termasuk akun admin & user test).
    ```bash
    php artisan migrate --seed
    ```

## 🚀 Menjalankan Server

Jalankan server pengembangan Laravel:

```bash
php artisan serve
