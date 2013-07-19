<?php

class Forum_Category extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table = 'forum_categories';
	const TYPE_GAME     = 2;
	const TYPE_STANDARD = 1;
	const TYPE_SUPPORT  = 3;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/

    /**
     * Validation rules
     *
     * @static
     * @var array $rules All rules this model must follow
     */
	public static $rules = array(
		'name'                => 'required|max:200',
		'keyName'             => 'required|max:200',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Game Relationship
     *
     * @return Game
     */
	public function game()
	{
		return $this->belongsTo('Game');
	}

    /**
     * Forum Board Relationship
     *
     * @return Forum_Board[]
     */
	public function boards()
	{
		return $this->hasMany('Forum_Board', 'forum_category_id');
	}

    /**
     * Forum Board Relationship (Ordered by post count)
     *
     * @return Forum_Board[]
     */
	public function boardsByPostCount()
	{
		return $this->hasMany('Forum_Board', 'forum_category_id')->order_by('postsCount');
	}

    /**
     * Forum Category Type Relationship
     *
     * @return Forum_Category_Type
     */
	public function type()
	{
		return $this->belongsTo('Forum_Category_Type', 'forum_category_type_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

    /**
     * Get count of posts in this category
     *
     * @return int
     */
	public function getPostsCountAttribute()
	{
		$boards   = Forum_Board::where('forum_category_id', '=', $this->id)->get();
		$boardIds = array_pluck($boards->toArray(), 'id');
		if (count($boardIds) > 0) {
			return Forum_Post::whereIn('forum_board_id', $boardIds)->count();
		}
		return 0;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

    /**
     * Move this category up one position
     *
     * @return int
     */
	public function moveUp()
	{
		$newValue = $this->position - 1;
	    $this->set_attribute('position', $newValue);
	    $this->save();
	}

    /**
     * Move this category down one position
     *
     * @return int
     */
	public function moveDown()
	{
		$newValue = $this->position + 1;
	    $this->set_attribute('position', $newValue);
	    $this->save();
	}

}