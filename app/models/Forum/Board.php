<?php

class Forum_Board extends Forum
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/

	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'forum_boards';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	const TYPE_APPLICATION = 3;
	const TYPE_CHILD       = 2;
	const TYPE_STANDARD    = 1;
	const TYPE_ROLEPLAYING = 4;
	const TYPE_GM          = 5;

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
		'forum_category_id'   => 'required|exists:forum_categories,uniqueId',
	);

	/********************************************************************
	 * Scopes
	 *******************************************************************/

	/********************************************************************
	 * Relationships
	 *******************************************************************/

    /**
     * Forum Category Relationship
     *
     * @return Forum_Category
     */
	public function category()
	{
		return $this->belongsTo('Forum_Category', 'forum_category_id');
	}

    /**
     * Forum Post Relationship
     *
     * @return Forum_Post[]
     */
	public function posts()
	{
		return $this->hasMany('Forum_Post', 'forum_board_id')->orderBy('modified_at', 'desc');
	}

    /**
     * Parent Forum Board Relationship
     *
     * @return Forum_Board
     */
	public function parent()
	{
		return $this->belongsTo('Forum_Board', 'parent_id');
	}

    /**
     * Children Forum Board Relationship
     *
     * @return Forum_Board
     */
	public function children()
	{
		return $this->hasMany('Forum_Board', 'parent_id');
	}

    /**
     * Forum Board Type Relationship
     *
     * @return Forum_Board_Type
     */
	public function type()
	{
		return $this->belongsTo('Forum_Board_Type', 'forum_board_type_id');
	}

	/********************************************************************
	 * Model events
	 *******************************************************************/

	public static function boot()
	{
		parent::boot();

		Forum_Board::creating(function($object)
		{
			$object->uniqueId = parent::findExistingReferences('Forum_Board');
		});

		Forum_Board::deleting(function($object)
		{
			// Make any child boards normal boards
			$childBoards = Forum_Board::where('parent_id', $object->id)->get();

			if ($childBoards->count() > 0) {
				foreach ($childBoards as $childBoard) {
					$childBoard->parent_id = null;
					$childBoard->save();
				}
			}

			$object->posts->each(function($post)
			{
				$post->delete();
			});
		});
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

    /**
     * Get count of posts in this board
     *
     * @return int
     */
	public function getPostsCountAttribute()
	{
		return $this->posts()->count();
	}

    /**
     * Get count of replies in this board
     *
     * @return int
     */
	public function getRepliesCountAttribute()
	{
		$replies = $this->posts()->with('replies')->get()->replies->count();
		return $replies;
	}

    /**
     * Get the last actual post from this board
     *
     * @return int
     */
	public function getLastPostAttribute()
	{
		$posts         = $this->posts()->get();
		$childrenPosts = $this->children()->with('posts')->get()->posts;

		$allPosts = null;
		if ($posts->count() > 0) {
			$allPosts = $posts;
		}
		if ($childrenPosts->count() > 0) {
			if (isset($allPosts)) {
				$allPosts->merge($childrenPosts->toArray());
			} else {
				$allPosts = $childrenPosts;
			}
		}

		if ($allPosts != null) {
			$allPosts = $allPosts->sortBy(function ($post) {
				return $post->modified_at;
			});
			$allPosts = $allPosts->reverse();
			return $allPosts[0];
		}

		return false;
	}

    /**
     * Get last update in this board
     *
     * @return Forum_Post|Forum_Reply
     */
	public function getLastUpdateAttribute()
	{
		$lastPost = $this->getLastPostAttribute();

		if ($lastPost != false) {
			return $lastPost->lastUpdate;
		}

		return false;
	}

    /**
     * Get the pagination page number for the last reply of the last post
     *
     * @return int
     */
	public function getLastUpdatePageAttribute()
	{
		$lastPost = $this->getLastPostAttribute();

		if ($lastPost instanceof Forum_Post) {
			$replies    = $lastPost->replies;
			$lastUpdate = $lastPost->lastUpdate;

			foreach ($replies as $key => $reply) {
				if ($reply->id == $lastUpdate->id) {
					return round($key/30) + 1;
				}
			}

		}
		return 1;
	}

    /**
     * Get child boards
     *
     * @return int
     */
	public function getChildrenAttribute()
	{
		return $this->children()->get();
	}

    /**
     * Get child board links and format them as needed
     *
     * @return int
     */
	public function getChildLinksAttribute()
	{
		$children = $this->children()->with('posts')->orderBy('position', 'asc')->get();

		if (count($children) > 0) {
			$links = array();

			foreach ($children as $child) {
				$posts = $child->posts;
				$posts = $posts->filter(function ($post) {
					if ($post->checkUserViewed(Auth::user()->id) == false) {
						return true;
					}
				});

				if (count($posts) > 0) {
					$links[] = '<b>' . HTML::linkIcon('forum/board/view/'. $child->id, 'icon-asterisk', $child->name) . '</b>';
					$count++;
				} else {
					$links[] = HTML::link('forum/board/view/'. $child->id, $child->name, array('style' => 'font-weight: normal;'));
				}
			}
			return implode(', ', $links);
		}

		return false;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}