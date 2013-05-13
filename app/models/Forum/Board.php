<?php

namespace Forum;
use Laravel;
use Aware;

class Board extends Aware
{
	/**
	 * Declarations
	 */
	public static $table = 'forum_boards';
	const TYPE_APPLICATION = 3;
	const TYPE_CHILD       = 2;
	const TYPE_STANDARD    = 1;
	const TYPE_ROLEPLAYING = 4;
	const TYPE_GM          = 5;

	/**
	 * Aware validation rules
	 */
	public static $rules = array(
		'name'                => 'required|max:200',
		'keyName'             => 'required|max:200',
		'forum_category_id'   => 'required|exists:forum_categories,id',
	);

	/**
	 * Getter and Setter methods
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
	public function get_postsCount()
	{
		return Post::where('forum_board_id', '=', $this->get_attribute('id'))->count();
	}
	public function get_repliesCount()
	{
		$posts = Post::where('forum_board_id', '=', $this->get_attribute('id'))->get('id');
		$postIds = array_pluck($posts, 'id');
		if (count($postIds) > 0) {
			return Reply::where_in('forum_post_id', $postIds)->count();
		}
		return 0;
	}
	public function get_lastUpdate()
	{
		$children = Board::where('parent_id', '=', $this->get_attribute('id'))->get('id');
		if (count($children) > 0) {
			$boardIds = array_pluck($children, 'id');
		} else {
			$boardIds = array();
		}
		array_push($boardIds, $this->get_attribute('id'));
		$post = Post::where_in('forum_board_id', $boardIds)->order_by('modified_at', 'desc')->first();
		if ($post != null) {
			return $post->lastUpdate;
		}
		return false;
	}
	public function get_lastPost()
	{
		$children = Board::where('parent_id', '=', $this->get_attribute('id'))->get('id');
		if (count($children) > 0) {
			$boardIds = array_pluck($children, 'id');
		} else {
			$boardIds = array();
		}
		array_push($boardIds, $this->get_attribute('id'));
		$post = Post::where_in('forum_board_id', $boardIds)->order_by('modified_at', 'desc')->first();
		if ($post != null) {
			return $post;
		}
		return false;
	}
	public function get_lastUpdatePage()
	{
		$lastUpdate = $this->get_lastUpdate();
		$lastPost   = $this->get_lastPost();

		if ($lastPost instanceof Post) {
			$replies = $lastPost->replies;
			foreach ($replies as $key => $reply) {
				if ($reply->id == $lastUpdate->id) {
					return round($key/30) + 1;
				}
			}
			
		}
		return 1;
	}
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}
	public function get_children()
	{
		return Board::where('parent_id', '=', $this->get_attribute('id'))->get();
	}
	public function get_childLinks()
	{
		$children = Board::where('parent_id', '=', $this->get_attribute('id'))->get();

		if (count($children) > 0) {
			$links = array();
			$count = 0;
			foreach ($children as $child) {
				$posts = Post::where('forum_board_id', '=', $child->id)->get('id');
				if (count($posts) > 0) {
					$postIds = array_pluck($posts, 'id');
					$viewedPosts = Post\View::where('user_id', '=', Laravel\Auth::user()->id)->where_in('forum_post_id', $postIds)->get();
					if (count($posts) > count($viewedPosts)) {
						$links[] = '<b>' . Laravel\HTML::linkIcon('forum/board/view/'. $child->keyName, 'icon-asterisk', $child->name) . '</b>';
						$count++;
					}
				}
				if ($count == 0) {
					$links[] = Laravel\HTML::link('forum/board/view/'. $child->keyName, $child->name, array('style' => 'font-weight: normal;'));
				}
				$count = 0;
			}
			return implode(', ', $links);
		}

		return false;
	}

	/**
	 * Relationships
	 */
	public function category()
	{
		return $this->belongs_to('Forum\Category', 'forum_category_id');
	}
	public function posts()
	{
		return $this->has_many('Forum\Post', 'forum_board_id');
	}
	public function parent()
	{
		return $this->belongs_to('Forum\Board', 'parent_id');
	}
	public function type()
	{
		return $this->belongs_to('Forum\Board\Type', 'forum_board_type_id');
	}
	public function rules()
	{
		return $this->has_many('Forum\Board\Rule', 'forum_board_id');
	}
	public function settings()
	{
		return $this->has_many('Forum\Board\Setting', 'forum_board_id');
	}

}