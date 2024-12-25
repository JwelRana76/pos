<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <style>
        .top-border{
            border: 1px solid #ddd;
            cursor: pointer;
        }
        .top-border:hover{
            text-decoration: none;
        }
    </style>
    <!-- Topbar Search -->
    <ul class="m-auto">
        @if(userHasPermission('customer-advance'))
        <a class="p-2 top-border mr-2" data-target="#customer_payment" data-toggle="modal"> <i class="fas fa-money-bill fa-fw mr-2 mr-2"></i>Customer Payment</a>
        @endif
        @if(userHasPermission('sale-store'))
        <a href="{{ route('sale.create') }}" class="p-2 top-border mr-2"> <i class="fas fa-scale-unbalanced fa-fw mr-2"></i>Sale</a>
        @endif
        @if(userHasPermission('purchase-store'))
        <a href="{{ route('purchase.create') }}" class="p-2 top-border mr-2"> <i class="fas fa-shopping-cart fa-fw mr-2"></i>Purchase</a>
        @endif
        @if(userHasPermission('supplier-advance'))
        <a class="p-2 top-border mr-2" data-target="#supplier_payment" data-toggle="modal"> <i class="fas fa-money-bill fa-fw mr-2"></i>Supplier Payment</a>
        @endif
    </ul>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">3+</span>
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerts Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 12, 2019</div>
                        <span class="font-weight-bold">A new monthly report is ready to
                            download!</span>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-donate text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 7, 2019</div>
                        $290.29 has been deposited into your account!
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">December 2, 2019</div>
                        Spending Alert: We've noticed unusually high spending for your account.
                    </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All
                    Alerts</a>
            </div>
        </li>

        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter">7</span>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                    Message Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="img/undraw_profile_1.svg" alt="...">
                        <div class="status-indicator bg-success"></div>
                    </div>
                    <div class="font-weight-bold">
                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                            problem I've been having.</div>
                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="img/undraw_profile_2.svg" alt="...">
                        <div class="status-indicator"></div>
                    </div>
                    <div>
                        <div class="text-truncate">I have the photos that you ordered last month, how
                            would you like them sent to you?</div>
                        <div class="small text-gray-500">Jae Chun · 1d</div>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="img/undraw_profile_3.svg" alt="...">
                        <div class="status-indicator bg-warning"></div>
                    </div>
                    <div>
                        <div class="text-truncate">Last month's report looks great, I am very happy
                            with
                            the progress so far, keep up the good work!</div>
                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                            alt="...">
                        <div class="status-indicator bg-success"></div>
                    </div>
                    <div>
                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                            told me that people say this to all dogs, even if they aren't good...</div>
                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                    </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More
                    Messages</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>


    </ul>
    @php
        $customers = App\Models\Customer::get();
        $suppliers = App\Models\Supplier::get();
        $accounts = App\Models\Account::select('name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
        $banks = App\Models\Bank::select('bank_name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
    @endphp
    <x-large-modal id="customer_payment">
        <x-form method="post" action="{{ route('customer.payment') }}">
            <div class="row">
                <div class="col-md-6">
                    <x-input id="date" value="{{ date('Y-m-d') }}" type="date" required/>
                </div>
                <div class="col-md-6">
                    <x-select id="customer" :options="$customers" />
                </div>
                <div class="col-md-6">
                    <x-select id="voucher" :options="[]" />
                </div>
                <div class="col-md-6">
                    <x-input id="payable" readonly required/>
                </div>
                <div class="col-md-6">
                    <x-input id="paid"  required/>
                </div>
                <div class="col-md-6">
                    <x-input id="change" readonly required/>
                </div>
                <div class="col-md-6">
                    <label for="payment_method">Payment Method</label>
                    <select required name="payment_method" id="payment_method" class="form-control selectpicker" title="select payment method">
                        <option value="0">Cash</option>
                        <option value="1">Bank</option>
                    </select>
                </div>
                <div class="col-md-6 d-none" id="account_part">
                    <x-select id="account" selectedId="1" :options="accounts()" />
                </div>
                <div class="col-md-6 d-none" id="bank_part">
                    <x-select id="bank" key="bank_name" :options="banks()" />
                </div>
                <div class="col-md-12">
                    <x-text-area id='note' name="note" />
                </div>
                <x-button value="Save" />
            </div>
        </x-form>
    </x-large-modal>
    <x-large-modal id="supplier_payment">
        <x-form method="post" action="{{ route('supplier.payment') }}">
            <div class="row">
                <div class="col-md-6">
                    <x-input id="date" value="{{ date('Y-m-d') }}" type="date" required/>
                </div>
                <div class="col-md-6">
                    <x-select id="supplier" :options="$suppliers" />
                </div>
                <div class="col-md-6">
                    <x-select id="voucher" :options="[]" />
                </div>
                <div class="col-md-6">
                    <x-input id="payable" readonly required/>
                </div>
                <div class="col-md-6">
                    <x-input id="paid"  required/>
                </div>
                <div class="col-md-6">
                    <x-input id="change" readonly required/>
                </div>
                <div class="col-md-6">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" required id="payment_method" class="form-control selectpicker" title="select payment method">
                        <option value="0">Cash</option>
                        <option value="1">Bank</option>
                    </select>
                </div>
                <div class="col-md-6 d-none" id="account_part">
                    <x-select id="account" selectedId="1" :options="accounts()" />
                </div>
                <div class="col-md-6 d-none" id="bank_part">
                    <x-select id="bank" key="bank_name" :options="banks()" />
                </div>
                <div class="col-md-12">
                    <x-text-area id='note' name="note" />
                </div>
                <x-button value="Save" />
            </div>
        </x-form>
    </x-large-modal>
    @push('js')
        <script>
        $(document).ready(function () {
            $('#customer_payment #payment_method').on('change', function () {
                if($(this).val() == 0){
                    $('#customer_payment #account_part').removeClass('d-none');
                    $('#customer_payment #bank_part').addClass('d-none');
                    $('#customer_payment #account').attr('required', 'true');
                    $('#customer_payment #bank').removeAttr('required');
                } else {
                    $('#customer_payment #bank_part').removeClass('d-none');
                    $('#customer_payment #account_part').addClass('d-none');
                    $('#customer_payment #account').removeAttr('required');
                    $('#customer_payment #bank').attr('required', 'true');
                }
            });
            $('#customer_payment #customer').change(function () { 
                customerChange($(this).val());
            });
            function customerChange(customer){
                $.get("/customer/sale/references/"+customer,
                    function (data) {
                        $('#customer_payment #payable').val(data.due.toFixed(2));
                        if(data.sales){
                            $('#customer_payment #voucher').empty();
                            data.sales.forEach(function(category) {
                                $('#customer_payment #voucher').append(
                                    $('<option>', {
                                        value: category.id,
                                        text: category.voucher_no
                                    })
                                );
                            });
                            $('#customer_payment #voucher').selectpicker('refresh');
                        }
                    }
                );
            }
            $('#customer_payment #voucher').change(function () { 
                $.get("/customer/sale/due/"+$(this).val(),
                    function (data) {
                        $('#customer_payment #payable').val(parseFloat(data).toFixed(2));
                    }
                );
            });
            $('#customer_payment #paid').on('input',function () { 
                var payable = parseFloat($('#customer_payment #payable').val());
                var paid = parseFloat($(this).val());
                if(paid > payable){
                    alert(`You can not pay more than ${payable}`);
                    $(this).val(payable);
                    $('#customer_payment #change').val(0);
                }else{
                    $('#customer_payment #change').val(payable - paid);
                }
            });

            // supplier payment js section
            $('#supplier_payment #payment_method').on('change', function () {
                if($(this).val() == 0){
                    $('#supplier_payment #account_part').removeClass('d-none');
                    $('#supplier_payment #bank_part').addClass('d-none');
                    $('#supplier_payment #account').attr('required', 'true');
                    $('#supplier_payment #bank').removeAttr('required');
                } else {
                    $('#supplier_payment #bank_part').removeClass('d-none');
                    $('#supplier_payment #account_part').addClass('d-none');
                    $('#supplier_payment #account').removeAttr('required');
                    $('#supplier_payment #bank').attr('required', 'true');
                }
            });
            $('#supplier_payment #supplier').change(function () { 
                supplierChange($(this).val());
            });
            function supplierChange(supplier){
                $.get("/supplier/purchase/references/"+supplier,
                    function (data) {
                        $('#supplier_payment #payable').val(data.due.toFixed(2));
                        if(data.purchases){
                            $('#supplier_payment #voucher').empty();
                            data.purchases.forEach(function(category) {
                                $('#supplier_payment #voucher').append(
                                    $('<option>', {
                                        value: category.id,
                                        text: category.voucher_no
                                    })
                                );
                            });
                            $('#supplier_payment #voucher').selectpicker('refresh');
                        }
                    }
                );
            }
            $('#supplier_payment #voucher').change(function () { 
                $.get("/supplier/purchase/due/"+$(this).val(),
                    function (data) {
                        $('#supplier_payment #payable').val(parseFloat(data).toFixed(2));
                    }
                );
            });
            const accounts = @json($accounts);
            const banks = @json($banks);
            $('#supplier_payment #paid').on('input',function () { 
                var payable = parseFloat($('#supplier_payment #payable').val());
                var payment_method = $('#supplier_payment #payment_method').val();
                var paid = parseFloat($(this).val());
                if (payment_method) {
                    var bank = $('#supplier_payment #bank').val();
                    var account = $('#supplier_payment #account').val();
                    if(payment_method == 0 && account){
                        var filteredBank = accounts.find(item => item.id == account);
                        var balance = parseFloat(filteredBank.balance);
                        if(parseFloat($(this).val()) > balance){
                            alert(`You Account Balance ${balance}`);
                            $(this).val(balance);
                        }
                    }else{
                        var filteredBank = banks.find(item => item.id == bank);
                        var balance = parseFloat(filteredBank.balance);
                        if(parseFloat($(this).val()) > balance){
                            alert(`You Bank Balance ${balance}`);
                            $(this).val(balance);
                        }
                    }
                    if(paid > payable){
                        alert(`You can not pay more than ${payable}`);
                        $(this).val(payable);
                        $('#supplier_payment #change').val(0);
                    }else{
                        $('#supplier_payment #change').val(payable - paid);
                    }
                }else{
                    alert('Select Payment Method First');
                    $(this).val(null);
                }
            });
        });
    </script>
    @endpush

</nav>
