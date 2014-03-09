<?php
class NewPerso extends DSPage
{
	public function onPreRender($param) {
		parent::onPreRender($param);
		if (!$this->IsPostBack) {
			//$this->Feats->setData1($this->getAvailableFeats());
			//$this->Feats->setData2(array());
		}
	}

	public function onLoad($param) 
	{
		parent::onLoad($param);
        if (!$this->IsPostBack) 
        {
            $this->PersoMultiView->ActiveView = $this->RaceView;
			
			$alignementfinder = AlignementRecord::finder();
			$alignements = $alignementfinder->findAll();
			$this->Alignement->DataSource = $alignements;
			$this->Alignement->dataBind();
			
			$classefinder = ClasseRecord::finder();
			$classes = $classefinder->findAll();
			$this->ClasseList->DataSource = $classes;
			$this->ClasseList->dataBind();
			$this->ClasseList->SelectedIndex = 0;
			$this->ClasseList->Rows = count($classes);
			
			$racefinder = RaceRecord::finder();
			$races = $racefinder->findAll();
			$this->RaceList->DataSource = $races;
			$this->RaceList->dataBind();
			$this->RaceList->SelectedIndex = 0;
			$this->RaceList->Rows = count($races);
			
			$regionfinder = RegionRecord::finder();
			$regions = $regionfinder->findAll();
			$this->Region->DataSource = $regions;
			$this->Region->dataBind();
			
			$skillfinder = SkillRecord::finder();
			$skills = $skillfinder->findAll();
			$this->SkillTable->DataSource = $skills;
			$this->SkillTable->dataBind();
			
			$this->LevelFeats->DataSource = $this->getClassLevelFeats();
			$this->LevelFeats->databind();
			
			$this->PointAttribut->Text = '20';
			$this->Force->Text = '10';
			$this->Constitution->Text = '10';
			$this->Dexterite->Text = '10';
			$this->Intelligence->Text = '10';
			$this->Sagesse->Text = '10';
			$this->Charisme->Text = '10';
			
			$this->changeRaceDescription($this, null);
			$this->changeClasseDescription($this, null);
			$this->resetSkillPoints();
			
			$this->AgeTextBox->Visible = false;
			$this->TailleTextBox->Visible = false;
			$this->PoidsTextBox->Visible = false;
			
			
		}
	}
	
