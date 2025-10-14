<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Protected email accounts that cannot be edited or deleted
     * Add more emails to this array to protect multiple accounts
     */
    protected static $protectedEmails = [
        'holitsky98@gmail.com',
        // Add more protected emails here if needed
        // 'admin@example.com',
        // 'superadmin@example.com',
    ];

    /**
     * Check if email is protected
     */
    protected function isProtectedEmail($email)
    {
        return in_array($email, self::$protectedEmails);
    }
    // GET /api/admin/users
    public function index(Request $request)
    {
        $title = 'User Management';
        // Check if it's a DataTables AJAX request
        if ($request->ajax()) {
            $query = UserModel::select('id','name','email','role','profile_image','created_at');

            // Search
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%")
                      ->orWhere('email', 'like', "%{$searchValue}%")
                      ->orWhere('role', 'like', "%{$searchValue}%");
                });
            }

            // Count total records
            $totalRecords = UserModel::count();
            $filteredRecords = $query->count();

            // Order
            if ($request->has('order')) {
                $columns = ['profile_image', 'name', 'email', 'role', 'created_at', 'id'];
                $orderColumnIndex = $request->order[0]['column'];
                $orderDir = $request->order[0]['dir'];

                if (isset($columns[$orderColumnIndex]) && $columns[$orderColumnIndex] !== 'profile_image') {
                    $query->orderBy($columns[$orderColumnIndex], $orderDir);
                }
            } else {
                $query->latest();
            }

            // Pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 15;
            $users = $query->skip($start)->take($length)->get();
            
            // Add is_protected flag to each user
            $users = $users->map(function($user) {
                $user->is_protected = $this->isProtectedEmail($user->email);
                return $user;
            });
            
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $users
            ]);
        }

        // Return view for non-AJAX requests
        return view('dashboard.pages.user-management.index', compact('title'));
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

    // GET /api/admin/users/{id} - Get single user details
    public function show($id)
    {
        $user = UserModel::findOrFail($id);
        return response()->json($user);
    }

    // PUT/PATCH /api/admin/users/{id} - Update user
    public function update(Request $request, $id)
    {
        $user = UserModel::findOrFail($id);

        // Prevent updating protected account
        if ($this->isProtectedEmail($user->email)) {
            return response()->json(['message' => 'This account is protected and cannot be updated'], 403);
        }

        $request->validate([
            'name'     => ['required','string','max:100'],
            'email'    => ['required','email','max:150', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['nullable','string','min:8'],
            'role'     => ['required', Rule::in([
                UserModel::ROLE_PESERTA, UserModel::ROLE_PANITIA, UserModel::ROLE_ADMIN, UserModel::ROLE_SUPERADMIN
            ])],
        ]);

        // Check permission: admin cannot create/update superadmin
        if ($request->user()->role === UserModel::ROLE_ADMIN) {
            if ($request->role === UserModel::ROLE_SUPERADMIN) {
                return response()->json(['message' => 'Forbidden to set role as superadmin'], 403);
            }
        }

        $updateData = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = $request->password;
        }

        $user->update($updateData);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    // DELETE /api/admin/users/{id} - Delete user
    public function destroy($id)
    {
        $user = UserModel::findOrFail($id);

        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Cannot delete your own account'], 403);
        }

        // Prevent deleting protected account
        if ($this->isProtectedEmail($user->email)) {
            return response()->json(['message' => 'This account is protected and cannot be deleted'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
