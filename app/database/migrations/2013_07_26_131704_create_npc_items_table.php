<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNpcItemsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('npc_items', function(Blueprint $table) {
            $table->increments('id');
            $table->string('npcMorphId', 10)->index();
            $table->string('npcMorphType')->index();
            $table->string('game_item_id', 10)->index();
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
        Schema::drop('npc_items');
    }

}
