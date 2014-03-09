<?php
class DSPage extends TPage
{
	public function onPreInit($param) {
		parent::onPreInit($param);
		//$this->Theme=$this->Application->Parameters['ThemeName'];
	}
	
	public function goToDefaultPage() {
		$this->gotoPage($this->Service->DefaultPage);
	}

	public function goToPage($pagePath,$getParameters=null)	{
		$this->Response->redirect($this->Service->constructUrl($pagePath, $getParameters, false));
	}
	
	public function getBaseUrl($forceSecureConnection=false)
	{
		/*$secure = $this->Request->getIsSecureConnection() || $forceSecureConnection;
		$url = ($secure ? "https://" : "http://") . $_SERVER ['SERVER_NAME'];
		$port = $_SERVER['SERVER_PORT'];
		if (($port != 80 && !$secure) || ($port != 443 && $secure)) {
			$url .= ':'.$port;
		}
		return $url;
		*/
		return $this->Request->getBaseUrl();
	}
	
	public function getApplicationUrl() {
        return $this->getBaseUrl().'/'.$this->Application->Parameters['AppName'];
	}
	
	public function getImagesUrl() {
        return $this->getApplicationUrl().'/images';
	}

	public function findComponentById($id) {
	    if(!strlen($id)) return null;
	    $object=$this;
	    $children=$object->getChildren();
	    foreach($children as $child) {
	        if ($object=$child->findBodyControl($id)) return $object;
	        else if ($object=$child->findComponentById($id)) return $object;
	    }
	    return null;
}
}
?>