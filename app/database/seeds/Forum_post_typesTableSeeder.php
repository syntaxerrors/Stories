<?php

class Forum_post_typesTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('forum_post_types')->truncate();

        $forum_post_types = array(
        	array(
	        	'name' => 'Standard',
	        	'keyName' => 'standard',
	        	'description' => 'Normal Category.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        	array(
	        	'name' => 'Locked',
	        	'keyName' => 'locked',
	        	'description' => 'A post locked from replies.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        	array(
	        	'name' => 'Sticky',
	        	'keyName' => 'sticky',
	        	'description' => 'A stickied post will always appear at the top of the first page.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        	array(
	        	'name' => 'Announcement',
	        	'keyName' => 'announcement',
	        	'description' => 'An announcement post will appear at the top of every page.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        	array(
	        	'name' => 'Conversation',
	        	'keyName' => 'conversation',
	        	'description' => 'A post or reply that is mainly dialog.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        	array(
	        	'name' => 'Inner Thought',
	        	'keyName' => 'inner-thought',
	        	'description' => 'A post or reply that contains a character\'s internal dialog.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        	array(
	            'name' => 'Action',
	            'keyName' => 'action',
	            'description' => 'A post or reply that contains actions.  Will allow the user to roll dice for the action.',
	            'created_at' => date('Y-m-d H:i:s')
	        ),
        	array(
	        	'name' => 'Application',
	        	'keyName' => 'application',
	        	'description' => 'A post requesting access to a game.',
	        	'created_at' => date('Y-m-d H:i:s')
	        ),
        );

        // Uncomment the below to run the seeder
        DB::table('forum_post_types')->insert($forum_post_types);
    }

}