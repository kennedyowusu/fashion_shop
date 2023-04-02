<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Product;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        // get some users and products
        $users = User::all();
        $products = Product::all();

        // create 5 carts with random users and products
        Cart::factory(5)
            ->make()
            ->each(function ($cart) use ($users, $products) {
                $cart->user_id = $users->random()->id;
                $cart->product_id = $products->random()->id;
                $cart->save();
            });
    }
}

