<?php
class NewWeaponType extends DSPage
{
	public function onInit($param) 
	{
        parent::onInit($param);
		if (!$this->IsPostBack) 
		{
			$this->useChanged(NULL, NULL);

			$damageTypefinder = DamageTypeRecord::finder();
			$damageTypes = $damageTypefinder->findAll();
			$this->DamageTypeList->DataSource = $damageTypes;
			$this->DamageTypeList->dataBind();
		}
	}

	public function saveButtonClicked($sender, $param) 
	{
        if ($this->IsValid) 
        {
			$weaponTypeRecord = new WeaponTypeRecord();
            $weaponTypeRecord->name = $this->Name->SafeText;
			$weaponTypeRecord->baseDescription = $this->Description->SafeText;
            $weaponTypeRecord->baseWeight = $this->BaseWeight->SafeText;
			$weaponTypeRecord->use = $this->UseList->SelectedValue;
			if ($this->UseList->SelectedValue == WeaponTypeRecord::RANGE_USE) 
			{
				$weaponTypeRecord->rangeType = $this->RangeTypeList->SelectedValue;
				$weaponTypeRecord->rangeIncrement = $this->RangeIncrement->TextBox->SafeText;
			} else 
			{
				$weaponTypeRecord->rangeType = NULL;
				$weaponTypeRecord->rangeIncrement = NULL;
			}
			$weaponTypeRecord->type = $this->TypeList->SelectedValue;
			$weaponTypeRecord->proficiency = $this->ProficiencyList->SelectedValue;
			$weaponTypeRecord->baseDamage = $this->BaseDamage->SafeText;
			$weaponTypeRecord->damageTypeId = $this->DamageTypeList->SelectedValue;
			$weaponTypeRecord->isDouble = $this->IsDouble->Checked;
			$weaponTypeRecord->isReach = $this->IsReach->Checked;
            $weaponTypeRecord->save();
            $this->goToPage('admin.ListWeaponType');
        }
    }
	
	public function useChanged($sender, $param) 
	{
		$this->RangeTypeDiv->Visible = $this->UseList->SelectedValue == WeaponTypeRecord::RANGE_USE;
		$this->RangeIncrementDiv->Visible = $this->UseList->SelectedValue == WeaponTypeRecord::RANGE_USE;
	}

}
?>