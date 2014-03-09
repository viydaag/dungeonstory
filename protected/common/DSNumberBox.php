<?php
class DSNumberBox extends TTextBox
{
	public function getValue()
	{
		//return $this->getViewState('Value', null);
		return $this->getText();
	}
	
	public function setValue($value)
	{
		//$this->setViewState('Value', TPropertyValue::ensureInteger($value), null);
		$this->setText($value);
	}
	
	public function getMinValue()
	{
		return $this->getViewState('MinValue', null);
	}
	
	public function setMinValue($value)
	{
		$this->setViewState('MinValue', TPropertyValue::ensureInteger($value), null);
	}
	
	public function getMaxValue()
	{
		return $this->getViewState('MaxValue', null);
	}
	
	public function setMaxValue($value)
	{
		$this->setViewState('MaxValue', TPropertyValue::ensureInteger($value), null);
	}
	
	public function getStep()
	{
		return $this->getViewState('Step', 1);
	}
	
	public function setStep($value)
	{
		$this->setViewState('Step', TPropertyValue::ensureInteger($value), null);
	}
	
	public function render($writer)
	{
		$writer->addAttribute('id', $this->getClientID());
		$writer->addAttribute('type', 'number');
		$writer->addAttribute('class', $this->getCssClass());
		$writer->addAttribute('value', $this->getValue());
		if ($this->getMinValue() !== null)
		{
			$writer->addAttribute('min', $this->getMinValue());
		}
		if ($this->getMaxValue() !== null)
		{
			$writer->addAttribute('max', $this->getMaxValue());
		}
		if ($this->getStep() !== null)
		{
			$writer->addAttribute('step', $this->getStep());
		}
		if ($this->getReadOnly())
		{
            $writer->addAttribute('readonly','readonly');
		}
        $isEnabled=$this->getEnabled(true);
        if(!$isEnabled && $this->getEnabled())  // in this case parent will not render 'disabled'
		{
            $writer->addAttribute('disabled','disabled');
		}
		$writer->renderBeginTag("input");
		$writer->renderEndTag();
	}
}
?>