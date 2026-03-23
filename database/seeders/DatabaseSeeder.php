<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@auxinorchem.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('Admin@2024'),
                'remember_token' => Str::random(10),
                'is_admin' => true,
            ]
        );

        $this->call([
            SettingsSeeder::class,
            HeroSlideSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            BlogPostSeeder::class,
            PageSeeder::class,
            PageSectionSeeder::class,
            PageElementSeeder::class,
        ]);
    }
}
