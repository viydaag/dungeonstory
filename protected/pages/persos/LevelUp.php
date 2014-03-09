<?php
class LevelUp extends DSPage
{
	private $_classe;
	private $_nextLevel;
	private $_classLevelBonus;
	
	public function onInit($param) 
	{
		parent::onInit($param);
        if (!$this->IsPostBack)
        {
        	$perso = $this->User->Perso;
            $this->PersoMultiView->ActiveView = $this->ClasseView;
            $this->_nextLevel = $perso->niveau + 1;

			$classefinder = ClasseRecord::finder();
			$classes = $classefinder->findAll();
			$this->ClasseList->DataSource = $classes;
			$this->ClasseList->dataBind();

			$this->RepeaterAbility->DataSource = $this->getBaseAbilityData();
			$this->RepeaterAbility->dataBind();
			$this->setAbilityData($this->getBaseAbilityData());

			$skillfinder = SkillRecord::finder();
			//$skills = $skillfinder->withPersoSkills('persoId = ?', $this->User->Perso->id)->findAll();
			$skills = $skillfinder->findAll();
			$this->SkillTable->DataSource = $skills;
			$this->SkillTable->dataBind();
			//$this->SkillTable->PersoSkillRepeater->DataSource="<%# $this->Parent->Data->persoSkills %>"
		}
	}
	
	public function next($sender, $param) 
	{
		if ($this->IsValid)
		{
			$this->_classe = ClasseRecord::finder()->findByPk($this->ClasseList->SelectedValue);
			if ($this->ClasseView->Active && $this->hasBonusAbilityScore()) 
			{
				$this->PersoMultiView->ActiveView = $this->AbilityView;
				$this->PointAttribut->Text = '1';
			}
			else if ($this->ClasseView->Active || $this->AbilityView->Active) 
			{
				$this->PersoMultiView->ActiveView = $this->SkillsView;
				$this->resetSkillPoints();
				$this->resetClassSkills();
			}
			else if ($this->SkillsView->Active && ($this->hasLevelBonusFeat() || $this->getClassLevelBonusFeats()))
			{
				$this->PersoMultiView->ActiveView = $this->FeatsView;
				if ($this->hasLevelBonusFeat())
				{
					$this->Feats->setData1($this->getAvailableFeats());
					$this->Feats->setData2(array());
				}
				$this->LevelFeats->DataSource = $this->getClassLevelBonusFeats();
				$this->LevelFeats->databind();
				$this->AssignedFeats->DataSource = $this->User->Perso->feats;
				$this->AssignedFeats->databind();
			}
			else
			{
				$this->RepeaterResumeAbility->dataSource = $this->getAbilityData();
				$this->RepeaterResumeAbility->databind();
				$this->fillSkillSummary();
				$this->fillFeatSummary();
				$this->PersoMultiView->ActiveView = $this->ResumeView;
			}
		}
	}
	
	public function back($sender, $param) 
	{
		if ($this->ResumeView->Active) 
		{
			$this->_classe = NULL;
			$this->PersoMultiView->ActiveView = $this->ClasseView;
		}
	}
	
	public function saveButtonClicked($sender, $param) 
	{
		$persoRecord = $this->User->Perso;
		$this->_classe = ClasseRecord::finder()->findByPk($this->ClasseList->SelectedValue);
		$persoRecord->vie += ($this->_classe->vie + $persoRecord->getAttributeBonus($persoRecord->constitution));		
		$persoRecord->niveau += 1;
		$persoRecord->save();
		
		$persoClasseRecord = PersoClasseRecord::finder()->findBy_persoId_and_classeId($persoRecord->id, $this->Classe->id);
		if ($persoClasseRecord === NULL) 
		{
			$persoClasseRecord = new PersoClasseRecord;
			$persoClasseRecord->persoId = $persoRecord->id;
			$persoClasseRecord->classeId = $this->Classe->id;
			$persoClasseRecord->niveauClasse = 1;
		} 
		else 
		{
			$persoClasseRecord->niveauClasse += 1;
		}
		$persoClasseRecord->save();
		$this->goToPage('persos.ViewPerso', array('persoId'=>$persoRecord->id));	
	}
	
	public function getClasse() 
	{
		return $this->_classe;
	}

	public function getClassLevel() 
	{
		return $this->User->Perso->getNextClassLevel($this->_classe->id);
	}

