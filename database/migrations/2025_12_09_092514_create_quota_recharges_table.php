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
        Schema::create('quota_recharges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('Utilisateur admin qui a effectué la recharge');
            $table->integer('amount')->comment('Montant de quota ajouté');
            $table->integer('quota_before')->comment('Quota avant la recharge');
            $table->integer('quota_after')->comment('Quota après la recharge');
            $table->text('notes')->nullable()->comment('Notes optionnelles sur la recharge');
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
        Schema::dropIfExists('quota_recharges');
    }
};
