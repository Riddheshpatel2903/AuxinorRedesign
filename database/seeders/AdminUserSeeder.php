<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@auxinor.com'],
            [
                'name'               => 'Admin',
                'email'              => 'admin@auxinor.com',
                'password'           => Hash::make('admin123'),
                'is_admin'           => true,
                'email_verified_at'  => now(),
            ]
        );
        $this->command->info('Admin user created: admin@auxinor.com / admin123');
    }
}
