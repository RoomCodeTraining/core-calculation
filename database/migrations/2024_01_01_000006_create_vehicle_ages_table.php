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
        Schema::create('vehicle_ages', function (Blueprint $table) {
            $table->id();
            $table->integer('value');
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->timestamps();

            $table->unique('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_ages');
    }
};

