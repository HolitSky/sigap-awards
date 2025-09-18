<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register new user (API only)
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = UserModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserModel::ROLE_PESERTA,
        ]);

        $token = $user->createToken('api', ['basic'])->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user,
        ], 201);
    }

    /**
     * Unified login method for both API and Web
     */
    public function login(Request $request)
    {
        // Determine if this is an API request
        $isApiRequest = $request->expectsJson() || $request->is('api/*');

        if ($isApiRequest) {
            // For API requests, use LoginRequest validation
            $loginRequest = app(LoginRequest::class);
            $data = $loginRequest->validated();
            $credentials = [
                'email' => $data['email'],
                'password' => $data['password']
            ];
        } else {
            // For web requests, use basic validation
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        }

        // Find user and verify password
        $user = UserModel::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            if ($isApiRequest) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            
            return back()->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        if ($isApiRequest) {
            // API Login - Return token
            $abilities = $this->getUserAbilities($user->role);
            $token = $user->createToken('api', $abilities)->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => $user,
            ]);
        } else {
            // Web Login - Create session
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')->with([
                'success' => 'Selamat datang, ' . $user->name . '!',
                'auth_action' => 'login',
            ]);
        }
    }

    /**
     * Unified logout method for both API and Web
     */
    public function logout(Request $request)
    {
        $isApiRequest = $request->expectsJson() || $request->is('api/*');

        if ($isApiRequest) {
            // API Logout - Delete current token
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        } else {
            // Web Logout - Destroy session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect('/login')->with([
                'success' => 'Anda telah berhasil logout.',
                'auth_action' => 'logout',
            ]);
        }
    }

    /**
     * Logout from all devices (API only)
     */
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out from all devices']);
    }

    /**
     * Get current user info
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Show login form (Web only)
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Get user abilities based on role
     */
    private function getUserAbilities($role)
    {
        return match ($role) {
            UserModel::ROLE_SUPERADMIN => ['*'],
            UserModel::ROLE_ADMIN => ['users:read', 'users:create', 'users:update', 'events:*'],
            UserModel::ROLE_PANITIA => ['panitia:*', 'basic'],
            default => ['basic'], // peserta
        };
    }
}
