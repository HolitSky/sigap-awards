<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventAdminViewEdit
{
    /**
     * Handle an incoming request.
     * Prevent admin-view role from accessing edit/update routes
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if ($user && $user->role === 'admin-view') {
            // Check if this is an edit/update route
            $routeName = $request->route()->getName();
            
            // Block routes that contain 'edit', 'update', 'store', 'bulk-score'
            if (
                str_contains($routeName, '.edit') ||
                str_contains($routeName, '.update') ||
                str_contains($routeName, '.store') ||
                str_contains($routeName, 'bulk-score')
            ) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Akses ditolak. Role admin-view hanya memiliki akses read-only.'
                    ], 403);
                }
                
                return redirect()->back()->with('error', 'Akses ditolak. Role admin-view hanya memiliki akses read-only.');
            }
        }

        return $next($request);
    }
}
