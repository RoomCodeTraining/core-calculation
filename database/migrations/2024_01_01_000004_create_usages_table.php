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
        Schema::create('usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('code')->nullable()->unique();
            $table->decimal('max_mileage_essence_per_year', 10, 2)->nullable();
            $table->decimal('max_mileage_diesel_per_year', 10, 2)->nullable();
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->timestamps();

            // Unique constraint for genre_id and slug when both are not null
            // Note: This is handled at application level as Laravel doesn't support partial unique indexes directly
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usages');
    }
};





