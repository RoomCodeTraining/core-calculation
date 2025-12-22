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
        Schema::create('vehicle_characteristics', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('type')->nullable()->comment('Type de véhicule');
            $table->text('options')->nullable()->comment('Options du véhicule');
            $table->integer('fiscal_power')->nullable()->comment('Puissance fiscale du véhicule');
            $table->integer('nb_seats')->nullable()->comment('Nombre de places');
            $table->decimal('new_market_value', 18, 2)->nullable()->comment('Valeur neuve du véhicule');
            $table->unsignedBigInteger('vehicle_model_id')->index()->nullable()->comment('ID du modèle de véhicule');
            $table->unsignedBigInteger('vehicle_genre_usage_id')->index()->nullable()->comment('ID de l\'usage du genre de véhicule');
            $table->unsignedBigInteger('vehicle_energy_id')->index()->nullable()->comment('ID de l\'énergie');
            $table->unsignedBigInteger('dealer_id')->index()->nullable()->comment('ID du concessionnaire');
            $table->unsignedBigInteger('status_id')->index()->nullable()->comment('ID du statut');
            $table->unsignedBigInteger('created_by')->index()->nullable()->comment('ID de l\'utilisateur qui a créé le véhicule');
            $table->timestamp('created_at')->nullable()->comment('Date de création');
            $table->unsignedBigInteger('updated_by')->index()->nullable()->comment('ID de l\'utilisateur qui a mis à jour le véhicule');
            $table->timestamp('updated_at')->nullable()->comment('Date de mise à jour');
            $table->unsignedBigInteger('deleted_by')->index()->nullable()->comment('ID de l\'utilisateur qui a supprimé le véhicule');
            $table->timestamp('deleted_at')->nullable()->comment('Date de suppression');

            $table->foreign('vehicle_model_id')
                ->references('id')
                ->on('vehicle_models')
                ->onDelete('cascade');

            $table->foreign('vehicle_genre_usage_id')
                ->references('id')
                ->on('vehicle_genre_usages')
                ->onDelete('cascade');

            $table->foreign('vehicle_energy_id')
                ->references('id')
                ->on('vehicle_energies')
                ->onDelete('cascade');

            $table->foreign('dealer_id')
                ->references('id')
                ->on('dealers')
                ->onDelete('cascade');

            $table->foreign('status_id')
                ->references('id')
                ->on('statuses')
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
        Schema::dropIfExists('vehicle_characteristics');
    }
};
