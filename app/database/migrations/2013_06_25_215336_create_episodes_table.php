<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEpisodesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('series_id')->index();
            $table->integer('game_id')->index();
            $table->integer('parentId')->index();
            $table->integer('seriesNumber');
            $table->string('title');
            $table->string('link');
            $table->date('date');
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
        Schema::drop('episodes');
    }

}
