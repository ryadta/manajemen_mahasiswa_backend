# Basic Auth Quick Start

## 🎯 What Changed?

Your API now uses **HTTP Basic Authentication** exclusively instead of Sanctum tokens.

### Old Way (Token-Based)
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

### New Way (Basic Auth)
```
Authorization: Basic YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM=
```

## ⚡ Quick Setup

### Using cURL
```bash
# Format: email:password encoded in base64
curl -X GET http://localhost:8000/api/mahasiswa \
  -H "Authorization: Basic YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM="
```

### Using Postman
1. Open any API request
2. Go to **Authorization** tab
3. Select **Basic Auth** from dropdown
4. Enter:
   - **Username:** `admin@example.com`
   - **Password:** `password123`
5. Send the request

### Using JavaScript/Fetch
```javascript
const credentials = btoa('admin@example.com:password123');
fetch('/api/mahasiswa', {
  headers: {
    'Authorization': `Basic ${credentials}`
  }
})
```

### Using Axios
```javascript
import axios from 'axios';

const auth = {
  username: 'admin@example.com',
  password: 'password123'
};

axios.get('/api/mahasiswa', { auth })
```

### Using Python
```python
import requests
from requests.auth import HTTPBasicAuth

response = requests.get(
    'http://localhost:8000/api/mahasiswa',
    auth=HTTPBasicAuth('admin@example.com', 'password123')
)
```

## 📌 Test Accounts

```
Email: admin@example.com
Password: password123
Role: admin (Full access)

Email: user@example.com
Password: password123
Role: mahasiswa (Read-only)
```

## 🔑 Main API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/login` | - | Validate credentials |
| POST | `/api/register` | - | Create new account |
| GET | `/api/user` | ✅ | Get current user |
| POST | `/api/logout` | ✅ | Logout |
| GET | `/api/mahasiswa` | ✅ | List students |
| GET | `/api/mahasiswa/{nim}` | ✅ | Get student details |
| POST | `/api/mahasiswa` | 👑 | Create student (Admin) |
| PUT | `/api/mahasiswa/{nim}` | 👑 | Update student (Admin) |
| DELETE | `/api/mahasiswa/{nim}` | 👑 | Delete student (Admin) |

**✅ = Basic Auth required | 👑 = Admin + Basic Auth required | - = No auth required**

## 💡 How Basic Auth Works

1. **Encode** your credentials: `email:password` → Base64
2. **Add** to header: `Authorization: Basic <encoded_value>`
3. **Send** with every request
4. **Done!** No token management needed

## 📚 Full Documentation

For comprehensive guide, see: **`BASIC_AUTH_GUIDE.md`**

## ✅ All Changes Made

- ✅ Removed Sanctum token generation
- ✅ Updated AuthController
- ✅ Removed HasApiTokens from User model
- ✅ Updated API routes
- ✅ Basic Auth middleware active
- ✅ Role-based access control working
- ✅ Documentation created

## 🚀 Ready to Use!

Your API is now fully configured for Basic Authentication. Start making requests using the examples above!

**Questions?** Check `BASIC_AUTH_GUIDE.md` for detailed documentation.
