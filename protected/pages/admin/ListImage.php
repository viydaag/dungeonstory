<?php
class ListImage extends DSPage
{	
	public function onInit($param) {
        parent::onInit($param);
		$this->ImageMultiView->ActiveView = $this->SexeView;
		$racefinder = RaceRecord::finder();
		$races = $racefinder->findAll();
		$this->RaceRepeater->DataSource = $races;
		$this->RaceRepeater->dataBind();
    }
	
	public function getSexe(){
		return($this->getViewState('Sexe', ''));
	}
	
	public function setSexe($value){
		$this->setViewState('Sexe', $value);
	}
	
	public function getRace(){
		return($this->getViewState('Race', ''));
	}
	
	public function setRace($value){
		$this->setViewState('Race', $value);
	}

	public function goToRaceView($sender, $param) {
		$this->setSexe($param->CommandName);
		$this->ImageMultiView->ActiveView = $this->RaceView;
	}
	
	public function goToImageView($sender, $param) {
		$this->setRace($param->CommandName);
		$this->ImageMultiView->ActiveView = $this->ImageView;	
		$this->ImagePortlet->generateImageView($this->Sexe, $this->Race);
	}
	
	public function fileUploaded($sender, $param) {
		$sender->setMaxFileSize(57344);
		switch($this->Sexe) {
			case ('M') : $sexeFolder = 'male';
						 break;
			case ('F') : $sexeFolder = 'female';
						 break;
		}
		$dir = 'images/'.$sexeFolder.'/'.$this->Race.'/';
        if($sender->HasFile) {
			//
			echo $sender->MaxFileSize.'<br/>';
			echo $sender->FileSize;
			if ($sender->FileSize > $sender->MaxFileSize) {
				throw new DSException(500, 'admin_upload_too_big_file', $sender->FileName);
			} 				
			$sender->saveAs($dir.$sender->FileName, true);
        }
		$this->ImagePortlet->generateImageView($this->Sexe, $this->Race);
    }

}
?>