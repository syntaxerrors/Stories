<?php

class Forum_BoardController extends BaseController {

    public function getView($boardSlug)
    {
        // Get the information
        $board              = Forum_Board::where('keyName', '=', $boardSlug)->first();

        if ($board->forum_board_type_id == Forum_Board::TYPE_GM && !$this->hasPermission('GAME_MASTER')) {
            $this->redirect('/', 'You must be a game master to access this board.', true);
        }

        $openIssues         = Forum_Post_Status::where('forum_support_status_id', '=', Forum_Support_Status::TYPE_OPEN)->count();
        $inProgressIssues   = Forum_Post_Status::where('forum_support_status_id', '=', Forum_Support_Status::TYPE_IN_PROGRESS)->count();
        $resolvedIssues     = Forum_Post_Status::where('forum_support_status_id', '=', Forum_Support_Status::TYPE_RESOLVED)->count();
        $announcements      = Forum_Post::with('author')->where('forum_board_id', '=', $board->id)->where('forum_post_type_id', '=', 5)->orderBy('modified_at', 'desc')->get();
        $posts              = Forum_Post::with('author')->where('forum_board_id', '=', $board->id)->whereNotIn('forum_post_type_id', array(5))->orderBy('modified_at', 'desc')->paginate(30);

        // Add quick links
        if ($board->forum_board_type_id == Forum_Board::TYPE_APPLICATION) {
            $this->addSubMenu('Add Character','character/add/'. $boardSlug);
        } else {
            $this->addSubMenu('Add Post','forum/post/add/'. $boardSlug);
        }

        // Set the template
        $this->setViewData('announcements', $announcements);
        $this->setViewData('posts', $posts);
        $this->setViewData('board', $board);
        $this->setViewData('openIssues', $openIssues);
        $this->setViewData('inProgressIssues', $inProgressIssues);
        $this->setViewData('resolvedIssues', $resolvedIssues);
    }

	public function getAdd($categorySlug = null)
	{
        // Make sure they can access this whole area
        if (!$this->hasPermission('FORUM_ADMIN')) {
            $this->redirect('/', 'You require the FORUM_ADMIN permission to view this area.', true);
        }

        // Get the information
        $category = null;
        if ($categorySlug != null) {
            $category   = Forum_Category::where('keyName', '=', $categorySlug)->first();
        }
        $boards      = $this->arrayToSelect(Forum_Board::orderBy('name', 'asc')->get(), 'id', 'name', 'Select a parent board');
        $categories = $this->arrayToSelect(Forum_Category::orderBy('position', 'asc')->get(), 'id', 'name', 'Select Category');
        $types      = $this->arrayToSelect(Forum_Board_Type::orderBy('name', 'asc')->get(), 'id', 'name', 'Select Board Type');

        // Set the template
        $this->setViewData('boards', $boards);
        $this->setViewData('category', $category);
        $this->setViewData('categories', $categories);
        $this->setViewData('types', $types);

    }

    public function postAdd()
    {
        // Handle any form data
        $input = Input::all();

        if ($input != null) {
            $board                      = new Forum_Board;
            $board->name                = $input['name'];
            $board->forum_category_id   = (isset($input['forum_category_id']) && $input['forum_category_id'] != 0 ? $input['forum_category_id'] : null);
            $board->forum_board_type_id = (isset($input['forum_board_type_id']) && $input['forum_board_type_id'] != 0 ? $input['forum_board_type_id'] : null);
            $board->parent_id           = (isset($input['parent_id']) && $input['parent_id'] != 0 ? $input['parent_id'] : null);
            $board->keyName             = Str::slug($input['name']);
            $board->description         = $input['description'];

            $board->save();

            if (count($board->getErrors()->all()) > 0){
                return Redirect::to(Request::path())->with('errors', $board->getErrors()->all());
            } else {
                return Redirect::to(Request::path())->with('message', $board->name.' has been submitted.');
            }
        }
	}
}