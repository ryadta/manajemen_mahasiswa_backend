<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaApiController;
use App\Http\Controllers\Api\AuthController;

// ===============================
// Public Authentication Routes (No auth required for login/register)
// ===============================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// ===============================
// Authentication Routes (Basic Auth)
// ===============================
Route::middleware('basicauth')->group(function () {
    // Auth info route
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// ===============================
// API Mahasiswa Routes (Role-Based Access Control with Basic Auth)
// ===============================

// Routes untuk semua authenticated users (Admin & Mahasiswa bisa view)
Route::middleware('basicauth')->group(function () {
    Route::controller(MahasiswaApiController::class)->group(function () {
        
        // GET: Tampilkan daftar mahasiswa (Admin & Mahasiswa)
        Route::get('/mahasiswa', 'apiIndex');
        
        // GET: Detail mahasiswa berdasarkan NIM (Admin & Mahasiswa)
        Route::get('/mahasiswa/{nim}', 'apiShow');
    });
});

// Routes khusus untuk Admin (CRUD operations)
Route::middleware(['basicauth', 'basicauth.role:admin'])->group(function () {
    Route::controller(MahasiswaApiController::class)->group(function () {
        
        // POST: Tambahkan mahasiswa baru (Admin only)
        Route::post('/mahasiswa', 'apiStore');
        
        // PUT: Ubah data mahasiswa berdasarkan NIM (Admin only)
        Route::put('/mahasiswa/{nim}', 'apiUpdate');
        
        // DELETE: Hapus mahasiswa berdasarkan NIM (Admin only)
        Route::delete('/mahasiswa/{nim}', 'apiDelete');
    });

    // User Management Routes (Admin only)
    Route::controller(\App\Http\Controllers\Api\UserManagementController::class)->group(function () {
        // Buat akun login untuk mahasiswa
        Route::post('/users/mahasiswa', 'storeMahasiswa');
        
        // Lihat semua user
        Route::get('/users', 'index');
    });
});

// ===============================
// Public Routes (No Authentication Required)
// ===============================

// Health check route
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'auth_type' => 'Basic Auth',
        'timestamp' => now()->toISOString()
    ]);
});

// Test accounts info
Route::get('/test-accounts', function () {
    return response()->json([
        'success' => true,
        'message' => 'Test accounts for Basic Auth',
        'accounts' => [
            [
                'email' => 'admin@example.com',
                'password' => 'password123',
                'role' => 'admin',
                'permissions' => 'Full CRUD access'
            ],
            [
                'email' => 'user@example.com',
                'password' => 'password123',
                'role' => 'mahasiswa',
                'permissions' => 'Read-only access'
            ]
        ],
        'usage' => 'Use email as username for Basic Auth'
    ]);
});

