<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEntitiesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities', function(Blueprint $table) {
            $table->string('uniqueId', 10);
            $table->primary('uniqueId');
            $table->string('user_id', 10)->index();
            $table->string('game_type_id', 10)->index();
            $table->string('name');
            $table->string('color', 6)->nullable();
            $table->text('description')->nullable();
            $table->boolean('hiddenFlag')->default(0);
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
        Schema::drop('entities');
    }

}