	public function getClassLevelBonus()
	{
		return ClassLevelBonusRecord::finder()->findBy_classId_and_levelId($this->Classe->id, $this->ClassLevel); 
	}

	public function getClassLevelBonusFeats()
	{
		return ClassLevelBonusFeatRecord::finder()->findAllBy_classId_and_levelId($this->Classe->id, $this->ClassLevel); 
	}

	public function hasBonusAbilityScore()
	{
		return $this->User->Perso->getNextLevel()->bonusAbilityScore > 0;
	}

	public function hasLevelBonusFeat()
	{
		return $this->User->Perso->getNextLevel()->bonusFeat > 0;
	}

	public function modifyScore($sender, $param) 
	{
		$score = 1;

		if (strcmp($param->CommandName, "add") == 0) 
		{
			$score = intval($this->PointAttribut->Text) - 1;
	
			foreach ($this->RepeaterAbility->getItems() as $i) 
			{
				if ($sender->CustomData != $i->SubButton->CustomData) 
				{
					$i->SubButton->Enabled = false;
				}
				else
				{
					$i->Ability->Text = intval($i->Ability->Text) + 1;
					$i->SubButton->Enabled = true;
					$this->saveAbilityData($i->SubButton->CustomData, $i->Ability->Text);
				}
				if ($score <= 0)
				{
					$i->AddButton->Enabled = false;
				}
			}
		}
		else if (strcmp($param->CommandName, "sub") == 0) 
		{
			$score = intval($this->PointAttribut->Text) + 1;

			if ($score > 0)
			{
				foreach ($this->RepeaterAbility->getItems() as $i) 
				{
					if ($sender->CustomData == $i->SubButton->CustomData) 
					{
						$i->Ability->Text = intval($i->Ability->Text) - 1;
					}
					
					DSHelper::disableObjectOnValue($i->AddButton, intval($i->Ability->Text), NULL, intval($i->Ability->Text) + $score);
					DSHelper::disableObjectOnValue($i->SubButton, intval($i->Ability->Text), intval($i->Ability->Text), NULL);
				}
			}
		}

		$this->PointAttribut->Text = $score;
	}

	public function getBaseAbilityData()
	{
		return array(
            array('id'=>1, 'abilityId'=>'Force', 'value'=>$this->User->Perso->getTotalForce()),
            array('id'=>2, 'abilityId'=>'Constitution', 'value'=>$this->User->Perso->getTotalConstitution()),
            array('id'=>3, 'abilityId'=>'Dexterity', 'value'=>$this->User->Perso->getTotalDexterite()),
            array('id'=>4, 'abilityId'=>'Intelligence', 'value'=>$this->User->Perso->getTotalIntelligence()),
            array('id'=>5, 'abilityId'=>'Wisdom', 'value'=>$this->User->Perso->getTotalSagesse()),
            array('id'=>6, 'abilityId'=>'Charisma', 'value'=>$this->User->Perso->getTotalCharisme())
        );
	}

	public function saveAbilityData($id, $value)
	{
		$data = $this->getAbilityData();
		foreach ($data as &$ability) 
		{
			if ($ability['id'] == $id)
			{
				$ability['value'] = $value;
				break;
			}
		}
		$this->setAbilityData($data);
	}

	public function getAbilityData()
	{
		return $this->getViewState('AbilityData');
	}

	public function setAbilityData($data)
	{
		return $this->setViewState('AbilityData', $data);
	}

	protected function calculateRemainingSkillPoints() 
	{
		$perso = $this->User->Perso;
		$classeId = $this->ClasseList->SelectedValue;
		$classeRecord = ClasseRecord::finder()->findByPk($classeId);
		$modifier = $perso->getAttributeBonus($perso->getTotalIntelligence());
		switch ($classeRecord->nom) 
		{
			case "Guerrier":
			case "Clerc":
			case "Paladin":
			case "Sorcier":
			case "Mage":
				$points = 2 + $modifier;
				break;
			case "Barbare":
			case "Druide":
			case "Moine":
				$points = 4 + $modifier;
				break;
			case "Barde":
			case "Rodeur":
				$points = 6 + $modifier;
				break;
			case "Roublard":
				$points = 8 + $modifier;
				break;
			default :
				$points = 0;
		}
		if ($points <= 0)
		{
			$points = 1;
		}
		$this->RemainingPoints->Text = $points;
	}

