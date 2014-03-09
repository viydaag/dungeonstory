<?php
class EditClasse extends DSPage
{
    public function onInit($param) 
    {
        parent::onInit($param);

        $classeRecord = $this->Classe;
		
        if(!$this->User->IsAdmin) 
        {
            throw new DSException(500, 'admin_view_disallowed');
		}
 
        if(!$this->IsPostBack) 
        {
            $this->NomEdit->Text = $classeRecord->nom;
			$this->VieEdit->Text = $classeRecord->vie;
			$this->DescriptionCourteEdit->Text = $classeRecord->descriptionCourte;
            $this->DescriptionEdit->Text = $classeRecord->description;
			
			$list = new TList;
			foreach ($classeRecord->skills as $value) 
			{
				$list->add($value->id);			
			}
			
			$skillfinder = SkillRecord::finder();
			$skills = $skillfinder->findAll();
			$this->SkillsEdit->DataSource = $skills;
			$this->SkillsEdit->SelectedValues = $list;
			$this->SkillsEdit->dataBind();
			
			$classlevelbonusfinder = ClassLevelBonusRecord::finder();
			$bonusList = $classlevelbonusfinder->findAllBy_ClassId($classeRecord->id);
			$this->ClassLevelBonusRepeater->DataSource = $bonusList;
			$this->ClassLevelBonusRepeater->databind();
			
			foreach ($this->ClassLevelBonusRepeater->Items as $item) 
			{
				$classlevelbonusfeatfinder = ClassLevelBonusFeatRecord::finder();
				$bonusFeatList = $classlevelbonusfeatfinder->findAllBy_classId_and_levelId($classeRecord->id, $item->LevelEdit->Text);
				$data = array();
				foreach($bonusFeatList as $i) 
				{
					$data[] = array(
					  'feat'=>$i->feat->id,
					  'class'=>$classeRecord->id,
					  'level'=>$item->LevelEdit->Text	  
					);
				}
				$item->FeatRepeater->DataSource = $data;
				$item->FeatRepeater->databind();
			}
		}
    }
 
    /**
     * Enregistre si toutes les validations sont Ok
     * cette méthode répond à l'évènement OnClick du bouton "Enregistrer".
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function saveButtonClicked($sender, $param) 
    {
        if ($this->IsValid) 
        {
			$finder = ClasseRecord::finder();
			$finder->DbConnection->Active = true;
			$transaction = $finder->DbConnection->beginTransaction();
            
			try 
			{
				$classeRecord = $this->Classe;
				$classeRecord->nom = $this->NomEdit->SafeText;
				$classeRecord->vie = TPropertyValue::ensureInteger($this->VieEdit->SafeText);
				$classeRecord->description = $this->DescriptionEdit->SafeText;
				$classeRecord->descriptionCourte = $this->DescriptionCourteEdit->SafeText;
				$classeRecord->save();
				
				//delete all actual class skills
				$classeId = $this->Classe->id;
				ClasseSkillRecord::finder()->deleteBy_classeId($classeId);
	
				//save the new skills
				$values = $this->SkillsEdit->SelectedValues;
				foreach ($values as $classSkill) 
				{
					$classeSkillRecord = new ClasseSkillRecord;
					$classeSkillRecord->classeId = $classeId;
					$classeSkillRecord->skillId = $classSkill;
					$classeSkillRecord->save();
				}
				
				//save class level bonuses
				foreach($this->ClassLevelBonusRepeater->getItems() as $i) 
				{
					$level = $i->LevelEdit->Text;		
					$finder = ClassLevelBonusRecord::finder();
					$bonusRecord = $finder->findBy_levelId_And_classId($level, $classeId);
					if ($bonusRecord != null) 
					{
						$bonusRecord->baseAttackBonus = $i->BaseAttackBonusEdit->Text;
						$bonusRecord->fortSave = TPropertyValue::ensureInteger($i->FortSaveEdit->Text);
						$bonusRecord->refSave = TPropertyValue::ensureInteger($i->RefSaveEdit->Text);
						$bonusRecord->willSave = TPropertyValue::ensureInteger($i->WillSaveEdit->Text);
						$bonusRecord->acBonus = TPropertyValue::ensureInteger($i->ACBonusEdit->Text);
						$bonusRecord->save();
					}
					//delete all actual class level bonus feats
					ClassLevelBonusFeatRecord::finder()->deleteBy_classId_and_levelId($classeId, $level);
					//save all class level bonus feats
					foreach($i->FeatRepeater->Items as $feat) 
					{
						$bonusFeatRecord = new ClassLevelBonusFeatRecord;
						$bonusFeatRecord->classId = $classeId;
						$bonusFeatRecord->levelId = $level;
						$bonusFeatRecord->featId = $feat->FeatList->SelectedValue;
						$bonusFeatRecord->save();
					}
				}
				$transaction->commit();
			} catch(Exception $e) 
			{
				$transaction->rollBack();
				throw new DSException(500, $e->getMessage());
			}		
			
           	$this->goToPage('admin.ListClasse');
        }
    }
	
	public function addFeat($sender, $param) 
	{
		$level = $sender->CustomData;
		foreach ($this->ClassLevelBonusRepeater->Items as $item) 
		{
			if ($item->FeatRepeater->CustomData == $level) 
			{
				$repeater = $item->FeatRepeater;
				$panel = $item->FeatPanel;
				break;
			}
		}
		$data = array();
		foreach($repeater->getItems() as $i) 
		{
			$data[] = array(
			  'feat'=>$i->FeatList->SelectedValue,
			  'class'=>$this->Classe->id,
			  'level'=>$repeater->CustomData	  
			);			
		}
		// on ajoute un nouveau feat, en fait on se contente d'ajouter une entre au tableau, le Repeater fera le reste
		$data[] = array(
			  'feat'=>1,
			  'class'=>$this->Classe->id,
			  'level'=>$repeater->CustomData
		);

		// on reinitialise le Repeater et on le remplit à nouveau
		$repeater->reset();
		$repeater->DataSource = $data;
		$repeater->dataBind();
		
		// on refait le rendu du Panel afin d'afficher le nouveau prérequis
		if($param!=null) $panel->render($param->getNewWriter());
	}
	
	public function removeFeat($sender, $param) 
	{
		$tab = explode("/", $sender->CustomData);
		$featId = $tab[0];
		$level = $tab[1];
		$data = array();
		//get the level row
		foreach ($this->ClassLevelBonusRepeater->Items as $item) 
		{
			if ($item->FeatRepeater->CustomData == $level) 
			{
				$repeater = $item->FeatRepeater;
				$panel = $item->FeatPanel;
				break;
			}
		}
		//fill the data
		foreach($repeater->getItems() as $i) 
		{
			if ($i->FeatList->SelectedValue != $featId && $i->FeatList->CustomData == $level) 
			{
				$data[] = array(
					'feat'=>$i->FeatList->SelectedValue,
					'class'=>$this->Classe->id,
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
 
    protected function getClasse() 
    {
        $classeID = (int)$this->Request['classeId'];
        $classeRecord = ClasseRecord::finder()->findByPk($classeID);
        if ($classeRecord === null) 
        {
            throw new DSException(500, 'classe_id_invalid', $classeID);
		}
        return $classeRecord;
    }
}
?>