<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\User;
use App\Models\Role;
use App\Models\UserHasRole;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

    public function index()
    {
        $user = User::active();
        $columns = User::$columns;
        if (request()->ajax()) {
            return DataTables::of($user)
                ->addColumn('role', function ($user) {
                    return $user->role->role->name ?? 'N/A';
                })
                ->addColumn('action', fn($item) => view('pages.user.action', compact('item'))->render())
                ->make(true);
        }
        $roles = Role::get();
        return view('pages.user.index', compact('columns', 'roles'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles',
            'password' => 'required|min:6',
            'conform_password' => 'required|same:password',
            'role_id' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);
            $user->role()->create([
                'role_id' => $request->role_id,
            ]);
            DB::commit();
            return redirect()->route('user.index')->with('success', 'User Added Successfully');
        } catch (Exception $th) {
            DB::rollBack();
            dd($th->getMessage());
        }
    }
    function edit($id)
    {
        $data = User::findOrFail($id);
        $roles = Role::get();
        return view('pages.user.edit', compact('roles', 'data'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'role_id' => 'required',
        ]);

        if ($request->password) {
            $request->validate([
                'password' => 'required|min:6',
                'conform_password' => 'required|same:password',
            ]);
        }
        $data['name'] = $request->name;
        $data['username'] = $request->username;
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        User::findOrFail($id)->update($data);
        return redirect()->route('user.index')->with('success', 'User Updated Successfully');
    }
    public function delete($id)
    {
        User::findOrFail($id)->update([
            'is_active' => false,
        ]);
        return redirect()->route('user.index')->with('success', 'User Deleted Successfully');
    }

    public function assign_role($id)
    {
        $data = User::findOrFail($id);
        return $data->role->role_id;
    }
    public function assign_role_store(Request $request)
    {
        UserHasRole::where('user_id', $request->user_id)->update([
            'role_id' => $request->role_id,
        ]);
        return back()->with('success', 'Role Re-assigned Successfully');
    }
}
