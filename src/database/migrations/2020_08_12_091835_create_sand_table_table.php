<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSandTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sand_table', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('project_id')->comment('项目ID');
            $table->integer('scene_id')->comment('场景ID');
            $table->string('bg_img')->comment('场景ID');
            $table->string('scene_name',50)->comment('场景名称');
            $table->string('heading',11)->nullable()->comment('角度');
            $table->string('align',10)->default('center')->comment('对齐方式');
            $table->string('x',11)->nullable()->comment('x轴');
            $table->string('y',11)->nullable()->comment('y轴');
            $table->integer('width')->nullable()->comment('bg_img图片宽');
            $table->integer('height')->nullable()->comment('bg_img图片高');
            $table->string('scene_thumb')->comment('图片路径');
            $table->integer('key')->comment('场景下唯一键');
            $table->softDeletes();

            $table->timestamps();
            $table->comment = '沙盘';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sand_table');
    }
}