    public function createButtonClicked($sender, $param) 
    {
        if ($this->IsValid)  
        {
			$user = UserRecord::finder()->findBy_pseudo($this->User->Name);
			if ($user === null) 
			{
				throw new DSException(500, 'profile_not_found');
			}
			
			$finder = PersoRecord::finder();
			$finder->DbConnection->Active = true;
			$transaction = $finder->DbConnection->beginTransaction();

			try 
			{
				// rempli l'objet UserRecord avec les données saisies
				$persoRecord = new PersoRecord;
				
				$persoRecord->niveau = 1;
				$persoRecord->experience = 0;
				$persoRecord->userId = $user->id;
				$persoRecord->aventureId = null;
				
				$persoRecord->raceId = $this->RaceList->SelectedValue;			
				
				$persoRecord->force = $this->Force->Text;
				$persoRecord->dexterite = $this->Dexterite->Text;
				$persoRecord->constitution = $this->Constitution->Text;
				$persoRecord->intelligence = $this->Intelligence->Text;
				$persoRecord->sagesse = $this->Sagesse->Text;
				$persoRecord->charisme = $this->Charisme->Text;
				
				$persoRecord->nom = $this->Nom->Text;
				$persoRecord->sexe = $this->Sexe->SelectedValue;
				$persoRecord->alignementId = $this->Alignement->SelectedValue;
				$persoRecord->regionId = $this->Region->SelectedValue;
				$persoRecord->age = (int)$this->AgeTextBox->Text;
				$persoRecord->taille = (int)$this->TailleTextBox->Text;
				$persoRecord->poids = (int)$this->PoidsTextBox->Text;
				$persoRecord->age = (int)$this->AgeTextBox->Text;
				$persoRecord->apparence = $this->ApparenceTextBox->Text;
				$persoRecord->personnalite = $this->PersonnaliteTextBox->Text;
				$persoRecord->histoire = $this->BackgroundTextBox->Text;			
					
				foreach($this->ImagePortlet->ImageRepeater->Items as $item) 
				{			
					if ($item->RadioButton->Checked)
					{
						$persoRecord->image = $item->RadioButton->InputAttributes['value'];
					}
				}
				
				$classeRecord = ClasseRecord::finder()->findByPk($this->ClasseList->SelectedValue);
				$persoRecord->vie += ($classeRecord->vie + $persoRecord->getAttributeBonus($this->Constitution->Text));
	 
				// l'enregistre dans la base de données par la méthode save de l'Active Record
				$persoRecord->save();
				
				//enregistre la première classe du perso
				$persoClasseRecord = new PersoClasseRecord;
				$persoClasseRecord->persoId = $persoRecord->id;
				$persoClasseRecord->classeId = $this->ClasseList->SelectedValue;
				$persoClasseRecord->niveauClasse = 1;
				$persoClasseRecord->save();
				
				//enregistre les skills avec leur rang
				foreach($this->SkillTable->Items as $skill) 
				{
					$skillId = $this->SkillTable->DataKeys->itemAt($skill->ItemIndex);
					$persoSkillRecord = new PersoSkillRecord;
					$persoSkillRecord->persoId = $persoRecord->id;
					$persoSkillRecord->skillId = $skillId;
					$persoSkillRecord->rank = intval($skill->SkillPointsColumn->SkillPoints->Text);
					//$ps = PersoSkillRecord::finder()->findByPk($persoRecord->id, $skillId);
					$persoSkillRecord->save();
				}
				
				//enregitre les feats sélectionnés
				foreach($this->Feats->List2->Items as $feat) 
				{
					$persoFeatRecord = new PersoFeatRecord;
					$persoFeatRecord->persoId = $persoRecord->id;
					$persoFeatRecord->featId = $feat->Value;
					$persoFeatRecord->save();
				}
				foreach ($this->LevelFeats->Items as $feat) 
				{
					$persoFeatRecord = new PersoFeatRecord;
					$persoFeatRecord->persoId = $persoRecord->id;
					$persoFeatRecord->featId = $feat->Value;
					$persoFeatRecord->save();
				}
				
				//end transaction
				$transaction->commit();
			} 
			catch(Exception $e) 
			{
				$transaction->rollBack();
				throw new DSException(500, $e->getMessage());
			}	
			
            // redirige l'utilisateur vers la page d'accueil
			$this->goToPage('persos.ViewPerso', array('persoId'=>$persoRecord->id));
        }
    }
	
	public function next($sender, $param) 
	{
		if ($this->RaceView->Active) 
		{
			$this->PersoMultiView->ActiveView = $this->ClasseView;
		} 
		else if ($this->ClasseView->Active) 
		{
			$this->PersoMultiView->ActiveView = $this->AttributView;
			$this->changeRaceModifier();
		} 
		else if ($this->AttributView->Active) 
		{
			$this->PersoMultiView->ActiveView = $this->SkillsView;
			$this->calculateRemainingSkillPoints();
			$this->resetSkillPoints();
			$this->resetClassSkills();
		} 
		else if ($this->SkillsView->Active) 
		{
			$this->PersoMultiView->ActiveView = $this->FeatsView;
			$this->Feats->setData1($this->getAvailableFeats());
			$this->Feats->setData2(array());
		}
		else if ($this->FeatsView->Active) 
		{
			$this->PersoMultiView->ActiveView = $this->DetailsView;
		}
		else if ($this->DetailsView->Active) 
		{
			$this->PersoMultiView->ActiveView = $this->ImageView;
			$race = RaceRecord::finder()->findByPk($this->RaceList->SelectedValue);
			$this->ImagePortlet->generateImageView($this->Sexe->SelectedValue, $race->nom);
		}
		else if ($this->ImageView->Active) 
		{
			$this->PersoMultiView->ActiveView = $this->ResumeView;
			$this->fillSkillSummary();
			$this->fillFeatSummary();
		}
	}
	
