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
		return Role::where('group', 'Forum')->orderBy('priority', 'asc')->get()->users();
	}

	/**
	 * Get the recent non-support posts
	 *
	 * @return array
	 */
	public function recentPosts()
	{
		// Get all non-support categories
		return Forum_Category::with('boards.posts')
			->whereNull('game_id')
			->where('forum_category_type_id', '!=', Forum_Category::TYPE_SUPPORT)
			->get()
			->boards
			->posts
			->take(5);
	}

	/**
	 * Get the recent posts for a category
	 *
	 * @return array
	 */
	public function recentCategoryPosts($categoryId)
	{
		// Get all non-support categories
		return Forum_Category::with('boards.posts')
			->where('uniqueId', $categoryId)
			->boards
			->posts
			->take(10);
	}

	/**
	 * Get the recent support posts
	 *
	 * @return array
	 */
	public function recentSupportPosts()
	{
		// Get all non-support categories
		return Forum_Category::with('boards.posts')
			->whereNull('game_id')
			->where('forum_category_type_id', '=', Forum_Category::TYPE_SUPPORT)
			->get()
			->boards
			->posts
			->take(5);
	}
}