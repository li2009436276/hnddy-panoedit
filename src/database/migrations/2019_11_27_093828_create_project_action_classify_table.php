<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectActionClassifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_action_classify', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('标签名称');
            $table->tinyInteger('include_status')->default(0)->comment('是否需要引入xml');
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
        Schema::dropIfExists('project_action_classify');
    }
}
