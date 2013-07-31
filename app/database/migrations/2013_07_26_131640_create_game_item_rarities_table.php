<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGameItemRaritiesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_item_rarities', function(Blueprint $table) {
            $table->increments('id');
            $table->string('game_type_id', 10)->index();
            $table->string('name');
            $table->string('color', 6)->nullable();
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
        Schema::drop('game_item_rarities');
    }

}
