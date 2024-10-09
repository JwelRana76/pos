<?php

namespace App\Service;

use App\Models\Unit;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UnitService
{

  protected $model = Unit::class;

  public function Index()
  {
    $data = $this->model::all();

    return DataTables::of($data)
      ->addColumn('action', fn ($item) => view('pages.unit.action', compact('item'))->render())
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
        $message = ['success' => 'Unit Inserted Successfully'];
      } else {
        $this->model::findOrFail($data['id'])->update([
          'name' => $data['name'],
          'code' => $data['code'],
        ]);
        $message = ['success' => 'Unit Updated Successfully'];
      }
      DB::commit();
      return $message;
    } catch (Exception $th) {
      DB::rollback();
      dd($th->getMessage());
    }
  }
}
