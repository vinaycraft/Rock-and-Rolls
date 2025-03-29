<?php

namespace Database\Seeders;

use App\Models\Dish;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    public function run()
    {
        $dishes = [
            [
                'name' => 'Classic Veg Roll',
                'description' => 'Fresh vegetables wrapped in a soft roll with our special sauce',
                'price' => 99.00,
                'category' => 'Veg Rolls',
                'is_available' => true,
            ],
            [
                'name' => 'Paneer Tikka Roll',
                'description' => 'Grilled paneer with spices and vegetables',
                'price' => 129.00,
                'category' => 'Veg Rolls',
                'is_available' => true,
            ],
            [
                'name' => 'Chicken Roll',
                'description' => 'Tender chicken pieces with fresh vegetables',
                'price' => 149.00,
                'category' => 'Non-Veg Rolls',
                'is_available' => true,
            ],
            [
                'name' => 'Mutton Seekh Roll',
                'description' => 'Spicy mutton seekh kebab wrapped in a roll',
                'price' => 179.00,
                'category' => 'Non-Veg Rolls',
                'is_available' => true,
            ],
            [
                'name' => 'Mushroom Roll',
                'description' => 'Grilled mushrooms with special herbs and spices',
                'price' => 119.00,
                'category' => 'Veg Rolls',
                'is_available' => true,
            ]
        ];

        foreach ($dishes as $dish) {
            Dish::create($dish);
        }
    }
}
