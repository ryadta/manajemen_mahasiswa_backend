<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthRoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $requiredRole): Response
    {
        // Get authenticated user from BasicAuthMiddleware
        $user = $request->attributes->get('authenticated_user');

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.'
            ], 401);
        }

        // Check if user has required role
        if ($user->role !== $requiredRole) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You do not have permission to access this resource.',
                'required_role' => $requiredRole,
                'your_role' => $user->role
            ], 403);
        }

        return $next($request);
    }
}
