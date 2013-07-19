<?php

class ForumController extends BaseController {

	public function getIndex()
	{
		// Get the categories
		$categories         = Forum_Category::with(array('type', 'boards'))->orderBy('position', 'asc')->get();
		$openIssues         = Forum_Post_Status::where('forum_support_status_id', '=', Forum_Support_Status::TYPE_OPEN)->count();
		$inProgressIssues   = Forum_Post_Status::where('forum_support_status_id', '=', Forum_Support_Status::TYPE_IN_PROGRESS)->count();
		$resolvedIssues     = Forum_Post_Status::where('forum_support_status_id', '=', Forum_Support_Status::TYPE_RESOLVED)->count();
		$forum              = new Forum;
		$recentPosts        = $forum->recentPosts();
		$recentSupportPosts = $forum->recentSupportPosts();
		// $games              = Game::active()->get();
		$games = array();

		// Set the template
		$this->setViewData('categories', $categories);
		$this->setViewData('openIssues', $openIssues);
		$this->setViewData('inProgressIssues', $inProgressIssues);
		$this->setViewData('resolvedIssues', $resolvedIssues);
		$this->setViewData('recentPosts', $recentPosts);
		$this->setViewData('recentSupportPosts', $recentSupportPosts);
		$this->setViewData('games', $games);
	}

	public function postPreview()
	{
		$this->skipView = true;
		$input = Input::all();
		return BBCode::parse(e($input['update']));
	}
}