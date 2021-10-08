<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmbedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('embed', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scene_id')->comment('场景ID');
            $table->integer('key')->comment('排序');
            $table->integer('type')->comment('类型：1：文字，2：序列图，3：视频，4：图片');
            $table->string('ath',50)->comment('y坐标');
            $table->string('atv',50)->comment('x坐标');
            $table->string('rx',50)->default('0')->comment('上下旋转');
            $table->string('ry',50)->default('0')->comment('左右旋转');
            $table->string('rz',50)->default('0')->comment('前后旋转');
            $table->float('scale')->default(1)->comment('缩放');
            $table->text('action_data')->comment('执行动作所需参数');
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
        Schema::dropIfExists('embed');
    }
}
