<?php

class PastesTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('pastes')->delete();

        $pastes = array(
            array(
                'user_id'   =>  '1',
                'name'      =>  'Test 1',
                'text'      =>  'Test Message',
                'fork'      =>  '',
                'private'   =>  '0',
                'clicks'    =>  '0',
                'slug'      =>  'XxFgA'
            ),
            array(
                'user_id'   =>  '1',
                'name'      =>  'Test 2',
                'text'      =>  'Test Message 2',
                'fork'      =>  '',
                'private'   =>  '0',
                'clicks'    =>  '1',
                'slug'      =>  'XxFgS'
            ),
            array(
                'user_id'   =>  '1',
                'name'      =>  'Test 3',
                'text'      =>  'Test Message 3',
                'fork'      =>  '',
                'private'   =>  '1',
                'clicks'    =>  '0',
                'slug'      =>  'XxFgD'
            ),
            array(
                'user_id'   =>  '1',
                'name'      =>  'Test 4',
                'text'      =>  'Test Message 4',
                'fork'      =>  '1',
                'private'   =>  '0',
                'clicks'    =>  '0',
                'slug'      =>  'XxFgG'
            ),
        );

        // Uncomment the below to run the seeder
        DB::table('pastes')->insert($pastes);
    }

}