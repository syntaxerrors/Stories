<?php
namespace Media;
use Media;
use Laravel;
use Aware;

class Category extends Aware
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	public static $table = 'media_categories';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name'      => 'required|max:200',
		'user_id'   => 'exists:users,id',
		'parent_id' => 'exists:media_categories,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public function user()
	{
		return $this->belongs_to('User');
	}

	public function parent()
	{
		return $this->belongs_to('Media\Category', 'parent_id');
	}

	public function media()
	{
		return $this->has_many('Media', 'media_category_id');
	}

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/**
	 * Get the last file
	 *
	 * @return Media
	 */
	public function get_lastFile()
	{
		return Media::where('media_category_id', '=', $this->id)->order_by('created_at', 'desc')->first();
	}

	/**
	 * Make the created_at data easier to read
	 *
	 * @return string
	 */
	public function get_created_at()
	{
		return date('F jS, Y \a\t h:ia', strtotime($this->get_attribute('created_at')));
	}

	/**
	 * Get the last file icon
	 *
	 * @return string
	 */
	public function get_lastFileIcon()
	{
		$media = $this->get_lastFile();
		if ($media instanceof Media) {
			$mime  = Laravel\File::mime($media->extension);

			if (strpos($mime, 'image') !== false) {
				return Laravel\HTML::image(
					'img/media/'. Laravel\Str::classify($this->name) .'/'. Laravel\Str::classify($media->name) .'.'. $media->extension,
					null,
					array('style' => 'width: 50px;max-height: 50px;')
				);
			} else {
				return '<i class="icon-folder-open icon-4x"></i>';
			}
		}
		return '';
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}