<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_data', function (Blueprint $table) {

            $table->increments('id');
            $table->text('action_data')->default('')->comment('操作数据');
            $table->tinyInteger('status')->default(1)->comment('状态：1：启用，2：禁用');
            $table->softDeletes();
            $table->timestamps();

            $table->comment = '热点或按钮事件';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_data');
    }
}
