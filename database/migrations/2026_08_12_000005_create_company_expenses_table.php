<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Operations'); // e.g. Operations, Tech, Salary, Marketing
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('expense_date');
            $table->string('paid_by')->nullable();
            $table->string('receipt_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_expenses');
    }
};
