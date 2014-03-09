<?php
class EditWeaponType extends DSPage
{
	public function onInit($param) 
	{
        parent::onInit($param);

        $weaponTypeRecord = $this->WeaponType;
		
        if (!$this->User->IsAdmin) 
        {
            throw new DSException(500, 'admin_view_disallowed');
		}
 
        if (!$this->IsPostBack)
        {
			$damageTypefinder = DamageTypeRecord::finder();
			$damageTypes = $damageTypefinder->findAll();
			$this->DamageTypeList->DataSource = $damageTypes;
			$this->DamageTypeList->dataBind();

            $this->Name->Text = $weaponTypeRecord->name;
            $this->Description->Text = $weaponTypeRecord->baseDescription;
			$this->BaseWeight->Text = $weaponTypeRecord->baseWeight;
			$this->UseList->SelectedValue = $weaponTypeRecord->use;
			if ($this->UseList->SelectedValue == WeaponTypeRecord::RANGE_USE) 
			{
				$this->RangeTypeList->SelectedValue = $weaponTypeRecord->rangeType;
				$this->RangeIncrement->TextBox->Text = $weaponTypeRecord->rangeIncrement;
			}
			$this->TypeList->SelectedValue = $weaponTypeRecord->type;
			$this->ProficiencyList->SelectedValue = $weaponTypeRecord->proficiency;
			$this->BaseDamage->Text = $weaponTypeRecord->baseDamage;
			$this->DamageTypeList->SelectedValue = $weaponTypeRecord->damageTypeId;
			$this->IsDouble->Checked = $weaponTypeRecord->isDouble;
			$this->IsReach->Checked = $weaponTypeRecord->isReach;
			$this->useChanged(NULL, NULL);
        }
    }

	public function saveButtonClicked($sender, $param) 
	{
        if ($this->IsValid) 
        {
			$weaponTypeRecord = $this->WeaponType;
            $weaponTypeRecord->name = $this->Name->SafeText;
			$weaponTypeRecord->baseDescription = $this->Description->SafeText;
            $weaponTypeRecord->baseWeight = $this->BaseWeight->SafeText;
			$weaponTypeRecord->use = $this->UseList->SelectedValue;
			if ($this->UseList->SelectedValue == WeaponTypeRecord::RANGE_USE) 
			{
				$weaponTypeRecord->rangeType = $this->RangeTypeList->SelectedValue;
				$weaponTypeRecord->rangeIncrement = $this->RangeIncrement->TextBox->SafeText;
			} 
			else
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
		// if ($this->UseList->SelectedValue == WeaponTypeRecord::MELEE_USE) 
		// {
		// 	$this->RangeTypeLabel->Display = 'Hidden';
		// 	$this->RangeTypeList->Display = 'Hidden';
		// 	$this->RangeIncrementLabel->Display = 'Hidden';
		// 	$this->RangeIncrement->Display = 'Hidden';
		// }
		// else if ($this->UseList->SelectedValue ==  WeaponTypeRecord::RANGE_USE) 
		// {
		// 	$this->RangeTypeLabel->Display = 'Fixed';
		// 	$this->RangeTypeList->Display = 'Fixed';
		// 	$this->RangeIncrementLabel->Display = 'Fixed';
		// 	$this->RangeIncrement->Display = 'Fixed';
		// }
	}
	
	protected function getWeaponType() 
	{
        // l'ID du message devant être modifié passé par un paramètre GET 
        $wpID = (int)$this->Request['weaponTypeId'];
        // utilise Active Record pour lire le message correspondant à cet ID
        $weaponTypeRecord = WeaponTypeRecord::finder()->findByPk($wpID);
        if ($weaponTypeRecord === null) 
        {
            throw new DSException(500, 'weapon_type_id_invalid', $wpID);
		}
        return $weaponTypeRecord;
    }
}
?>
