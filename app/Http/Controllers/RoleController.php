<?php

namespace App\Http\Controllers;

use App\Models\PermissionGroup;
use App\Models\Role;
use App\Service\RoleService;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    public function __construct()
    {
        $this->baseService = new RoleService;
    }
    public function index()
    {
        $role = $this->baseService->Index();
        $columns = Role::$columns;
        if (request()->ajax()) {
            return $role;
        }
        return view('pages.role.index', compact('columns'))->with('success', 'Permission Setup Successfully');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles',
        ]);
        Role::create([
            'name' => $request->name,
        ]);
        return redirect()->route('role.index')->with('success', 'Role Added Successfully');
    }
    function edit($id)
    {
        $data = Role::findOrFail($id);
        return $data;
    }
    public function update(Request $request, $id)
    {
        Role::findOrFail($request->update_id)->fill([
            'name' => $request->name,
        ])->save();
        return redirect()->route('role.index')->with('success', 'Role Updated Successfully');
    }
    public function delete($id)
    {
        Role::findOrFail($id)->delete();
        return redirect()->route('role.index')->with('success', 'Role Deleted Successfully');
    }
    function permission($id)
    {
        $groups = PermissionGroup::all();
        $role = Role::findOrFail($id);
        return view('pages.role.role_permission', compact('groups', 'role'));
    }
    function permission_store(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $this->baseService->setPermission($request->all(), $id);
        return redirect()->route('role.permission', $id)->with('success', 'Permission Setup Successfully');
    }
}
