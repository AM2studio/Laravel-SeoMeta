<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeoMetasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_type')->nullable();
            $table->integer('model_id')->unsigned()->nullable();
            $table->text('route')->nullable();
            $table->text('key');
            $table->text('value');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('seo_metas');
    }
}
