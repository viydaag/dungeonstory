<?php
 
class ListLevel extends DSPage
{

	public function onInit($param) {
        parent::onInit($param);
		
		if(!$this->User->IsAdmin) {
            throw new DSException(500, 'admin_view_disallowed');
		}
		
        if(!$this->IsPostBack) {	
            $this->bindData();
       	}
    }
	
	public function bindData() {
		$this->LevelGrid->DataSource = $this->getDataSource();
        $this->LevelGrid->dataBind();
	}
	
	protected function getDataSource() {
		$criteria=new TActiveRecordCriteria;
        $criteria->OrdersBy['niveau'] = 'asc';
		$finder = LevelRecord::finder();
		$data = $finder->findAll($criteria);
		return $data;
	}
 
    public function itemCreated($sender, $param) {
		$item=$param->Item;
    }
 
    public function editItem($sender, $param) {
        $this->LevelGrid->EditItemIndex = $param->Item->ItemIndex;
        $this->bindData();
    }
 
    public function saveItem($sender, $param) {
        $item = $param->Item;
		$id = $item->LevelColumn->Data;		
		$levelRecord = LevelRecord::finder()->findByPk($id);
		$levelRecord->maxExperience = TPropertyValue::ensureInteger($item->XPColumn->TextBox->Text);
		$levelRecord->bonusFeat = $item->BonusFeatColumn->CheckBox->Checked;
		$levelRecord->bonusAbilityScore = $item->BonusAbilityColumn->CheckBox->Checked;
		$levelRecord->maxClassSkillRanks = TPropertyValue::ensureInteger($item->MaxClassSkillRanksColumn->TextBox->Text);
		$levelRecord->maxCrossClassSkillRanks = TPropertyValue::ensureInteger($item->MaxCrossClassSkillRanksColumn->TextBox->Text);
		$levelRecord->save();
		
		$this->onLevelCreation($levelRecord);
		
        $this->LevelGrid->EditItemIndex = -1;
        $this->bindData();
    }
 
    public function cancelItem($sender, $param) {
        $this->LevelGrid->EditItemIndex = -1;
        $this->bindData();		
    }
	
	public function addItem($sender, $param) {
		//add new level to the database		
		$levelRecord = new LevelRecord();
		$levelRecord->maxExperience = 0;
		$levelRecord->bonusFeat = false;
		$levelRecord->bonusAbilityScore = false;
		$levelRecord->maxClassSkillRanks = 0;
		$levelRecord->maxCrossClassSkillRanks = 0;
		$levelRecord->save();
		
		$this->onLevelCreation($levelRecord);
		
		$this->LevelGrid->EditItemIndex = -1;
		$this->bindData();
	}
	
	protected function onLevelCreation($levelRecord) {
		$classes = ClasseRecord::finder()->findAll();
		$finder = ClassLevelBonusRecord::finder();
		foreach ($classes as $class) {
			$bonus = $finder->findBy_levelId_And_classId($levelRecord->niveau, $class->id);
			if ($bonus == null) {
				$classLevelBonusRecord = new ClassLevelBonusRecord();
				$classLevelBonusRecord->classId = $class->id;
				$classLevelBonusRecord->levelId = $levelRecord->niveau;
				$classLevelBonusRecord->baseAttackBonus = '';
				$classLevelBonusRecord->fortSave = 0;
				$classLevelBonusRecord->refSave = 0;
				$classLevelBonusRecord->willSave = 0;
				
				$classLevelBonusRecord->save();
			}			
		}
	}
	
}
 
?>