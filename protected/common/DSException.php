<?php
class DSException extends THttpException
{
	/**
	 * @return string path to the error message file
	 */
	protected function getErrorMessageFile() {
		return dirname(__FILE__).'/messages.txt';
	}
}
?>