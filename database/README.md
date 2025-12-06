# Database Login System - README

## 📋 Overview
Database ini telah dikonfigurasi untuk sistem login dengan menggunakan **Laravel Sanctum** untuk API authentication.

## 🗄️ Database Structure

### Tables Created:

1. **users** - Menyimpan data pengguna
   - `id` - Primary key
   - `name` - Nama pengguna
   - `email` - Email (unique)
   - `password` - Password (hashed)
   - `email_verified_at` - Timestamp verifikasi email
   - `remember_token` - Token untuk "Remember Me"
   - `created_at`, `updated_at` - Timestamps

2. **personal_access_tokens** - Token untuk API authentication
   - Digunakan oleh Laravel Sanctum
   - Menyimpan token akses untuk setiap user

3. **password_reset_tokens** - Token untuk reset password

4. **sessions** - Session management

## 👥 Sample Users

Database telah di-seed dengan 3 user contoh:

| Name      | Email                | Password     | Role       | Permissions              |
|-----------|----------------------|--------------|------------|--------------------------|
| Admin     | admin@example.com    | password123  | admin      | Full CRUD Access         |
| Test User | user@example.com     | password123  | mahasiswa  | Read Only (View)         |
| John Doe  | john@example.com     | password123  | mahasiswa  | Read Only (View)         |

## 🚀 Setup Instructions

### 1. Jalankan Migration (jika belum)
```bash
php artisan migrate
```

### 2. Jalankan Seeder untuk membuat user contoh
```bash
php artisan db:seed --class=UserSeeder
```

### 3. Start Laravel Server
```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

## 🔌 API Endpoints

### Public Endpoints (Tidak perlu authentication):
- `POST /api/login` - Login user
- `POST /api/register` - Register user baru

### Protected Endpoints (Perlu authentication token):
- `GET /api/user` - Get data user yang sedang login
- `POST /api/logout` - Logout user

## 📝 Files Created

1. **database/seeders/UserSeeder.php**
   - Seeder untuk membuat user contoh

2. **app/Http/Controllers/Api/AuthController.php**
   - Controller untuk handle login, register, logout, dan get user

3. **routes/api.php**
   - Routes untuk authentication API

4. **database/schema.sql**
   - SQL schema dan query examples

5. **API_DOCUMENTATION.md**
   - Dokumentasi lengkap API dengan contoh penggunaan

## 🧪 Testing

### Menggunakan cURL:
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "password123"}'
```

### Menggunakan JavaScript/React:
```javascript
const [email, setEmail] = useState('');
const [password, setPassword] = useState('');

const handleLogin = async (e) => {
    e.preventDefault();
    
    const response = await fetch('http://localhost:8000/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    });
    
    const data = await response.json();
    
    if (data.success) {
        localStorage.setItem('auth_token', data.data.token);
        localStorage.setItem('user', JSON.stringify(data.data.user));
        // Redirect ke dashboard atau update state
    }
};
```

## 🔐 Security Features

1. **Password Hashing** - Password di-hash menggunakan bcrypt
2. **Token Authentication** - Menggunakan Laravel Sanctum
3. **Email Validation** - Email harus unique
4. **CSRF Protection** - Built-in Laravel protection
5. **Rate Limiting** - Dapat dikonfigurasi di routes

## 📚 Additional Resources

- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [Laravel Authentication](https://laravel.com/docs/authentication)
- Lihat `API_DOCUMENTATION.md` untuk contoh lengkap

## 🛠️ Troubleshooting

### Error: "No connection could be made"
- Pastikan database server (MySQL/SQLite) sudah running
- Cek konfigurasi `.env` file

### Error: "Table not found"
- Jalankan: `php artisan migrate`

### Error: "CORS"
- Update `config/cors.php` untuk allow origin frontend Anda

## 📞 Support

Jika ada pertanyaan atau masalah, silakan buka issue atau hubungi developer.

---

**Created:** 2025-11-22
**Laravel Version:** 11.x
**Authentication:** Laravel Sanctum
