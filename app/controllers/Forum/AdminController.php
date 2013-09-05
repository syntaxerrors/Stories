<?php

class Forum_AdminController extends BaseController {

	public function getDashboard()
	{
		// Get the data
		$escalatedPostsCount = Forum_Moderation::where('adminReviewFlag', 1)->count();
		$reportLogsCount     = Forum_Moderation_Log::count();
		$userCount           = User::count();
		$categoryCount       = Forum_Category::count();
		$boardCount          = Forum_Board::whereNull('parent_id')->count();

		$this->setViewData('escalatedPostsCount', $escalatedPostsCount);
		$this->setViewData('reportLogsCount', $reportLogsCount);
		$this->setViewData('userCount', $userCount);
		$this->setViewData('categoryCount', $categoryCount);
		$this->setViewData('boardCount', $boardCount);
	}

	public function getEscalatedPosts()
	{
		$escalatedPosts = Forum_Moderation::where('adminReviewFlag', 1)->get();

		$this->setViewData('escalatedPosts', $escalatedPosts);
	}

	public function getReportLogs()
	{
		$reportLogs = Forum_Moderation_Log::orderBy('created_at', 'desc')->get();

		$this->setViewPath('forum.moderation.reportlogs');
		$this->setViewData('reportLogs', $reportLogs);
	}

	public function getUsers()
	{
		$users = User::orderBy('username', 'asc')->get();

		// Get the role information for each user
		$users = $users->each(function($user) {
			$highestRole = $user->getHighestRoleObject('Forum');
			$roles       = User_Permission_Role::where('group', 'Forum')->where('id', '!=', User_Permission_Role::FORUM_ADMIN)->get();
			$higherRoles = $this->arrayToSelect($roles, 'id', 'name', 'Select a new role');

			$user->highestRole = $highestRole;
			$user->higherRoles = $higherRoles;
		});

		$this->setViewData('users', $users);
	}

	public function getCategories()
	{
		$categories = Forum_Category::orderBy('position', 'asc')->get();

		$this->setViewData('categories', $categories);
	}

	public function postMoveCategories()
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			foreach ($input['sortCategories'] as $position => $categoryId) {
				$category           = Forum_Category::find($categoryId);
				$category->position = $position + 1;

				$this->save($category);
			}
		}
	}

	public function postCategoryEdit()
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			$category          = Forum_Category::find($input['pk']);
			$category->name    = $input['value'];
			$category->keyName = Str::slug($input['value']);

			$this->save($category);
		}
	}

	public function getDeleteCategory($categoryId)
	{
		$this->skipView();

		$category = Forum_Category::find($categoryId);
		$category->delete();

		$this->redirect('forum/admin/dashboard#categories', $category->name .' has been removed.');
	}

	public function getBoards()
	{
		$categories = Forum_Category::orderBy('position', 'asc')->get();

		$boards     = Forum_Board::whereNull('parent_id')->get();
		$boards     = $boards->filter(function ($board) {
			if ($board->children->count() > 0) {
				return true;
			}
		});

		$this->setViewData('categories', $categories);
		$this->setViewData('boards', $boards);
	}

	public function postBoardEdit()
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			$board          = Forum_Board::find($input['pk']);
			$board->name    = $input['value'];
			$board->keyName = Str::slug($input['value']);

			$this->save($board);
		}
	}

	public function getDeleteBoard($boardId)
	{
		$this->skipView();

		$board = Forum_Board::find($boardId);
		$board->delete();

		$this->redirect('forum/admin/dashboard#boards', $board->name .' has been removed.');
	}

	public function postMoveBoards()
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			foreach ($input as $parentId => $boards) {
				foreach ($boards as $position => $boardId) {
					$board           = Forum_Board::find($boardId);
					$board->position = $position + 1;

					$this->save($board);
				}
			}
		}
	}

	public function postUpdateRole($userId, $newRoleId = null)
	{
		$this->skipView();

		if ($newRoleId != null && $newRoleId != 0) {
			$user = User::find($userId);
			if (!$user->roles->contains($newRoleId)) {
				$user->updateGroupRole('Forum', $newRoleId);
			}

			$this->ajaxResponse->setStatus('success');
			$this->ajaxResponse->addData('role', $user->getHighestRole('Forum'));
		} else {
			$this->ajaxResponse->addError('roleId', 'Invalid role supplied.');
		}

		// Send the response
		return $this->ajaxResponse->sendResponse();
	}
}