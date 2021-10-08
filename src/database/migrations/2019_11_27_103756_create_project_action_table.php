<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_action', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->comment('场景ID');
            $table->integer('action_id')->comment('action表ID');
            $table->text('value')->comment('值');
            $table->string('key',50)->comment('项目下action唯一键，相当于排序');
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
        Schema::dropIfExists('project_action');
    }
}
