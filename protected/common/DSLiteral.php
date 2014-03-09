<?php

class DSLiteral extends TLiteral
{
	public function getHLengh() {
		if ($this->getViewState('HLengh', '') == NULL) {
			return 75;
		} else {
			return($this->getViewState('HLengh', ''));
		}
	}
	
	public function setHLengh($value) {
		$this->setViewState('HLengh', TPropertyValue::ensureInteger($value));
	}

	public function getText() {
		return wordwrap(parent::getText(), $this->HLengh, '<br />', true);
	}
}

?>