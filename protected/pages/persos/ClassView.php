<?php
class ClassView extends TView 
{
	public function getTemplate()
	{
		if($this->_localTemplate===null)
		{
			$class=get_class($this);
			if(!isset(self::$_template[$class]))
				self::$_template[$class]=$this->loadTemplate();
			return self::$_template[$class];
		}
		else
			return $this->_localTemplate;
	}

	/**
	 * Loads the template associated with this control class.
	 * @return ITemplate the parsed template structure
	 */
	protected function loadTemplate()
	{
		Prado::trace("Loading template ".get_class($this),'System.Web.UI.TTemplateControl');
		$template=$this->getService()->getTemplateManager()->getTemplateByClassName(get_class($this));
		return $template;
	}

	/**
	 * Creates child controls.
	 * This method is overridden to load and instantiate control template.
	 * This method should only be used by framework and control developers.
	 */
	public function createChildControls()
	{
		if($tpl=$this->getTemplate())
		{
			foreach($tpl->getDirective() as $name=>$value)
			{
				if(is_string($value))
					$this->setSubProperty($name,$value);
				else
					throw new TConfigurationException('templatecontrol_directive_invalid',get_class($this),$name);
			}
			$tpl->instantiateIn($this);
		}
	}
}
?>
