<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ setting()->name_short }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    @if(userHasPermission('product-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('product*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#product"
            aria-expanded="true" aria-controls="product">
            <i class="fa-brands fa-wpforms"></i>
            <span>Product</span>
        </a>
        <div id="product" class="collapse {{Request::is('product*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('product-index'))
                <a class="collapse-item {{Request::is('product')?'active':''}}" href="{{ route('product.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Product List</a>
                @endif
                @if(userHasPermission('product-store'))
                <a class="collapse-item {{Request::is('product/create')?'active':''}}" href="{{ route('product.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Add Product</a>
                @endif
                @if(userHasPermission('product-advance'))
                <a class="collapse-item {{Request::is('product/trash')?'active':''}}" href="{{ route('product.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Trash List</a>
                @endif
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('adjustment-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('adjustment*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#adjustment"
            aria-expanded="true" aria-controls="adjustment">
            <i class="fa-brands fa-wpforms"></i>
            <span>Adjustment</span>
        </a>
        <div id="adjustment" class="collapse {{Request::is('adjustment*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('adjustment-index'))
                <a class="collapse-item {{Request::is('adjustment')?'active':''}}" href="{{ route('adjustment.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Adjustment List</a>
                @endif
                @if(userHasPermission('adjustment-store'))
                <a class="collapse-item {{Request::is('adjustment/create')?'active':''}}" href="{{ route('adjustment.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Add adjustment</a>
                @endif
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('purchase-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('purchase*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#purchase"
            aria-expanded="true" aria-controls="purchase">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Purchase</span>
        </a>
        <div id="purchase" class="collapse {{Request::is('purchase*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('purchase-index'))
                <a class="collapse-item {{Request::is('purchase')?'active':''}}" href="{{ route('purchase.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Purchase List</a>
                @endif
                @if(userHasPermission('purchase-store'))
                <a class="collapse-item {{Request::is('purchase/create')?'active':''}}" href="{{ route('purchase.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Add Purchase</a>
                @endif
                @if(userHasPermission('purchase-advance'))
                <a class="collapse-item {{Request::is('purchase/trash')?'active':''}}" href="{{ route('purchase.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Trash List</a>
                @endif
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('sale-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('sale*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#sale"
            aria-expanded="true" aria-controls="sale">
            <i class="fas fa-fw fa-scale-unbalanced"></i>
            <span>Sale</span>
        </a>
        <div id="sale" class="collapse {{Request::is('sale*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('sale-index'))
                <a class="collapse-item {{Request::is('sale')?'active':''}}" href="{{ route('sale.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Sale List</a>
                @endif
                @if(userHasPermission('sale-store'))
                <a class="collapse-item {{Request::is('sale/create')?'active':''}}" href="{{ route('sale.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Add Sale</a>
                @endif
                @if(userHasPermission('sale-advance'))
                <a class="collapse-item {{Request::is('sale/trash')?'active':''}}" href="{{ route('sale.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Trash List</a>
                @endif
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('return-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('return*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#return"
            aria-expanded="true" aria-controls="return">
            <i class="fas fa-fw fa-right-left"></i>
            <span> Return</span>
        </a>
        <div id="return" class="collapse {{Request::is('return*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('sale-store'))
                <a class="collapse-item {{Request::is('return-sale*')?'active':''}}" href="{{ route('sale.return.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i>Sale</a>
                
                <a class="collapse-item {{Request::is('return-purchase*')?'active':''}}" href="{{ route('purchase.return.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i>Purchase</a>
                @endif
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('customer-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('customer*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#customer"
            aria-expanded="true" aria-controls="customer">
            <i class="fas fa-fw fa-people-group"></i>
            <span>Customer</span>
        </a>
        <div id="customer" class="collapse {{Request::is('customer*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('customer-index'))
                <a class="collapse-item {{Request::is('customer')?'active':''}}" href="{{ route('customer.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Customer List</a>
                @endif
                @if(userHasPermission('customer-store'))
                <a class="collapse-item {{Request::is('customer/create')?'active':''}}" href="{{ route('customer.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Add Customer</a>
                @endif
                @if(userHasPermission('customer-advance'))
                <a class="collapse-item {{Request::is('customer/trash')?'active':''}}" href="{{ route('customer.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Trash List</a>
                @endif
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('supplier-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('supplier*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#supplier"
            aria-expanded="true" aria-controls="supplier">
            <i class="fas fa-fw fa-users"></i>
            <span>Supplier</span>
        </a>
        <div id="supplier" class="collapse {{Request::is('supplier*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('supplier-index'))
                <a class="collapse-item {{Request::is('supplier')?'active':''}}" href="{{ route('supplier.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Supplier List</a>
                @endif
                @if(userHasPermission('supplier-store'))
                <a class="collapse-item {{Request::is('supplier/create')?'active':''}}" href="{{ route('supplier.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Add Supplier</a>
                @endif
                @if(userHasPermission('supplier-advance'))
                <a class="collapse-item {{Request::is('supplier/trash')?'active':''}}" href="{{ route('supplier.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Trash List</a>
                @endif
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('employee-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('employee*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#employee"
            aria-expanded="true" aria-controls="employee">
            <i class="fas fa-fw fa-people-line"></i>
            <span>Employee</span>
        </a>
        <div id="employee" class="collapse {{Request::is('employee*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('employee-index'))
                <a class="collapse-item {{Request::is('employee')?'active':''}}" href="{{ route('employee.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Employee List</a>
                @endif
                @if(userHasPermission('employee-store'))
                <a class="collapse-item {{Request::is('employee/create')?'active':''}}" href="{{ route('employee.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Add Employee</a>
                @endif
                @if(userHasPermission('employee-advance'))
                <a class="collapse-item {{Request::is('employee/trash')?'active':''}}" href="{{ route('employee.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Trash List</a>
                @endif
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('accounting-module'))
    <li class="nav-item">
        <a class="nav-link {{ Request::is('accounting*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#accounting" aria-expanded="true" aria-controls="accounting">
            <i class="fas fa-fw fa-cog"></i>
            <span>Accounting</span>
        </a>
        <div id="accounting" class="collapse {{ Request::is('accounting*') ? 'show' : '' }}" aria-labelledby="headingTwo"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('accounting-index'))
                <a class="collapse-item {{ Request::is('accounting/account*') ? 'active' : '' }}"
                    href="{{ route('account.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>Account</a>
                <a class="collapse-item {{ Request::is('accounting/bank*') ? 'active' : '' }}"
                    href="{{ route('bank.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>Bank</a>
                <a class="collapse-item {{ Request::is('accounting/bank-transection') ? 'active' : '' }}"
                    href="{{ route('bank_transection.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>Bank Transection</a>
                <a class="collapse-item {{ Request::is('accounting/bank-transection/create') ? 'active' : '' }}"
                    href="{{ route('bank_transection.create') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>New Transection</a>
                @endif
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('income-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('income*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#income"
            aria-expanded="true" aria-controls="income">
            <i class="fas fa-fw fa-money-bill-wheat"></i>
            <span>Income</span>
        </a>
        <div id="income" class="collapse {{Request::is('income*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('income-index'))
                <a class="collapse-item {{Request::is('income')?'active':''}}" href="{{ route('income.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Income List</a>
                @endif
                @if(userHasPermission('income-advance'))
                <a class="collapse-item {{Request::is('income-category')?'active':''}}" href="{{ route('income-category.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Income Category</a>
                <a class="collapse-item {{Request::is('income/trash')?'active':''}}" href="{{ route('income.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Income Trash</a>
                <a class="collapse-item {{Request::is('income-category/trash')?'active':''}}" href="{{ route('income-category.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Category Trash</a>
                @endif
                
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('invest-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('invest*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#invest"
            aria-expanded="true" aria-controls="invest">
            <i class="fas fa-fw fa-money-bill-wheat"></i>
            <span>Invest</span>
        </a>
        <div id="invest" class="collapse {{Request::is('invest*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('invest-index'))
                <a class="collapse-item {{Request::is('invest')?'active':''}}" href="{{ route('invest.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Invest List</a>
                @endif
                @if(userHasPermission('income-advance'))
                <a class="collapse-item {{Request::is('invest/trash')?'active':''}}" href="{{ route('invest.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Invest Trash</a>
                @endif
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('loan-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('loan*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#loan"
            aria-expanded="true" aria-controls="loan">
            <i class="fas fa-fw fa-wallet"></i>
            <span>Loan</span>
        </a>
        <div id="loan" class="collapse {{Request::is('loan*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('loan-index'))
                <a class="collapse-item {{Request::is('loan')?'active':''}}" href="{{ route('loan.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Loan List</a>
                @endif
                @if(userHasPermission('loan-store'))
                <a class="collapse-item {{Request::is('loan/create')?'active':''}}" href="{{ route('loan.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Loan Create</a>
                @endif
                @if(userHasPermission('loan-advance'))
                <a class="collapse-item {{Request::is('loan/trash')?'active':''}}" href="{{ route('loan.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Trash List</a>
                @endif
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('expense-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('expense*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#expense"
            aria-expanded="true" aria-controls="expense">
            <i class="fas fa-fw fa-credit-card"></i>
            <span>Expense</span>
        </a>
        <div id="expense" class="collapse {{Request::is('expense*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @if(userHasPermission('expense-index'))
                <a class="collapse-item {{Request::is('expense*')?'active':''}}" href="{{ route('expense.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Expense List</a>
                @endif
                @if(userHasPermission('expense-advance'))
                <a class="collapse-item {{Request::is('expense-category')?'active':''}}" href="{{ route('expense-category.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Expense Category</a>
                <a class="collapse-item {{Request::is('expense/trash')?'active':''}}" href="{{ route('expense.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Expense Trash</a>
                <a class="collapse-item {{Request::is('expense-category/trash')?'active':''}}" href="{{ route('expense-category.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Category Trash</a>
                @endif
            </div>
        </div>
    </li>
    @endif
    @if(userHasPermission('payrole-module'))
    <li class="nav-item">
        <a class="nav-link {{Request::is('payrole*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#payrole"
            aria-expanded="true" aria-controls="payrole">
            <i class="fas fa-fw fa-wallet"></i>
            <span>Payrole</span>
        </a>
        <div id="payrole" class="collapse {{Request::is('payrole*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{Request::is('payrole/salary-particular*')?'active':''}}" href="{{ route('salary-particular.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Salary Particular</a>
                <a class="collapse-item {{Request::is('payrole/salary-assign*')?'active':''}}" href="{{ route('salary-assign.index',0) }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Salary Assign</a>
                <a class="collapse-item {{Request::is('payrole/salary-submit/create')?'active':''}}" href="{{ route('salary-submit.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Salary Submit</a>
                <a class="collapse-item {{Request::is('payrole/salary-submit')?'active':''}}" href="{{ route('salary-submit.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Monthly Salary List</a>
                <a class="collapse-item {{Request::is('payrole/advance-salary/create')?'active':''}}" href="{{ route('advance-salary.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Advance Salary</a>
                <a class="collapse-item {{Request::is('payrole/advance-salary')?'active':''}}" href="{{ route('advance-salary.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Advance Salary List</a>
                <a class="collapse-item {{Request::is('payrole/salary-payment/create')?'active':''}}" href="{{ route('salary-payment.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Salary Payment</a>
                <a class="collapse-item {{Request::is('payrole/salary-payment')?'active':''}}" href="{{ route('salary-payment.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Salary Payment List</a>
            </div>
        </div>
    </li>
    @endif
    <li class="nav-item">
        <a class="nav-link {{Request::is('report*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#report"
            aria-expanded="true" aria-controls="report">
            <i class="fas fa-fw fa-credit-card"></i>
            <span>Report</span>
        </a>
        <div id="report" class="collapse {{Request::is('report*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{Request::is('report/cashbook')?'active':''}}" href="{{ route('report.cashbook') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Cashbook</a>
                <a class="collapse-item {{Request::is('report/product')?'active':''}}" href="{{ route('report.product') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Product Report</a>
                <a class="collapse-item {{Request::is('report/date-wise-sale')?'active':''}}" href="{{ route('report.datewisesale') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> DWS Report</a>
                <a class="collapse-item {{Request::is('report/date-wise-purchase')?'active':''}}" href="{{ route('report.datewisepurchase') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> DWP Report</a>
                <a class="collapse-item {{Request::is('report/income')?'active':''}}" href="{{ route('report.income') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Income Report</a>
                <a class="collapse-item {{Request::is('report/expense')?'active':''}}" href="{{ route('report.expense') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Expense Report</a>
                <a class="collapse-item {{Request::is('report/sale')?'active':''}}" href="{{ route('report.sale') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Sale Report</a>
                <a class="collapse-item {{Request::is('report/purchase')?'active':''}}" href="{{ route('report.purchase') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Purchase Report</a>
                <a class="collapse-item {{Request::is('report/customer-ledger')?'active':''}}" href="{{ route('report.customerledger') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Customer Ledger</a>
                <a class="collapse-item {{Request::is('report/supplier-ledger')?'active':''}}" href="{{ route('report.supplierledger') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Supplier Ledger</a>
                <a class="collapse-item {{Request::is('report/bank')?'active':''}}" href="{{ route('report.bank') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Bank Report</a>
                <a class="collapse-item {{Request::is('report/account')?'active':''}}" href="{{ route('report.account') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Account Report</a>
            </div>
        </div>
    </li>
    @if(userHasPermission('setting-module') || auth()->user()->id == 1)
    <li class="nav-item">
        <a class="nav-link {{ Request::is('setting*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#setting" aria-expanded="true" aria-controls="setting">
            <i class="fas fa-fw fa-cog"></i>
            <span>Setting</span>
        </a>
        <div id="setting" class="collapse {{ Request::is('setting*') ? 'show' : '' }}" aria-labelledby="headingTwo"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ Request::is('setting/role*') ? 'active' : '' }}"
                    href="{{ route('role.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>Role</a>
                <a class="collapse-item {{ Request::is('setting/user*') ? 'active' : '' }}"
                    href="{{ route('user.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>User</a>

                <a class="collapse-item {{ Request::is('setting/brand*') ? 'active' : '' }}"
                    href="{{ route('brand.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>Brand</a>
                <a class="collapse-item {{ Request::is('setting/category*') ? 'active' : '' }}"
                    href="{{ route('category.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>Category</a>
                <a class="collapse-item {{ Request::is('setting/unit*') ? 'active' : '' }}"
                    href="{{ route('unit.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>Unit</a>
                <a class="collapse-item {{ Request::is('setting/size*') ? 'active' : '' }}"
                    href="{{ route('size.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>Size</a>

                <a class="collapse-item {{ Request::is('setting/division*') ? 'active' : '' }}"
                    href="{{ route('division.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>Division</a>
                <a class="collapse-item {{ Request::is('setting/district*') ? 'active' : '' }}"
                    href="{{ route('district.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>District</a>
                <a class="collapse-item {{ Request::is('setting/site_setting*') ? 'active' : '' }}"" href="{{ route('site_setting.index') }}"><i
                        class="fas fa-fw fa-arrow-right mr-2"></i>Setting</a>
                <a class="collapse-item {{ Request::is('setting/voucher_setting*') ? 'active' : '' }}"" href="{{ route('voucher_setting.index') }}"><i
                        class="fas fa-fw fa-arrow-right mr-2"></i>Voucher Setting</a>

            </div>
        </div>
    </li>
    @endif
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
