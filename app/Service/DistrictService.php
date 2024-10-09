<?php

namespace App\Service;

use App\Models\District;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DistrictService {

  protected $model = District::class;

  public function Index()
  {
    $data = $this->model::all();

    return DataTables::of($data)
      ->addColumn('division', function ($item) {
        return $item->division->name ?? 'N/A';
      })
      ->addColumn('action', fn ($item) => view('pages.district.action', compact('item'))->render())
      ->make(true);
  }
  public function create($data)
  {
    DB::beginTransaction();
    try {
      if ($data['id'] == null) {
        $this->model::create([
          'division_id' => $data['division_id'],
          'name' => $data['name'],
          'code' => $data['code'],
        ]);
        $message = ['success' => 'District Inserted Successfully'];
      } else {
        $this->model::findOrFail($data['id'])->update([
          'division_id' => $data['division_id'],
          'name' => $data['name'],
          'code' => $data['code'],
        ]);
        $message = ['success' => 'District Updated Successfully'];
      }
      DB::commit();
      return $message;
    } catch (Exception $th) {
      DB::rollback();
      dd($th->getMessage());
    }
  }
}