<?php
class DSErrorReport extends DSPage
{
	public function onLoad($param) {
		parent::onLoad($param);
		$this->ErrorMessage->Text = $this->Application->SecurityManager->validateData($this->Request['errormsg']);
	}
}
?>