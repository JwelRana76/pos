<?php

namespace App\Service;

use App\Models\Employee;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class EmployeeService
{
    protected $model = Employee::class;

    public function Index()
    {
        $data = $this->model::get();

        return DataTables::of($data)
            ->addColumn('image', function ($item) {
                if ($item->image) {
                    $img = '<img src="/upload/employee/' . $item->image . '" alt="logo" width="80px">';
                } else {
                    $img = '<img src="/upload/customer/default_product.jpg" alt="logo" width="80px">';
                }
                return $img;
            })
            ->addColumn('district', function ($item) {
                return $item->district->name ?? 'N/A';
            })
            ->addColumn('action', fn($item) => view('pages.employee.action', compact('item'))->render())
            ->rawColumns(['image', 'action'])
            ->make(true);
    }
    public function Trash()
    {
        $data = $this->model::onlyTrashed();

        return DataTables::of($data)
            ->addColumn('image', function ($item) {
                if ($item->image) {
                    $img = '<img src="/upload/employee/' . $item->image . '" alt="logo" width="80px">';
                } else {
                    $img = '<img src="/upload/customer/default_product.jpg" alt="logo" width="80px">';
                }
                return $img;
            })
            ->addColumn('district', function ($item) {
                return $item->district->name ?? 'N/A';
            })
            ->addColumn('action', fn($item) => view('pages.employee.taction', compact('item'))->render())
            ->rawColumns(['image', 'action'])
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $employee_data['name'] = $data['name'];
            $employee_data['contact'] = $data['contact'];
            $employee_data['district_id'] = $data['district'];
            $employee_data['email'] = $data['email'];
            $employee_data['address'] = $data['address'];
            if (isset($data['image'])) {
                $path = "upload/employee";
                $file = $data['image'];
                $name = time() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $name);
                $employee_data['image'] = $name;
            }

            if (isset($data['is_user'])) {
                $user = User::create([
                    'name' => $data['name'],
                    'username' => $data['user_name'],
                    'password' => Hash::make($data['password']),
                ]);
                $user->role()->create([
                    'role_id' => $data['role'],
                ]);
                $employee_data['user_id'] = $user->id;
                $employee_data['is_user'] = $data['is_user'] == 'on' ? true : false;
            }
            $this->model::create($employee_data);
            $message = ['success' => 'Employee Inserted Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd('Error: ' . $th->getMessage() . ' in file ' . $th->getFile() . ' on line ' . $th->getLine());
        }
    }
    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $employee = $this->model::findOrFail($id);
            $employee_data['name'] = $data['name'];
            $employee_data['contact'] = $data['contact'];
            $employee_data['district_id'] = $data['district'];
            $employee_data['email'] = $data['email'];
            $employee_data['address'] = $data['address'];
            if (isset($data['image'])) {

                $uploadDirectory = 'upload/employee/';
                $existingImagePath = $uploadDirectory . $employee->image;
                if (file_exists($existingImagePath) && $employee->image) {
                    unlink($existingImagePath);
                }
                $path = "upload/employee";
                $file = $data['image'];
                $name = time() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $name);
                $employee_data['image'] = $name;
            }
            if (isset($data['is_user'])) {
                $user = User::findOrFail($employee->user_id);
                if ($user) {
                    $user->update([
                        'name' => $data['name'],
                        'username' => $data['user_name'],
                        'password' => Hash::make($data['password']),
                    ]);
                } else {
                    $user = User::create([
                        'name' => $data['name'],
                        'username' => $data['user_name'],
                        'password' => Hash::make($data['password']),
                    ]);
                    $user->role()->create([
                        'role_id' => $data['role'],
                    ]);
                }
                $employee_data['user_id'] = $user->id;
                $employee_data['is_user'] = $data['is_user'] == 'on' ? true : false;
            }
            $employee->update($employee_data);
            $message = ['success' => 'Employee Updated Successfully'];
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
}
