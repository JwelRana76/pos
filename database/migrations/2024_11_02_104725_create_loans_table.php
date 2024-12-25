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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('contact')->nullable();
            $table->double('amount');
            $table->float('interest')->comment('%')->default(0);
            $table->text('note')->nullable();
            $table->double('total_interest')->default(0);
            $table->boolean('loan_type')->comment('1=give/0=take');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
