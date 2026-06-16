<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('vehicle_characteristics', 'vehicle_genre_usage_id')) {
            return;
        }

        $rows = DB::table('vehicle_characteristics')
            ->whereNotNull('vehicle_genre_usage_id')
            ->select('id', 'vehicle_genre_usage_id')
            ->get();

        foreach ($rows as $row) {
            $characteristic = DB::table('vehicle_characteristics')->where('id', $row->id)->first();

            DB::table('vehicle_characteristic_genre_usages')->updateOrInsert(
                [
                    'vehicle_characteristic_id' => $row->id,
                    'vehicle_genre_usage_id' => $row->vehicle_genre_usage_id,
                ],
                [
                    'status_id' => $characteristic->status_id ?? 1,
                    'created_by' => $characteristic->created_by ?? 1,
                    'updated_by' => $characteristic->updated_by ?? 1,
                ]
            );
        }

        Schema::table('vehicle_characteristics', function (Blueprint $table) {
            $table->dropForeign(['vehicle_genre_usage_id']);
            $table->dropColumn('vehicle_genre_usage_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('vehicle_characteristics', 'vehicle_genre_usage_id')) {
            return;
        }

        Schema::table('vehicle_characteristics', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicle_genre_usage_id')->index()->nullable()->after('vehicle_model_id');

            $table->foreign('vehicle_genre_usage_id')
                ->references('id')
                ->on('vehicle_genre_usages')
                ->onDelete('cascade');
        });

        $pivotRows = DB::table('vehicle_characteristic_genre_usages')
            ->select('vehicle_characteristic_id', 'vehicle_genre_usage_id')
            ->get()
            ->groupBy('vehicle_characteristic_id');

        foreach ($pivotRows as $vehicleCharacteristicId => $relations) {
            DB::table('vehicle_characteristics')
                ->where('id', $vehicleCharacteristicId)
                ->update(['vehicle_genre_usage_id' => $relations->first()->vehicle_genre_usage_id]);
        }
    }
};
