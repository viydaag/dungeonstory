<?php
class Editfeat extends DSPage
{
    public function onInit($param) {
        parent::onInit($param);

        $featRecord = $this->Feat;
		
        if(!$this->User->IsAdmin) {
            throw new DSException(500, 'admin_view_disallowed');
		}
 
        if(!$this->IsPostBack) {		
            $this->NameEdit->Text = $featRecord->name;
            $this->DescriptionEdit->Text = $featRecord->description;
			$this->TypeEdit->SelectedValue = $featRecord->type;
			
			$data = array();
			$featPreList = $featRecord->prerequisites;
			foreach($featPreList as $i) {
				if ($i->type == 0) {
					$value = NULL;
				} else if ($i->type == 1) {
					$value = $i->abilityValue;
				} else if ($i->type == 2) {
					$value = $i->baseAttackBonus;
				} else if ($i->type == 3) {
					$value = $i->level;
				} else if ($i->type == FeatPrerequisiteRecord::CLASSE) {
					$value = NULL;
				}
				$data[] = array(
				  'id'=>$i->id,
				  'type'=>$i->type,
				  'feat'=>$i->featPrerequisiteId,
				  'class'=>$i->classId,
				  'ability'=>$i->abilityId,
				  'value'=>$value  
				);
			}
			$this->FeatPrerequisiteRepeater->DataSource = $data;
			$this->FeatPrerequisiteRepeater->dataBind();	
		}
    }
 
    public function saveButtonClicked($sender, $param) {
        if ($this->IsValid) {
			$finder = FeatRecord::finder();
			$finder->DbConnection->Active = true;
			$transaction = $finder->DbConnection->beginTransaction();

			try {
				$featRecord = $this->Feat;
				$featRecord->name = $this->NameEdit->SafeText;
				$featRecord->description = $this->DescriptionEdit->SafeText;
				$featRecord->type = $this->TypeEdit->SelectedValue;
				$featRecord->save();
				
				//delete all actual prerequisites
				$featId = $this->Feat->id;
				FeatPrerequisiteRecord::finder()->deleteBy_featId($featId);
				
				//save new prerequisites
				foreach($this->FeatPrerequisiteRepeater->getItems() as $i) {
					$featPre = new FeatPrerequisiteRecord();
					$featPre->featId = $featRecord->id;
					$type = $i->PrerequisiteTypeList->SelectedValue;
					$featPre->type = $type;

					//Charactéristique
					if ($type == 0) {
						$featPre->featPrerequisiteId = $i->FeatList->SelectedValue;
					//Attribut
					} else if ($type == 1) {
						$featPre->abilityId = $i->AbilityList->SelectedValue;
						$featPre->abilityValue = $i->Value->SafeText;
					//Bonus d'attaque
					} else if ($type == 2) {
						$featPre->baseAttackBonus = $i->Value->SafeText;
					//Niveau
					} else if ($type == 3) {
						$featPre->level = $i->Value->SafeText;
					//Classe
					} else if ($type == FeatPrerequisiteRecord::CLASSE) {
						$featPre->classId = $i->ClassList->SelectedValue;
					}
					$featPre->save();
				}
				$transaction->commit();
			} catch(Exception $e) {
				$transaction->rollBack();
				throw new DSException(500, $e->getMessage());
			}		
			
            $this->goToPage('admin.ListFeat');
        }
    }
	
	public function deleteButtonClicked($sender, $param) {
		$finder = FeatPrerequisiteRecord::finder();
		$preList = $finder->findAllBy_featId($this->Feat->id);
		
		foreach ($preList as $pre) {
			$pre->delete();
		}
		
		$this->Feat->delete();
		
		$this->goToPage('admin.ListFeat');
	}
	
