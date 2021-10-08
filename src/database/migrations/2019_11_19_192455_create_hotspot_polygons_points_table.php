<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotspotPolygonsPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotspot_polygons_points', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotspot_id')->comment('热点ID');
            $table->decimal('ath',14,8)->default(0.00000000)->comment('x轴坐标');
            $table->decimal('atv',14,8)->default(0.00000000)->comment('y轴坐标');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotspot_polygons_points');
    }
}
