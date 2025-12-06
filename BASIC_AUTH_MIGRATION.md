# 🔐 Basic Auth Migration Documentation

## 📋 Overview

Sistem telah berhasil diubah dari **JWT/Bearer Token Authentication (Laravel Sanctum)** menjadi **Basic Authentication**. Perubahan ini menyederhanakan proses autentikasi dengan menggunakan username/password yang dikirim dalam setiap request.

## 🔄 Perubahan yang Dilakukan

### 1. Backend Changes

#### Middleware Updates
- **BasicAuthMiddleware.php**: Diperbarui untuk mendukung multiple users dan role-based access
- **BasicAuthRoleMiddleware.php**: Middleware baru untuk role checking dengan Basic Auth
- **bootstrap/app.php**: Registrasi middleware baru

#### API Routes Changes
```php
// Sebelum (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/mahasiswa', [MahasiswaApiController::class, 'apiIndex']);
});

// Sesudah (Basic Auth)
Route::middleware('basicauth')->group(function () {
    Route::get('/mahasiswa', [MahasiswaApiController::class, 'apiIndex']);
});
```

#### New Endpoints
- `GET /api/health` - Health check endpoint
- `GET /api/test-accounts` - Test accounts information
- `GET /api/user` - Get authenticated user (Basic Auth version)
- `POST /api/logout` - Logout endpoint (Basic Auth version)

### 2. Frontend Changes

#### API Client Updates (`api-client.js`)
```javascript
// Sebelum (Bearer Token)
headers['Authorization'] = `Bearer ${this.token}`;

// Sesudah (Basic Auth)
const basicAuth = btoa(`${email}:${password}`);
headers['Authorization'] = `Basic ${basicAuth}`;
```

#### Authentication Flow
- **Login**: Credentials disimpan di localStorage dan digunakan untuk setiap request
- **Logout**: Credentials dihapus dari localStorage
- **Registration**: Disabled (tidak didukung Basic Auth)

#### UI Updates
- Auth page: Menghilangkan opsi registrasi
- Dashboard: Menambahkan indikator "Basic Auth"
- Test page: Menambahkan test untuk Basic Auth

## 🔧 Technical Implementation

### Basic Auth Middleware
```php
public function handle(Request $request, Closure $next, $requiredRole = null): Response
{
    $hasCredentials = isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']);
    
    if (!$hasCredentials) {
        return $this->unauthorizedResponse();
    }

    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    $user = User::where('email', $username)->first();

    if (!$user || !Hash::check($password, $user->password)) {
        return $this->unauthorizedResponse();
    }

    // Check role if required
    if ($requiredRole && $user->role !== $requiredRole) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. You do not have permission to access this resource.',
            'required_role' => $requiredRole,
            'your_role' => $user->role
        ], 403);
    }

    $request->attributes->set('authenticated_user', $user);
    return $next($request);
}
```

### Frontend Basic Auth Implementation
```javascript
// Store credentials
storeCredentials(email, password) {
    localStorage.setItem('auth_email', email);
    localStorage.setItem('auth_password', password);
    this.credentials = { email, password };
}

// Get auth headers
getAuthHeaders() {
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };
    
    if (this.credentials) {
        const basicAuth = btoa(`${this.credentials.email}:${this.credentials.password}`);
        headers['Authorization'] = `Basic ${basicAuth}`;
    }
    
    return headers;
}
```

## 🚀 Usage Guide

### 1. Login Process
```javascript
// Frontend
const result = await apiClient.login('admin@example.com', 'password123');

// Backend validates credentials and returns user info
// Credentials stored in localStorage for subsequent requests
```

### 2. API Requests
```javascript
// Setiap request otomatis menyertakan Basic Auth header
const result = await apiClient.getAllMahasiswa();

// Header yang dikirim:
// Authorization: Basic YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM=
```

### 3. Role-Based Access
```php
// Admin only routes
Route::middleware(['basicauth', 'basicauth.role:admin'])->group(function () {
    Route::post('/mahasiswa', [MahasiswaApiController::class, 'apiStore']);
});

// All authenticated users
Route::middleware('basicauth')->group(function () {
    Route::get('/mahasiswa', [MahasiswaApiController::class, 'apiIndex']);
});
```

## 🔍 Testing

### Manual Testing
1. **Akses**: `http://localhost:8000/test-api.html`
2. **Run**: "Run All Tests" untuk comprehensive testing
3. **Check**: Success rate dan error details

### Test Accounts
```
Admin Account:
- Email: admin@example.com
- Password: password123
- Role: admin

Mahasiswa Account:
- Email: user@example.com
- Password: password123
- Role: mahasiswa
```

