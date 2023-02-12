<?php

namespace Database\Seeders;

use App\Models\Suppliers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Suppliers::factory()->count(25)->create();
    }
}