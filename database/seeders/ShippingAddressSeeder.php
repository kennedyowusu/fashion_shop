<?php

namespace Database\Seeders;

use App\Models\ShippingAdress;
use Illuminate\Database\Seeder;

class ShippingAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShippingAdress::factory()->count(5)->create();
    }
}
