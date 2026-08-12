<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_assets', function (Blueprint $table) {
            $table->id();
            $table->string('symbol'); // e.g. TATAPOWER, ADANIGREEN, DOGE, VET, SOL
            $table->string('name');
            $table->enum('asset_type', ['stock_nse', 'crypto'])->default('stock_nse');
            $table->decimal('quantity', 18, 6)->default(0);
            $table->decimal('buy_price', 15, 4)->default(0);
            $table->decimal('sell_price', 15, 4)->nullable();
            $table->decimal('buy_sell_charges', 12, 2)->default(0);
            $table->decimal('investment_amount', 15, 2)->default(0);
            $table->string('api_identifier')->nullable(); // e.g. dogecoin, vechain, solana for coingecko
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_assets');
    }
};
