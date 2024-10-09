<?php

namespace App\Service;

use App\Models\Brand;
use App\Service\Service as ServiceService;
use App\Services\Service;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BrandService extends ServiceService
{
  protected $model = Brand::class;

  public function Index()
  {
    $data = $this->model::all();

    return DataTables::of($data)
      ->addColumn('action', fn($item) => view('pages.brand.action', compact('item'))->render())
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
        $message = ['success' => 'Brand Inserted Successfully'];
      } else {
        $this->model::findOrFail($data['id'])->update([
          'name' => $data['name'],
          'code' => $data['code'],
        ]);
        $message = ['success' => 'Brand Updated Successfully'];
      }
      DB::commit();
      return $message;
    } catch (Exception $th) {
      DB::rollback();
      dd($th->getMessage());
    }
  }
}
