<?php
class NewClasse extends DSPage
{
	public function onInit($param) {
        parent::onInit($param);

        if(!$this->User->IsAdmin) {
            throw new DSException(500, 'admin_view_disallowed');
		}
 
        if(!$this->IsPostBack) {			
			$skillfinder = SkillRecord::finder();
			$skills = $skillfinder->findAll();
			$this->Skills->DataSource = $skills;
			$this->Skills->dataBind();
			
			$levelfinder = LevelRecord::finder();
			$levelList = $levelfinder->findAll();
			
			$data = array();
			foreach ($levelList as $i) {
				$data[] = array(
				  'level'=>$i->niveau,
				  'baseAttackBonus'=>0,
				  'fortSave'=>0,
				  'refSave'=>0,
				  'willSave'=>0,
				  'acBonus'=>0		  
				);
			}
			$this->ClassLevelBonusRepeater->DataSource = $data;
			$this->ClassLevelBonusRepeater->databind();		
		}
    }

	public function saveButtonClicked($sender, $param) {
        if ($this->IsValid) {
			$finder = ClasseRecord::finder();
			$finder->DbConnection->Active = true;
			$transaction = $finder->DbConnection->beginTransaction();
            
			try {
				$classeRecord = new ClasseRecord();
				$classeRecord->nom = $this->Nom->SafeText;
				$classeRecord->description = $this->Description->SafeText;
				$classeRecord->descriptionCourte = $this->DescriptionCourte->SafeText;
				$classeRecord->vie = $this->Vie->SafeText;
				$classeRecord->save();
				
				$values = $this->Skills->SelectedValues;
				foreach ($values as $classSkill) {
					$classeSkillRecord = new ClasseSkillRecord;
					$classeSkillRecord->classeId = $classeRecord->id;
					$classeSkillRecord->skillId = $classSkill;
					$classeSkillRecord->save();
				}
				
				//save class level bonuses
				foreach($this->ClassLevelBonusRepeater->getItems() as $i) {
					$bonusRecord = new ClassLevelBonusRecord();
					$bonusRecord->levelId = $i->Level->Text;
					$bonusRecord->classId = $classeRecord->id;
					$bonusRecord->baseAttackBonus = $i->BaseAttackBonus->Text;
					$bonusRecord->fortSave = TPropertyValue::ensureInteger($i->FortSave->Text);
					$bonusRecord->refSave = TPropertyValue::ensureInteger($i->RefSave->Text);
					$bonusRecord->willSave = TPropertyValue::ensureInteger($i->WillSave->Text);
					$bonusRecord->acBonus = TPropertyValue::ensureInteger($i->ACBonus->Text);
					$bonusRecord->save();
					//save all class level bonus feats
					foreach($i->FeatRepeater->Items as $feat) {
						$bonusFeatRecord = new ClassLevelBonusFeatRecord;
						$bonusFeatRecord->classId = $classeRecord->id;
						$bonusFeatRecord->levelId = $i->Level->Text;
						$bonusFeatRecord->featId = $feat->FeatList->SelectedValue;
						$bonusFeatRecord->save();
					}
				}
				$transaction->commit();
			} catch(Exception $e) {
				$transaction->rollBack();
				throw new DSException(500, $e->getMessage());
			}		
            $this->goToPage('admin.ListClasse');
        }
    }
	
	public function addFeat($sender, $param) {
		$level = $sender->CustomData;
		foreach ($this->ClassLevelBonusRepeater->Items as $item) {
			if ($item->FeatRepeater->CustomData == $level) {
				$repeater = $item->FeatRepeater;
				$panel = $item->FeatPanel;
				break;
			}
		}
		$data = array();
		foreach($repeater->getItems() as $i) {
			$data[] = array(
			  'feat'=>$i->FeatList->SelectedValue,
			  'class'=>'',
			  'level'=>$repeater->CustomData	  
			);			
		}
		# on ajoute un nouveau feat, en fait on se contente d'ajouter une entre au tableau, le Repeater fera le reste
		$data[] = array(
			  'feat'=>1,
			  'class'=>'',
			  'level'=>$repeater->CustomData
		);
		# on reinitialise le Repeater et on le remplit à nouveau
		$repeater->reset();
		$repeater->DataSource = $data;
		$repeater->dataBind();
		
		# on refait le rendu du Panel afin d'afficher le nouveau prérequis
		if($param!=null) $panel->render($param->getNewWriter());
	}
	
	public function removeFeat($sender, $param) {
		$tab = explode("/", $sender->CustomData);
		$featId = $tab[0];
		$level = $tab[1];
		$data = array();
		//get the level row
		foreach ($this->ClassLevelBonusRepeater->Items as $item) {
			if ($item->FeatRepeater->CustomData == $level) {
				$repeater = $item->FeatRepeater;
				$panel = $item->FeatPanel;
				break;
			}
		}
		//fill the data
		foreach($repeater->getItems() as $i) {
			if ($i->FeatList->SelectedValue != $featId && $i->FeatList->CustomData == $level) {
				$data[] = array(
					'feat'=>$i->FeatList->SelectedValue,
					'class'=>'',
					'level'=>$repeater->CustomData	  
				);
			}
		}
		
		// on reinitialise le Repeater et on le remplit à nouveau
		$repeater->reset();
		$repeater->DataSource = $data;
		$repeater->dataBind();
		
		// on refait le rendu du Panel afin d'afficher le nouveau prérequis
		if($param!=null) $panel->render($param->getNewWriter());
	}
}
?>