<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true)->comment('用户ID');
            $table->string('title', 50)->nullable()->comment('用户ID');
            $table->string('path')->comment('环物路径');
            $table->string('thumb')->comment('缩略图');
            $table->integer('num', false, true)->comment('环物数量');
            $table->tinyInteger('status', false, true)->default(1)->comment('状态：1：正常，2：禁用');
            $table->softDeletes();
            $table->timestamps();
            $table->comment = '环物图';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rings');
    }
}
