<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForumBoardsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_boards', function(Blueprint $table) {
            $table->string('uniqueId', 10);
            $table->primary('uniqueId');
            $table->string('forum_category_id', 10)->index();
            $table->integer('forum_board_type_id')->index();
            $table->string('name');
            $table->string('keyName')->index();
            $table->text('description');
            $table->integer('position')->nullable()->index();
            $table->string('parent_id', 10)->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('forum_boards');
    }

}