	public function back($sender, $param) {
		if ($this->ClasseView->Active) {
			$this->PersoMultiView->ActiveView = $this->RaceView;
		} else if ($this->AttributView->Active) {
			$this->PersoMultiView->ActiveView = $this->ClasseView;
		} else if ($this->SkillsView->Active) {
			$this->PersoMultiView->ActiveView = $this->AttributView;
		} else if ($this->FeatsView->Active) {
			$this->PersoMultiView->ActiveView = $this->SkillsView;
		} else if ($this->DetailsView->Active) {
			$this->PersoMultiView->ActiveView = $this->FeatsView;
		} else if ($this->ImageView->Active) {
			$this->PersoMultiView->ActiveView = $this->DetailsView;
		} else if ($this->ResumeView->Active) {
			$this->PersoMultiView->ActiveView = $this->ImageView;
		}
	}
	
	public function changeRaceDescription($sender, $param) {
		$raceId = $this->RaceList->SelectedValue;
		$race = RaceRecord::finder()->findByPk($raceId);
		$this->RaceDescription->Text = $race->descriptionCourte;
	}
	
	public function changeClasseDescription($sender, $param) {
		$classeId = $this->ClasseList->SelectedValue;
		$classe = ClasseRecord::finder()->findByPk($classeId);
		$this->ClasseDescription->Text = $classe->descriptionCourte;
	}
	
	public function rollAge($sender, $param) {
		if ($this->isValid) {
			$raceId = $this->RaceList->SelectedValue;
			$race = RaceRecord::finder()->findByPk($raceId);
			$dice = new DSDice($race->modifAge);
			$result = $dice->roll();
			$age = (int)$this->BaseAgeTextBox->Text + $result;
			$this->AgeTextBox->Text = $age;
			$this->AgeTextBox->Visible = true;
			$this->BaseAgeTextBox->Enabled = false;
			$this->RollAge->Enabled = false;
		}
	}
	
	public function rollTaille($sender, $param) {
		if ($this->isValid) {
			$raceId = $this->RaceList->SelectedValue;
			$race = RaceRecord::finder()->findByPk($raceId);
			$dice = new DSDice($race->modifTaille);
			$result = $dice->roll();
			$taille = (int)$this->BaseTailleTextBox->Text + $result;
			$this->TailleTextBox->Text = $taille;
			$this->TailleTextBox->Visible = true;
			$this->BaseTailleTextBox->Enabled = false;
			$this->RollTaille->Enabled = false;
		}
	}
	
	public function rollPoids($sender, $param) {
		if ($this->isValid) {
			$raceId = $this->RaceList->SelectedValue;
			$race = RaceRecord::finder()->findByPk($raceId);
			$dice = new DSDice($race->modifPoids);
			$result = $dice->roll();
			$diffTaille = (int)$this->TailleTextBox->Text - (int)$this->BaseTailleTextBox->Text;
			$poids = (int)$this->BasePoidsTextBox->Text + ($result * $diffTaille);
			$this->PoidsTextBox->Text = $poids;
			$this->PoidsTextBox->Visible = true;
			$this->BasePoidsTextBox->Enabled = false;
			$this->RollPoids->Enabled = false;
		}
	}
	
