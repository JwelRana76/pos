<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\AdvanceSalaryController;
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
use App\Http\Controllers\InvestController;
use App\Http\Controllers\InvestReturnController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanReturnController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryAssignController;
use App\Http\Controllers\SalaryParticularController;
use App\Http\Controllers\SalaryPaymentController;
use App\Http\Controllers\SalarySubmitController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
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
    Route::group(['prefix' => 'invest', 'as' => 'invest.'], function () {
        Route::get('/', [InvestController::class, 'index'])->name('index');
        Route::get('/trash', [InvestController::class, 'trash'])->name('trash');
        Route::get('/restore/{id}', [InvestController::class, 'restore'])->name('restore');
        Route::post('/store', [InvestController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [InvestController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [InvestController::class, 'delete'])->name('delete');
        Route::get('/pdelete/{id}', [InvestController::class, 'pdelete'])->name('pdelete');
    });
    Route::group(['prefix' => 'invest-return', 'as' => 'invest_return.'], function () {
        Route::get('/', [InvestReturnController::class, 'index'])->name('index');
        Route::get('/view/{id}', [InvestReturnController::class, 'view'])->name('view');
        Route::post('/store', [InvestReturnController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [InvestReturnController::class, 'edit'])->name('edit');
        Route::post('/update', [InvestReturnController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [InvestReturnController::class, 'delete'])->name('delete');
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
    Route::group(['prefix' => 'supplier', 'as' => 'supplier.'], function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/trash', [SupplierController::class, 'trash'])->name('trash');
        Route::get('/create', [SupplierController::class, 'create'])->name('create');
        Route::post('/store', [SupplierController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SupplierController::class, 'edit'])->name('edit');
        Route::get('/restore/{id}', [SupplierController::class, 'restore'])->name('restore');
        Route::post('/update/{id}', [SupplierController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SupplierController::class, 'delete'])->name('delete');
        Route::get('/pdelete/{id}', [SupplierController::class, 'pdelete'])->name('pdelete');
        Route::post('/import', [SupplierController::class, 'import'])->name('import');
        Route::get('/purchase/references/{id}', [SupplierController::class, 'purchaseDetails'])->name('purchasedetails');
        Route::get('/purchase/due/{id}', [SupplierController::class, 'purchaseDue']);
        Route::post('/payment', [SupplierController::class, 'payment'])->name('payment');
        Route::get('/payment/details/{id}', [SupplierController::class, 'paymentDetails']);
        Route::delete('/payment/delete/{id}', [SupplierController::class, 'paymentDelete'])->name('payment.delete');
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
    Route::group(['prefix' => 'adjustment', 'as' => 'adjustment.'], function () {
        Route::get('/', [AdjustmentController::class, 'index'])->name('index');
        Route::get('/create', [AdjustmentController::class, 'create'])->name('create');
        Route::post('/store', [AdjustmentController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AdjustmentController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [AdjustmentController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [AdjustmentController::class, 'delete'])->name('delete');
        Route::get('/show/{id}', [AdjustmentController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'purchase', 'as' => 'purchase.'], function () {
        Route::get('/', [PurchaseController::class, 'index'])->name('index');
        Route::get('/trash', [PurchaseController::class, 'trash'])->name('trash');
        Route::get('/create', [PurchaseController::class, 'create'])->name('create');
        Route::post('/store', [PurchaseController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PurchaseController::class, 'edit'])->name('edit');
        Route::get('/restore/{id}', [PurchaseController::class, 'restore'])->name('restore');
        Route::post('/update/{id}', [PurchaseController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [PurchaseController::class, 'delete'])->name('delete');
        Route::get('/pdelete/{id}', [PurchaseController::class, 'pdelete'])->name('pdelete');
        Route::post('/getProduct/', [PurchaseController::class, 'getProduct'])->name('getProduct');
        Route::get('/show/{id}', [PurchaseController::class, 'show'])->name('show');
        Route::get('/dueamount/{id}', [PurchaseController::class, 'dueamount']);
    });
    Route::group(['prefix' => 'return-purchase', 'as' => 'purchase.return.'], function () {
        Route::get('/', [PurchaseReturnController::class, 'index'])->name('index');
        Route::get('/create', [PurchaseReturnController::class, 'create'])->name('create');
        Route::post('/store', [PurchaseReturnController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PurchaseReturnController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [PurchaseReturnController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [PurchaseReturnController::class, 'delete'])->name('delete');
        Route::post('/getProduct/', [PurchaseReturnController::class, 'getProduct'])->name('getProduct');
        Route::get('/show/{id}', [PurchaseReturnController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'sale', 'as' => 'sale.'], function () {
        Route::get('/', [SaleController::class, 'index'])->name('index');
        Route::get('/trash', [SaleController::class, 'trash'])->name('trash');
        Route::get('/create', [SaleController::class, 'create'])->name('create');
        Route::post('/store', [SaleController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SaleController::class, 'edit'])->name('edit');
        Route::get('/restore/{id}', [SaleController::class, 'restore'])->name('restore');
        Route::post('/update/{id}', [SaleController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SaleController::class, 'delete'])->name('delete');
        Route::get('/pdelete/{id}', [SaleController::class, 'pdelete'])->name('pdelete');
        Route::post('/getProduct/', [SaleController::class, 'getProduct'])->name('getProduct');
        Route::get('/invoice/{id}', [SaleController::class, 'invoice'])->name('invoice');
        Route::get('/show/{id}', [SaleController::class, 'show'])->name('show');
        Route::get('/dueamount/{id}', [SaleController::class, 'dueamount']);
    });
    Route::group(['prefix' => 'return-sale', 'as' => 'sale.return.'], function () {
        Route::get('/', [SaleReturnController::class, 'index'])->name('index');
        Route::get('/create', [SaleReturnController::class, 'create'])->name('create');
        Route::post('/store', [SaleReturnController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SaleReturnController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SaleReturnController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SaleReturnController::class, 'delete'])->name('delete');
        Route::post('/getProduct/', [SaleReturnController::class, 'getProduct'])->name('getProduct');
        Route::get('/show/{id}', [SaleReturnController::class, 'show'])->name('show');
    });
    Route::group(['prefix' => 'payrole/salary-particular', 'as' => 'salary-particular.'], function () {
        Route::get('/', [SalaryParticularController::class, 'index'])->name('index');
        Route::post('/store', [SalaryParticularController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SalaryParticularController::class, 'edit'])->name('edit');
        Route::get('/delete/{id}', [SalaryParticularController::class, 'delete'])->name('delete');
    });
    Route::group(['prefix' => 'payrole/salary-assign', 'as' => 'salary-assign.'], function () {
        Route::get('/{id}', [SalaryAssignController::class, 'index'])->name('index');
        Route::post('/store', [SalaryAssignController::class, 'store'])->name('store');
        Route::get('/delete/{id}', [SalaryAssignController::class, 'delete'])->name('delete');
        Route::get('/details/{id}', [SalaryAssignController::class, 'salarydetails']);
    });
    Route::group(['prefix' => 'payrole/salary-submit', 'as' => 'salary-submit.'], function () {
        Route::get('/', [SalarySubmitController::class, 'index'])->name('index');
        Route::get('/create', [SalarySubmitController::class, 'create'])->name('create');
        Route::post('/store', [SalarySubmitController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SalarySubmitController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SalarySubmitController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SalarySubmitController::class, 'delete'])->name('delete');
    });
    Route::group(['prefix' => 'payrole/salary-payment', 'as' => 'salary-payment.'], function () {
        Route::get('/', [SalaryPaymentController::class, 'index'])->name('index');
        Route::get('/create', [SalaryPaymentController::class, 'create'])->name('create');
        Route::post('/store', [SalaryPaymentController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SalaryPaymentController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SalaryPaymentController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [SalaryPaymentController::class, 'delete'])->name('delete');
        Route::get('/salary_details/{month}/{employee_id}', [SalaryPaymentController::class, 'salary_details']);
    });
    Route::group(['prefix' => 'payrole/advance-salary', 'as' => 'advance-salary.'], function () {
        Route::get('/', [AdvanceSalaryController::class, 'index'])->name('index');
        Route::get('/create', [AdvanceSalaryController::class, 'create'])->name('create');
        Route::post('/store', [AdvanceSalaryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AdvanceSalaryController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [AdvanceSalaryController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [AdvanceSalaryController::class, 'delete'])->name('delete');
    });
    Route::group(['prefix' => 'loan', 'as' => 'loan.'], function () {
        Route::get('/', [LoanController::class, 'index'])->name('index');
        Route::get('/trash', [LoanController::class, 'trash'])->name('trash');
        Route::get('/create', [LoanController::class, 'create'])->name('create');
        Route::post('/store', [LoanController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [LoanController::class, 'edit'])->name('edit');
        Route::get('/restore/{id}', [LoanController::class, 'restore'])->name('restore');
        Route::post('/update/{id}', [LoanController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [LoanController::class, 'delete'])->name('delete');
        Route::get('/pdelete/{id}', [LoanController::class, 'pdelete'])->name('pdelete');
    });
    Route::group(['prefix' => 'loan-return', 'as' => 'loan-return.'], function () {
        Route::get('/view/{id}', [LoanReturnController::class, 'index'])->name('index');
        Route::get('/create', [LoanReturnController::class, 'create'])->name('create');
        Route::post('/store', [LoanReturnController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [LoanReturnController::class, 'edit'])->name('edit');
        Route::post('/update', [LoanReturnController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [LoanReturnController::class, 'delete'])->name('delete');
    });
    Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
        Route::get('/product', [ReportController::class, 'productReport'])->name('product');
        Route::get('/sale', [ReportController::class, 'saleReport'])->name('sale');
        Route::get('/purchase', [ReportController::class, 'purchaseReport'])->name('purchase');
        Route::get('/income', [ReportController::class, 'incomeReport'])->name('income');
        Route::get('/expense', [ReportController::class, 'expenseReport'])->name('expense');
    });
});

require __DIR__ . '/auth.php';