	public function resetSkillPoints() 
	{
		$this->calculateRemainingSkillPoints();
		foreach($this->SkillTable->Items as $item) 
		{
			$skillId = $this->SkillTable->DataKeys->itemAt($item->ItemIndex);
			$skillRanks = PersoSkillRecord::finder()->findAllBy_persoId_and_skillId($this->User->Perso->id, $skillId);
			$item->SkillRankColumn->PersoSkillsRepeater->DataSource = $skillRanks;
			$item->SkillRankColumn->PersoSkillsRepeater->dataBind();
			$this->enableButton(true, $item->addSkillColumn->Button);
			$this->enableButton(false, $item->subSkillColumn->Button);
			//$item->addSkillColumn->Enabled = true;
			//$item->subSkillColumn->Enabled = false;
		}
	}

	protected function resetClassSkills() 
	{
		foreach ($this->SkillTable->Items as $item) 
		{
			$skillId = $this->SkillTable->DataKeys->itemAt($item->ItemIndex);
			$skill = SkillRecord::finder()->findByPk($skillId);
			
			if ($skill->isClassSkill($this->ClasseList->SelectedValue)) 
			{
            	$item->SkillClassColumn->SkillClassLabel->Text = 'Compétence de classe';
			} 
			else 
			{
				$item->SkillClassColumn->SkillClassLabel->Text = '';
			}
		}
	}

	public function dataBindSkillRepeater($sender, $param)
	{
		if ($param->Item->ItemType=='Item' || $param->Item->ItemType=='AlternatingItem') 
		{
			$item = $param->Item;

			$skillRanks = PersoSkillRecord::finder()->findAllBy_persoId_and_skillId($this->User->Perso->id, $item->Data->id);
			//$skills = $skillfinder->withPersoSkills('persoId = ?', $this->User->Perso->id)->findAll();

			$item->SkillRankColumn->PersoSkillsRepeater->DataSource = $skillRanks;
			$item->SkillRankColumn->PersoSkillsRepeater->dataBind();
			$this->enableButton(false, $item->subSkillColumn->Button);
			//$item->subSkillColumn->Enabled = false;
		}
	}

	public function modifySkillPoints($sender, $param) 
	{
		$item = $param->Item;
		if ($item instanceof TDataGridPager) return;
		$levelRecord = $this->User->Perso->getNextLevel();
		if (strcmp($param->CommandName, "addSkill") == 0)
		{
			$this->addSkill($item);
			
			foreach($this->SkillTable->getItems() as $i) 
			{
				$skillRecord = SkillRecord::finder()->findByPk($this->SkillTable->DataKeys->itemAt($i->ItemIndex));
				$isClassSkill = $skillRecord->isClassSkill($this->ClasseList->SelectedValue);

				if (intval($this->RemainingPoints->Text) <= 0 || 
					($isClassSkill && intval($i->SkillRankColumn->PersoSkillsRepeater->Items[0]->SkillRank->Text) >= $levelRecord->maxClassSkillRanks) ||
					(intval($this->RemainingPoints->Text) <= 1 && !$isClassSkill) ||
					(!$isClassSkill && intval($i->SkillRankColumn->PersoSkillsRepeater->Items[0]->SkillRank->Text) >= $levelRecord->maxCrossClassSkillRanks))
				{
					//$i->addSkillColumn->Enabled = false;
					$this->enableButton(false, $i->addSkillColumn->Button);
				}
				if ($i === $item)
				{
					//$i->subSkillColumn->Button->Enabled = true;
					$this->enableButton(true, $i->subSkillColumn->Button);
				}				
			}
		} 
		else if (strcmp($param->CommandName, "subSkill") == 0) 
		{
			$this->subSkill($item);

			foreach($this->SkillTable->getItems() as $i) 
			{
				$skillRecord = SkillRecord::finder()->findByPk($this->SkillTable->DataKeys->itemAt($i->ItemIndex));
				$isClassSkill = $skillRecord->isClassSkill($this->ClasseList->SelectedValue);

				if ((intval($this->RemainingPoints->Text) >= 1 && 
						$isClassSkill && 
						intval($i->SkillRankColumn->PersoSkillsRepeater->Items[0]->SkillRank->Text) < $levelRecord->maxClassSkillRanks) 
					||
					(intval($this->RemainingPoints->Text) >= 2 && 
						!$isClassSkill &&
						intval($i->SkillRankColumn->PersoSkillsRepeater->Items[0]->SkillRank->Text) < $levelRecord->maxCrossClassSkillRanks))
				{
					$this->enableButton(true, $i->addSkillColumn->Button);
				}				
			}
		}
	}

