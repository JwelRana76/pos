<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function permissionCheck($role, $permission)
    {
        $find = RoleHasPermission::where(['role_id' => $role, 'permission_id' => $permission])->first();
        if ($find) {
            return true;
        } else {
            return false;
        }
    }
}
