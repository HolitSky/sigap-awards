<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //register
    public function register(RegisterRequest $request){
        $data = $request->validated();

        $user = UserModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => ($data['password']),
            'role' => UserModel::ROLE_PESERTA,
        ]);

        $token = $user->createToken('api', ['basic'])->plainTextToken;

        return response()->json([
            'message'      => 'Registration successful',
            'access_token' => $token,
            'token_type'   => 'bearer',
            'user'         => $user,
        ], 201);
    }

    // POST /api/auth/login
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = UserModel::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // $user->tokens()->delete(); // uncomment untuk single-session

        // abilities by role (opsional, kalau mau dicek di policy/route)
        $abilities = match ($user->role) {
            UserModel::ROLE_SUPERADMIN => ['*'],
            UserModel::ROLE_ADMIN      => ['users:read','users:create','users:update','events:*'],
            UserModel::ROLE_PANITIA    => ['panitia:*','basic'],
            default               => ['basic'], // peserta
        };

        $token = $user->createToken('api', $abilities)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'user'         => $user,
        ]);
    }

    // GET /api/auth/me
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // POST /api/auth/logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    //POST /api/auth/logout-all
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out from all devices']);
    }
}
