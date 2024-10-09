<?php

namespace App\Service;

use App\Models\Role;
use App\Models\RoleHasPermission;
use App\Service\Service;
use Yajra\DataTables\Facades\DataTables;

class RoleService extends Service
{
    protected $model = Role::class;

    public function Index()
    {
        $role = $this->model::all();

        return DataTables::of($role)
            ->addColumn('action', fn($item) => view('pages.role.action', compact('item'))->render())
            ->make(true);
    }
    public function setPermission($data, $id)
    {
        $permissions = $data['permissions'];

        RoleHasPermission::where('role_id', $id)->delete();
        foreach ($permissions as $permission) {
            $assign = new RoleHasPermission();
            $assign->role_id = $id;
            $assign->permission_id = $permission;
            $assign->save();
        }
    }
}