	private function enableButton($enabled, $button)
	{
		if ($button instanceof TButton)
		{
			$button->Enabled = $enabled;
			$this->checkButtonColor($button);
		}		
	}

	private function checkButtonColor($button)
	{
		if ($button->Enabled == true)
		{
			$button->BackColor= 'green';
		}
		else
		{
			$button->BackColor= 'red';
		}
	}

	public function addSkill($item) 
	{
		$skillId = $this->SkillTable->DataKeys->itemAt($item->ItemIndex);
		$repeater = $item->SkillRankColumn->PersoSkillsRepeater;
		$list = new TList;
		foreach ($repeater->getItems() as $skillRankItem)
		{
			$persoSkillRecord = PersoSkillRecord::finder()->findBy_persoId_and_skillId($this->User->Perso->id, $skillId);
			$persoSkillRecord->rank = intval($skillRankItem->SkillRank->Text) + 1;
			$list->add($persoSkillRecord);
		}
		$repeater->DataSource = $list;
		$repeater->dataBind();

		$classSkill = ClasseSkillRecord::finder()->findByPk($this->ClasseList->SelectedValue, $skillId);
		if ($classSkill !== null) 
		{  
			$this->RemainingPoints->Text = (int)$this->RemainingPoints->Text - 1;
		} 
		else 
		{
			$this->RemainingPoints->Text = (int)$this->RemainingPoints->Text - 2;
		}		
	}
	
	public function subSkill($item) 
	{
		$skillId = $this->SkillTable->DataKeys->itemAt($item->ItemIndex);
		$repeater = $item->SkillRankColumn->PersoSkillsRepeater;
		$list = new TList;
		foreach ($repeater->getItems() as $skillRankItem)
		{
			$persoSkillRecord = PersoSkillRecord::finder()->findBy_persoId_and_skillId($this->User->Perso->id, $skillId);
			if ($persoSkillRecord->rank >= intval($skillRankItem->SkillRank->Text) - 1)
			{
				$this->enableButton(false, $item->subSkillColumn->Button);
				//$item->subSkillColumn->Enabled = false;
				//$item->subSkillColumn->Style->BackColor = "red";
			}

			$persoSkillRecord->rank = intval($skillRankItem->SkillRank->Text) - 1;
			$list->add($persoSkillRecord);
		}
		$repeater->DataSource = $list;
		$repeater->dataBind();

		$classSkill = ClasseSkillRecord::finder()->findByPk($this->ClasseList->SelectedValue, $skillId);
		if ($classSkill !== null) 
		{  
			$this->RemainingPoints->Text = (int)$this->RemainingPoints->Text + 1;
		} 
		else 
		{
			$this->RemainingPoints->Text = (int)$this->RemainingPoints->Text + 2;
		}
	}

	protected function fillSkillSummary() 
	{
		$data = array();
		foreach ($this->SkillTable->Items as $i) 
		{
			$skillId = $this->SkillTable->DataKeys->itemAt($i->ItemIndex);
			$persoSkillRecord = PersoSkillRecord::finder()->findBy_persoId_and_skillId($this->User->Perso->id, $skillId);
			$rank = intval($i->SkillRankColumn->PersoSkillsRepeater->Items[0]->SkillRank->Text);
			if ($rank > $persoSkillRecord->rank)
			{
				$data[] = array(
				  'id'=>$skillId,
				  'name'=>$i->SkillNameColumn->Text,
				  'rank'=>$rank
				);
			}			
		}
		$this->SkillSummaryTable->DataSource = $data;
		$this->SkillSummaryTable->databind();			
	}

