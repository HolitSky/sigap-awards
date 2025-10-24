<?php

namespace App\Http\Controllers\DashboardPeserta;

use App\Http\Controllers\Controller;
use App\Models\UserPeserta;
use App\Models\BpkhList;
use App\Models\ProdusenList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        // Generate captcha on page load
        $this->generateCaptcha();
        return view('peserta-auth.login');
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
        session(['peserta_captcha_answer' => strval($result)]);
        session(['peserta_captcha_image' => $base64]);

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
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate CAPTCHA first
        $captchaAnswer = session('peserta_captcha_answer');
        $userCaptcha = $request->input('captcha');

        if (!$captchaAnswer || $userCaptcha != $captchaAnswer) {
            // Regenerate captcha on error
            $this->generateCaptcha();
            return back()->withErrors([
                'captcha' => 'CAPTCHA salah. Silakan coba lagi.',
            ])->withInput($request->only('email'));
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        if ($validator->fails()) {
            // Regenerate captcha on validation error
            $this->generateCaptcha();
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('peserta')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Clear captcha after successful login
            session()->forget(['peserta_captcha_answer', 'peserta_captcha_image']);
            
            return redirect()->intended(route('peserta.dashboard'))
                ->with('success', 'Selamat datang, ' . Auth::guard('peserta')->user()->name);
        }

        // Regenerate captcha on failed login
        $this->generateCaptcha();
        return redirect()->back()
            ->withErrors(['email' => 'Email atau password salah'])
            ->withInput($request->only('email'));
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        // Generate captcha on page load
        $this->generateCaptcha();
        
        $bpkhList = BpkhList::orderBy('nama_wilayah')->get();
        $produsenList = ProdusenList::orderBy('nama_unit')->get();
        
        return view('peserta-auth.daftar', compact('bpkhList', 'produsenList'));
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        // Validate CAPTCHA first
        $captchaAnswer = session('peserta_captcha_answer');
        $userCaptcha = $request->input('captcha');

        if (!$captchaAnswer || $userCaptcha != $captchaAnswer) {
            // Regenerate captcha on error
            $this->generateCaptcha();
            return back()->withErrors([
                'captcha' => 'CAPTCHA salah. Silakan coba lagi.',
            ])->withInput();
        }

        // Validasi berbeda berdasarkan kategori
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user_peserta,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'foto' => 'required|image|mimes:jpeg,jpg,png|max:1536', // 1.5MB = 1536KB
            'kategori' => 'required|in:bpkh,produsen',
        ];

        // Tambahkan validasi sesuai kategori
        if ($request->kategori === 'bpkh') {
            $rules['bpkh_id'] = 'required|exists:bpkh_list,id';
        } elseif ($request->kategori === 'produsen') {
            $rules['produsen_id'] = 'required|exists:produsen_list,id';
        }

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'foto.required' => 'Foto profil wajib diupload',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, jpg, atau png',
            'foto.max' => 'Ukuran foto maksimal 1.5MB',
            'kategori.required' => 'Kategori wajib dipilih',
            'bpkh_id.required' => 'Wilayah BPKH wajib dipilih',
            'bpkh_id.exists' => 'Wilayah BPKH tidak valid',
            'produsen_id.required' => 'Unit Produsen wajib dipilih',
            'produsen_id.exists' => 'Unit Produsen tidak valid',
        ]);

        if ($validator->fails()) {
            // Regenerate captcha on validation error
            $this->generateCaptcha();
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle foto upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('peserta-photos', 'public');
        }

        // Prepare data
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'foto' => $fotoPath,
            'kategori' => $request->kategori,
            'status' => 'active',
        ];

        // Set bpkh_id or produsen_id based on kategori
        if ($request->kategori === 'bpkh') {
            $data['bpkh_id'] = $request->bpkh_id;
            $data['produsen_id'] = null;
        } else {
            $data['produsen_id'] = $request->produsen_id;
            $data['bpkh_id'] = null;
        }

        $user = UserPeserta::create($data);

        // Auto login after registration
        Auth::guard('peserta')->login($user);

        // Clear captcha after successful registration
        session()->forget(['peserta_captcha_answer', 'peserta_captcha_image']);

        return redirect()->route('peserta.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::guard('peserta')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('peserta.login')
            ->with('success', 'Anda telah berhasil logout');
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        return view('peserta-dashboard.dashboard');
    }
}
