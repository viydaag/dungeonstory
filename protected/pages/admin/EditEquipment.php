<?php
class EditEquipment extends DSPage
{
	public function onInit($param) 
	{
        parent::onInit($param);

        $equipmentRecord = $this->getEquipment();
		
        if (!$this->User->IsAdmin) 
        {
            throw new DSException(500, 'admin_view_disallowed');
		}
 
		if (!$this->IsPostBack) 
		{
			$this->Name->Text = $equipmentRecord->name;
			$this->Description->Text = $equipmentRecord->description;
			$this->Weight->Text = $equipmentRecord->weight;
			$this->Magical->Checked = $equipmentRecord->isMagical;
			$this->Purchasable->Checked = $equipmentRecord->isPurchasable;
			$this->Sellable->Checked = $equipmentRecord->isSellable;
				
			if ($equipmentRecord->type == EquipmentRecord::ARMOR_TYPE)
			{
				$armorTypefinder = ArmorTypeRecord::finder();
				$armorTypes = $armorTypefinder->findAll();
				$this->ArmorType->DataSource = $armorTypes;
				$this->ArmorType->dataBind();

				$armorRecord = $this->getArmor($equipmentRecord->id);
				$this->ArmorTypeDiv->Visible = true;
				$this->ArmorDiv->Visible = true;
				$this->WeaponTypeDiv->Visible = false;
				$this->WeaponDiv->Visible = false;
				$this->AcBonus->Text = $armorRecord->acBonus;
				$this->ArmorCheckPenalty->Text = $armorRecord->armorCheckPenalty;
				$this->AcMagicalBonus->Text = $armorRecord->magicalACBonus;
				$this->ArcaneSpellFailure->Text = $armorRecord->arcaneSpellFailure;
				$this->ArmorType->SelectedValue = $armorRecord->armorTypeId;

				$this->setViewState('type', EquipmentRecord::ARMOR_TYPE);
			}
			else if ($equipmentRecord->type == EquipmentRecord::WEAPON_TYPE)
			{
				$weaponTypefinder = WeaponTypeRecord::finder();
				$weaponTypes = $weaponTypefinder->findAll();
				$this->WeaponType->DataSource = $weaponTypes;
				$this->WeaponType->dataBind();

				$damageTypefinder = DamageTypeRecord::finder();
				$damageTypes = $damageTypefinder->findAll();
				$this->AdditionalDamageTypeList->DataSource = $damageTypes;
				$this->AdditionalDamageTypeList->dataBind();

				$weaponRecord = $this->getWeapon($equipmentRecord->id);
				$this->ArmorTypeDiv->Visible = false;
				$this->ArmorDiv->Visible = false;
				$this->WeaponTypeDiv->Visible = true;
				$this->WeaponDiv->Visible = true;
				$this->BaseDamage->Text = $weaponRecord->damage;
				$this->AdditionalDamage->Text = $weaponRecord->additionalDamage;
				$this->AdditionalDamageTypeList->SelectedValue = $weaponRecord->additionalDamageTypeId;
				$this->WeaponMagicalBonus->Text = $weaponRecord->magicalBonus;
				$this->WeaponType->SelectedValue = $weaponRecord->weaponTypeId;

				$this->setViewState('type', EquipmentRecord::WEAPON_TYPE);
			}
		}
	}

	public function saveButtonClicked($sender, $param) 
	{
        if ($this->IsValid) 
        {
			$finder = EquipmentRecord::finder();
			$finder->DbConnection->Active = true;
			$transaction = $finder->DbConnection->beginTransaction();

			try 
			{
				$equipmentRecord = $this->getEquipment();
				$equipmentRecord->name = $this->Name->SafeText;
				$equipmentRecord->description = $this->Description->SafeText;
				$equipmentRecord->cost = 0;
				$equipmentRecord->weight = TPropertyValue::ensureInteger($this->Weight->SafeText);
				$equipmentRecord->isMagical = $this->Magical->Checked;
				$equipmentRecord->isPurchasable = $this->Purchasable->Checked;
				$equipmentRecord->isSellable = $this->Sellable->Checked;
				$equipmentRecord->save();

				if ($equipmentRecord->type == EquipmentRecord::ARMOR_TYPE)
				{
					$armorRecord = $this->getArmor($equipmentRecord->id);
					$armorRecord->acBonus = TPropertyValue::ensureInteger($this->AcBonus->SafeText);
					$armorRecord->armorCheckPenalty = TPropertyValue::ensureInteger($this->ArmorCheckPenalty->SafeText);
					$armorRecord->magicalACBonus = TPropertyValue::ensureInteger($this->AcMagicalBonus->SafeText);
					$armorRecord->arcaneSpellFailure = TPropertyValue::ensureInteger($this->ArcaneSpellFailure->SafeText);
					$armorRecord->armorTypeId = $this->ArmorType->SelectedValue;
					$armorRecord->save();
				}
				else if ($equipmentRecord->type == EquipmentRecord::WEAPON_TYPE)
				{
					$weaponRecord = $this->getWeapon($equipmentRecord->id);
					$weaponRecord->damage = $this->BaseDamage->SafeText;
					$weaponRecord->additionalDamage = $this->AdditionalDamage->SafeText;
					$weaponRecord->additionalDamageTypeId = $this->AdditionalDamageTypeList->SelectedValue;
					$weaponRecord->magicalBonus = $this->WeaponMagicalBonus->SafeText;
					$weaponRecord->weaponTypeId = $this->WeaponType->SelectedValue;
					$weaponRecord->save();
				}

				$transaction->commit();
				$this->goToPage('admin.ListEquipment');
			}
			catch(Exception $e) 
			{
				$transaction->rollBack();
				throw new DSException(500, $e->getMessage());
			}

        }
    }

    protected function getEquipment() 
    {
        // l'ID du message devant être modifié passé par un paramètre GET 
        $equipmentID = (int)$this->Request['equipmentId'];
        // utilise Active Record pour lire le message correspondant à cet ID
        $equipmentRecord = EquipmentRecord::finder()->findByPk($equipmentID);
        if ($equipmentRecord === null) 
        {
            throw new DSException(500, 'equipment_id_invalid', $equipmentID);
		}
        return $equipmentRecord;
    }

    protected function getArmor($equimentId)
    {
    	$armorRecord = ArmorRecord::finder()->findByPk($equimentId);
    	if ($armorRecord === null) 
        {
            throw new DSException(500, 'equipment_id_invalid', $equimentId);
		}
        return $armorRecord;
    }

    protected function getWeapon($equimentId)
    {
    	$weaponRecord = WeaponRecord::finder()->findByPk($equimentId);
    	if ($weaponRecord === null) 
        {
            throw new DSException(500, 'equipment_id_invalid', $equimentId);
		}
        return $weaponRecord;
    }

    public function magicalChanged($sender, $param)
	{
		$this->AcMagicalBonusDiv->Visible = $sender->Checked && $this->getViewState('type') == EquipmentRecord::ARMOR_TYPE;
		$this->WeaponMagicalBonusDiv->Visible = $sender->Checked && $this->getViewState('type') == EquipmentRecord::WEAPON_TYPE;

		if ($this->AcMagicalBonusDiv->Visible == false)
		{
			$this->AcMagicalBonus->Text = "0";
		}
		if ($this->WeaponMagicalBonusDiv->Visible == false)
		{
			$this->WeaponMagicalBonus->Text = "0";
		}
	}
}
?>