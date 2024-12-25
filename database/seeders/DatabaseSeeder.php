<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(UserSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(DivisionSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(RoleSeeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(VoucherSettingSeeder::class);
    }
}
