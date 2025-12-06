# 🔗 WEB API INTEGRATION DOCUMENTATION

## 📋 Overview

Dokumentasi ini menjelaskan integrasi lengkap antara **Frontend Web Application** dengan **Backend Laravel API** untuk sistem manajemen mahasiswa.

## 🏗️ Arsitektur Sistem

```
┌─────────────────┐    HTTP/AJAX    ┌─────────────────┐
│   Frontend      │ ◄────────────► │   Backend       │
│   (Vanilla JS)  │                │   (Laravel API) │
│                 │                │                 │
│ • HTML/CSS/JS   │                │ • REST API      │
│ • Bootstrap UI  │                │ • Sanctum Auth  │
│ • API Client    │                │ • SQLite DB     │
└─────────────────┘                └─────────────────┘
```

## 📁 Struktur File Frontend

```
public/
├── index.html              # Landing page dengan auto-redirect
├── auth.html               # Halaman login/register
├── dashboard.html          # Dashboard utama dengan CRUD
└── js/
    ├── api-client.js       # API client untuk komunikasi backend
    └── dashboard.js        # Logic dashboard dan CRUD operations
```

## 🔌 API Endpoints yang Terintegrasi

### Authentication
- `POST /api/login` - User login
- `POST /api/register` - User registration  
- `POST /api/logout` - User logout
- `GET /api/user` - Get current user info

### Mahasiswa Management
- `GET /api/mahasiswa` - List all mahasiswa (All users)
- `GET /api/mahasiswa/{nim}` - Get mahasiswa by NIM (All users)
- `POST /api/mahasiswa` - Create mahasiswa (Admin only)
- `PUT /api/mahasiswa/{nim}` - Update mahasiswa (Admin only)
- `DELETE /api/mahasiswa/{nim}` - Delete mahasiswa (Admin only)

### User Management
- `GET /api/users` - List all users (Admin only)
- `POST /api/users/mahasiswa` - Create mahasiswa account (Admin only)

## 🔐 Authentication Flow

### 1. Login Process
```javascript
// Frontend: api-client.js
const result = await apiClient.login(email, password);

// Backend Response
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": { "id": 1, "name": "Admin", "email": "admin@example.com", "role": "admin" },
        "token": "1|abcdefghijklmnopqrstuvwxyz..."
    }
}

// Frontend: Store token
localStorage.setItem('auth_token', token);
localStorage.setItem('user', JSON.stringify(user));
```

### 2. API Request with Authentication
```javascript
// Frontend: Automatic header injection
headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
}

// Backend: Sanctum middleware validation
Route::middleware('auth:sanctum')->group(function () {
    // Protected routes
});
```

## 🎯 Role-Based Access Control

### Frontend Implementation
```javascript
// Check user role
const isAdmin = apiClient.isAdmin();
const isMahasiswa = apiClient.isMahasiswa();

// Show/hide UI elements based on role
if (isAdmin) {
    document.getElementById('admin-controls').style.display = 'block';
} else {
    document.getElementById('admin-controls').style.display = 'none';
}
```

### Backend Implementation
```php
// Role middleware
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/mahasiswa', [MahasiswaApiController::class, 'apiStore']);
    Route::put('/mahasiswa/{nim}', [MahasiswaApiController::class, 'apiUpdate']);
    Route::delete('/mahasiswa/{nim}', [MahasiswaApiController::class, 'apiDelete']);
});
```

## 📊 CRUD Operations Flow

### 1. Create Mahasiswa
```javascript
// Frontend: Validation
const validation = apiClient.validateMahasiswaData(formData);
if (!validation.isValid) {
    showAlert('danger', validation.errors.join('<br>'));
    return;
}

// API Call
const result = await apiClient.createMahasiswa(formData);

// Backend: Validation & Storage
$request->validate([
    'nim' => 'required|numeric|digits_between:8,10|unique:mahasiswas,nim',
    'nama' => 'required|string|max:100',
    // ... other validations
]);

$mahasiswa = Mahasiswa::create($request->all());
```

### 2. Read Mahasiswa
```javascript
// Frontend: Load data
const result = await apiClient.getAllMahasiswa();

// Render in DataTable
this.dataTable = $('#mahasiswa-table').DataTable({
    data: result.data,
    columns: [/* column definitions */]
});
```

### 3. Update Mahasiswa
```javascript
// Frontend: Load existing data
const result = await apiClient.getMahasiswaByNim(nim);
// Fill form with existing data
document.getElementById('nama').value = mahasiswa.nama;

// Save changes
await apiClient.updateMahasiswa(nim, formData);
```

### 4. Delete Mahasiswa
```javascript
// Frontend: Confirmation
if (!confirm(`Apakah Anda yakin ingin menghapus ${mahasiswaName}?`)) {
    return;
}

// API Call
await apiClient.deleteMahasiswa(nim);

// Reload data
await this.loadMahasiswaData();
```

## 🌐 CORS Configuration

### Backend Configuration
```php
// config/cors.php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => [
    'http://localhost:3000',
    'http://localhost:8000',
    'http://127.0.0.1:8000'
],
'supports_credentials' => true,
```

### Custom CORS Middleware
```php
// app/Http/Middleware/CorsMiddleware.php
public function handle(Request $request, Closure $next): Response
{
    if ($request->getMethod() === "OPTIONS") {
        return response('', 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
    
    return $next($request)
        ->header('Access-Control-Allow-Origin', '*');
}
```

## 🔄 Error Handling

