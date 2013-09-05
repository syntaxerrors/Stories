<?php

class Forum_Board extends BaseModel
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
		return $this->hasMany('Forum_Post', 'forum_board_id');
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
		return Forum_Post::where('forum_board_id', '=', $this->id)->count();
	}

    /**
     * Get count of replies in this board
     *
     * @return int
     */
	public function getRepliesCountAttribute()
	{
		$postIds = Forum_Post::where('forum_board_id', $this->id)->get()->id->toArray();
		if (count($postIds) > 0) {
			return Forum_Reply::whereIn('forum_post_id', $postIds)->count();
		}
		return 0;
	}

    /**
     * Get last update in this board
     *
     * @return Forum_Post|Forum_Reply
     */
	public function getLastUpdateAttribute()
	{
		$children = Forum_Board::where('parent_id', '=', $this->id)->get();
		if (count($children) > 0) {
			$boardIds = array_pluck($children->toArray(), 'uniqueId');
		} else {
			$boardIds = array();
		}
		array_push($boardIds, $this->id);
		$post = Forum_Post::whereIn('forum_board_id', $boardIds)->orderBy('modified_at', 'desc')->first();
		if ($post != null) {
			return $post->lastUpdate;
		}
		return false;
	}

    /**
     * Get the last actual post from this board
     *
     * @return int
     */
	public function getLastPostAttribute()
	{
		$children = Forum_Board::where('parent_id', '=', $this->id)->get();
		if (count($children) > 0) {
			$boardIds = array_pluck($children->toArray(), 'uniqueId');
		} else {
			$boardIds = array();
		}
		array_push($boardIds, $this->id);
		$post = Forum_Post::whereIn('forum_board_id', $boardIds)->orderBy('modified_at', 'desc')->first();
		if ($post != null) {
			return $post;
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
		$lastUpdate = $this->getLastUpdateAttribute();
		$lastPost   = $this->getLastPostAttribute();

		if ($lastPost instanceof Forum_Post) {
			$replies = $lastPost->replies;
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
		return Forum_Board::where('parent_id', '=', $this->id)->get();
	}

    /**
     * Get child board links and format them as needed
     *
     * @return int
     */
	public function getChildLinksAttribute()
	{
		$children = Forum_Board::where('parent_id', '=', $this->id)->orderBy('position', 'asc')->get();

		if (count($children) > 0) {
			$links = array();
			$count = 0;
			foreach ($children as $child) {
				$posts = Forum_Post::where('forum_board_id', '=', $child->id)->get();
				if (count($posts) > 0) {
					$postIds = $posts->uniqueId->toArray();
					$viewedPosts = Forum_Post_View::where('user_id', '=', Auth::user()->id)->whereIn('forum_post_id', $postIds)->get();
					if (count($posts) > count($viewedPosts)) {
						$links[] = '<b>' . HTML::linkIcon('forum/board/view/'. $child->keyName, 'icon-asterisk', $child->name) . '</b>';
						$count++;
					}
				}
				if ($count == 0) {
					$links[] = HTML::link('forum/board/view/'. $child->keyName, $child->name, array('style' => 'font-weight: normal;'));
				}
				$count = 0;
			}
			return implode(', ', $links);
		}

		return false;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/

    /**
     * Overload delete to cascade to posts
     *
     * @return void
     */
	public function delete()
	{
		if (count($this->posts) > 0) {
			foreach ($this->posts as $post) {
				$post->delete();
			}
		}
		parent::delete();
	}

}