<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGameCharactersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_characters', function(Blueprint $table) {
            $table->increments('id');
            $table->string('game_id', 10)->index();
            $table->string('character_id', 10)->index();
            $table->timestamps();
            $table->unique(array('game_id', 'character_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('game_characters');
    }

}
