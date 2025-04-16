<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dish;
use Database\Seeders\AdminSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create test user
        User::create([
            'name' => 'Test User',
            'mobile' => '9876543210',
            'password' => bcrypt('password')
        ]);

        // Create dishes
        $dishes = [
            [
                'name' => 'Margherita Pizza',
                'description' => 'Classic pizza with tomato sauce and mozzarella cheese',
                'base_price' => 299.00,
                'price_with_cheese' => 349.00,
                'is_available' => true
            ],
            [
                'name' => 'Chicken Tikka Pizza',
                'description' => 'Pizza topped with spicy chicken tikka and onions',
                'base_price' => 399.00,
                'price_with_cheese' => 449.00,
                'is_available' => true
            ]
        ];

        foreach ($dishes as $dish) {
            Dish::create($dish);
        }

        $this->call([
            AdminSeeder::class,
        ]);
    }
}
