<?php
class ListEquipment extends DSPage
{
    public function onInit($param) 
    {
        parent::onInit($param);
        if (!$this->IsPostBack) 
        {
			//$criteria = new TActiveRecordCriteria;
			//$criteria->OrdersBy['id'] = 'asc';
			$armors = ArmorRecord::finder()->findAll();
            $this->RepeaterArmor->DataSource = $armors;
            $this->RepeaterArmor->dataBind();

            $weapons = WeaponRecord::finder()->findAll();
            $this->RepeaterWeapon->DataSource = $weapons;
            $this->RepeaterWeapon->dataBind();

            $rings = EquipmentRecord::finder()->findAllByType(EquipmentRecord::RING_TYPE);
            $this->RepeaterRing->DataSource = $rings;
            $this->RepeaterRing->dataBind();

            $amulets = EquipmentRecord::finder()->findAllByType(EquipmentRecord::AMULET_TYPE);
            $this->RepeaterAmulet->DataSource = $amulets;
            $this->RepeaterAmulet->dataBind();

            $bracers = EquipmentRecord::finder()->findAllByType(EquipmentRecord::BRACER_TYPE);
            $this->RepeaterBracer->DataSource = $bracers;
            $this->RepeaterBracer->dataBind();

            $boots = EquipmentRecord::finder()->findAllByType(EquipmentRecord::BOOT_TYPE);
            $this->RepeaterBoot->DataSource = $boots;
            $this->RepeaterBoot->dataBind();

            $belts = EquipmentRecord::finder()->findAllByType(EquipmentRecord::BELT_TYPE);
            $this->RepeaterBelt->DataSource = $belts;
            $this->RepeaterBelt->dataBind();

            $utils = EquipmentRecord::finder()->findAllByType(EquipmentRecord::UTIL_TYPE);
            $this->RepeaterUtil->DataSource = $utils;
            $this->RepeaterUtil->dataBind();
        }

    }

    public function editButtonClicked($sender, $param)
    {
        $id = $param->CommandParameter;

        $this->goToPage('admin.EditEquipment', array('equipmentId'=>$id));
    }

    public function getWeaponTypeString($type)
    {
        switch($type)
        {
            case WeaponTypeRecord::LIGHT_TYPE:
                return "Léger";
            case WeaponTypeRecord::ONE_HANDED_TYPE:
                return "Une main";
            case WeaponTypeRecord::TWO_HANDED_TYPE:
                return "Deux mains";
            default:
                return "";
        }
    }


    // public function btnShow_OnClick($sender, $param) 
    // {
    //     foreach($this->RepeaterArmor->getItems() as $i)
    //     {
    //         if ($sender->CustomData == $i->mpnlTest->CustomData) 
    //         {
    //             // var_dump($i->mpnlTest->CustomData);
    //             $i->mpnlTest->Show();
    //         }
    //     }
    // }
    
    // public function btnClose_OnClick($sender, $param) 
    // {
    //     foreach($this->RepeaterArmor->getItems() as $i)
    //     {
    //         if ($sender->CustomData == $i->mpnlTest->CustomData) 
    //         {
    //             // var_dump($i->mpnlTest->CustomData);
    //             $i->mpnlTest->Hide();
    //         }
    //     }
    // }
}
?>