	public function modifyScore($sender, $param) 
	{
		if (strcmp($param->CommandName, "add") == 0) 
		{
			$this->PointAttribut->Text = intval($this->PointAttribut->Text) - 1;
			switch ($param->CommandParameter) 
			{
				case "force":
    				$this->Force->Text = intval($this->Force->Text) + 1;
					$this->disableObjectOnValue($this->SubForceButton, $this->Force->Text, 3, NULL);
    				break;
				case "constitution":
    				$this->Constitution->Text = intval($this->Constitution->Text) + 1;
					$this->disableObjectOnValue($this->SubConstButton, $this->Constitution->Text, 3, NULL);
    				break;
				case "dexterite":
    				$this->Dexterite->Text = intval($this->Dexterite->Text) + 1;
					$this->disableObjectOnValue($this->SubDexButton, $this->Dexterite->Text, 3, NULL);
    				break;
				case "intelligence":
    				$this->Intelligence->Text = intval($this->Intelligence->Text) + 1;
					$this->disableObjectOnValue($this->SubIntelButton, $this->Intelligence->Text, 3, NULL);
    				break;
				case "sagesse":
    				$this->Sagesse->Text = intval($this->Sagesse->Text) + 1;
					$this->disableObjectOnValue($this->SubSagesseButton, $this->Sagesse->Text, 3, NULL);	
    				break;
				case "charisme":
    				$this->Charisme->Text = intval($this->Charisme->Text) + 1;
					$this->disableObjectOnValue($this->SubCharismeButton, $this->Charisme->Text, 3, NULL);
    				break;
			} 
		} 
		else if (strcmp($param->CommandName, "sub") == 0) 
		{
			$this->PointAttribut->Text = intval($this->PointAttribut->Text) + 1;
			switch ($param->CommandParameter) 
			{
				case "force":
    				$this->Force->Text = intval($this->Force->Text) - 1;
					$this->disableObjectOnValue($this->SubForceButton, $this->Force->Text, 3, NULL);
    				break;
				case "constitution":
    				$this->Constitution->Text = intval($this->Constitution->Text) - 1;
					$this->disableObjectOnValue($this->SubConstButton, $this->Constitution->Text, 3, NULL);
    				break;
				case "dexterite":
    				$this->Dexterite->Text = intval($this->Dexterite->Text) - 1;
					$this->disableObjectOnValue($this->SubDexButton, $this->Dexterite->Text, 3, NULL);
    				break;
				case "intelligence":
    				$this->Intelligence->Text = intval($this->Intelligence->Text) - 1;;
					$this->disableObjectOnValue($this->SubIntelButton, $this->Intelligence->Text, 3, NULL);	
    				break;
				case "sagesse":
    				$this->Sagesse->Text = intval($this->Sagesse->Text) - 1;
					$this->disableObjectOnValue($this->SubSagesseButton, $this->Sagesse->Text, 3, NULL);	
    				break;
				case "charisme":
    				$this->Charisme->Text = intval($this->Charisme->Text) - 1;
					$this->disableObjectOnValue($this->SubCharismeButton, $this->Charisme->Text, 3, NULL);	
    				break;
			}
		}
		$this->calculateTotal();
		$this->verifyPointRestant();
	}
	
	protected function disableObjectOnValue($object, $value, $minValue, $maxValue) {
		if ($minValue !== NULL && $maxValue !== NULL) {
			if (intval($value) <= intval($minValue) || intval($value) >= intval($maxValue)) {
				$object->Enabled = false;
			} else {
				$object->Enabled = true;
			}
		} else if ($minValue !== NULL) {
			if (intval($value) <= intval($minValue)) {
				$object->Enabled = false;
			} else {
				$object->Enabled = true;
			}
		} else if ($maxValue !== NULL) {
			if (intval($value) >= intval($maxValue)) {
				$object->Enabled = false;
			} else {
				$object->Enabled = true;
			}
		}
	}
	
