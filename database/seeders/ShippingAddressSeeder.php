<?php

namespace Database\Seeders;

use App\Http\Requests\ShippingAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShippingAddress::factory()->count(5)->create();
    }
}
