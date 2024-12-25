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
        Schema::create('voucher_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('expense')->default(0);
            $table->boolean('pos')->default(0);
            $table->boolean('purchase')->default(0);
            $table->boolean('salary')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_settings');
    }
};
