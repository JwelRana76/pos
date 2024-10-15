<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ setting()->name_short }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.html">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link {{Request::is('product*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#product"
            aria-expanded="true" aria-controls="product">
            <i class="fa-brands fa-wpforms"></i>
            <span>Product</span>
        </a>
        <div id="product" class="collapse {{Request::is('product*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{Request::is('product')?'active':''}}" href="{{ route('product.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Product List</a>
                <a class="collapse-item {{Request::is('product/create')?'active':''}}" href="{{ route('product.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Add Product</a>
                <a class="collapse-item {{Request::is('product/trash')?'active':''}}" href="{{ route('product.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Trash List</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Request::is('purchase*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#purchase"
            aria-expanded="true" aria-controls="purchase">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Purchase</span>
        </a>
        <div id="purchase" class="collapse {{Request::is('purchase*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{Request::is('purchase')?'active':''}}" href="{{ route('purchase.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Purchase List</a>
                <a class="collapse-item {{Request::is('purchase/create')?'active':''}}" href="{{ route('purchase.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Add Purchase</a>
                <a class="collapse-item {{Request::is('purchase/trash')?'active':''}}" href="{{ route('purchase.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Trash List</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Request::is('customer*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#customer"
            aria-expanded="true" aria-controls="customer">
            <i class="fas fa-fw fa-people-group"></i>
            <span>Customer</span>
        </a>
        <div id="customer" class="collapse {{Request::is('customer*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{Request::is('customer')?'active':''}}" href="{{ route('customer.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Customer List</a>
                <a class="collapse-item {{Request::is('customer/create')?'active':''}}" href="{{ route('customer.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Add Customer</a>
                <a class="collapse-item {{Request::is('customer/trash')?'active':''}}" href="{{ route('customer.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Trash List</a>
                
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Request::is('supplier*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#supplier"
            aria-expanded="true" aria-controls="supplier">
            <i class="fas fa-fw fa-users"></i>
            <span>Supplier</span>
        </a>
        <div id="supplier" class="collapse {{Request::is('supplier*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{Request::is('supplier')?'active':''}}" href="{{ route('supplier.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Supplier List</a>
                <a class="collapse-item {{Request::is('supplier/create')?'active':''}}" href="{{ route('supplier.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Add Supplier</a>
                <a class="collapse-item {{Request::is('supplier/trash')?'active':''}}" href="{{ route('supplier.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Trash List</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Request::is('employee*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#employee"
            aria-expanded="true" aria-controls="employee">
            <i class="fas fa-fw fa-people-line"></i>
            <span>Employee</span>
        </a>
        <div id="employee" class="collapse {{Request::is('employee*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{Request::is('employee')?'active':''}}" href="{{ route('employee.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Employee List</a>
                <a class="collapse-item {{Request::is('employee/create')?'active':''}}" href="{{ route('employee.create') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Add Employee</a>
                <a class="collapse-item {{Request::is('employee/trash')?'active':''}}" href="{{ route('employee.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Trash List</a>
                
            </div>
        </div>
    </li>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link {{ Request::is('accounting*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#accounting" aria-expanded="true" aria-controls="accounting">
            <i class="fas fa-fw fa-cog"></i>
            <span>Accounting</span>
        </a>
        <div id="accounting" class="collapse {{ Request::is('accounting*') ? 'show' : '' }}" aria-labelledby="headingTwo"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ Request::is('accounting/account*') ? 'active' : '' }}"
                    href="{{ route('account.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>Account</a>
                <a class="collapse-item {{ Request::is('accounting/bank*') ? 'active' : '' }}"
                    href="{{ route('bank.index') }}"> <i class="fas fa-fw fa-arrow-right mr-2"></i>Bank</a>

            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Request::is('income*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#income"
            aria-expanded="true" aria-controls="income">
            <i class="fas fa-fw fa-money-bill-wheat"></i>
            <span>Income</span>
        </a>
        <div id="income" class="collapse {{Request::is('income*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                
                <a class="collapse-item {{Request::is('income')?'active':''}}" href="{{ route('income.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Income List</a>
                <a class="collapse-item {{Request::is('income-category')?'active':''}}" href="{{ route('income-category.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Income Category</a>
                <a class="collapse-item {{Request::is('income/trash')?'active':''}}" href="{{ route('income.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Income Trash</a>
                <a class="collapse-item {{Request::is('income-category/trash')?'active':''}}" href="{{ route('income-category.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Category Trash</a>
                
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link {{Request::is('expense*')?'':'collapsed'}}" href="#" data-toggle="collapse" data-target="#expense"
            aria-expanded="true" aria-controls="expense">
            <i class="fas fa-fw fa-credit-card"></i>
            <span>Expense</span>
        </a>
        <div id="expense" class="collapse {{Request::is('expense*')?'show':''}}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                
                <a class="collapse-item {{Request::is('expense*')?'active':''}}" href="{{ route('expense.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Expense List</a>
                <a class="collapse-item {{Request::is('expense-category')?'active':''}}" href="{{ route('expense-category.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Expense Category</a>
                <a class="collapse-item {{Request::is('expense/trash')?'active':''}}" href="{{ route('expense.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Expense Trash</a>
                <a class="collapse-item {{Request::is('expense-category/trash')?'active':''}}" href="{{ route('expense-category.trash') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Category Trash</a>
                
            </div>
        </div>
    </li>
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
                <a class="collapse-item" href="{{ route('site_setting.index') }}"><i
                        class="fas fa-fw fa-arrow-right mr-2"></i>Setting</a>

            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
