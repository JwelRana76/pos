<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public static $columns = [
        ['name' => 'holder_name', 'data' => 'holder_name'],
        ['name' => 'account_no', 'data' => 'account_no'],
        ['name' => 'bank_name', 'data' => 'bank_name'],
        ['name' => 'balance', 'data' => 'balance'],
        ['name' => 'default', 'data' => 'default'],
        ['name' => 'action', 'data' => 'action'],
    ];
    public function getBalanceAttribute()
    {
        $sale_payment = SalePayment::where('bank_id', $this->id)->sum('amount');
        $invest = Invest::where('bank_id', $this->id)->sum('amount');
        $purchase_payment = PurchasePayment::where('bank_id', $this->id)->sum('amount');
        $salary_payment = SalaryPayment::where('bank_id', $this->id)->sum('amount');
        $bank_deposit = BankTransection::where('bank_id', $this->id)->where('type', 1)->sum('amount');
        $bank_withdraw = BankTransection::where('bank_id', $this->id)->where('type', 0)->sum('amount');


        return $sale_payment + $bank_deposit - $bank_withdraw + $invest - $purchase_payment - $salary_payment;
    }
}
