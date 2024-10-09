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
                
                <a class="collapse-item {{Request::is('income*')?'active':''}}" href="{{ route('income.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Income List</a>
                
                
                <a class="collapse-item {{Request::is('income-category*')?'active':''}}" href="{{ route('income-category.index') }}"><i class="fas fa-fw fa-arrow-right mr-2"></i> Income Category</a>
                
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
