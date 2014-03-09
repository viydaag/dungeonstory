<?php
class NewFeat extends DSPage
{
	private $alreadyRan = null;

	public function onInit($param) {
        parent::onInit($param);

        if (!$this->User->IsAdmin) {
            throw new DSException(500, 'admin_view_disallowed');
		}
    }

	public function saveButtonClicked($sender, $param) 
	{
        if ($this->IsValid) 
        {
			$finder = FeatRecord::finder();
			$finder->DbConnection->Active = true;
			$transaction = $finder->DbConnection->beginTransaction();

			try 
			{
				$featRecord = new FeatRecord();
				$featRecord->name = $this->Name->SafeText;
				$featRecord->description = $this->Description->SafeText;
				$featRecord->type = $this->TypeList->SelectedValue;
				$featRecord->save();
				
				//save all prerequisite
				foreach($this->FeatPrerequisiteRepeater->getItems() as $i) 
				{
					$featPre = new FeatPrerequisiteRecord();
					$featPre->featId = $featRecord->id;
					$type = $i->PrerequisiteTypeList->SelectedValue;
					$featPre->type = $type;

					//Charactéristique
					if ($type == FeatPrerequisiteRecord::FEAT) 
					{
						$featPre->featPrerequisiteId = $i->FeatList->SelectedValue;
					}
					//Attribut
					else if ($type == FeatPrerequisiteRecord::ABILITY) 
					{
						$featPre->abilityId = $i->AbilityList->SelectedValue;
						$featPre->abilityValue = $i->Value->SafeText;
					} 
					//Bonus d'attaque
					else if ($type == FeatPrerequisiteRecord::ATTACK_BONUS) 
					{
						$featPre->baseAttackBonus = $i->Value->SafeText;
					}
					//Niveau
					else if ($type == FeatPrerequisiteRecord::LEVEL) 
					{
						$featPre->level = $i->Value->SafeText;
					} 
					//Classe
					else if ($type == FeatPrerequisiteRecord::CLASSE) 
					{
						$featPre->classId = $i->ClassList->SelectedValue;
					}
					$featPre->save();
				}
				$transaction->commit();
			} 
			catch(Exception $e) 
			{
				$transaction->rollBack();
				throw new DSException(500, $e->getMessage());
			}		
			
            $this->goToPage('admin.ListFeat');
        }
    }
	
	public function prerequisiteTypeChanged($sender, $param) 
	{		
		foreach($this->FeatPrerequisiteRepeater->getItems() as $i)
		{
			if ($sender->CustomData == $i->PrerequisiteTypeList->CustomData) 
			{
				//Charactéristique
				if ($sender->SelectedItem->Value == 0) 
				{		
					$i->FeatList->Display = "Fixed";
					$i->AbilityList->Display = "Hidden";
					$i->Value->Display = "Hidden";
					$i->ClassList->Display = "Hidden";
				//Attribut
				} else if ($sender->SelectedValue == 1) 
				{
					$i->FeatList->Display = "Hidden";
					$i->AbilityList->Display = "Fixed";
					$i->Value->Display = "Fixed";
					$i->ClassList->Display = "Hidden";
				//Bonus d'attaque
				} else if ($sender->SelectedItem->Value == 2) 
				{
					$i->FeatList->Display = "Hidden";
					$i->AbilityList->Display = "Hidden";
					$i->Value->Display = "Fixed";
					$i->ClassList->Display = "Hidden";
				//Niveau
				} else if ($sender->SelectedItem->Value == 3) 
				{
					$i->FeatList->Display = "Hidden";
					$i->AbilityList->Display = "Hidden";
					$i->Value->Display = "Fixed";
					$i->ClassList->Display = "Hidden";
				//Classe
				} else if ($sender->SelectedItem->Value == FeatPrerequisiteRecord::CLASSE) 
				{
					$i->FeatList->Display = "Hidden";
					$i->AbilityList->Display = "Hidden";
					$i->Value->Display = "Hidden";
					$i->ClassList->Display = "Fixed";
				}
			}
		}
		if ($param!=null) $this->MyPanel->render($param->getNewWriter());
	}	

	public function addPrerequisite($sender, $param) 
	{
		# on lit les valeurs deja saisies dans les TextBox
		$n = 0;
		$data = array();
		foreach($this->FeatPrerequisiteRepeater->getItems() as $i)
		{
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
		if ($param!=null) $this->MyPanel->render($param->getNewWriter());
	}
	
	public function supprimerTextBox($sender, $param) 
	{
		$n = 0;
		$data = array();
		foreach($this->FeatPrerequisiteRepeater->getItems() as $i) 
		{
			if ($sender->CustomData != $n) 
			{
				$data[] = array(
				  'id'=>$n++,
				  'type'=>$i->PrerequisiteTypeList->SelectedValue,
				  'feat'=>$i->FeatList->SelectedValue,
				  'class'=>$i->ClassList->SelectedValue,
				  'ability'=>$i->AbilityList->SelectedValue,
				  'value'=>$i->Value->SafeText			  
				);
			} 
			else 
			{
				$n++;
			}
		}
		$this->FeatPrerequisiteRepeater->reset();
		$this->FeatPrerequisiteRepeater->DataSource = $data;
		$this->FeatPrerequisiteRepeater->dataBind();
		if ($param!=null) $this->MyPanel->render($param->getNewWriter());
	}

}
?>