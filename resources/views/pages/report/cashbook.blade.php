<x-admin title="Cashbook">
    <x-page-header head="Cashbook" />
    <div class="row">
        @php
            $start_date = request()->start_date ?? date('Y-m-d');
            $end_date = request()->end_date ?? date('Y-m-d');
        @endphp
        <div class="col-md-12">
            <div class="card card-body">
                <form action="{{ route('report.cashbook') }}" method="get">
                    <div class="row">
                        <table class="table table-responsive">
                            <tr>
                                <td style="width: 20%"></td>
                                <td><input type="date" name="start_date" required value="{{ $start_date }}" class="form-control"></td>
                                <td>To</td>
                                <td><input type="date" name="end_date" required value="{{ $end_date }}" class="form-control"></td>
                                <td><button class="btn btn-success">Submit</button></td>
                                <td><button type="button" id="print_button" class="btn btn-info"><i class="fa-solid fa-print"></i></button></td>
                                <td style="width: 20%"></td>
                            </tr>
                        </table>

                    </div>
                </form>
            </div>
        </div>
        @php
            $open_sale_cash = App\Models\Sale::join('sale_payments','sales.id','sale_payments.sale_id')->where('sale_payments.bank_id',null)->whereDate('sale_payments.created_at','<',$start_date)->sum('sale_payments.amount');
            $open_sale_bank = App\Models\Sale::join('sale_payments','sales.id','sale_payments.sale_id')->where('sale_payments.account_id',null)->whereDate('sale_payments.created_at','<',$start_date)->sum('sale_payments.amount');
            $open_income = App\Models\Income::whereDate('created_at','<',$start_date)->sum('amount');
            $open_invest_cash = App\Models\Invest::whereDate('created_at','<',$start_date)->where('bank_id',null)->sum('amount');
            $open_invest_bank = App\Models\Invest::whereDate('created_at','<',$start_date)->where('account_id',null)->sum('amount');
            $open_take_loan = App\Models\Loan::whereDate('created_at','<',$start_date)->where('loan_type',0)->sum('amount');
            $open_bank_withdraw = App\Models\BankTransection::whereDate('created_at','<',$start_date)->where('type',0)->sum('amount');
            $open_purchase_return = App\Models\PurchaseReturn::whereDate('created_at','<',$start_date)->sum('grand_total');

            $open_purchase_cash = App\Models\Purchase::join('purchase_payments','purchases.id','purchase_payments.purchase_id')->where('purchase_payments.bank_id',null)->whereDate('purchase_payments.created_at','<',$start_date)->sum('purchase_payments.amount');
            $open_purchase_bank = App\Models\Purchase::join('purchase_payments','purchases.id','purchase_payments.purchase_id')->where('purchase_payments.account_id',null)->whereDate('purchase_payments.created_at','<',$start_date)->sum('purchase_payments.amount');
            $open_expense = App\Models\Expense::whereDate('created_at','<',$start_date)->sum('amount');
            $open_payrole_cash = App\Models\SalaryPayment::whereDate('created_at','<',$start_date)->where('bank_id',null)->sum('amount');
            $open_payrole_bank = App\Models\SalaryPayment::whereDate('created_at','<',$start_date)->where('account_id',null)->sum('amount');
            $open_give_loan = App\Models\Loan::whereDate('created_at','<',$start_date)->where('loan_type',1)->sum('amount');
            $open_bank_deposit = App\Models\BankTransection::whereDate('created_at','<',$start_date)->where('type',1)->sum('amount');
            $open_sale_return = App\Models\SaleReturn::whereDate('created_at','<',$start_date)->sum('grand_total');

            $open_credit_cash = $open_sale_cash + $open_income + $open_invest_cash + $open_take_loan + $open_bank_withdraw + $open_purchase_return;
            $open_credit_bank = $open_sale_bank + $open_invest_bank + $open_bank_deposit;
            $open_debit_cash = $open_purchase_cash + $open_expense + $open_payrole_cash + $open_give_loan + $open_bank_deposit + $open_sale_return;
            $open_debit_bank = $open_purchase_bank +  $open_payrole_bank + $open_bank_withdraw ;
            $opening_balance_cash = $open_credit_cash - $open_debit_cash;
            $opening_balance_bank = $open_credit_bank - $open_debit_bank;

            $sale = App\Models\Sale::whereDate('sales.created_at','>=',$start_date)->whereDate('sales.created_at','<=',$end_date)->sum('grand_total');
            $sale_paid_cash = App\Models\Sale::join('sale_payments','sales.id','sale_payments.sale_id')->where('sale_payments.bank_id',null)->whereDate('sales.created_at','>=',$start_date)->whereDate('sales.created_at','<=',$end_date)->where('is_due',0)->sum('sale_payments.amount');
            $sale_paid_bank = App\Models\Sale::join('sale_payments','sales.id','sale_payments.sale_id')->where('sale_payments.account_id',null)->whereDate('sales.created_at','>=',$start_date)->whereDate('sales.created_at','<=',$end_date)->where('is_due',0)->sum('sale_payments.amount');

            $due_collection_cash = App\Models\Sale::join('sale_payments','sales.id','sale_payments.sale_id')->where('sale_payments.bank_id',null)->whereDate('sale_payments.created_at','>=',$start_date)->whereDate('sales.created_at','<=',$end_date)->where('is_due',1)->sum('sale_payments.amount');
            $due_collection_bank = App\Models\Sale::join('sale_payments','sales.id','sale_payments.sale_id')->where('sale_payments.account_id',null)->whereDate('sale_payments.created_at','>=',$start_date)->whereDate('sales.created_at','<=',$end_date)->where('is_due',1)->sum('sale_payments.amount');
            $income = App\Models\Income::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('amount');
            $invest_cash = App\Models\Invest::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->where('bank_id',null)->sum('amount');
            $invest_bank = App\Models\Invest::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->where('account_id',null)->sum('amount');
            $take_loan = App\Models\Loan::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->where('loan_type',0)->sum('amount');
            $bank_withdraw = App\Models\BankTransection::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->where('type',0)->sum('amount');
            $purchase_return = App\Models\PurchaseReturn::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('grand_total');

            $purchase = App\Models\Purchase::where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->sum('grand_total');
            $purchase_payment_cash = App\Models\Purchase::join('purchase_payments','purchases.id','purchase_payments.purchase_id')
                ->where('purchase_payments.bank_id',null)->whereDate('purchase_payments.created_at','>=',$start_date)
                ->whereDate('purchase_payments.created_at','<=',$end_date)->where('is_due',0)->sum('purchase_payments.amount');
            $purchase_payment_bank = App\Models\Purchase::join('purchase_payments','purchases.id','purchase_payments.purchase_id')
                ->where('purchase_payments.account_id',null)->whereDate('purchase_payments.created_at','>=',$start_date)
                ->whereDate('purchase_payments.created_at','<=',$end_date)->where('is_due',0)->sum('purchase_payments.amount');
            $due_payment_cash = App\Models\Purchase::join('purchase_payments','purchases.id','purchase_payments.purchase_id')
                ->where('purchase_payments.account_id',null)->whereDate('purchase_payments.created_at','>=',$start_date)
                ->whereDate('purchase_payments.created_at','<=',$end_date)->where('is_due',1)->sum('purchase_payments.amount');
            $due_payment_bank = App\Models\Purchase::join('purchase_payments','purchases.id','purchase_payments.purchase_id')
                ->where('purchase_payments.bank_id',null)->whereDate('purchase_payments.created_at','>=',$start_date)
                ->whereDate('purchase_payments.created_at','<=',$end_date)->where('is_due',1)->sum('purchase_payments.amount');
            $expense = App\Models\Expense::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('amount');
            $payrole_cash = App\Models\SalaryPayment::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->where('bank_id',null)->sum('amount');
            $payrole_bank = App\Models\SalaryPayment::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->where('account_id',null)->sum('amount');
            $give_loan = App\Models\Loan::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->where('loan_type',1)->sum('amount');
            $bank_deposit = App\Models\BankTransection::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->where('type',1)->sum('amount');
            $sale_return = App\Models\SaleReturn::whereDate('created_at','>=',$start_date)->whereDate('created_at','<=',$end_date)->sum('grand_total');

            $credit_cash = $sale_paid_cash + $due_collection_cash + $income + $invest_cash + $take_loan + $bank_withdraw + $purchase_return;
            $credit_bank = $sale_paid_bank + $due_collection_bank + $invest_bank + $bank_deposit;

            $debit_cash = $purchase_payment_cash + $due_payment_cash + $expense + $payrole_cash + $give_loan + $bank_deposit + $sale_return;
            $debit_bank = $purchase_payment_bank + $due_payment_bank + $payrole_bank + $bank_withdraw;

            $closing_balance_cash = $opening_balance_cash + $credit_cash - $debit_cash;
            $closing_balance_bank = $opening_balance_bank + $credit_bank - $debit_bank;
        @endphp
        <div class="col-md-12">
            <div class="card" id="print_div">
                <div class="card-header" style="background: #02462d;color:white;text-align:center">
                     <table class="m-auto">
                        <tr>
                            <td colspan="3">Opening Balance</td>
                        </tr>
                        <tr>
                            <td>Cash</td>
                            <td>|</td>
                            <td>Bank</td>
                        </tr>
                        <tr>
                            <td>{{ number_format($opening_balance_cash,2) }}</td>
                            <td>|</td>
                            <td>{{ number_format($opening_balance_bank,2) }}</td>
                        </tr>
                     </table>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th colspan="3">Credit</th>
                                <th colspan="3">Debit</th>
                            </tr>
                            <tr>
                                <th>Title</th>
                                <th class="text-center">Cash</th>
                                <th class="text-center">Bank</th>
                                <th>Title</th>
                                <th class="text-center">Cash</th>
                                <th class="text-center">Bank</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <tr>
                                <td>Sale</td>
                                <td>{{ number_format($sale,2) }}</td>
                                <td></td>
                                <td>Purchase</td>
                                <td>{{ number_format($purchase,2) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Sale Paid</td>
                                <td>{{ number_format($sale_paid_cash,2) }}</td>
                                <td>{{ number_format($sale_paid_bank,2) }}</td>
                                <td>Purchase Paid</td>
                                <td>{{ number_format($purchase_payment_cash,2) }}</td>
                                <td>{{ number_format($purchase_payment_bank,2) }}</td>
                            </tr>
                            <tr>
                                <td>Sale Due</td>
                                <td>{{ number_format($sale - ($sale_paid_cash + $sale_paid_bank),2) }}</td>
                                <td></td>
                                <td>Purchase Due</td>
                                <td>{{ number_format($purchase - ($purchase_payment_cash + $purchase_payment_bank),2) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Due Collection</td>
                                <td>{{ number_format($due_collection_cash,2) }}</td>
                                <td>{{ number_format($due_collection_bank,2) }}</td>
                                <td>Due Paid</td>
                                <td>{{ number_format($due_payment_cash,2) }}</td>
                                <td>{{ number_format($due_payment_bank,2) }}</td>
                            </tr>
                            <tr>
                                <td>Income</td>
                                <td>{{ number_format($income,2) }}</td>
                                <td></td>
                                <td>Expense</td>
                                <td>{{ number_format($expense,2) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Bank Transection</td>
                                <td>{{ number_format($bank_withdraw,2) }}</td>
                                <td>{{ number_format($bank_deposit,2) }}</td>
                                <td>Bank Transection</td>
                                <td>{{ number_format($bank_deposit,2) }}</td>
                                <td>{{ number_format($bank_withdraw,2) }}</td>
                            </tr>
                            <tr>
                                <td>Purchase Return</td>
                                <td>{{ number_format($purchase_return,2) }}</td>
                                <td></td>
                                <td>Sale Return</td>
                                <td>{{ number_format($sale_return,2) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Invest</td>
                                <td>{{ number_format($invest_cash,2) }}</td>
                                <td>{{ number_format($invest_bank,2) }}</td>
                                <td>Payrole</td>
                                <td>{{ number_format($payrole_cash,2) }}</td>
                                <td>{{ number_format($payrole_bank,2) }}</td>
                            </tr>
                            <tr>
                                <td>Take Loan</td>
                                <td>{{ number_format($take_loan,2) }}</td>
                                <td></td>
                                <td>Give Loan</td>
                                <td>{{ number_format($give_loan,2) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>{{ number_format($credit_cash,2) }}</td>
                                <td>{{ number_format($credit_bank,2) }}</td>
                                <td>Total</td>
                                <td>{{ number_format($debit_cash,2) }}</td>
                                <td>{{ number_format($debit_bank,2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer" style="background: #02462d;color:white;text-align:center">
                     <table class="m-auto">
                        <tr>
                            <td colspan="3">Closing Balance</td>
                        </tr>
                        <tr>
                            <td>Cash</td>
                            <td>|</td>
                            <td>Bank</td>
                        </tr>
                        <tr>
                            <td>{{ number_format($closing_balance_cash,2) }}</td>
                            <td>|</td>
                            <td>{{ number_format($closing_balance_bank,2) }}</td>
                        </tr>
                     </table>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
        document.getElementById('print_button').addEventListener('click', function () {
            const printContent = document.getElementById('print_div').innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload(); // Reload to restore scripts and event listeners
        });
    </script>
    @endpush
</x-admin>
