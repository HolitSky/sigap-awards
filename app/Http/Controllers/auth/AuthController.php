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
            // For web requests, validate CAPTCHA first
            $captchaAnswer = session('captcha_answer');
            $userCaptcha = $request->input('captcha');

            if (!$captchaAnswer || $userCaptcha != $captchaAnswer) {
                // Regenerate captcha on error
                $this->generateCaptcha();
                return back()->withErrors([
                    'captcha' => 'CAPTCHA salah. Silakan coba lagi.',
                ])->withInput($request->only('email'));
            }

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
            
            // Regenerate captcha on failed login
            $this->generateCaptcha();
            return back()->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        if ($isApiRequest) {
            // API Login - Return token
            $abilities = $this->getUserAbilities($user->role);
            $token = $user->createToken('api', $abilities)->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => $user,
            ]);
        } else {
            // Web Login - Create session
            Auth::login($user);
            $request->session()->regenerate();
            
            // Clear captcha after successful login
            session()->forget(['captcha_answer', 'captcha_image']);
            
            return redirect()->intended(route('dashboard.index'))
                ->with('success', 'Anda berhasil login!')
                ->with('auth_action', 'login');
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
     * Show login form
     */
    public function showLoginForm()
    {
        // Generate captcha on page load
        $this->generateCaptcha();
        return view('auth.login');
    }

    /**
     * Generate CAPTCHA image
     */
    private function generateCaptcha()
    {
        // Generate random numbers and operation
        $num1 = rand(10, 20);
        $num2 = rand(1, 10);
        $operation = rand(0, 1) ? '+' : '-';

        // Calculate the result
        $result = ($operation == '+') ? $num1 + $num2 : $num1 - $num2;

        // Create the CAPTCHA text
        $captcha_text = "$num1 $operation $num2 = ?";

        // Create image
        $image = imagecreatetruecolor(200, 60);
        $bg = imagecolorallocate($image, 40, 40, 40);
        $fg = imagecolorallocate($image, 255, 255, 255);
        $line = imagecolorallocate($image, 100, 100, 100);
        imagefill($image, 0, 0, $bg);

        // Add noise lines
        for ($i = 0; $i < 3; $i++) {
            imageline($image, rand(0, 200), rand(0, 60), rand(0, 200), rand(0, 60), $line);
        }

        // Add text
        imagestring($image, 5, 40, 20, $captcha_text, $fg);
        
        // Add noise pixels
        for ($i = 0; $i < 150; $i++) {
            imagesetpixel($image, rand(0, 200), rand(0, 60), $fg);
        }

        // Output image
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        $base64 = 'data:image/png;base64,' . base64_encode($imageData);

        // Store CAPTCHA result in session
        session(['captcha_answer' => strval($result)]);
        session(['captcha_image' => $base64]);

        return $base64;
    }

    /**
     * Refresh CAPTCHA (AJAX)
     */
    public function refreshCaptcha()
    {
        $newCaptcha = $this->generateCaptcha();
        return response()->json(['captcha' => $newCaptcha]);
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
