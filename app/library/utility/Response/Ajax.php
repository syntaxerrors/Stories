<?php

class Utility_Response_Ajax {

	/**
	 * The status of this ajax request.
	 */
	public $status = 'error';

	/**
	 * A list of the errors in this ajax request
	 */
	public $errors = array();

	/**
	 * A list of the data in this ajax request
	 */
	public $data = array();

    /**
     * Add data to the json response
     *
     * @param  string  $dataKey
     * @param  string  $dataValue
     * @return Utility_Response_Ajax
     */
	public function addData($dataKey, $dataValue)
	{
		$this->data[$dataKey] = $dataValue;

		return $this;
	}

    /**
     * Add more than one error to the ajax response
     *
     * @param  array  $errors
     * @return Utility_Response_Ajax
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
     * @return Utility_Response_Ajax
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
     * Set the response status
     *
     * @param  string  $newStatus
     * @return Utility_Response_Ajax
     */
	public function setStatus($newStatus)
	{
		$this->status = $newStatus;

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
     * @return Response
     */
	public function sendResponse()
	{
		return Response::json($this);
	}
}