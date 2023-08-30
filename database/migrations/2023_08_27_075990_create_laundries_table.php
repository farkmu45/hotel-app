<?php

use App\Models\Customer;
use App\Models\LaundryType;
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
        Schema::create('laundries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 7);
            $table->foreignIdFor(Customer::class)
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(Room::class)
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(LaundryType::class)
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedSmallInteger('weight');
            $table->unsignedInteger('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laundries');
    }
};