	public function getAvailableFeats() 
	{
		$featfinder = FeatRecord::finder();
		$allFeats = $featfinder->findAll();
		$persoFeats = $this->User->Perso->feats;

		//get only unassigned feats
		$feats = array_diff($allFeats, $persoFeats);
		
		foreach ($feats as $feat) 
		{
			$isOK = true;
			$classes = array();
			foreach ($feat->prerequisites as $pre) 
			{
				if ($pre->type == FeatPrerequisiteRecord::FEAT) 
				{
					if ($this->isFeatAssigned($pre->featPrerequisiteId) == false) 
					{
						$isOK = false;
					}
				} 
				else if ($pre->type == FeatPrerequisiteRecord::ABILITY) 
				{
					$abilityfinder = AbilityRecord::finder();
					$ability = $abilityfinder->findByPk($pre->abilityId);
					$value = 0;
					
					foreach ($this->getAbilityData() as $newAbility)
					{
						if ($ability->name == $newAbility['abilityId'])
						{
							$value = $newAbility['value'];
						}
					}
					
					if ($value < $pre->abilityValue)
					{
						$isOK = false;
						break;
					}
				} 
				else if ($pre->type == FeatPrerequisiteRecord::ATTACK_BONUS) 
				{
					$attackBonus = $this->User->Perso->getAttackBonus();
					if ($attackBonus[0] < $pre->baseAttackBonus)
					{
						$isOk = false;
					}
				}
				else if ($pre->type == FeatPrerequisiteRecord::LEVEL) 
				{
					if ($pre->level > $this->User->Perso->getNextLevel()) 
					{
						$isOK = false;
					}
				} 
				else if ($pre->type == FeatPrerequisiteRecord::CLASSE) 
				{
					//save the class prerequisite in an array to check later if the character fit one of the classes
					$classes[] = $pre;
				}
			}
			//if the feat respects all prerequisites
			if ($isOK) 
			{
				$isClassOK = false;
				//class prerequisites must be respected at least for one class
				if (count($classes) > 0) 
				{
					foreach ($classes as $preClass) 
					{
						if ($preClass->classId == $this->ClasseList->SelectedValue) 
						{
							$isClassOK = true;
							break;
						}
					}
					if ($isClassOK && $this->isFeatAssigned($feat->id) == false) 
					{
						$availableFeats[] = $feat;
					}
				} 
				else if ($this->isFeatAssigned($feat->id) == false)
				{
					$availableFeats[] = $feat;
				}
			}			
		}
		
		return $availableFeats;
	}

	protected function isFeatAssigned($featId)
	{
		$isFeatAssigned = false;

		//check if character as feat
		foreach ($this->User->Perso->feats as $assignedFeat)
		{
			if ($assignedFeat->id == $featId) 
			{
				$isFeatAssigned = true;
				break;
			}
		}
		if (!$isFeatAssigned)
		{
			foreach ($this->getClassLevelBonusFeats() as $classAssignedFeat) 
			{
				if ($classAssignedFeat->featId == $featId) 
				{
					$isFeatAssigned = true;
					break;
				}
			}
		}
		
		return $isFeatAssigned;
	}

	protected function getAssignedFeats()
	{
		$feats = $this->User->Perso->feats;
		$isFeatAssigned = false;

		foreach ($this->getClassLevelBonusFeats() as $classAssignedFeat) 
		{
			foreach ($this->User->Perso->feats as $assignedFeat)
			{
				if ($assignedFeat->id == $classAssignedFeat->featId) 
				{
					$isFeatAssigned = true;
					break;
				}
			}
			if (!$isFeatAssigned)
			{
				$feats[] = $classAssignedFeat->feat;
				$isFeatAssigned = false;
			}
		}

		return $feats;
	}

	protected function fillFeatSummary() 
	{
		$data = array();
		foreach ($this->Feats->List2->Items as $i) 
		{
			$data[] = array(
			  'id'=>$i->Value,
			  'name'=>$i->Text
			);
		}
		foreach ($this->LevelFeats->Items as $i) 
		{
			$data[] = array(
			  'id'=>$i->Value,
			  'name'=>$i->Text
			);
		}
		$this->FeatSummaryTable->DataSource = $data;
		$this->FeatSummaryTable->databind();			
	}

	public function featSelectedChanged($sender, $param) 
	{
		if ($this->isFeatAssigned($sender->SelectedValue)) 
		{
			$this->Feats->AddToList1Button->Enabled = false;
		} 
		else 
		{
			$this->Feats->AddToList1Button->Enabled = true;
		}
	}
}
?>