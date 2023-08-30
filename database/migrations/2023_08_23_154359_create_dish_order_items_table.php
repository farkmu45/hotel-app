<?php

use App\Models\Dish;
use App\Models\DishOrder;
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
        Schema::create('dish_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Dish::class)
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(DishOrder::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedSmallInteger('qty');
            $table->unsignedInteger('price_per_item');
            $table->unsignedInteger('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_order_items');
    }
};
