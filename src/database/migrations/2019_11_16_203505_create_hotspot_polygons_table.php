<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotspotPolygonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotspot_polygons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scene_id')->comment('场景ID');
            $table->integer('key')->comment('场景下唯一ID');
            $table->string('title')->default('')->comment('标题');
            $table->tinyInteger('type')->default(1)->comment('类型：1：全景切换，2：链接 ，3：图片，4：文字，5：语音热点，6：内嵌链接，11：图文热点，12：天空遮罩，13：地面遮罩');
            $table->text('action')->nullable()->comment('执行动作');
            $table->decimal('ath',14,8)->default(0.00000000)->comment('x轴坐标');
            $table->decimal('atv',14,8)->default(0.00000000)->comment('y轴坐标');
            $table->tinyInteger('icon_type')->default(1)->comment('图片类型：1：系统图片，2：用户图片');
            $table->text('action_data')->nullable()->comment('执行动作所需参数');
            $table->text('img')->nullable()->comment('图片参数');
            $table->tinyInteger('show_title')->default(1)->comment('标题是否显示：1：显示：0：不显示');
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
        Schema::dropIfExists('hotspot_polygons');
    }
}