	protected function verifyPointRestant()
	{
		if (intval($this->PointAttribut->Text) <= 0) 
		{
			$this->AddForceButton->Enabled = false;
			$this->AddConstButton->Enabled = false;
			$this->AddDexButton->Enabled = false;
			$this->AddIntelButton->Enabled = false;
			$this->AddSagesseButton->Enabled = false;
			$this->AddCharismeButton->Enabled = false;
		} 
		else 
		{
			$this->AddForceButton->Enabled = true;
			$this->AddConstButton->Enabled = true;
			$this->AddDexButton->Enabled = true;
			$this->AddIntelButton->Enabled = true;
			$this->AddSagesseButton->Enabled = true;
			$this->AddCharismeButton->Enabled = true;
		}		
	}
	
	protected function changeRaceModifier() {
		$raceId = $this->RaceList->SelectedValue;
		$raceRecord = RaceRecord::finder()->findByPk($raceId);
		$this->ForceModif->Text = $raceRecord->modifForce;
		$this->ConstitutionModif->Text = $raceRecord->modifConstitution;
		$this->DexteriteModif->Text = $raceRecord->modifDexterite;
		$this->IntelligenceModif->Text = $raceRecord->modifIntelligence;
		$this->SagesseModif->Text = $raceRecord->modifSagesse;
		$this->CharismeModif->Text = $raceRecord->modifCharisme;
		$this->calculateTotal();
	}
	
	protected function calculateTotal() {
		$this->ForceTotal->Text = intval($this->Force->Text) + intval($this->ForceModif->Text);
		$this->ConstitutionTotal->Text = intval($this->Constitution->Text) + intval($this->ConstitutionModif->Text);
		$this->DexteriteTotal->Text = intval($this->Dexterite->Text) + intval($this->DexteriteModif->Text);
		$this->IntelligenceTotal->Text = intval($this->Intelligence->Text) + intval($this->IntelligenceModif->Text);
		$this->SagesseTotal->Text = intval($this->Sagesse->Text) + intval($this->SagesseModif->Text);
		$this->CharismeTotal->Text = intval($this->Charisme->Text) + intval($this->CharismeModif->Text);
	}
	
	protected function calculateRemainingSkillPoints() {
		$classeId = $this->ClasseList->SelectedValue;
		$classeRecord = ClasseRecord::finder()->findByPk($classeId);
		if ((int)$this->IntelligenceTotal->Text < 10) {
			$intBonus = 0;
		} else {
			$intBonus =  floor((int)$this->IntelligenceTotal->Text / 2) - 5;
		}
		switch ($classeRecord->nom) {
			case "Guerrier":
			case "Clerc":
			case "Paladin":
			case "Sorcier":
			case "Mage":
				$points = (2 + $intBonus) * 4;
				break;
			case "Barbare":
			case "Druide":
			case "Moine":
				$points = (4 + $intBonus) * 4;
				break;
			case "Barde":
			case "Rodeur":
				$points = (6 + $intBonus) * 4;
				break;
			case "Roublard":
				$points = (8 + $intBonus) * 4;
				break;
			default :
				$points = 0;
		}
		$this->RemainingPoints->Text = $points;
	}
	
	public function modifySkillPoints($sender, $param) {
		$item = $param->Item;
		if ($item instanceof TDataGridPager) return;
		if (strcmp($param->CommandName, "addSkill") == 0) {
			$this->addSkill($item);
		} else if (strcmp($param->CommandName, "subSkill") == 0) {
			$this->subSkill($item);
		}
	}
	
