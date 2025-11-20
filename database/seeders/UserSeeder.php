<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'unique_id' => '12345678',
            'name' => 'John Doe',
            'email' => 'admin@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'), // 12345678
            'auth_type' => 'email_pass',
            'phone_country_code' => '+91',
            'phone_number' => '1234567890',
            'profile_img' => 'https://example.com/profile.jpg',
            'app_version' => '1.0.0',
            'ip_address' => '127.0.0.1',
            'firebase_id' => '1234567890',
            'acc_status' => 'active',
        ]);
    }
}
