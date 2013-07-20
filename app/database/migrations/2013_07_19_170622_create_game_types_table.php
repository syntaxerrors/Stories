<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGameTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('game_types', function(Blueprint $table) {
            $table->string('uniqueId', 10);
            $table->primary('uniqueId');
            $table->string('name');
            $table->integer('keyName');
            $table->text('description')->nullable();
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
        Schema::drop('game_types');
	}

}