	public function addSkill($item) {
		$skillId = $this->SkillTable->DataKeys->itemAt($item->ItemIndex);
		$levelRecord = LevelRecord::finder()->findByPk(1);
		$item->SkillPointsColumn->SkillPoints->Text = (int)$item->SkillPointsColumn->SkillPoints->Text + 1;
		$this->verifyRemainingSkillPoints();
		$classSkill = ClasseSkillRecord::finder()->findByPk($this->ClasseList->SelectedValue, $skillId);
		if ($classSkill !== null) {  
			$this->RemainingPoints->Text = (int)$this->RemainingPoints->Text - 1;
			$this->disableObjectOnValue($item->subSkillColumn, $item->SkillPointsColumn->SkillPoints->Text, 0, NULL);
			$this->disableObjectOnValue($item->addSkillColumn, $item->SkillPointsColumn->SkillPoints->Text, NULL, $levelRecord->maxClassSkillRanks);
		} else {
			$this->RemainingPoints->Text = (int)$this->RemainingPoints->Text - 2;
			$this->disableObjectOnValue($item->subSkillColumn, $item->SkillPointsColumn->SkillPoints->Text, 0, NULL);
			$this->disableObjectOnValue($item->addSkillColumn, $item->SkillPointsColumn->SkillPoints->Text, NULL, $levelRecord->maxCrossClassSkillRanks);
		}
		//$skillfinder = SkillRecord::finder();
			//$skills = $skillfinder->findAll();
			//$this->SkillTable->DataSource = $skills;
			//$this->SkillTable->dataBind();
	}
	
	public function subSkill($item) {
		$skillId = $this->SkillTable->DataKeys->itemAt($item->ItemIndex);
		$levelRecord = LevelRecord::finder()->findByPk(1);
		$item->SkillPointsColumn->SkillPoints->Text = (int)$item->SkillPointsColumn->SkillPoints->Text - 1;
		$this->verifyRemainingSkillPoints();
		$classSkill = ClasseSkillRecord::finder()->findByPk($this->ClasseList->SelectedValue, $skillId);
		if ($classSkill !== null) {  
			$this->RemainingPoints->Text = (int)$this->RemainingPoints->Text + 1;
			$this->disableObjectOnValue($item->subSkillColumn, $item->SkillPointsColumn->SkillPoints->Text, 0, NULL);
			$this->disableObjectOnValue($item->addSkillColumn, $item->SkillPointsColumn->SkillPoints->Text, NULL, $levelRecord->maxClassSkillRanks);
		} else {
			$this->RemainingPoints->Text = (int)$this->RemainingPoints->Text + 2;
			$this->disableObjectOnValue($item->subSkillColumn, $item->SkillPointsColumn->SkillPoints->Text, 0, NULL);
			$this->disableObjectOnValue($item->addSkillColumn, $item->SkillPointsColumn->SkillPoints->Text, NULL, $levelRecord->maxCrossClassSkillRanks);
		}
	}
	
	protected function verifyRemainingSkillPoints() {
		if (intval($this->RemainingPoints->Text) <= 0)	{
			foreach($this->SkillTable->Items as $item) {			
				$item->addSkillColumn->Enabled = false;
			}
		} else {
			foreach($this->SkillTable->Items as $item) {			
				$item->addSkillColumn->Enabled = true;
			}
		}
		
	}
	
	protected function resetSkillPoints() {
		foreach($this->SkillTable->Items as $item) {
			$item->SkillPointsColumn->SkillPoints->Text = '0';	
			$this->disableObjectOnValue($item->subSkillColumn, $item->SkillPointsColumn->SkillPoints->Text, 0, NULL);
		}
	}
	
	public function getRace() {
		$raceId = $this->RaceList->SelectedValue;
		$raceRecord = RaceRecord::finder()->findByPk($raceId);
		return $raceRecord;
	}
	
