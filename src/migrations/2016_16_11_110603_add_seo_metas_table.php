<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeoMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_type');
            $table->integer('model_id')->unsigned();
            $table->text('key');
            $table->text('value');

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
        Schema::drop('seo_metas');
    }
}
