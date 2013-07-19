<?php

class Forum extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

	/**
	 * Get all users that have a forum role
	 *
	 * @return array
	 */
	public function users()
	{
		// Get all forum roles
		return Role::where('group', '=', 'Forum')->order_by('value', 'asc')->users()->get();
	}

	/**
	 * Get the recent non-support posts
	 *
	 * @return array
	 */
	public function recentPosts()
	{
		// Get all non-support categories
		$boardIds = Forum_Category::whereNull('game_id')->where('forum_category_type_id', '!=', Forum_Category::TYPE_SUPPORT)->get()->boards->id;

		if (count($boardIds) > 0) {
			// Get the last 5 posts in those boards
			return Forum_Post::whereIn('forum_board_id', $boardIds)->orderBy('modified_at', 'desc')->take(5)->get();
		}

		return array();
	}

	/**
	 * Get the recent posts for a category
	 *
	 * @return array
	 */
	public function recentCategoryPosts($categoryId)
	{
		// Get all the boards in this category
		$boards = Forum_Board::where('forum_category_id', '=', $categoryId)->get('id');

		if (count($boards) > 0) {
			// Get the last 5 posts in those boards
			$boardIds = array_pluck($boards, 'id');
			return Forum_Post::where_in('forum_board_id', $boardIds)->order_by('modified_at', 'desc')->take(10)->get();
		}
		return array();
	}

	/**
	 * Get the recent support posts
	 *
	 * @return array
	 */
	public function recentSupportPosts()
	{
		// Get all support categories
		$categoryIds = Forum_Category::whereNull('game_id')->where('forum_category_type_id', '=', Forum_Category::TYPE_SUPPORT)->get()->id->toArray();


		if (count($categoryIds) > 0) {
			// Get all the boards in those categories
			$boardIds      = Forum_Board::whereIn('forum_category_id', $categoryIds)->whereNotIn('forum_board_type_id', array(Forum_Board::TYPE_GM))->get()->id->toArray();

			if (count($boardIds) > 0) {
				// Get the last 5 posts in those boards
				return Forum_Post::whereIn('forum_board_id', $boardIds)->orderBy('modified_at', 'desc')->take(5)->get();
			}
		}
		return array();
	}
}