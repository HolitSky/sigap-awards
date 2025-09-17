<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        UserModel::updateOrCreate([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => 'SuperAdminSigap2025',
            'role' => UserModel::ROLE_SUPERADMIN,
        ]);

        UserModel::updateOrCreate([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'AdminSigap2025',
            'role' => UserModel::ROLE_ADMIN,
        ]);

        UserModel::updateOrCreate([
            'name' => 'Panitia',
            'email' => 'panitia@gmail.com',
            'password' => 'PanitiaSigap2025',
            'role' => UserModel::ROLE_PANITIA,
        ]);

        UserModel::updateOrCreate([
            'name' => 'Peserta',
            'email' => 'peserta@gmail.com',
            'password' => 'PesertaSigap2025',
            'role' => UserModel::ROLE_PESERTA,
        ]);
    }
}
