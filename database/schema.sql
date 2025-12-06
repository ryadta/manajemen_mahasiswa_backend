-- ========================================
-- DATABASE SCHEMA FOR LOGIN SYSTEM
-- ========================================

-- Table: users
-- Menyimpan data pengguna untuk sistem login
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Table: password_reset_tokens
-- Menyimpan token untuk reset password
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

-- Table: sessions
-- Menyimpan session pengguna yang sedang login
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
);

-- Table: personal_access_tokens
-- Menyimpan token untuk API authentication (Laravel Sanctum)
CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_tokenable (tokenable_type, tokenable_id)
);

-- ========================================
-- SAMPLE DATA
-- ========================================

-- Insert sample users (password: password123)
INSERT INTO users (name, email, password, created_at, updated_at) VALUES
('Admin', 'admin@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
('Test User', 'user@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
('John Doe', 'john@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

-- ========================================
-- QUERY EXAMPLES
-- ========================================

-- 1. Cek user berdasarkan email (untuk login)
SELECT * FROM users WHERE email = 'admin@example.com';

-- 2. Tampilkan semua users
SELECT id, name, email, created_at FROM users;

-- 3. Hitung jumlah users
SELECT COUNT(*) as total_users FROM users;

-- 4. Cari user berdasarkan nama
SELECT * FROM users WHERE name LIKE '%Admin%';

-- 5. Update user data
UPDATE users SET name = 'New Name' WHERE email = 'admin@example.com';

-- 6. Hapus user
DELETE FROM users WHERE email = 'test@example.com';

-- 7. Cek active sessions
SELECT s.*, u.name, u.email 
FROM sessions s 
LEFT JOIN users u ON s.user_id = u.id 
WHERE s.last_activity > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 MINUTE));

-- 8. Lihat semua tokens yang aktif
SELECT t.*, u.name, u.email 
FROM personal_access_tokens t
JOIN users u ON t.tokenable_id = u.id
WHERE t.tokenable_type = 'App\\Models\\User';
