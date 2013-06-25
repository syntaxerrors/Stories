<?php

class UsersTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('users')->delete();

        $users = array(
            array(
                'username'  => 'riddles',
                'password'  => '$2y$08$2nCHGO5NjS2C5ONAWC5OoeOx.CIOrwL0xKKHF3v794gTTszKpMTVS',
                'firstName' => 'Brandon',
                'lastName'  => 'Hyde',
                'status_id' => '1',
                'email'     => 'riddles@dev-toolbox.com'
            ),
            array(
                'username'  => 'Stygian',
                'password'  => '$2a$08$fcFULUuEsMWo35UCs71mRu54w/oGqBIJjSCwRLd1vX7Oe2bqyeBFG',
                'firstName' => 'Travis',
                'lastName'  => 'Blasingame',
                'status_id' => '1',
                'email'     => 'stygian.warlock.v2@gmail.com'
            ),
            array(
                'username'  => 'irish',
                'password'  => '$2a$08$NnBaKDC9NEXcxLSAz3v5wuLQyPe5zNxnlx6AGPKBUHiVNYU6v4eTC',
                'firstName' => 'Irish',
                'lastName'  => '',
                'status_id' => '1',
                'email'     => 'wes.murphy@yahoo.com'
            ),
        );

        // Uncomment the below to run the seeder
        DB::table('users')->insert($users);
    }

}