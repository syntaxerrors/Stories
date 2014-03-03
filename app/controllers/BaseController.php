<?php

class BaseController extends MenuController {

	protected $transformer;

	public function __construct()
	{
		parent::__construct();

		$class = str_replace('Controller', 'Transformer', get_called_class());

		if (class_exists($class)) {
			$this->transformer = new $class;
		}
	}

}