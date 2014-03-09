<?php
class NewEquipment extends DSPage
{
	public function onInit($param) 
	{
        parent::onInit($param);
		if (!$this->IsPostBack) 
		{
			//$this->useChanged(NULL, NULL);
			//$this->typeChanged($this->Type, null);
			//$this->magicalChanged($this->Magical, null);

			$armorTypefinder = ArmorTypeRecord::finder();
			$armorTypes = $armorTypefinder->findAll();
			$this->ArmorType->DataSource = $armorTypes;
			$this->ArmorType->dataBind();

			$weaponTypefinder = WeaponTypeRecord::finder();
			$weaponTypes = $weaponTypefinder->findAll();
			$this->WeaponType->DataSource = $weaponTypes;
			$this->WeaponType->dataBind();

			$damageTypefinder = DamageTypeRecord::finder();
			$damageTypes = $damageTypefinder->findAll();
			$this->AdditionalDamageTypeList->DataSource = $damageTypes;
			$this->AdditionalDamageTypeList->dataBind();

			//$this->typeChanged($this->Type, null);
			//$this->magicalChanged($this->Magical, null);

		}
	}

	public function onLoad($param)
	{
		parent::onLoad($param);
		// $this->typeChanged($this->Type, null);
		// $this->magicalChanged($this->Magical, null);
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
				$equipmentRecord = new EquipmentRecord();
				$equipmentRecord->name = $this->Name->SafeText;
				$equipmentRecord->description = $this->Description->SafeText;
				$equipmentRecord->cost = 0;
				$equipmentRecord->weight = TPropertyValue::ensureInteger($this->Weight->SafeText);
				$equipmentRecord->isMagical = $this->Magical->Checked;
				$equipmentRecord->type = $this->Type->SelectedValue;
				$equipmentRecord->isPurchasable = $this->Purchasable->Checked;
				$equipmentRecord->isSellable = $this->Sellable->Checked;
				$equipmentRecord->save();

				if ($equipmentRecord->type == EquipmentRecord::ARMOR_TYPE)
				{
					$armorRecord = new ArmorRecord();
					$armorRecord->id = $equipmentRecord->id;
					$armorRecord->acBonus = TPropertyValue::ensureInteger($this->AcBonus->SafeText);
					$armorRecord->armorCheckPenalty = TPropertyValue::ensureInteger($this->ArmorCheckPenalty->SafeText);
					$armorRecord->magicalACBonus = TPropertyValue::ensureInteger($this->AcMagicalBonus->SafeText);
					$armorRecord->arcaneSpellFailure = TPropertyValue::ensureInteger($this->ArcaneSpellFailure->SafeText);
					$armorRecord->armorTypeId = $this->ArmorType->SelectedValue;
					$armorRecord->save();
				}
				else if ($equipmentRecord->type == EquipmentRecord::WEAPON_TYPE)
				{
					$weaponRecord = new WeaponRecord();
					$weaponRecord->id = $equipmentRecord->id;
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
	
	public function typeChanged($sender, $param) 
	{
		Prado::varDump($this->Type->SelectedValue);
		// echo($sender->SelectedIndex);
		$this->ArmorTypeDiv->Visible = $this->Type->SelectedValue == EquipmentRecord::ARMOR_TYPE;
		$this->ArmorDiv->Visible = $this->Type->SelectedValue == EquipmentRecord::ARMOR_TYPE;
		$this->WeaponTypeDiv->Visible = $this->Type->SelectedValue == EquipmentRecord::WEAPON_TYPE;
		$this->WeaponDiv->Visible = $this->Type->SelectedValue == EquipmentRecord::WEAPON_TYPE;
		$this->setViewState('type', $this->Type->SelectedValue);
		$this->magicalChanged($this->Magical, null);
	}

	public function armorTypeChanged($sender, $param) 
	{
		$finder = ArmorTypeRecord::finder();
		$armorTypeRecord = $finder->findByPK($sender->SelectedValue);
		if ($armorTypeRecord !== null)
		{
			$this->Name->Text = $armorTypeRecord->name;
			$this->Description->Text = $armorTypeRecord->baseDescription;
			$this->Weight->Text = $armorTypeRecord->baseWeight;
			$this->AcBonus->Text = $armorTypeRecord->baseAC;
			$this->ArmorCheckPenalty->Text = $armorTypeRecord->baseArmorCheckPenalty;
			$this->ArcaneSpellFailure->Text = $armorTypeRecord->baseArcaneSpellFailure;
		}
		else
		{
			$this->Name->Text = "";
			$this->Description->Text = "";
			$this->Weight->Text = "";
			$this->AcBonus->Text = "";
			$this->ArmorCheckPenalty->Text = "";
			$this->ArcaneSpellFailure->Text = "";
		}
	}

	public function weaponTypeChanged($sender, $param) 
	{
		$finder = WeaponTypeRecord::finder();
		$weaponTypeRecord = $finder->findByPK($sender->SelectedValue);
		if ($weaponTypeRecord !== null)
		{
			$this->Name->Text = $weaponTypeRecord->name;
			$this->Description->Text = $weaponTypeRecord->baseDescription;
			$this->Weight->Text = $weaponTypeRecord->baseWeight;
			$this->BaseDamage->Text = $weaponTypeRecord->baseDamage;
		}
		else
		{
			$this->Name->Text = "";
			$this->Description->Text = "";
			$this->Weight->Text = "";
			$this->BaseDamage->Text = "";
		}
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