<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_prices', function (Blueprint $table) {
            $table->id();
            $table->string('crop_name');
            $table->string('category');
            $table->decimal('min_price', 10, 2);
            $table->decimal('max_price', 10, 2);
            $table->decimal('modal_price', 10, 2);
            $table->string('unit')->default('quintal');
            $table->string('market_name');
            $table->string('state');
            $table->string('district');
            $table->date('price_date');
            $table->enum('trend', ['up', 'down', 'stable'])->default('stable');
            $table->timestamps();

            $table->index(['crop_name', 'price_date']);
            $table->index(['state', 'market_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_prices');
    }
};
