<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('monthly_salary_id')->constrained('monthly_salaries')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('bank_id')->nullable()->constrained('banks')->onDelete('cascade');
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->string('voucher_no');
            $table->double('due_salary');
            $table->double('advance');
            $table->double('total_salary');
            $table->double('amount');
            $table->boolean('payment_type')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};
