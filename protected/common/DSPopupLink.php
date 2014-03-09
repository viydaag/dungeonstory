<?php

/**
 * class file
 */

/**
* This component displays a popup link on a web page.
*
*/

class DSPopupLink extends THyperLink
{
	/**
	 * @param URL of the popoup
	 */
	public function setPopupUrl($value)
	{
		$this->setViewState("PopupUrl", $value);
	}

	/**
	 * @return URL of the popoup
	 */
	public function getPopupUrl()
	{
		return $this->getViewState("PopupUrl");
	}

	/**
	 * @param width of the popoup
	 */
	public function setPopupWidth($value)
	{
		$this->setViewState("PopupWidth", $value);
	}

	/**
	 * @return width of the popoup
	 */
	public function getPopupWidth()
	{
		return $this->getViewState("PopupWidth");
	}

	/**
	 * @param height of the popoup
	 */
	public function setPopupHeight($value)
	{
		$this->setViewState("PopupHeight", $value);
	}

	/**
	 * @return height of the popoup
	 */
	public function getPopupHeight()
	{
		return $this->getViewState("PopupHeight");
	}

	/**
	 * @param distance from left screen border to the popoup
	 */
	public function setPopupLeft($value)
	{
		$this->setViewState("PopupLeft", $value);
	}

	/**
	* @return distance from left screen border to the popoup
	*/
	public function getPopupLeft()
	{
		return $this->getViewState("PopupLeft");
	}

	/**
	 * @param distance from top screen border to the popoup
	 */
	public function setPopupTop($value)
	{
		$this->setViewState("PopupTop", $value);
	}

	/**
	* @return distance from top screen border to the popoup
	*/
	public function getPopupTop()
	{
		return $this->getViewState("PopupTop");
	}

	function onPreRender()
	{
		$options= "width=" . $this->getPopupWidth() . ",";
		$options .= "height=" . $this->getPopupHeight() . ",";
		$options .= "left=" . $this->getPopupLeft() . ",top=" . $this->getPopupTop() . ",";

		$this->getStyle()->setStyleField("cursor", "pointer");
		$this->getAttributes()->add('OnClick', 'Window1 = window.open("' . $this->getPopupUrl() . '","Window1","' . $options . '");');
	}

	
	
}
?>

