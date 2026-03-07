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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('reference')->unique()->index()->nullable();
            $table->integer('quantity')->nullable();
            $table->unsignedBigInteger('entity_id')->index()->nullable();
            $table->unsignedBigInteger('validated_by')->index()->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->unsignedBigInteger('cancelled_by')->index()->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable()->comment('Motif d\'annulation');
            $table->unsignedBigInteger('rejected_by')->index()->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable()->comment('Motif de rejet');
            $table->unsignedBigInteger('status_id')->index()->nullable();
            $table->unsignedBigInteger('created_by')->index()->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('updated_by')->index()->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->index()->nullable();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('entity_id')
                ->references('id')
                ->on('entities')
                ->onDelete('cascade');

            $table->foreign('validated_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('cancelled_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('rejected_by')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('orders');
    }
};
