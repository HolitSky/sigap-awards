<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    // GET /api/admin/users
    public function index()
    {
        return UserModel::select('id','name','email','role','created_at')->latest()->paginate(15);
    }

    // POST /api/admin/users  (admin boleh buat peserta/panitia; superadmin boleh semua)
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required','string','max:100'],
            'email'    => ['required','email','max:150','unique:users,email'],
            'password' => ['required','string','min:8'],
            'role'     => ['nullable', Rule::in([
                UserModel::ROLE_PESERTA, UserModel::ROLE_PANITIA, UserModel::ROLE_ADMIN, UserModel::ROLE_SUPERADMIN
            ])],
        ]);

        $role = $request->input('role') ?: UserModel::ROLE_PESERTA;

        if ($request->user()->role === UserModel::ROLE_ADMIN) {
            // admin tidak boleh membuat superadmin
            if ($role === UserModel::ROLE_SUPERADMIN) {
                return response()->json(['message' => 'Forbidden to create superadmin'], 403);
            }
        }

        $u = UserModel::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password, // auto-hash
            'role'     => $role,
        ]);

        return response()->json($u, 201);
    }

    // PATCH /api/superadmin/users/{id}/role   (KHUSUS superadmin)
    public function updateRole(Request $request, UserModel $user)
    {
        if ($request->user()->role !== UserModel::ROLE_SUPERADMIN) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'role' => ['required', Rule::in([
                UserModel::ROLE_PESERTA, UserModel::ROLE_PANITIA, UserModel::ROLE_ADMIN, UserModel::ROLE_SUPERADMIN
            ])],
        ]);

        $user->update(['role' => $request->role]);
        return response()->json(['message' => 'Role updated', 'user' => $user]);
    }
}
