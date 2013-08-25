<?php

class Forum_CategoryController extends BaseController {


    public function getView($categorySlug)
    {
        if ($this->hasPermission('FORUM_ADMIN')) {
            $this->addSubMenu('Add Board','forum/board/add/'. $categorySlug);
        }
        // Get the categories
        $category = Forum_Category::with(array('type', 'boards'))->where('uniqueId', '=', $categorySlug)->first();

        // Set the template
        $this->setViewData('category', $category);
    }

	public function getAdd($gameId = null)
	{
        // Make sure they can access this whole area
        $this->checkPermission('FORUM_ADMIN');

        // Get the information
        $categories = $this->arrayToSelect(Forum_Category::orderBy('position', 'asc')->get(), 'position', 'name', 'Place After...');
        $types      = $this->arrayToSelect(Forum_Category_Type::orderByNameAsc()->get(), 'id', 'name', 'Select Category Type');

        // Set the template
        $this->setViewData('categories', $categories);
        $this->setViewData('types', $types);

        // Handle games
        if ($this->gameMode) {
            $game = null;
            if ($gameId != null) {
                $game   = Game::find($gameId);
            }
            $games      = $this->arrayToSelect(Game::orderByNameAsc()->get(), 'id', 'name', 'Select a game');

            $this->setViewData('games', $games);
            $this->setViewData('game', $game);
        }
    }

    public function postAdd()
    {
        // Handle any form data
        $input = Input::all();

        if ($input != null) {
            // Get the new position
            if (isset($input['position']) && $input['position'] != 0) {
                $position = $input['position'] + 1;
                // Set all others properly
                $moveCategories = Forum_Category::where('position', '>=', $position)->get();
                if ($moveCategories != null) {
                    foreach ($moveCategories as $category) {
                        $category->moveDown();
                    }
                }
            } elseif ($input['position'] == 0) {
                $firstCategory = Forum_Category::orderBy('position', 'desc')->first();
                if ($firstCategory != null) {
                    $position = $firstCategory->position + 1;
                } else {
                    $position = 1;
                }
            } else {
                $position = 1;
            }

            $category                         = new Forum_Category;
            $category->name                   = $input['name'];
            $category->game_id                = (isset($input['game_id']) && $input['game_id'] != 0 ? $input['game_id'] : null);
            $category->forum_category_type_id = (isset($input['forum_category_type_id']) && $input['forum_category_type_id'] != 0 ? $input['forum_category_type_id'] : null);
            $category->keyName                = Str::slug($input['name']);
            $category->description            = $input['description'];
            $category->position               = $position;

            $this->checkErrorsSave($category);

            return $this->redirect(null, $category->name.' has been submitted.');
        }
	}
}