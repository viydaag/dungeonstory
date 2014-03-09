<?php
class NewMonster extends DSPage
{
	public function onInit($param) {
        parent::onInit($param);

        if (!$this->User->IsAdmin) {
            throw new DSException(500, 'admin_view_disallowed');
		}
		
		if (!$this->IsPostBack) {
		 	$alignmentfinder = AlignementRecord::finder();
			$alignments = $alignmentfinder->findAll();
			$this->Alignment->DataSource = $alignments;
			$this->Alignment->dataBind();
		}
    }

	public function saveButtonClicked($sender, $param) {
        if ($this->IsValid) {
			$finder = MonsterRecord::finder();
			$finder->DbConnection->Active = true;
			$transaction = $finder->DbConnection->beginTransaction();
            
			try {
				$monsterRecord = new MonsterRecord();
				$monsterRecord->name = $this->Name->SafeText;
				$monsterRecord->description = $this->Description->SafeText;
				$monsterRecord->image = $this->MonsterImage;		
				$monsterRecord->level = $this->Level->SafeText;
				$monsterRecord->challengeRating = $this->CR->SafeText;
				$monsterRecord->levelAdjustment = $this->LevelAdjustment->SafeText;
				$monsterRecord->xpAward = $this->XP->SafeText;
				$monsterRecord->baseAC = $this->AC->SafeText;
				$monsterRecord->life = $this->Life->SafeText;
				$monsterRecord->alignmentId = $this->Alignment->SelectedValue;
				$monsterRecord->initiative = $this->Initiative->SafeText;
				$monsterRecord->strength = $this->Str->SafeText;
				$monsterRecord->dexterity = $this->Dex->SafeText;
				$monsterRecord->constitution = $this->Con->SafeText;
				$monsterRecord->intelligence = $this->Int->SafeText;
				$monsterRecord->wisdom = $this->Wis->SafeText;
				$monsterRecord->charisma = $this->Cha->SafeText;
				$monsterRecord->save();
				
				$transaction->commit();
			} catch(Exception $e) {
				$transaction->rollBack();
				throw new DSException(500, $e->getMessage());
			}
			$this->goToPage('admin.ListMonster');
        }
    }
	
	public function getMonsterImage(){
		return($this->getViewState('MonsterImage', ''));
	}
	
	public function setMonsterImage($value){
		$this->setViewState('MonsterImage', $value);
	}
	
	public function fileUploaded($sender, $param) {
		$sender->setMaxFileSize(102400);
		$dir = 'images/monsters/';
        if($sender->HasFile) {
			if ($sender->FileSize > $sender->MaxFileSize) {
				throw new DSException(500, 'admin_upload_too_big_file', $sender->FileName);
			} 				
			//$sender->saveAs($dir.$sender->FileName, true);
        }
		$this->Image->ImageUrl = $dir.$sender->FileName;
		$this->setMonsterImage($sender->FileName);
    }
}
?>
