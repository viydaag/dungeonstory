<?php
class EditArmorType extends DSPage
{
	public function onInit($param) 
    {
        parent::onInit($param);

        $armorTypeRecord = $this->getArmorType();
		
        if (!$this->User->IsAdmin)
        {
            throw new DSException(500, 'admin_view_disallowed');
		}
 
        if (!$this->IsPostBack) 
        {
            $this->Name->Text = $armorTypeRecord->name;
            $this->Description->Text = $armorTypeRecord->baseDescription;
			$this->BaseWeight->Text = $armorTypeRecord->baseWeight;
			$this->TypeList->SelectedValue = $armorTypeRecord->armorType;
			$this->BaseAC->Text = $armorTypeRecord->baseAC;
			$this->MaxDexBonus->Text = $armorTypeRecord->maxDexBonus;
			$this->BaseArmorCheckPenalty->Text = $armorTypeRecord->baseArmorCheckPenalty;
			$this->BaseArcaneSpellFailure->Text = $armorTypeRecord->baseArcaneSpellFailure;
        }
    }

	public function saveButtonClicked($sender, $param) 
	{
        if ($this->IsValid)
        {
			$armorTypeRecord = $this->getArmorType();
            $armorTypeRecord->name = $this->Name->SafeText;
			$armorTypeRecord->baseDescription = $this->Description->SafeText;
            $armorTypeRecord->baseWeight = $this->BaseWeight->SafeText;
			$armorTypeRecord->armorType = $this->TypeList->SelectedValue;
			$armorTypeRecord->baseAC = $this->BaseAC->SafeText;
			$armorTypeRecord->maxDexBonus = $this->MaxDexBonus->SafeText;
			$armorTypeRecord->baseArmorCheckPenalty = $this->BaseArmorCheckPenalty->SafeText;
			$armorTypeRecord->baseArcaneSpellFailure = $this->BaseArcaneSpellFailure->SafeText;
            $armorTypeRecord->save();
            $this->goToPage('admin.ListArmorType');
        }
    }

    public function deleteButtonClicked($sender, $param) 
    {
		$armorTypeRecord = $this->getArmorType();
		
		$armorTypeRecord->delete();
		
		$this->goToPage('admin.ListArmorType');
	}

    protected function getArmorType() 
    {
        // l'ID du message devant être modifié passé par un paramètre GET 
        $armorTypeID = (int)$this->Request['armorTypeId'];
        // utilise Active Record pour lire le message correspondant à cet ID
        $armorTypeRecord = ArmorTypeRecord::finder()->findByPk($armorTypeID);
        if ($armorTypeRecord === null) 
        {
            throw new DSException(500, 'armorType_id_invalid', $armorTypeID);
		}
        return $armorTypeRecord;
    }
}
?>