### Frontend Error Handling
```javascript
async handleResponse(response) {
    const data = await response.json();
    
    if (!response.ok) {
        // Handle authentication errors
        if (response.status === 401) {
            this.logout();
            throw new Error('Session expired. Please login again.');
        }
        
        // Handle validation errors
        if (response.status === 422 && data.errors) {
            const errorMessages = Object.values(data.errors).flat();
            throw new Error(errorMessages.join(', '));
        }
        
        throw new Error(data.message || `HTTP Error: ${response.status}`);
    }
    
    return data;
}
```

### Backend Error Responses
```php
// Validation Error (422)
{
    "message": "The given data was invalid.",
    "errors": {
        "nim": ["The nim field is required."],
        "nama": ["The nama field is required."]
    }
}

// Authentication Error (401)
{
    "success": false,
    "message": "Unauthenticated."
}

// Authorization Error (403)
{
    "success": false,
    "message": "Unauthorized. You do not have permission to access this resource.",
    "required_role": "admin",
    "your_role": "mahasiswa"
}
```

## 📱 Responsive UI Features

### Bootstrap Integration
```html
<!-- Responsive table -->
<div class="table-responsive">
    <table id="mahasiswa-table" class="table table-striped table-hover">
        <!-- Table content -->
    </table>
</div>

<!-- Mobile-friendly modals -->
<div class="modal fade" id="mahasiswaModal" tabindex="-1">
    <div class="modal-dialog">
        <!-- Modal content -->
    </div>
</div>
```

### DataTables Integration
```javascript
this.dataTable = $('#mahasiswa-table').DataTable({
    responsive: true,
    pageLength: 10,
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
    }
});
```

## 🧪 Testing & Validation

### Frontend Validation
```javascript
validateMahasiswaData(data) {
    const errors = [];
    
    if (!data.nim || !/^\d{8,10}$/.test(data.nim)) {
        errors.push('NIM harus berupa angka 8-10 digit');
    }
    
    if (!data.no_hp || !/^08\d{8,12}$/.test(data.no_hp)) {
        errors.push('No HP harus dimulai dengan 08 dan 10-14 digit');
    }
    
    return { isValid: errors.length === 0, errors };
}
```

### Backend Validation
```php
$request->validate([
    'nim' => 'required|numeric|digits_between:8,10|unique:mahasiswas,nim',
    'nama' => 'required|string|max:100',
    'jurusan' => 'required|string|max:100',
    'angkatan' => 'required|integer|min:2000|max:' . date('Y'),
    'no_hp' => 'required|regex:/^08[0-9]{8,12}$/',
    'alamat' => 'required|string|max:255'
]);
```

## 🚀 Deployment & Setup

### 1. Backend Setup
```bash
# Install dependencies
composer install

# Setup database
php artisan migrate
php artisan db:seed

# Start server
php artisan serve
```

### 2. Frontend Setup
```bash
# No build process required - vanilla JS
# Just serve the public directory
# Files are ready to use
```

### 3. Access URLs
- **Landing Page**: `http://localhost:8000/index.html`
- **Login**: `http://localhost:8000/auth.html`
- **Dashboard**: `http://localhost:8000/dashboard.html`
- **API Base**: `http://localhost:8000/api`

## 🔧 Configuration Options

### API Client Configuration
```javascript
// Change base URL if needed
const apiClient = new MahasiswaAPIClient('http://your-api-domain.com/api');

// Custom headers
const customHeaders = {
    'X-Custom-Header': 'value'
};
```

### Environment Variables
```env
# .env file
APP_URL=http://localhost:8000
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
SESSION_DOMAIN=localhost
```

## 📊 Performance Optimizations

### 1. Caching Strategy
```javascript
// Cache mahasiswa data
localStorage.setItem('mahasiswa_cache', JSON.stringify(data));
localStorage.setItem('cache_timestamp', Date.now());

// Check cache validity (5 minutes)
const cacheAge = Date.now() - localStorage.getItem('cache_timestamp');
if (cacheAge < 300000) {
    // Use cached data
}
```

### 2. Lazy Loading
```javascript
// Load data only when needed
async loadMahasiswaData() {
    if (this.mahasiswaData.length === 0) {
        const result = await apiClient.getAllMahasiswa();
        this.mahasiswaData = result.data;
    }
}
```

## 🔍 Debugging Tips

### 1. Network Debugging
```javascript
// Enable detailed logging
console.log('API Request:', url, config);
console.log('API Response:', data);

// Check network tab in browser dev tools
// Look for CORS errors, 401/403 responses
```

### 2. Authentication Debugging
```javascript
// Check token validity
console.log('Token:', localStorage.getItem('auth_token'));
console.log('User:', JSON.parse(localStorage.getItem('user')));

// Test API endpoint directly
fetch('/api/user', {
    headers: { 'Authorization': `Bearer ${token}` }
});
```

## 🎯 Best Practices

### 1. Security
- Always validate data on both frontend and backend
- Use HTTPS in production
- Implement rate limiting
- Sanitize user inputs

### 2. User Experience
- Show loading states during API calls
- Provide clear error messages
- Implement auto-logout on token expiry
- Use responsive design principles

### 3. Code Organization
- Separate API logic from UI logic
- Use consistent naming conventions
- Implement proper error handling
- Add comprehensive comments

## 📚 Additional Resources

- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [Bootstrap Documentation](https://getbootstrap.com/docs/)
- [DataTables Documentation](https://datatables.net/)
- [Fetch API Documentation](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API)

---

**✅ Integrasi web API telah berhasil dikonfigurasi dan siap digunakan!**
