<?php

class Utility_Response_Post {

	/**
	 * The status of this ajax request.
	 */
	public $status = 'error';

	/**
	 * The location to send successful updates
	 */
	public $successPath;

	/**
	 * The message to display on success
	 */
	public $successMessage;

	/**
	 * A list of the errors in this ajax request
	 */
	public $errors = array();

	public function __construct($path = null, $message = null)
	{
		if ($path != null) {
			$this->setSuccessPath($path);
		} else {
			$this->setSuccessPath(Request::path());
		}

		$this->setSuccessMessage($message);
	}

    /**
     * Attempt to save the model
     *
     * @param  object  $model
     * @return Utility_Response_Post
     */
	public function save($model, $redirect = false)
	{
		$dirty = $model->getDirty();

		if (count($dirty) > 0) {
			$model->save();

			if ($model == true && count($model->getErrors()->all()) > 0) {

				// Messages from aware are in a different format. Parse them into the error format.
				foreach ($model->getErrors()->getMessages() as $key => $message) {
					foreach ($message as $text) {
						$this->addError($key, $text);
					}
				}
			}
		}

		return $this;
	}

    /**
     * Add more than one error to the ajax response
     *
     * @param  array  $errors
     * @return Utility_Response_Post
     */
	public function addErrors(array $errors)
	{
		$this->errors = array_merge($this->errors, $errors);

		return $this;
	}

    /**
     * Add an error to the ajax response
     *
     * @param  string  $errorKey
     * @param  string  $errorMessage
     * @return Utility_Response_Post
     */
	public function addError($errorKey, $errorMessage)
	{
		$this->errors[$errorKey] = $errorMessage;

		return $this;
	}

    /**
     * Get the currect response errors
     *
     * @return array
     */
	public function getErrors()
	{
		return $this->errors;
	}

    /**
     * count the errors in the current response
     *
     * @return int
     */
	public function errorCount()
	{
		return count($this->errors);
	}

    /**
     * Save then check for errors, call redirect if there are any
     *
     * @return int
     */
	public function checkErrorsSave($model, $path = null)
	{
		$this->save($model);

		if ($this->errorCount() > 0) {
			return $this->redirect($path);
		}
	}

    /**
     * Set the response status
     *
     * @param  string  $newStatus
     * @return Utility_Response_Post
     */
	public function setStatus($newStatus)
	{
		$this->status = $newStatus;

		return $this;
	}

    /**
     * Set the success parameters
     *
     * @param  string  $path
     * @param  string  $message
     * @return Utility_Response_Post
     */
	public function setSuccess($path = null, $message = null)
	{
		$this->setSuccessPath($path);
		$this->setSuccessMessage($message);

		return $this;
	}

    /**
     * Set the success path
     *
     * @param  string  $path
     * @return Utility_Response_Post
     */
	public function setSuccessPath($path = null)
	{
		if ($path != null) {
			$this->successPath    = $path;
		}

		return $this;
	}

    /**
     * Set the success message
     *
     * @param  string  $message
     * @return Utility_Response_Post
     */
	public function setSuccessMessage($message = null)
	{
		if ($message != null) {
			$this->successMessage    = $message;
		}

		return $this;
	}

    /**
     * get the response status
     *
     * @return string
     */
	public function getStatus()
	{
		return $this->status;
	}

    /**
     * Convert this object to a json response and send it
     *
     * @param  string  $path
     * @param  string  $message
     * @return Redirect
     */
	public function redirect($path = null, $message = null)
	{
		$this->setSuccess($path, $message);

		if ($this->errorCount() > 0) {
			if ($path == 'back') {
				$back = $this->redirectBack();
				return $back->with('errors', $this->getErrors())->withInput()->send();
			} else {
				return Redirect::to(Request::path())->with('errors', $this->getErrors())->withInput()->send();
			}
		} else {
			if ($this->successMessage == null) {
				if ($this->successPath == 'back') {
					$back = $this->redirectBack();
					return $back->withInput()->send();
				}
				return Redirect::to($this->successPath)->send();
			} else {
				if ($this->successPath == 'back') {
					$back = $this->redirectBack();
					return $back->with('message', $this->successMessage)->withInput()->send();
				}
				return Redirect::to($this->successPath)->with('message', $this->successMessage)->withInput()->send();
			}
		}
	}

	protected function redirectBack()
	{
		if (!Redirect::getUrlGenerator()->getRequest()->headers->get('referer')) {
			return Redirect::to('/');
		} else {
			return Redirect::back();
		}
	}
}