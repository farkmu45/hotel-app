<?php

use App\Models\Customer;
use App\Models\Room;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 7);
            $table->foreignIdFor(Customer::class)->constrained()
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignIdFor(Room::class)->constrained()
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