### cURL Testing
```bash
# Test with admin credentials
curl -X GET http://localhost:8000/api/mahasiswa \
  -u "admin@example.com:password123" \
  -H "Accept: application/json"

# Test with mahasiswa credentials
curl -X GET http://localhost:8000/api/mahasiswa \
  -u "user@example.com:password123" \
  -H "Accept: application/json"

# Test admin-only endpoint
curl -X POST http://localhost:8000/api/mahasiswa \
  -u "admin@example.com:password123" \
  -H "Content-Type: application/json" \
  -d '{"nim":"12345678","nama":"Test","jurusan":"IT","angkatan":2024,"no_hp":"081234567890","alamat":"Test"}'
```

## 🛡️ Security Considerations

### Advantages of Basic Auth
- **Simplicity**: Mudah diimplementasikan dan dipahami
- **Stateless**: Tidak perlu token management
- **Standard**: HTTP Basic Auth adalah standar yang well-established
- **No Expiry**: Tidak ada token expiry issues

### Security Notes
- **HTTPS Required**: Basic Auth harus digunakan dengan HTTPS di production
- **Credential Storage**: Credentials disimpan di localStorage (consider security implications)
- **Browser Caching**: Browser mungkin cache credentials
- **Logout**: Logout tidak benar-benar menghapus credentials dari browser

### Production Recommendations
```php
// .env for production
APP_ENV=production
APP_DEBUG=false

// Force HTTPS
APP_URL=https://your-domain.com

// Secure session settings
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
```

## 📊 Performance Impact

### Positive Impacts
- **Reduced Complexity**: No token generation/validation
- **Faster Requests**: No token lookup in database
- **Simpler State Management**: No token refresh logic

### Considerations
- **Database Query**: User lookup on every request
- **Password Hashing**: bcrypt verification on every request
- **No Caching**: User data not cached (could be optimized)

## 🔧 Configuration

### Environment Variables
```env
# Basic Auth settings (optional, for simple single-user setup)
BASIC_AUTH_USER=admin
BASIC_AUTH_PASS=password123

# Database settings remain the same
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Middleware Configuration
```php
// bootstrap/app.php
$middleware->alias([
    'basicauth' => BasicAuthMiddleware::class,
    'basicauth.role' => BasicAuthRoleMiddleware::class,
]);
```

## 🚨 Migration Checklist

### ✅ Completed
- [x] Update BasicAuthMiddleware for multi-user support
- [x] Create BasicAuthRoleMiddleware for role checking
- [x] Update API routes to use Basic Auth
- [x] Modify API client for Basic Auth
- [x] Update frontend pages (auth, dashboard, index, test)
- [x] Remove Sanctum dependencies from routes
- [x] Add health check and test endpoints
- [x] Update documentation

### 🔄 Optional Improvements
- [ ] Add rate limiting for Basic Auth
- [ ] Implement user credential caching
- [ ] Add audit logging for authentication
- [ ] Create admin panel for user management
- [ ] Add password strength requirements

## 🐛 Troubleshooting

### Common Issues

#### 1. 401 Unauthorized
```
Error: Authentication failed. Please check your credentials.
```
**Solution**: Verify email/password combination in database

#### 2. 403 Forbidden
```
Error: Access denied. Insufficient permissions.
```
**Solution**: Check user role in database matches required role

#### 3. CORS Issues
```
Error: Access to fetch blocked by CORS policy
```
**Solution**: Ensure CORS middleware is properly configured

#### 4. Credentials Not Persisting
```
Error: User logged out after page refresh
```
**Solution**: Check localStorage for auth_email and auth_password

### Debug Commands
```bash
# Check user in database
php artisan tinker
>>> User::where('email', 'admin@example.com')->first();

# Clear cache
php artisan config:clear
php artisan route:clear

# Check routes
php artisan route:list --path=api
```

## 📚 API Documentation

### Authentication Endpoints
- `GET /api/health` - Health check (public)
- `GET /api/test-accounts` - Test accounts info (public)
- `GET /api/user` - Get authenticated user (Basic Auth required)
- `POST /api/logout` - Logout (Basic Auth required)

### Mahasiswa Endpoints
- `GET /api/mahasiswa` - List all (Basic Auth required)
- `GET /api/mahasiswa/{nim}` - Get by NIM (Basic Auth required)
- `POST /api/mahasiswa` - Create (Admin only)
- `PUT /api/mahasiswa/{nim}` - Update (Admin only)
- `DELETE /api/mahasiswa/{nim}` - Delete (Admin only)

### User Management Endpoints
- `GET /api/users` - List all users (Admin only)
- `POST /api/users/mahasiswa` - Create mahasiswa account (Admin only)

## 🎯 Next Steps

1. **Test thoroughly** dengan berbagai skenario
2. **Monitor performance** setelah deployment
3. **Consider caching** untuk user lookup optimization
4. **Implement rate limiting** untuk security
5. **Add audit logging** untuk compliance

---

**✅ Migration to Basic Auth completed successfully!**

Sistem sekarang menggunakan Basic Authentication yang lebih sederhana namun tetap aman untuk environment yang tepat.
