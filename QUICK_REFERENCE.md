# 🚀 QUICK REFERENCE - Role-Based Access Control

## 📋 Roles & Permissions

```
┌─────────────┬──────┬────────┬────────┬────────┐
│ Role        │ View │ Create │ Update │ Delete │
├─────────────┼──────┼────────┼────────┼────────┤
│ Admin       │  ✅  │   ✅   │   ✅   │   ✅   │
│ Mahasiswa   │  ✅  │   ❌   │   ❌   │   ❌   │
└─────────────┴──────┴────────┴────────┴────────┘
```

## 👥 Test Accounts

```bash
# Admin Account
Email: admin@example.com
Password: password123
Role: admin
Permissions: Full CRUD

# Mahasiswa Account 1
Email: user@example.com
Password: password123
Role: mahasiswa
Permissions: Read Only

# Mahasiswa Account 2
Email: john@example.com
Password: password123
Role: mahasiswa
Permissions: Read Only
```

## 🔌 API Endpoints

### Authentication (Public)
```
POST /api/login          # Login
POST /api/register       # Register (auto role: mahasiswa)
```

### User Info (Protected)
```
GET  /api/user           # Get current user
POST /api/logout         # Logout
```

### Mahasiswa - View (All Users)
```
GET /api/mahasiswa       # List all
GET /api/mahasiswa/{nim} # Get by NIM
```

### Mahasiswa - CRUD (Admin Only)
```
POST   /api/mahasiswa        # Create
PUT    /api/mahasiswa/{nim}  # Update
DELETE /api/mahasiswa/{nim}  # Delete
```

## 🧪 Quick Test

### 1. Test Admin (Full Access)
```bash
# Login
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password123"}'

# Save the token, then create mahasiswa
curl -X POST http://127.0.0.1:8000/api/mahasiswa \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"nim":"12345678","nama":"Test","jurusan":"IT"}'
```

### 2. Test Mahasiswa (Read Only)
```bash
# Login
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'

# View mahasiswa (Success)
curl -X GET http://127.0.0.1:8000/api/mahasiswa \
  -H "Authorization: Bearer YOUR_TOKEN"

# Try to create (Will fail with 403)
curl -X POST http://127.0.0.1:8000/api/mahasiswa \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"nim":"99999999","nama":"Test","jurusan":"IT"}'
```

## 💻 Frontend Code

### Check Role
```javascript
const user = JSON.parse(localStorage.getItem('user'));
const isAdmin = user?.role === 'admin';

if (isAdmin) {
    // Show CRUD buttons
} else {
    // Show view only
}
```

### React Component
```jsx
function MahasiswaManager() {
    const user = JSON.parse(localStorage.getItem('user'));
    const isAdmin = user?.role === 'admin';

    return (
        <div>
            <ViewButton />  {/* All users */}
            {isAdmin && (
                <>
                    <CreateButton />
                    <UpdateButton />
                    <DeleteButton />
                </>
            )}
        </div>
    );
}
```

## 📱 Test Page

Open in browser:
```
http://127.0.0.1:8000/test-role.html
```

## 🔐 Error Responses

### 401 - Not Authenticated
```json
{
    "success": false,
    "message": "Unauthenticated."
}
```

### 403 - Wrong Role
```json
{
    "success": false,
    "message": "Unauthorized. You do not have permission to access this resource.",
    "required_role": "admin",
    "your_role": "mahasiswa"
}
```

## 📚 Documentation Files

- `RBAC_SUMMARY.md` - Full implementation summary
- `RBAC_DOCUMENTATION.md` - Complete guide
- `database/README.md` - Database setup
- `API_DOCUMENTATION.md` - API reference

## ⚡ Quick Commands

```bash
# Run migration
php artisan migrate

# Seed users
php artisan db:seed --class=UserSeeder

# Start server
php artisan serve

# Open test page
start http://127.0.0.1:8000/test-role.html
```

---

**Need help?** Check `RBAC_DOCUMENTATION.md` for detailed examples!
