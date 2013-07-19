<?php

class Forum_board_typesTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('forum_board_types')->truncate();

        $forum_board_types = array(
        	array(
	        	'name' => 'Standard',
	        	'keyName' => 'standard',
	        	'description' => 'Normal Category.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        	array(
	            'name' => 'Child',
	            'keyName' => 'child',
	            'description' => 'A child board will appear inside it\'s parent instead of under it.',
	            'created_at' => date('Y-m-d H:i:s')
	        ),
        	array(
	        	'name' => 'Application',
	        	'keyName' => 'application',
	        	'description' => 'The location all game applications will go.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        );

        // Uncomment the below to run the seeder
        DB::table('forum_board_types')->insert($forum_board_types);
    }

}