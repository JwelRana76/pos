<?php

namespace App\Service;

use App\Models\Division;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DivisionService
{

  protected $model = Division::class;

  public function Index()
  {
    $data = $this->model::get();

    return DataTables::of($data)
      ->addColumn('action', fn($item) => view('pages.division.action', compact('item'))->render())
      ->make(true);
  }
  public function create($data)
  {
    DB::beginTransaction();
    try {
      if ($data['id'] == null) {
        $this->model::create([
          'name' => $data['name'],
          'code' => $data['code'],
        ]);
        $message = ['success' => 'Division Inserted Successfully'];
      } else {
        $this->model::findOrFail($data['id'])->update([
          'name' => $data['name'],
          'code' => $data['code'],
        ]);
        $message = ['success' => 'Division Updated Successfully'];
      }
      DB::commit();
      return $message;
    } catch (Exception $th) {
      DB::rollback();
      dd($th->getMessage());
    }
  }
}
