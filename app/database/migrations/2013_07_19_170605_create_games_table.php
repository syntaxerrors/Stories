<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('games', function(Blueprint $table) {
            $table->string('uniqueId', 10);
            $table->primary('uniqueId');
            $table->string('game_type_id', 10)->index();
            $table->string('name');
            $table->integer('keyName');
            $table->text('description')->nullable();
            $table->boolean('activeFlag')->default(1);
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
        Schema::drop('games');
	}

}