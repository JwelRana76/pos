<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'home'])->name('dashboard');
    Route::get('/dashboard/data', [ProfileController::class, 'getData'])->name('users.data');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'setting/role', 'as' => 'role.'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('edit');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::get('/delete/{id}', [RoleController::class, 'delete'])->name('delete');
        Route::post('/update/{id}', [RoleController::class, 'update'])->name('update');
        Route::get('/permission/{id}', [RoleController::class, 'permission'])->name('permission');
        Route::post('/permission/store/{id}', [RoleController::class, 'permission_store'])->name('permission.store');
    });
    Route::group(['prefix' => 'setting/site_setting', 'as' => 'site_setting.'], function () {
        Route::get('/', [SiteSettingController::class, 'index'])->name('index');
        Route::post('/update/{id}', [SiteSettingController::class, 'update'])->name('update');
    });
    Route::group(['prefix' => 'setting/user', 'as' => 'user.'], function () {
        Route::get('/', [UsersController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [UsersController::class, 'edit'])->name('edit');
        Route::post('/store', [UsersController::class, 'store'])->name('store');
        Route::get('/delete/{id}', [UsersController::class, 'delete'])->name('delete');
        Route::post('/update/{id}', [UsersController::class, 'update'])->name('update');
        Route::get('/assign_role/{id}', [UsersController::class, 'assign_role'])->name('role_assign');
        Route::post('/assign_role', [UsersController::class, 'assign_role_store'])->name('role_assign_store');
    });
    Route::group(['prefix' => 'setting/division', 'as' => 'division.'], function () {
        Route::get('/', [DivisionController::class, 'index'])->name('index');
        Route::post('/store', [DivisionController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [DivisionController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [DivisionController::class, 'delete'])->name('delete');
        Route::post('/divisionstore', [DivisionController::class, 'divisionstore'])->name('divisionstore');
    });
    Route::group(['prefix' => 'setting/district', 'as' => 'district.'], function () {
        Route::get('/', [DistrictController::class, 'index'])->name('index');
        Route::post('/store', [DistrictController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [DistrictController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [DistrictController::class, 'delete'])->name('delete');
        Route::post('/districtstore', [DistrictController::class, 'districtstore'])->name('districtstore');
    });
    Route::group(['prefix' => 'setting/brand', 'as' => 'brand.'], function () {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::post('/store', [BrandController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [BrandController::class, 'delete'])->name('delete');
        Route::post('/brandstore', [BrandController::class, 'brandstore'])->name('brandstore');
    });
    Route::group(['prefix' => 'setting/category', 'as' => 'category.'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [CategoryController::class, 'delete'])->name('delete');
        Route::post('/categorystore', [CategoryController::class, 'categorystore'])->name('categorystore');
    });
    Route::group(['prefix' => 'setting/size', 'as' => 'size.'], function () {
        Route::get('/', [SizeController::class, 'index'])->name('index');
        Route::post('/store', [SizeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SizeController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [SizeController::class, 'delete'])->name('delete');
        Route::post('/sizestore', [SizeController::class, 'sizestore'])->name('sizestore');
    });
    Route::group(['prefix' => 'setting/unit', 'as' => 'unit.'], function () {
        Route::get('/', [UnitController::class, 'index'])->name('index');
        Route::post('/store', [UnitController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UnitController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [UnitController::class, 'delete'])->name('delete');
        Route::post('/unitstore', [UnitController::class, 'unitstore'])->name('unitstore');
    });
    // accounting menu route section
    Route::group(['prefix' => 'accounting/account', 'as' => 'account.'], function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::post('/store', [AccountController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AccountController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [AccountController::class, 'delete'])->name('delete');
    });
});

require __DIR__ . '/auth.php';
