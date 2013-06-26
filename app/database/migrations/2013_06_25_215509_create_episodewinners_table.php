<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEpisodeWinnersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episode_winners', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('episode_id')->index();
            $table->integer('winmorph_id')->index();
            $table->string('winmorph_type')->index();
            $table->string('startTimestamp')->null();
            $table->string('endTimestamp')->null();
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
        Schema::drop('episode_winners');
    }

}
