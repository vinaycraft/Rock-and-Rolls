<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        try {
            // First, remove any existing admins
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('admins')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Create new admin
            Admin::create([
                'name' => 'Administrator',
                'email' => 'admin@rocknroll.com',
                'password' => Hash::make('admin123'),
            ]);

            $this->command->info('Admin created successfully!');
            $this->command->info('Email: admin@rocknroll.com');
            $this->command->info('Password: admin123');
        } catch (\Exception $e) {
            $this->command->error('Error creating admin: ' . $e->getMessage());
        }
    }
}
