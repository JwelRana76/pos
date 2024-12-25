<?php

namespace Database\Seeders;

use App\Models\VoucherSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VoucherSetting::create([
            'expense' => 0,
            'pos' => 0,
            'salary' => 0,
            'purchase' => 0,
        ]);
    }
}
