<?php
class DSLabeledTextBox extends TCompositeControl
{
    private $_label;
    private $_textbox;
 
    public function createChildControls() {
        $this->_label = new TActiveLabel;
        $this->_label->setID('Label');
        $this->getControls()->add($this->_label);
        $this->getControls()->add('&nbsp;');
        $this->_textbox = new TActiveTextBox;
        $this->_textbox->setID('TextBox');
        $this->_label->setForControl('TextBox');
        $this->getControls()->add($this->_textbox);
    }
 
    public function getLabel() {
        $this->ensureChildControls();
        return $this->_label;
    }
 
    public function getTextBox() {
        $this->ensureChildControls();
        return $this->_textbox;
    }
	
	public function setDisplay($value) {
		$this->_label->setDisplay($value);
		$this->_textbox->setDisplay($value);
	}
}
?>