<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRichTextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rich_text', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid',100)->comment('前端uuid');
            $table->longText('content')->nullable()->comment('内容');
            $table->softDeletes();
            $table->timestamps();
            $table->comment = '富文本热点';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rich_text');
    }
}
