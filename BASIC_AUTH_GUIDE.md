# Basic Authentication Guide

## Overview
This API uses **HTTP Basic Authentication** for all protected endpoints. Basic Auth sends credentials (email and password) encoded in the `Authorization` header with each request.

## How Basic Auth Works

### Header Format
```
Authorization: Basic <base64_encoded_credentials>
```

Where `<base64_encoded_credentials>` is the Base64 encoding of `email:password`

### Example
For user with:
- Email: `admin@example.com`
- Password: `password123`

Encoded: `admin@example.com:password123` → `YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM=`

Header:
```
Authorization: Basic YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM=
```

## Authentication Endpoints

### 1. Login (Validate Credentials)
Validates your credentials and returns user information.

**Endpoint:** `POST /api/login`
**Authentication:** None required
**Request Body:**
```json
{
  "email": "admin@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "Administrator",
      "email": "admin@example.com",
      "role": "admin"
    },
    "usage": "Use email as username and password in Basic Auth header for all API requests"
  }
}
```

### 2. Register (Create Account)
Creates a new user account.

**Endpoint:** `POST /api/register`
**Authentication:** None required
**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Registration successful",
  "data": {
    "user": {
      "id": 3,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "mahasiswa"
    },
    "usage": "Use email as username and password in Basic Auth header for API requests"
  }
}
```

### 3. Get Current User
Returns information about the authenticated user.

**Endpoint:** `GET /api/user`
**Authentication:** Basic Auth required
**Response (200):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "Administrator",
      "email": "admin@example.com",
      "role": "admin",
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z"
    }
  }
}
```

### 4. Logout
Confirms logout. Client should clear stored credentials.

**Endpoint:** `POST /api/logout`
**Authentication:** Basic Auth required
**Response (200):**
```json
{
  "success": true,
  "message": "Logout successful. Clear your stored credentials in the client."
}
```

## Protected Endpoints

All API endpoints except `/login`, `/register`, and `/health` require Basic Authentication.

### Mahasiswa (Students) Endpoints

#### Get All Students
- **Method:** `GET`
- **Endpoint:** `/api/mahasiswa`
- **Auth:** Basic Auth (Admin & Mahasiswa)
- **Response:** List of all students

#### Get Student by NIM
- **Method:** `GET`
- **Endpoint:** `/api/mahasiswa/{nim}`
- **Auth:** Basic Auth (Admin & Mahasiswa)
- **Response:** Single student details

#### Create Student (Admin Only)
- **Method:** `POST`
- **Endpoint:** `/api/mahasiswa`
- **Auth:** Basic Auth (Admin only)
- **Request Body:**
```json
{
  "nim": "2020001",
  "nama": "Student Name",
  "jurusan": "Computer Science",
  "angkatan": 2020,
  "no_hp": "081234567890",
  "alamat": "Jl. Example No. 123"
}
```

#### Update Student (Admin Only)
- **Method:** `PUT`
- **Endpoint:** `/api/mahasiswa/{nim}`
- **Auth:** Basic Auth (Admin only)

#### Delete Student (Admin Only)
- **Method:** `DELETE`
- **Endpoint:** `/api/mahasiswa/{nim}`
- **Auth:** Basic Auth (Admin only)

### User Management Endpoints (Admin Only)

#### Create Mahasiswa User Account
- **Method:** `POST`
- **Endpoint:** `/api/users/mahasiswa`
- **Auth:** Basic Auth (Admin only)
- **Request Body:**
```json
{
  "name": "Student Name",
  "email": "student@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### List All Users
- **Method:** `GET`
- **Endpoint:** `/api/users`
- **Auth:** Basic Auth (Admin only)

## Error Responses

### 401 Unauthorized
When credentials are missing or invalid:
```json
{
  "success": false,
  "message": "Authentication required. Please provide valid credentials."
}
```

### 403 Forbidden
When user doesn't have permission for the resource:
```json
{
  "success": false,
  "message": "Unauthorized. You do not have permission to access this resource.",
  "required_role": "admin",
  "your_role": "mahasiswa"
}
```

### 404 Not Found
When requested resource doesn't exist:
```json
{
  "status": false,
  "message": "Data tidak ditemukan"
}
```

## Testing with cURL

### Example: Get all students
```bash
curl -X GET http://localhost:8000/api/mahasiswa \
  -H "Authorization: Basic YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM="
```

### Example: Create a student
```bash
curl -X POST http://localhost:8000/api/mahasiswa \
  -H "Authorization: Basic YWRtaW5AZXhhbXBsZS5jb206cGFzc3dvcmQxMjM=" \
  -H "Content-Type: application/json" \
  -d '{
    "nim": "2020001",
    "nama": "John Doe",
    "jurusan": "Computer Science",
    "angkatan": 2020,
    "no_hp": "081234567890",
    "alamat": "Jl. Example No. 123"
  }'
```

## Testing with Postman

1. Open Postman and create a new request
2. Set the URL to your API endpoint
3. Go to **Authorization** tab
4. Select **Basic Auth** from the dropdown
5. Enter:
   - **Username:** Your email
   - **Password:** Your password
6. Click **Send**

Postman will automatically encode the credentials and add the `Authorization` header.

## Test Accounts

### Admin Account
- **Email:** `admin@example.com`
- **Password:** `password123`
- **Role:** `admin`
- **Permissions:** Full CRUD access

### Mahasiswa Account
- **Email:** `user@example.com`
- **Password:** `password123`
- **Role:** `mahasiswa`
- **Permissions:** Read-only access

## Roles & Permissions

### Admin Role
- ✅ View all students
- ✅ Create new students
- ✅ Update student information
- ✅ Delete students
- ✅ Manage user accounts
- ✅ View all users

### Mahasiswa Role
- ✅ View all students
- ✅ View specific student details
- ❌ Create, update, or delete students
- ❌ Manage accounts

## Public Endpoints (No Auth Required)

### Health Check
- **Method:** `GET`
- **Endpoint:** `/api/health`
- **Response:**
```json
{
  "success": true,
  "message": "API is running",
  "auth_type": "Basic Auth",
  "timestamp": "2025-01-01T00:00:00.000000Z"
}
```

### Test Accounts Info
- **Method:** `GET`
- **Endpoint:** `/api/test-accounts`

## Best Practices

1. **Always use HTTPS** in production - Basic Auth sends credentials in every request
2. **Store credentials securely** - Never hardcode in client code
3. **Use environment variables** for sensitive data
4. **Regenerate passwords** periodically
5. **Log authentication attempts** for security monitoring
6. **Implement rate limiting** to prevent brute force attacks

## Troubleshooting

### "Authentication required. Please provide valid credentials."
- Verify your email and password are correct
- Ensure credentials are properly Base64 encoded
- Check that the `Authorization` header is present in the request

### "Unauthorized. You do not have permission to access this resource."
- Your role doesn't have permission for this endpoint
- Admin endpoints require `admin` role
- Contact administrator to change your role

### 500 Internal Server Error
- Check server logs for detailed error information
- Ensure database connection is working
- Verify all required fields are provided in request body

## Additional Resources

- [HTTP Basic Authentication (RFC 7617)](https://tools.ietf.org/html/rfc7617)
- [Base64 Encoding](https://www.base64encode.org/)
- [Postman Basic Auth Documentation](https://learning.postman.com/docs/sending-requests/authorization/basic-auth/)
