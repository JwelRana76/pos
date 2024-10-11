<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = ['Khulna', 'Rajshahi', 'Rangpur', 'Dhaka'];
        foreach ($items as $key => $item) {
            Division::create([
                'name' => $item,
                'code' => rand(1000, 9999)
            ]);
        }
    }
}
