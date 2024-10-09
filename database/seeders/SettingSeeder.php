<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'name' => 'Demo Inventory',
            'name_short' => 'DI',
            'address' => 'Jashore Sadar, Jashore',
            'email' => 'jwelranajr8676@gmail.com',
            'contact' => '01571-166570',
        ]);
    }
}