	public function prerequisiteTypeChanged($sender, $param) {		
		foreach($this->FeatPrerequisiteRepeater->getItems() as $i) {
			if ($sender->CustomData == $i->PrerequisiteTypeList->CustomData) {
				//Charactéristique
				if ($sender->SelectedItem->Value == 0) {		
					$i->FeatList->Display = "Fixed";
					$i->AbilityList->Display = "Hidden";
					$i->Value->Display = "Hidden";
					$i->ClassList->Display = "Hidden";
				//Attribut
				} else if ($sender->SelectedValue == 1) {
					$i->FeatList->Display = "Hidden";
					$i->AbilityList->Display = "Fixed";
					$i->Value->Display = "Fixed";
					$i->ClassList->Display = "Hidden";
				//Bonus d'attaque
				} else if ($sender->SelectedItem->Value == 2) {
					$i->FeatList->Display = "Hidden";
					$i->AbilityList->Display = "Hidden";
					$i->Value->Display = "Fixed";
					$i->ClassList->Display = "Hidden";
				//Niveau
				} else if ($sender->SelectedItem->Value == 3) {
					$i->FeatList->Display = "Hidden";
					$i->AbilityList->Display = "Hidden";
					$i->Value->Display = "Fixed";
					$i->ClassList->Display = "Hidden";
				//Classe
				} else if ($sender->SelectedItem->Value == FeatPrerequisiteRecord::CLASSE) {
					$i->FeatList->Display = "Hidden";
					$i->AbilityList->Display = "Hidden";
					$i->Value->Display = "Hidden";
					$i->ClassList->Display = "Fixed";
				}
			}
		}
		if($param!=null) $this->MyPanel->render($param->getNewWriter());
	}	
	
	public function addPrerequisite($sender, $param) {
		# on lit les valeurs deja saisies dans les TextBox
		$n = 0;
		$data = array();
		foreach($this->FeatPrerequisiteRepeater->getItems() as $i) {
			$data[] = array(
			  'id'=>$n++,
			  'type'=>$i->PrerequisiteTypeList->SelectedValue,
			  'feat'=>$i->FeatList->SelectedValue,
			  'class'=>$i->ClassList->SelectedValue,
			  'ability'=>$i->AbilityList->SelectedValue,
			  'value'=>$i->Value->SafeText			  
			);
		}
		# on ajoute un nouveau prérequis, en fait on se contente d'ajouter une entre au tableau, le Repeater fera le reste
		$data[] = array(
			  'id'=>$n,
			  'type'=>0,
			  'feat'=>'',
			  'class'=>'',
			  'ability'=>'',
			  'value'=>''	
			);
		# on reinitialise le Repeater et on le remplit à nouveau
		$this->FeatPrerequisiteRepeater->reset();
		$this->FeatPrerequisiteRepeater->DataSource = $data;
		$this->FeatPrerequisiteRepeater->dataBind();
		
		# on refait le rendu du Panel afin d'afficher le nouveau prérequis
		if($param!=null) $this->MyPanel->render($param->getNewWriter());
	}
	
	public function supprimerTextBox($sender, $param) {
		//$n = 0;
		$data = array();
		foreach($this->FeatPrerequisiteRepeater->getItems() as $i) {
			if ($sender->CustomData != $i->Supprimer->Customdata) {
				$data[] = array(
				  'id'=>$i->Supprimer->Customdata,
				  'type'=>$i->PrerequisiteTypeList->SelectedValue,
				  'feat'=>$i->FeatList->SelectedValue,
				  'class'=>$i->ClassList->SelectedValue,
				  'ability'=>$i->AbilityList->SelectedValue,
				  'value'=>$i->Value->SafeText			  
				);
			}
		}
		$this->FeatPrerequisiteRepeater->reset();
		$this->FeatPrerequisiteRepeater->DataSource = $data;
		$this->FeatPrerequisiteRepeater->dataBind();
		if($param!=null) $this->MyPanel->render($param->getNewWriter());
	}
 
    protected function getFeat() {
        // l'ID du message devant être modifié passé par un paramètre GET 
        $featID = (int)$this->Request['featId'];
        // utilise Active Record pour lire le message correspondant à cet ID
        $featRecord = FeatRecord::finder()->findByPk($featID);
        if ($featRecord === null) {
            throw new DSException(500, 'feat_id_invalid', $featID);
		}
        return $featRecord;
    }
}
?>