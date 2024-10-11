<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ProductController;
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
    Route::group(['prefix' => 'accounting/bank', 'as' => 'bank.'], function () {
        Route::get('/', [BankController::class, 'index'])->name('index');
        Route::post('/store', [BankController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BankController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [BankController::class, 'delete'])->name('delete');
    });
    Route::group(['prefix' => 'income-category', 'as' => 'income-category.'], function () {
        Route::get('/', [IncomeCategoryController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [IncomeCategoryController::class, 'edit'])->name('edit');
        Route::get('/trash', [IncomeCategoryController::class, 'trash'])->name('trash');
        Route::get('/restore/{id}', [IncomeCategoryController::class, 'restore'])->name('restore');
        Route::post('/store', [IncomeCategoryController::class, 'store'])->name('store');
        Route::post('/categprystore', [IncomeCategoryController::class, 'categprystore'])->name('categprystore');
        Route::get('/delete/{id}', [IncomeCategoryController::class, 'delete'])->name('delete');
        Route::get('/pdelete/{id}', [IncomeCategoryController::class, 'pdelete'])->name('pdelete');
    });
    Route::group(['prefix' => 'income', 'as' => 'income.'], function () {
        Route::get('/', [IncomeController::class, 'index'])->name('index');
        Route::get('/trash', [IncomeController::class, 'trash'])->name('trash');
        Route::get('/restore/{id}', [IncomeController::class, 'restore'])->name('restore');
        Route::post('/store', [IncomeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [IncomeController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [IncomeController::class, 'delete'])->name('delete');
        Route::get('/pdelete/{id}', [IncomeController::class, 'pdelete'])->name('pdelete');
    });
    Route::group(['prefix' => 'expense-category', 'as' => 'expense-category.'], function () {
        Route::get('/', [ExpenseCategoryController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [ExpenseCategoryController::class, 'edit'])->name('edit');
        Route::get('/trash', [ExpenseCategoryController::class, 'trash'])->name('trash');
        Route::get('/restore/{id}', [ExpenseCategoryController::class, 'restore'])->name('restore');
        Route::post('/store', [ExpenseCategoryController::class, 'store'])->name('store');
        Route::post('/categprystore', [ExpenseCategoryController::class, 'categprystore'])->name('categprystore');
        Route::get('/delete/{id}', [ExpenseCategoryController::class, 'delete'])->name('delete');
        Route::get('/pdelete/{id}', [ExpenseCategoryController::class, 'pdelete'])->name('pdelete');
    });
    Route::group(['prefix' => 'expense', 'as' => 'expense.'], function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('index');
        Route::get('/trash', [ExpenseController::class, 'trash'])->name('trash');
        Route::get('/restore/{id}', [ExpenseController::class, 'restore'])->name('restore');
        Route::post('/store', [ExpenseController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ExpenseController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [ExpenseController::class, 'delete'])->name('delete');
        Route::get('/pdelete/{id}', [ExpenseController::class, 'pdelete'])->name('pdelete');
    });

    Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/trash', [CustomerController::class, 'trash'])->name('trash');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/store', [CustomerController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('edit');
        Route::get('/restore/{id}', [CustomerController::class, 'restore'])->name('restore');
        Route::post('/update/{id}', [CustomerController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [CustomerController::class, 'delete'])->name('delete');
        Route::get('/pdelete/{id}', [CustomerController::class, 'pdelete'])->name('pdelete');
        Route::post('/import', [CustomerController::class, 'import'])->name('import');
        Route::get('/sale/references/{id}', [CustomerController::class, 'saleDetails']);
        Route::get('/sale/due/{id}', [CustomerController::class, 'saleDue']);
        Route::post('/payment', [CustomerController::class, 'payment'])->name('payment');
        Route::get('/payment/details/{id}', [CustomerController::class, 'paymentDetails']);
        Route::delete('/payment/delete/{id}', [CustomerController::class, 'paymentDelete'])->name('payment.delete');
    });
    Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/trash', [EmployeeController::class, 'trash'])->name('trash');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/store', [EmployeeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('edit');
        Route::get('/restore/{id}', [EmployeeController::class, 'restore'])->name('restore');
        Route::post('/update/{id}', [EmployeeController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [EmployeeController::class, 'delete'])->name('delete');
        Route::get('/pdelete/{id}', [EmployeeController::class, 'pdelete'])->name('pdelete');
        Route::post('/import', [EmployeeController::class, 'import'])->name('import');
    });
    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/trash', [ProductController::class, 'trash'])->name('trash');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::get('/restore/{id}', [ProductController::class, 'restore'])->name('restore');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('delete');
        Route::get('/pdelete/{id}', [ProductController::class, 'pdelete'])->name('pdelete');
        Route::post('/import', [ProductController::class, 'import'])->name('import');
    });
});

require __DIR__ . '/auth.php';
