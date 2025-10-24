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
        return view('peserta-auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('peserta')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('peserta.dashboard'))
                ->with('success', 'Selamat datang, ' . Auth::guard('peserta')->user()->name);
        }

        return redirect()->back()
            ->withErrors(['email' => 'Email atau password salah'])
            ->withInput($request->only('email'));
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        $bpkhList = BpkhList::orderBy('kode_wilayah')->get();
        $produsenList = ProdusenList::orderBy('nama_unit')->get();
        
        return view('peserta-auth.daftar', compact('bpkhList', 'produsenList'));
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user_peserta,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'foto' => 'required|image|mimes:jpeg,jpg,png|max:1536', // 1.5MB = 1536KB
            'kategori' => 'required|in:bpkh,produsen',
            'bpkh_id' => 'required_if:kategori,bpkh|exists:bpkh_list,id',
            'produsen_id' => 'required_if:kategori,produsen|exists:produsen_list,id',
        ], [
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
            'bpkh_id.required_if' => 'Wilayah BPKH wajib dipilih',
            'produsen_id.required_if' => 'Unit Produsen wajib dipilih',
        ]);

        if ($validator->fails()) {
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
