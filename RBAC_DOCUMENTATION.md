# ROLE-BASED ACCESS CONTROL (RBAC) DOCUMENTATION

## 🎯 Overview

Sistem ini menggunakan **Role-Based Access Control** dengan 2 role:

| Role      | Permissions                                    |
|-----------|------------------------------------------------|
| **Admin** | Create, Read, Update, Delete (Full CRUD)      |
| **Mahasiswa** | Read Only (View data mahasiswa)            |

---

## 👥 User Roles

### Sample Users:

| Name      | Email                | Password     | Role       |
|-----------|----------------------|--------------|------------|
| Admin     | admin@example.com    | password123  | admin      |
| Test User | user@example.com     | password123  | mahasiswa  |
| John Doe  | john@example.com     | password123  | mahasiswa  |

---

## 🔐 API Endpoints & Permissions

### Authentication Endpoints (Public)

#### 1. Login
**POST** `/api/login`

**Request:**
```json
{
    "email": "admin@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin",
            "email": "admin@example.com",
            "role": "admin"
        },
        "token": "1|abcdefg..."
    }
}
```

#### 2. Register
**POST** `/api/register`

**Request:**
```json
{
    "name": "New User",
    "email": "newuser@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response:** User akan otomatis mendapat role `mahasiswa`

---

### Protected Endpoints

#### 3. Get Current User
**GET** `/api/user`

**Headers:**
```
Authorization: Bearer {token}
```

**Permissions:** All authenticated users

---

#### 4. Logout
**POST** `/api/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Permissions:** All authenticated users

---

## 📊 Mahasiswa Endpoints (Role-Based)

### View Operations (All Users)

#### 5. Get All Mahasiswa
**GET** `/api/mahasiswa`

**Headers:**
```
Authorization: Bearer {token}
```

**Permissions:** ✅ Admin, ✅ Mahasiswa

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "nim": "12345678",
            "nama": "John Doe",
            "jurusan": "Informatika"
        }
    ]
}
```

---

#### 6. Get Mahasiswa by NIM
**GET** `/api/mahasiswa/{nim}`

**Headers:**
```
Authorization: Bearer {token}
```

**Permissions:** ✅ Admin, ✅ Mahasiswa

---

### CRUD Operations (Admin Only)

#### 7. Create Mahasiswa
**POST** `/api/mahasiswa`

**Headers:**
```
Authorization: Bearer {token}
```

**Permissions:** ✅ Admin, ❌ Mahasiswa

**Request:**
```json
{
    "nim": "12345678",
    "nama": "New Student",
    "jurusan": "Informatika"
}
```

**Response if Mahasiswa tries:**
```json
{
    "success": false,
    "message": "Unauthorized. You do not have permission to access this resource.",
    "required_role": "admin",
    "your_role": "mahasiswa"
}
```

---

#### 8. Update Mahasiswa
**PUT** `/api/mahasiswa/{nim}`

**Headers:**
```
Authorization: Bearer {token}
```

**Permissions:** ✅ Admin, ❌ Mahasiswa

**Request:**
```json
{
    "nama": "Updated Name",
    "jurusan": "Updated Jurusan"
}
```

---

#### 9. Delete Mahasiswa
**DELETE** `/api/mahasiswa/{nim}`

**Headers:**
```
Authorization: Bearer {token}
```

**Permissions:** ✅ Admin, ❌ Mahasiswa

---

## 🧪 Testing Guide

### Test Page
Open: `http://127.0.0.1:8000/test-role.html`

### Test Scenario 1: Admin User

1. **Login** sebagai admin@example.com
2. **View** mahasiswa → ✅ Success
3. **Create** mahasiswa → ✅ Success
4. **Update** mahasiswa → ✅ Success
5. **Delete** mahasiswa → ✅ Success

### Test Scenario 2: Mahasiswa User

