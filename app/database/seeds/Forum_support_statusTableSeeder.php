<?php

class Forum_support_statusTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('forum_support_status')->truncate();

        $forum_support_status = array(
        	array(
	        	'name' => 'Open',
	        	'description' => 'A new support request or one that has not been started.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        	array(
	        	'name' => 'In Progress',
	        	'description' => 'A support issue that is currently being worked on.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        	array(
	        	'name' => 'Resolved',
	        	'description' => 'A support issue that has been marked as fixed by the dev or the user.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        	array(
	        	'name' => 'Wont fix',
	        	'description' => 'A support issue that is not a bug or simply will not be addressed.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        );

        // Uncomment the below to run the seeder
        DB::table('forum_support_status')->insert($forum_support_status);
    }

}