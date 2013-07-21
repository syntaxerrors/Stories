<?php

class AnimaController extends BaseController {

	public function getIndex($gameId)
	{
		$this->setViewData('gameId', $gameId);
	}

	public function getCharacters()
	{
		$this->setViewData('test', 'test');
	}

}