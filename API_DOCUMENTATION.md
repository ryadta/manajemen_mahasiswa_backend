# API AUTHENTICATION DOCUMENTATION

## Base URL
```
http://localhost:8000/api
```

## Endpoints

### 1. Register (Daftar User Baru)
**POST** `/register`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response Success (201):**
```json
{
    "success": true,
    "message": "Registration successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz..."
    }
}
```

---

### 2. Login
**POST** `/login`

**Request Body:**
```json
{
    "email": "admin@example.com",
    "password": "password123"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin",
            "email": "admin@example.com"
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz..."
    }
}
```

**Response Error (422):**
```json
{
    "message": "The provided credentials are incorrect.",
    "errors": {
        "email": [
            "The provided credentials are incorrect."
        ]
    }
}
```

---

### 3. Get User (Protected)
**GET** `/user`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Admin",
            "email": "admin@example.com",
            "email_verified_at": null,
            "created_at": "2025-11-22T12:00:00.000000Z",
            "updated_at": "2025-11-22T12:00:00.000000Z"
        }
    }
}
```

---

### 4. Logout (Protected)
**POST** `/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Logout successful"
}
```

---

## Sample Users (Seeded)

| Name      | Email                | Password     |
|-----------|----------------------|--------------|
| Admin     | admin@example.com    | password123  |
| Test User | user@example.com     | password123  |
| John Doe  | john@example.com     | password123  |

---

## 👥 User Management (Admin Only)

### 10. Create Mahasiswa Account
Membuat akun login baru untuk mahasiswa.

**POST** `/api/users/mahasiswa`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Permissions:** ✅ Admin, ❌ Mahasiswa

**Request Body:**
```json
{
    "name": "Budi Santoso",
    "email": "budi@student.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response Success (201):**
```json
{
    "success": true,
    "message": "Akun mahasiswa berhasil dibuat",
    "data": {
        "id": 5,
        "name": "Budi Santoso",
        "email": "budi@student.com",
        "role": "mahasiswa",
        "created_at": "2025-11-22T14:30:00.000000Z"
    }
}
```

### 11. List All Users
Melihat daftar semua user di sistem.

**GET** `/api/users`

**Headers:**
```
Authorization: Bearer {admin_token}
```

**Permissions:** ✅ Admin, ❌ Mahasiswa

**Response Success (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Admin",
            "email": "admin@example.com",
            "role": "admin",
            "created_at": "..."
        },
        {
            "id": 2,
            "name": "Budi",
            "email": "budi@student.com",
            "role": "mahasiswa",
            "created_at": "..."
        }
    ]
}
```

---

## Testing dengan cURL

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'
```

### Register
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "New User",
    "email": "newuser@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Get User (dengan token)
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Logout (dengan token)
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Testing dengan JavaScript (Fetch API)

### Login
```javascript
const login = async (email, password) => {
    try {
        const response = await fetch('http://localhost:8000/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Simpan token
            localStorage.setItem('auth_token', data.data.token);
            localStorage.setItem('user', JSON.stringify(data.data.user));
            console.log('Login successful:', data);
        } else {
            console.error('Login failed:', data);
        }
        
        return data;
    } catch (error) {
        console.error('Error:', error);
    }
};

// Contoh penggunaan
login('admin@example.com', 'password123');
```

### Register
```javascript
const register = async (name, email, password, passwordConfirmation) => {
    try {
        const response = await fetch('http://localhost:8000/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name,
                email,
                password,
                password_confirmation: passwordConfirmation
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            localStorage.setItem('auth_token', data.data.token);
            localStorage.setItem('user', JSON.stringify(data.data.user));
            console.log('Registration successful:', data);
        }
        
        return data;
    } catch (error) {
        console.error('Error:', error);
    }
};
```

### Get User
```javascript
const getUser = async () => {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch('http://localhost:8000/api/user', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
            }
        });
        
        const data = await response.json();
        console.log('User data:', data);
        return data;
    } catch (error) {
        console.error('Error:', error);
    }
};
```

### Logout
```javascript
const logout = async () => {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch('http://localhost:8000/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            console.log('Logout successful');
        }
        
        return data;
    } catch (error) {
        console.error('Error:', error);
    }
};
```

---

## React Integration Example

```javascript
import { useState } from 'react';

function LoginForm() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');

    const handleLogin = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError('');

        try {
            const response = await fetch('http://localhost:8000/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (data.success) {
                // Simpan token dan user data
                localStorage.setItem('auth_token', data.data.token);
                localStorage.setItem('user', JSON.stringify(data.data.user));
                
                // Redirect atau update state
                console.log('Login successful!');
                // window.location.href = '/dashboard';
            } else {
                setError(data.message || 'Login failed');
            }
        } catch (err) {
            setError('An error occurred. Please try again.');
            console.error('Error:', err);
        } finally {
            setLoading(false);
        }
    };

    return (
        <form onSubmit={handleLogin}>
            {error && <div className="error">{error}</div>}
            
            <input
                type="email"
                placeholder="Email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
            />
            
            <input
                type="password"
                placeholder="Password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
            />
            
            <button type="submit" disabled={loading}>
                {loading ? 'Loading...' : 'Login'}
            </button>
        </form>
    );
}

export default LoginForm;
```

---

## CORS Configuration

Jika frontend dan backend di domain berbeda, tambahkan di `config/cors.php`:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:3000', 'http://localhost:5173'],
'supports_credentials' => true,
```
