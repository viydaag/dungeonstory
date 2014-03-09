<?php
class EditMonster extends DSPage
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
			
			$monsterRecord = $this->Monster;
			$this->Name->Text = $monsterRecord->name;
			$this->Description->Text = $monsterRecord->description;
			$this->Image->ImageUrl = 'images/monsters/'.$monsterRecord->image;
			$this->setMonsterImage($monsterRecord->image);
			$this->Level->Text = $monsterRecord->level;
			$this->CR->Text = $monsterRecord->challengeRating;
			$this->LevelAdjustment->Text = $monsterRecord->levelAdjustment;
			$this->XP->Text = $monsterRecord->xpAward;
			$this->AC->Text = $monsterRecord->baseAC;
			$this->Life->Text = $monsterRecord->life;
			$this->Alignment->SelectedValue = $monsterRecord->alignmentId;
			$this->Initiative->Text = $monsterRecord->initiative;
			$this->Str->Text = $monsterRecord->strength;
			$this->Dex->Text = $monsterRecord->dexterity;
			$this->Con->Text = $monsterRecord->constitution;
			$this->Int->Text = $monsterRecord->intelligence;
			$this->Wis->Text = $monsterRecord->wisdom;
			$this->Cha->Text = $monsterRecord->charisma;
		}
    }

	public function saveButtonClicked($sender, $param) {
        if ($this->IsValid) {
			$finder = MonsterRecord::finder();
			$finder->DbConnection->Active = true;
			$transaction = $finder->DbConnection->beginTransaction();
            
			try {
				$monsterRecord = $this->Monster;
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
	
	protected function getMonster() {
        // l'ID du message devant être modifié passé par un paramètre GET 
        $monsterId = (int)$this->Request['monsterId'];
        // utilise Active Record pour lire le message correspondant à cet ID
        $monsterRecord = MonsterRecord::finder()->findByPk($monsterId);
        if ($monsterRecord === null) {
            throw new DSException(500, 'monster_id_invalid', $monsterId);
		}
        return $monsterRecord;
    }
}
?>
