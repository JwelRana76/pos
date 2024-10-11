<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = ['Jashore', 'Narail', 'Rangpur Sadar', 'Nilphamari', 'Lalmonirhat'];
        $ids = ['1', '1', '3', '3', '3'];
        foreach ($items as $key => $item) {
            District::create([
                'division_id' => $ids[$key],
                'name' => $item,
                'code' => rand(1000, 9999)
            ]);
        }
    }
}
