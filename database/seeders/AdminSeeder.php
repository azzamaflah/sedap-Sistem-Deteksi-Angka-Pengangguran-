<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $adminExists = User::where('email', 'admin@bantulwe.com')->first();

        if (!$adminExists) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@bantulwe.com',
                'password' => Hash::make('admin123'), // Ganti dengan password yang kuat
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

            echo "Admin user created successfully!\n";
            echo "Email: admin@bantulwe.com\n";
            echo "Password: admin123\n";
        } else {
            echo "Admin user already exists.\n";
        }
    }
}
