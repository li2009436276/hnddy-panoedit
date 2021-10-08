<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSceneDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scene_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scene_id')->comment('全景ID');
            $table->string('title')->nullable()->comment('标题');
            $table->string('url')->nullable()->comment('封面');
            $table->string('ath',50)->nullable()->comment('水平视角');
            $table->string('atv',50)->nullable()->comment('垂直视角');
            $table->string('fov',50)->nullable()->comment('视角远近距离');
            $table->integer('key')->default(1)->comment('排序');
            $table->softDeletes();
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
        Schema::dropIfExists('scene_details');
    }
}
