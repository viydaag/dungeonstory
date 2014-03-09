<?php
class NewArmorType extends DSPage
{
	public function saveButtonClicked($sender, $param) 
	{
        if ($this->IsValid)
        {
			$armorTypeRecord = new ArmorTypeRecord();
            $armorTypeRecord->name = $this->Name->SafeText;
			$armorTypeRecord->baseDescription = $this->Description->SafeText;
            $armorTypeRecord->baseWeight = $this->BaseWeight->SafeText;
			$armorTypeRecord->armorType = $this->TypeList->SelectedValue;
			$armorTypeRecord->baseAC = $this->BaseAC->SafeText;
			$armorTypeRecord->maxDexBonus = $this->MaxDexBonus->SafeText;
			$armorTypeRecord->baseArmorCheckPenalty = $this->BaseArmorCheckPenalty->SafeText;
			$armorTypeRecord->baseArcaneSpellFailure = $this->BaseArcaneSpellFailure->SafeText;
			$armorTypeRecord->speed = 1;
            $armorTypeRecord->save();
            $this->goToPage('admin.ListArmorType');
        }
    }
}
?>