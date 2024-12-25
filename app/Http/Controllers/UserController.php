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

class UserController extends Controller
{

    public function index()
    {
        $user = User::get();
        $columns = User::$columns;
        if (request()->ajax()) {
            return DataTables::of($user)
                ->addColumn('role', function ($user) {
                    return $user->role->role->name ?? 'N/A';
                })
                ->addColumn('email', function ($user) {
                    return $user->email ?? 'N/A';
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
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'conform_password' => 'required|same:password',
            'role_id' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
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
            'email' => 'required',
            'role_id' => 'required',
        ]);

        if ($request->password) {
            $request->validate([
                'password' => 'required|min:6',
                'conform_password' => 'required|same:password',
            ]);
        }
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }
        User::findOrFail($id)->update($data);
        return redirect()->route('user.index')->with('success', 'User Updated Successfully');
    }
    public function delete($id)
    {
        User::findOrFail($id)->delete();
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
