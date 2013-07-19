<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('chats', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('chat_room_id')->index();
            $table->integer('user_id')->index();
            $table->integer('character_id')->index()->nullable();
            $table->text('message');
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
        Schema::drop('chats');
	}

}