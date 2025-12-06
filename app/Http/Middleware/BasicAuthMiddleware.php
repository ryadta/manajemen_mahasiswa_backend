<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $requiredRole = null): Response
    {
        // Set cache control headers
        header('Cache-Control: no-cache, must-revalidate, max-age=0');

        // Check if credentials are provided
        $hasCredentials = isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']);

        if (!$hasCredentials) {
            return $this->unauthorizedResponse();
        }

        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];

        // Find user by email
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

        // Add user to request for later use
        $request->attributes->set('authenticated_user', $user);

        return $next($request);
    }

    /**
     * Return unauthorized response
     */
    private function unauthorizedResponse(): Response
    {
        return response()->json([
            'success' => false,
            'message' => 'Authentication required. Please provide valid credentials.'
        ], 401)->header('WWW-Authenticate', 'Basic realm="Manajemen Mahasiswa API"');
    }
}