	public function getAvailableFeats() {
		$featfinder = FeatRecord::finder();
		$feats = $featfinder->findAll();		
		$availableFeats = array();
		
		foreach ($feats as $feat) 
		{
			$isOK = true;
			$classes = array();
			foreach ($feat->prerequisites as $pre) 
			{
				if ($pre->type == FeatPrerequisiteRecord::FEAT) {
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
					
					switch ($ability->abbreviation)
					{
						case 'For' : 
							$value = $this->ForceTotal->Text;
							break;
						case 'Con' : 
							$value = $this->ConstitutionTotal->Text;
							break;
						case 'Dex' : 
							$value = $this->DexteriteTotal->Text;
							break;
						case 'Int' :
							$value = $this->IntelligenceTotal->Text;
							break;
						case 'Sag' : 
							$value = $this->SagesseTotal->Text;
							break;
						case 'Cha' : 
							$value = $this->CharismeTotal->Text;
							break;
					}
					
					if ($value < $pre->abilityValue) 
					{
						$isOK = false;
						break;
					}
				}
				else if ($pre->type == FeatPrerequisiteRecord::ATTACK_BONUS) 
				{
					//do nothing; perso has no attack yet
				} 
				else if ($pre->type == FeatPrerequisiteRecord::LEVEL) 
				{
					if ($pre->level > 1) 
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
	
	public function skillItemCreated($sender,$param) {
        $item = $param->Item;
        if($item->ItemType==='Item' || $item->ItemType==='AlternatingItem' || $item->ItemType==='EditItem') {
			$skillId = $this->SkillTable->DataKeys->itemAt($item->ItemIndex);
			$skill = SkillRecord::finder()->findByPk($skillId);
			
			if ($skill->isClassSkill($this->ClasseList->SelectedValue)) {
            	$item->SkillClassColumn->SkillClassLabel->Text = 'Compétence de classe';
			} else {
				$item->SkillClassColumn->SkillClassLabel->Text = '';
			}
        }
    }
	
	protected function resetClassSkills() {
		foreach($this->SkillTable->Items as $item) {
			$skillId = $this->SkillTable->DataKeys->itemAt($item->ItemIndex);
			$skill = SkillRecord::finder()->findByPk($skillId);
			
			if ($skill->isClassSkill($this->ClasseList->SelectedValue)) {
            	$item->SkillClassColumn->SkillClassLabel->Text = 'Compétence de classe';
			} else {
				$item->SkillClassColumn->SkillClassLabel->Text = '';
			}
		}
	}
	
	protected function fillSkillSummary() {
		$data = array();
		foreach ($this->SkillTable->Items as $i) {
			$skillId = $this->SkillTable->DataKeys->itemAt($i->ItemIndex);
			$data[] = array(
			  'id'=>$skillId,
			  'name'=>$i->SkillNameColumn->Text,
			  'rank'=>$i->SkillPointsColumn->SkillPoints->Text  
			);
		}
		$this->SkillSummaryTable->DataSource = $data;
		$this->SkillSummaryTable->databind();			
	}
	
	protected function fillFeatSummary() {
		$data = array();
		foreach ($this->Feats->List2->Items as $i) {
			$data[] = array(
			  'id'=>$i->Value,
			  'name'=>$i->Text
			);
		}
		foreach ($this->LevelFeats->Items as $i) {
			$data[] = array(
			  'id'=>$i->Value,
			  'name'=>$i->Text
			);
		}
		$this->FeatSummaryTable->DataSource = $data;
		$this->FeatSummaryTable->databind();			
	}
	
	protected function getClassLevelFeats() {
		$classId = $this->ClasseList->SelectedValue;
		$feats = ClassLevelBonusFeatRecord::finder()->findAllBy_classId_and_levelId($classId, 1);
		$data = array();
		//print_r($feats);
		foreach ($feats as $bonusFeat) {
			$data[] = $bonusFeat->feat;
		}
		return $data;
	}
	
	protected function isFeatAssigned($featId) {
		$isFeatAssigned = false;
		foreach ($this->getClassLevelFeats() as $assignedFeat) {
			if ($assignedFeat->id == $featId) {
				$isFeatAssigned = true;
				break;
			}
		}
		return $isFeatAssigned;
	}
	
	public function featSelectedChanged($sender, $param) {
		if ($this->isFeatAssigned($sender->SelectedValue)) {
			$this->Feats->AddToList1Button->Enabled = false;
		} else {
			$this->Feats->AddToList1Button->Enabled = true;
		}
	}
	
}
?>