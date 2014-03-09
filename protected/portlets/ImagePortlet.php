<?php
class ImagePortlet extends DSPortlet
{
	public function getWithRadioButton() {
		return($this->getViewState('WithRadioButton', ''));
	}
	
	public function setWithRadioButton($value) {
		$this->setViewState('WithRadioButton', TPropertyValue::ensureBoolean($value));
	}

    public function generateImageView($sexe, $race) {
		$images = $this->getImages($sexe, $race);
		$this->ImageRepeater->DataSource=$images;
        $this->ImageRepeater->dataBind();
	}
	
	public function getSelectedImage() {
		if ($this->getWithRadioButton() != NULL) {
			foreach($this->ImageRepeater->Items as $item) {			
				if ($item->RadioButton->Checked) {
					return $item->RadioButton->InputAttributes['value'];
				}
			}
		}
		return NULL;			
	}
	
	protected function getImages($sexe, $race) {
		//echo $race;		 
		switch($sexe) {
			case ('M') : $sexeFolder = 'male';
						 break;
			case ('F') : $sexeFolder = 'female';
						 break;
		}
		$dir = 'images/'.$sexeFolder.'/'.$race.'/';
		//echo $dir.'<br/>';

		try {
			$files = scandir(utf8_decode($dir));
			$i = 1;
			foreach ($files as &$file) {
				if ($file != '.' && $file != '..' && ereg("(.*)\.(jpg|JPG|bmp|BMP|jpeg|JPEG|png|PNG|gif|GIF)", $file)) {
					$url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/dungeonstory/'.$dir.$file;
					$entry = array('id'=>$i,'name'=>$file,'path'=>$dir.$file, 'url'=>$url);
					$images[$i] = $entry;
					$i = $i + 1;
					//echo 'http://'.$_SERVER['SERVER_NAME'].'/dungeonstory/'.$dir.$file;
				}
			}
		} catch (TPhpErrorException $e) {
			//throw new DSException(400, 'le dossier '.$dir.' est inexistant');
			throw new DSException(400, $e->getErrorMessage());
		}
		return $images;
		//return null;
	}
}
?>