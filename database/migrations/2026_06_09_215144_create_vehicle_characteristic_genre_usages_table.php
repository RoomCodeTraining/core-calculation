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
        Schema::create('vehicle_characteristic_genre_usages', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            
            $table->unsignedBigInteger('vehicle_characteristic_id')->nullable();
            $table->unsignedBigInteger('vehicle_genre_usage_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->index('vehicle_characteristic_id', 'vc_vgu_vc_id_idx');
            $table->index('vehicle_genre_usage_id', 'vc_vgu_vgu_id_idx');
            $table->index('status_id', 'vc_vgu_status_id_idx');
            $table->index('created_by', 'vc_vgu_created_by_idx');
            $table->index('updated_by', 'vc_vgu_updated_by_idx');
            $table->index('deleted_by', 'vc_vgu_deleted_by_idx');
            $table->timestamp('deleted_at')->nullable();

            $table->unique(['vehicle_characteristic_id', 'vehicle_genre_usage_id'], 'vc_vgu_unique');

            $table->foreign('vehicle_characteristic_id', 'vc_vgu_vehicle_characteristic_fk')
                ->references('id')
                ->on('vehicle_characteristics')
                ->onDelete('cascade');

            $table->foreign('vehicle_genre_usage_id', 'vc_vgu_vehicle_genre_usage_fk')
                ->references('id')
                ->on('vehicle_genre_usages')
                ->onDelete('cascade');

            $table->foreign('status_id', 'vc_vgu_status_id_fk')
                ->references('id')
                ->on('statuses')
                ->onDelete('cascade');

            $table->foreign('created_by', 'vc_vgu_created_by_fk')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('updated_by', 'vc_vgu_updated_by_fk')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('deleted_by', 'vc_vgu_deleted_by_fk')
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
        Schema::dropIfExists('vehicle_characteristic_genre_usages');
    }
};
