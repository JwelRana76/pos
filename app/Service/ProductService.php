<?php

namespace App\Service;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\Unit;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductService
{
    protected $model = Product::class;

    public function Index()
    {
        $data = $this->model::get();

        return DataTables::of($data)
            ->addColumn('image', function ($item) {
                if ($item->image) {
                    $img = '<img src="/upload/product/' . $item->image . '" alt="logo" width="80px">';
                } else {
                    $img = '<img src="/default_product.png" alt="logo" width="80px" height="50px">';
                }
                return $img;
            })
            ->addColumn('category', function ($item) {
                return $item->category->name ?? 'N/A';
            })
            ->addColumn('brand', function ($item) {
                return $item->brand->name ?? 'N/A';
            })
            ->addColumn('stock', function ($item) {
                return $item->stock;
            })
            ->addColumn('action', fn($item) => view('pages.product.action', compact('item'))->render())
            ->rawColumns(['image', 'action'])
            ->make(true);
    }
    public function Trash()
    {
        $data = $this->model::onlyTrashed();

        return DataTables::of($data)
            ->addColumn('image', function ($item) {
                if ($item->image) {
                    $img = '<img src="/upload/product/' . $item->image . '" alt="logo" width="80px">';
                } else {
                    $img = '<img src="/default_product.png" alt="logo" width="80px" height="50px">';
                }
                return $img;
            })
            ->addColumn('category', function ($item) {
                return $item->category->name ?? 'N/A';
            })
            ->addColumn('brand', function ($item) {
                return $item->brand->name ?? 'N/A';
            })
            ->addColumn('stock', function ($item) {
                return $item->stock;
            })
            ->addColumn('action', fn($item) => view('pages.product.taction', compact('item'))->render())
            ->rawColumns(['image', 'action'])
            ->make(true);
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $product_data['name'] = $data['name'];
            $product_data['code'] = $data['code'];
            $product_data['category_id'] = $data['category'];
            $product_data['brand_id'] = $data['brand'];
            $product_data['size_id'] = $data['size'];
            $product_data['unit_id'] = $data['unit'];
            $product_data['cost'] = $data['cost'];
            $product_data['price'] = $data['price'];
            $product_data['alert_qty'] = $data['alert_qty'];
            if (isset($data['image'])) {
                $path = "upload/product";
                $file = $data['image'];
                $name = time() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $name);
                $product_data['image'] = $name;
            }
            $this->model::create($product_data);
            $message = 'Product Inserted Successfully';
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }
    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $product = $this->model::findOrFail($id);
            $product_data['name'] = $data['name'];
            $product_data['code'] = $data['code'];
            $product_data['category_id'] = $data['category'];
            $product_data['brand_id'] = $data['brand'];
            $product_data['size_id'] = $data['size'];
            $product_data['unit_id'] = $data['unit'];
            $product_data['cost'] = $data['cost'];
            $product_data['price'] = $data['price'];
            $product_data['alert_qty'] = $data['alert_qty'];


            if (isset($data['image'])) {

                $uploadDirectory = 'upload/product/';
                $existingImagePath = $uploadDirectory . $product->image;
                if (file_exists($existingImagePath) && $product->image) {
                    unlink($existingImagePath);
                }

                $path = "upload/product";
                $file = $data['image'];
                $name = time() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $name);
                $product_data['image'] = $name;
            }
            $product->update($product_data);
            $message = 'Product Updated Successfully';
            DB::commit();
            return $message;
        } catch (Exception $th) {
            DB::rollback();
            dd($th->getMessage());
        }
    }

    function Import($data)
    {
        if (!empty($data['product_file'])) {
            $file = $data['product_file'];
            if ($file->getClientOriginalExtension() === 'csv') {
                DB::beginTransaction();
                try {
                    $csvData = file_get_contents($file);
                    $csvArray = array_map("str_getcsv", explode("\n", $csvData));
                    array_shift($csvArray);
                    array_pop($csvArray);
                    $headers = ['name', 'code', 'category_id', 'brand_id', 'size_id', 'unit_id', 'cost', 'price', 'carton_size'];
                    foreach ($csvArray as $row) {
                        $duplicate_code = $this->model::where('code', $row[1])->first();
                        if ($duplicate_code) {
                            return ['error' => 'Duplicate Product Code Don\'t insert'];
                        }
                        $find_cat = Category::where('name', $row[2])->first();
                        if (!$find_cat) {
                            $cat = Category::create([
                                'name' => $row[2],
                                'code' => rand(1000, 9999),
                            ]);
                            $row[2] = $cat->id;
                        } else {
                            $row[2] = $find_cat->id;
                        }
                        $findbrand = Brand::where('name', $row[3])->first();
                        if (!$findbrand) {
                            $brand = Brand::create([
                                'name' => $row[3],
                                'code' => rand(1000, 9999),
                            ]);
                            $row[3] = $brand->id;
                        } else {
                            $row[3] = $findbrand->id;
                        }
                        $findssize = Size::where('name', $row[4])->first();
                        if (!$findssize) {
                            $size = Size::create([
                                'name' => $row[4],
                                'code' => rand(1000, 9999),
                            ]);
                            $row[4] = $size->id;
                        } else {
                            $row[4] = $findssize->id;
                        }
                        $findunit = Unit::where('name', $row[5])->first();
                        if (!$findunit) {
                            $unit = Unit::create([
                                'name' => $row[5],
                                'code' => rand(1000, 9999),
                            ]);
                            $row[5] = $unit->id;
                        } else {
                            $row[5] = $findunit->id;
                        }
                        $product = new $this->model();
                        $productData = array_combine($headers, $row);
                        foreach ($productData as $key => $value) {
                            $product->$key = $value;
                        }

                        $product->save();
                    }
                    $message = 'Product Imported Successfully';
                    DB::commit();
                    return $message;
                } catch (Exception $th) {
                    DB::rollback();
                    dd($th->getMessage());
                }
            } else {
                return ['error' => 'Invalid file format. Please upload a CSV file.'];
            }
        }
        return ['error' => 'Invalid file Please upload a CSV file.'];
    }
}
