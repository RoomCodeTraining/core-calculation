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
        Schema::create('depreciation_tables', function (Blueprint $table) {
            $table->id();
            $table->decimal('value', 10, 2);
            $table->foreignId('usage_id')->constrained('genres')->onDelete('cascade');
            $table->foreignId('vehicle_age_id')->constrained('vehicle_ages')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->timestamps();

            $table->unique(['usage_id', 'vehicle_age_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depreciation_tables');
    }
};

