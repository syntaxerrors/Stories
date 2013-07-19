<?php

class Forum_category_typesTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('forum_category_types')->truncate();

        $forum_category_types = array(
        	array(
	        	'name' => 'Standard',
	        	'keyName' => 'standard',
	        	'description' => 'Normal Category.',
	        	'created_at' => date('Y-m-d H:i:s')
        	),
        	array(
	            'name' => 'Game',
	            'keyName' => 'game',
	            'description' => 'A category whose boards will be used for forum gaming.',
	            'created_at' => date('Y-m-d H:i:s')
        	),
        	array(
	        	'name' => 'Technical Support',
	        	'keyName' => 'technical-support',
	        	'description' => 'A category designed for issues and feature requests.',
        		'created_at' => date('Y-m-d H:i:s')
        	),
        );

        // Uncomment the below to run the seeder
        DB::table('forum_category_types')->insert($forum_category_types);
    }

}