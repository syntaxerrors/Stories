<?php

class Utility_Crud {

	/**
	 * The title of the page
	 */
	public $title;

	/**
	 * The column to sort on
	 */
	public $sortProperty;

	/**
	 * A flag to show the delete button
	 */
	public $deleteFlag = true;

	/**
	 * The location to send deletes
	 */
	public $deleteLink;

	/**
	 * The column to find the object by
	 */
	public $deleteProperty = 'id';

	/**
	 * An array of extra buttons to display
	 */
	public $buttons = array();

	/**
	 * The fields to display on the page
	 */
	public $displayFields = array();

	/**
	 * The fields to use in the add/edit form
	 */
	public $formFields = array();

	/**
	 * Ad an extra button per row
	 *
	 * @param  string  $name
	 * @param  string  $linkLocation
	 * @param  string  $linkName
	 * @param  array   $options
	 * @return Utility_Crud
	 */
	public function addButton($name, $linkLocation, $linkName, $options = array())
	{
		$this->buttons[$name] = HTML::link($linkLocation, $linkName, $options);

		return $this;
	}

	/**
	 * Add a field to the display
	 *
	 * @param  string  $fieldName
	 * @param  string  $linkLocation
	 * @param  string  $linkProperty
	 * @return Utility_Crud
	 */
	public function addDisplayField($fieldName, $linkLocation = null, $linkProperty = null, )
	{
		$this->displayFields[$fieldName] = array
		(
			'linkLocation' => $linkLocation,
			'linkProperty' => $linkProperty,
		);

		return $this;
	}

	/**
	 * Add a field to the form
	 *
	 * @param  string  $fieldName
	 * @param  string  $fieldType
	 * @param  array   $selectArray
	 * @return Utility_Crud
	 */
	public function addFormField($fieldName, $fieldType, $selectArray = null, $required = false)
	{
		$this->formFields[$fieldName] = array
		(
			'field'       => $fieldType,
			'selectArray' => $selectArray,
			'required'    => $required,
		);

		return $this;
	}

	/**
	 * Set the title
	 *
	 * @param  string  $title
	 * @return Utility_Crud
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Set the sort property
	 *
	 * @param  string  $sortProperty
	 * @return Utility_Crud
	 */
	public function setSortProperty($sortProperty)
	{
		$this->sortProperty = $sortProperty;

		return $this;
	}

	/**
	 * Set the delete flag
	 *
	 * @param  string  $deleteFlag
	 * @return Utility_Crud
	 */
	public function setDeleteFlag($deleteFlag)
	{
		$this->deleteFlag = $deleteFlag;

		return $this;
	}

	/**
	 * Set the delete link
	 *
	 * @param  string  $deleteLink
	 * @return Utility_Crud
	 */
	public function setDeleteLink($deleteLink)
	{
		$this->deleteLink = $deleteLink;

		return $this;
	}

	/**
	 * Set the delete property
	 *
	 * @param  string  $deleteProperty
	 * @return Utility_Crud
	 */
	public function setDeleteProperty($deleteProperty)
	{
		$this->deleteProperty = $deleteProperty;

		return $this;
	}
}