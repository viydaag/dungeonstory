<?php

Prado::using('Application.Portlets.DSPortlet');

class AccountPortlet extends DSPortlet
{
	public function logout($sender,$param) {
		$this->Application->getModule('auth')->logout();
		//$this->Response->reload();
		$this->Response->redirect($this->Service->constructUrl($this->Service->DefaultPage));
		//$this->goToDefaultPage();
	}
}
?>