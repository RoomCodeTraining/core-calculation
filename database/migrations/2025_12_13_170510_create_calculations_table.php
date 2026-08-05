<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('calculations', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();

            $table->string('reference')->unique();

            $table->string('license_plate')->nullable();
            $table->decimal('mileage', 18, 2)->nullable();
            $table->string('serial_number')->nullable();
            $table->date('first_entry_into_circulation_date')->nullable();
            $table->date('calculation_date')->nullable();
            $table->string('insured')->nullable();
            $table->json('evaluation')->nullable();

            $table->unsignedBigInteger('vehicle_characteristic_id')->index()->nullable();
            $table->unsignedBigInteger('entity_id')->index()->nullable();
            $table->unsignedBigInteger('status_id')->index()->nullable()->comment('ID du statut');
            $table->unsignedBigInteger('created_by')->index()->nullable()->comment('ID de l\'utilisateur qui a créé le véhicule');
            $table->timestamp('created_at')->nullable()->comment('Date de création');
            $table->unsignedBigInteger('updated_by')->index()->nullable()->comment('ID de l\'utilisateur qui a mis à jour le véhicule');
            $table->timestamp('updated_at')->nullable()->comment('Date de mise à jour');
            $table->unsignedBigInteger('deleted_by')->index()->nullable()->comment('ID de l\'utilisateur qui a supprimé le véhicule');
            $table->timestamp('deleted_at')->nullable()->comment('Date de suppression');

            $table->foreign('vehicle_characteristic_id')
                ->references('id')
                ->on('vehicle_characteristics')
                ->onDelete('cascade');

            $table->foreign('status_id')
                ->references('id')
                ->on('statuses')
                ->onDelete('cascade');

            $table->foreign('entity_id')
                ->references('id')
                ->on('entities')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('deleted_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('calculations');
    }
};
