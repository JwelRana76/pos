<?php

namespace App\Models;

use Illuminate\Cache\Events\RetrievingKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nette\Schema\Expect;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'name', 'data' => 'name'],
        ['name' => 'account_no', 'data' => 'account_no'],
        ['name' => 'balance', 'data' => 'balance'],
        ['name' => 'default', 'data' => 'default'],
        ['name' => 'action', 'data' => 'action'],
    ];

    public function getBalanceAttribute()
    {
        $sale_payment = SalePayment::where('account_id', $this->id)->sum('amount');
        $invest = Invest::where('account_id', $this->id)->sum('amount');
        $purchase_payment = PurchasePayment::where('account_id', $this->id)->sum('amount');
        $salary_payment = SalaryPayment::where('account_id', $this->id)->sum('amount');
        $sale_return = SaleReturn::where('account_id', $this->id)->sum('grand_total');
        $purchase_return = PurchaseReturn::where('account_id', $this->id)->sum('grand_total');
        $income = Income::where('account_id', $this->id)->sum('amount');
        $expense = Expense::where('account_id', $this->id)->sum('amount');
        $take_loan = Loan::where('account_id', $this->id)->where('loan_type', 0)->sum('amount');
        $give_loan = Loan::where('account_id', $this->id)->where('loan_type', 1)->sum('amount');
        $bank_deposit = BankTransection::where('account_id', $this->id)->where('type', 1)->sum('amount');
        $bank_withdraw = BankTransection::where('account_id', $this->id)->where('type', 0)->sum('amount');
        return $sale_payment - $bank_deposit + $bank_withdraw + $take_loan - $give_loan + $income - $expense + $purchase_return + $invest - $purchase_payment - $salary_payment - $sale_return;
        // return $salary_payment;
    }
}
