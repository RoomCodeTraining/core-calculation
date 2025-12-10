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
        Schema::create('quota_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('endpoint')->comment('Endpoint API appelé');
            $table->string('method')->default('GET')->comment('Méthode HTTP utilisée');
            $table->integer('quota_used')->default(1)->comment('Quota utilisé pour cet appel');
            $table->integer('quota_remaining_after')->comment('Quota restant après cet appel');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('organization_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quota_usages');
    }
};
