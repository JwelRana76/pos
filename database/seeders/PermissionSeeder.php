<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            'product',
            'purchase',
            'sale',
            'return',
            'supplier',
            'accounting',
            'customer',
            'employee',
            'payrole',
            'expense',
            'income',
            'setting',
            'invest',
            'adjustment',
            'loan',
        ];
        $permissions = ['module', 'index', 'store', 'update', 'delete', 'advance'];


        for ($i = 0; $i < count($groups); $i++) {
            $g = PermissionGroup::create(['name' => $groups[$i]]);
            for ($j = 0; $j < count($permissions); $j++) {
                $p = new Permission();
                $p->permission_group_id = $g->id;
                $p->name = $groups[$i] . '-' . $permissions[$j];
                $p->save();
            }
        }
    }
}
