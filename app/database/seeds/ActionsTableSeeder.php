<?php

class ActionsTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('actions')->truncate();

        $actions = array(
            array(
                'name' => 'Game Board Access',
                'keyName' => 'GAME_BOARD',
                'description' => 'Ability to access the game boards for interactive games'
            ),
            array(
                'name' => 'Anima Gm Access',
                'keyName' => 'ANIMA_GM',
                'description' => 'Ability to access the Anima: GM specific areas.'
            ),
            array(
                'name' => 'Firefly Gm Access',
                'keyName' => 'FIREFLY_GM',
                'description' => 'Ability to access the Firefly: GM specific areas.'
            ),
            array(
                'name' => 'Create Chat Rooms',
                'keyName' => 'CHAT_CREATE',
                'description' =>'Grants the ability to create new chat rooms.'
            ),
            // 5
            array(
                'name' => 'Forum Access',
                'keyName' => 'FORUM_VIEW',
                'description' => 'Ability to view the forums.'
            ),
            array(
                'name' => 'Forum Administration',
                'keyName' => 'FORUM_ADMIN',
                'description' => 'Ability to access the admin panel in the forums.'
            ),
            array(
                'name' => 'Forum Moderation',
                'keyName' => 'FORUM_MOD',
                'description' => 'Ability to access the moderator panel.'
            ),
            array(
                'name' => 'Forum Post',
                'keyName' => 'FORUM_POST',
                'description' => 'Ability to post in the forums.'
            ),
            array(
                'name' => 'Game Template Manage',
                'keyName' => 'GAME_TEMPLATE_MANAGE',
                'description' => 'Ability to edit game wide templates.'
            ),
            array(
                'name' => 'Promote to front page',
                'keyName' => 'PROMOTE_FRONT_PAGE',
                'description' => 'Grants the ability to promote a forum post to the front page.'
            ),
            // 11
            array(
                'name' => 'Promote to front page: Games',
                'keyName' => 'PROMOTE_FRONT_PAGE_GAME',
                'description' => 'Grants the ability to promote a gaming forum post to the front page.'
            ),
            array(
                'name' => 'Game Master Board',
                'keyName' => 'GAME_MASTER_BOARD',
                'description' => 'Grants the ability to access the game master only board.'
            )
        );

        // Uncomment the below to run the seeder
        DB::table('actions')->insert($actions);
    }

}