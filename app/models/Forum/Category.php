<?php

class Forum_Category extends Forum
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'forum_categories';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	const TYPE_GAME     = 2;
	const TYPE_STANDARD = 1;
	const TYPE_SUPPORT  = 3;

	/**
	 * Soft Delete users instead of completely removing them
	 *
	 * @var bool $softDelete Whether to delete or soft delete
	 */
	protected $softDelete = true;


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
		return $this->hasMany('Forum_Board', 'forum_category_id')->orderBy('position', 'asc');
	}

    /**
     * Forum Board Relationship (Ordered by post count)
     *
     * @return Forum_Board[]
     */
	public function boardsByPostCount()
	{
		return $this->hasMany('Forum_Board', 'forum_category_id')->orderBy('postsCount');
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
	 * Model events
	 *******************************************************************/

	public static function boot()
	{
		parent::boot();

		Forum_Category::creating(function($object)
		{
			$object->uniqueId = parent::findExistingReferences('Forum_Category');
		});

		Forum_Category::deleting(function($object)
		{
			$object->boards->each(function($board)
			{
				$board->delete();
			});
		});
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
		$postCount = $this->boards()->with('posts')->get()->posts->count();
		return $postCount;
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
		$this->position = $newValue;
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
		$this->position = $newValue;
		$this->save();
	}

}