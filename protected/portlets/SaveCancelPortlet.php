<?php
class SaveCancelPortlet extends DSPortlet
{
	public function getTitle() {
		return($this->getViewState('Title', ''));
	}
	
	public function setTitle($value) {
		$this->setViewState('Title', $value);
	}
	
	public function getSaveButton() {
        $this->ensureChildControls();
        return $this->getRegisteredObject('SaveButton');
    }

	public function getCancelButton() {
        $this->ensureChildControls();
        return $this->getRegisteredObject('CancelButton');
    }
}