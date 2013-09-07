<?php

class ForumController extends BaseController {

	public function getIndex()
	{
		// Get the categories
		$categories         = Forum_Category::with(array('type', 'boards'))->orderBy('position', 'asc')->get();
		$statuses           = Forum_Post_Status::all();

		$openIssues       = 0;
		$inProgressIssues = 0;
		$resolvedIssues   = 0;
		foreach ($statuses as $status) {
			switch ($status->forum_support_status_id) {
				case Forum_Support_Status::TYPE_OPEN:
					$openIssues++;
				break;
				case Forum_Support_Status::TYPE_IN_PROGRESS:
					$inProgressIssues++;
				break;
				case Forum_Support_Status::TYPE_RESOLVED:
					$resolvedIssues++;
				break;
			}
		}

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
		$this->skipView();
		$input = Input::all();
		return Utility_Response_BBCode::parse(e($input['update']));
	}
}