<?php

namespace Forum;
use Aware;

class Category extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'forum_categories';
	const TYPE_GAME     = 2;
	const TYPE_STANDARD = 1;
	const TYPE_SUPPORT  = 3;

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'name'                => 'required|max:200',
		'keyName'             => 'required|max:200',
	);

	/**
	 * Getter and Setter methods
	 */
	public function get_postsCount()
	{
		$boards = Board::where('forum_category_id', '=', $this->get_attribute('id'))->get('id');
		$boardIds = array_pluck($boards, 'id');
		if (count($boardIds) > 0) {
			return Post::where_in('forum_board_id', $boardIds)->count();
		}
		return 0;
	}
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * Relationships
	 */
	public function game()
	{
		return $this->belongs_to('Game');
	}
	public function boards()
	{
		return $this->has_many('Forum\Board', 'forum_category_id');
	}
	public function boardsByPostCount()
	{
		return $this->has_many('Forum\Board', 'forum_category_id')->order_by('postsCount');
	}
	public function type()
	{
		return $this->belongs_to('Forum\Category\Type', 'forum_category_type_id');
	}
	public function rules()
	{
		return $this->has_many('Forum\Category\Rule', 'forum_category_id');
	}
	public function settings()
	{
		return $this->has_many('Forum\Category\Setting', 'forum_category_id');
	}

	/**
	 * Extra Methods
	 */
	public function moveUp()
	{
		$newValue = $this->get_attribute('position') - 1;
	    $this->set_attribute('position', $newValue);
	    $this->save();
	}
	public function moveDown()
	{
		$newValue = $this->get_attribute('position') + 1;
	    $this->set_attribute('position', $newValue);
	    $this->save();
	}

}