1. **Login** sebagai user@example.com
2. **View** mahasiswa → ✅ Success
3. **Create** mahasiswa → ❌ 403 Forbidden
4. **Update** mahasiswa → ❌ 403 Forbidden
5. **Delete** mahasiswa → ❌ 403 Forbidden

---

## 💻 Code Examples

### JavaScript/React - Check User Role

```javascript
const user = JSON.parse(localStorage.getItem('user'));

if (user.role === 'admin') {
    // Show CRUD buttons
    console.log('User is admin - show all buttons');
} else {
    // Show only view buttons
    console.log('User is mahasiswa - hide create/update/delete buttons');
}
```

### JavaScript - Handle Permission Error

```javascript
const createMahasiswa = async (data) => {
    try {
        const response = await fetch('http://127.0.0.1:8000/api/mahasiswa', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.status === 403) {
            alert('Permission denied! Only admin can create mahasiswa.');
            return;
        }

        if (result.success) {
            console.log('Mahasiswa created successfully');
        }
    } catch (error) {
        console.error('Error:', error);
    }
};
```

### React Component Example

```jsx
import { useState, useEffect } from 'react';

function MahasiswaManager() {
    const [user, setUser] = useState(null);
    const [mahasiswa, setMahasiswa] = useState([]);

    useEffect(() => {
        const userData = JSON.parse(localStorage.getItem('user'));
        setUser(userData);
    }, []);

    const isAdmin = user?.role === 'admin';

    return (
        <div>
            <h1>Mahasiswa Manager</h1>
            
            {/* View button - visible to all */}
            <button onClick={viewMahasiswa}>
                View Mahasiswa
            </button>

            {/* CRUD buttons - only visible to admin */}
            {isAdmin && (
                <>
                    <button onClick={createMahasiswa}>Create</button>
                    <button onClick={updateMahasiswa}>Update</button>
                    <button onClick={deleteMahasiswa}>Delete</button>
                </>
            )}

            {/* Display mahasiswa list */}
            <ul>
                {mahasiswa.map(m => (
                    <li key={m.nim}>{m.nama} - {m.jurusan}</li>
                ))}
            </ul>
        </div>
    );
}
```

---

## 🔧 Database Schema

### Users Table (Updated)

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'mahasiswa') DEFAULT 'mahasiswa',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

---

## 🛡️ Security Features

1. **Token-based Authentication** - Laravel Sanctum
2. **Role Verification** - Middleware checks user role before allowing access
3. **Password Hashing** - Bcrypt
4. **CSRF Protection** - Built-in Laravel protection
5. **Input Validation** - All inputs are validated

---

## 📝 Error Responses

### 401 Unauthorized (Not logged in)
```json
{
    "success": false,
    "message": "Unauthenticated."
}
```

### 403 Forbidden (Wrong role)
```json
{
    "success": false,
    "message": "Unauthorized. You do not have permission to access this resource.",
    "required_role": "admin",
    "your_role": "mahasiswa"
}
```

---

## 🚀 Quick Start

1. **Login as Admin:**
   ```bash
   curl -X POST http://127.0.0.1:8000/api/login \
     -H "Content-Type: application/json" \
     -d '{"email":"admin@example.com","password":"password123"}'
   ```

2. **Use the token to create mahasiswa:**
   ```bash
   curl -X POST http://127.0.0.1:8000/api/mahasiswa \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{"nim":"12345678","nama":"Test","jurusan":"IT"}'
   ```

3. **Login as Mahasiswa and try to create (will fail):**
   ```bash
   # Login as mahasiswa
   curl -X POST http://127.0.0.1:8000/api/login \
     -H "Content-Type: application/json" \
     -d '{"email":"user@example.com","password":"password123"}'
   
   # Try to create (will get 403)
   curl -X POST http://127.0.0.1:8000/api/mahasiswa \
     -H "Authorization: Bearer MAHASISWA_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{"nim":"99999999","nama":"Test","jurusan":"IT"}'
   ```

---

**Created:** 2025-11-22  
**Version:** 1.0  
**Authentication:** Laravel Sanctum + Role-Based Access Control
