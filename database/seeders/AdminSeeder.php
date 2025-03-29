<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'phone_number' => '9999999999',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);
    }
}
