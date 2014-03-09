<?php
class ListArmorType extends DSPage
{
    public function onInit($param) 
    {
        parent::onInit($param);
        if (!$this->IsPostBack) 
        {
			$criteria = new TActiveRecordCriteria;
            $criteria->Condition = 'armorType = :type';
            $criteria->Parameters[':type'] = ArmorTypeRecord::LIGHT;
			$criteria->OrdersBy['name'] = 'asc';
			$data = ArmorTypeRecord::finder()->findAll($criteria);
            $this->RepeaterArmorTypeLight->DataSource=$data;
            $this->RepeaterArmorTypeLight->dataBind();

            $criteria = new TActiveRecordCriteria;
            $criteria->Condition = 'armorType = :type';
            $criteria->Parameters[':type'] = ArmorTypeRecord::MEDIUM;
            $criteria->OrdersBy['name'] = 'asc';
            $data = ArmorTypeRecord::finder()->findAll($criteria);
            $this->RepeaterArmorTypeMedium->DataSource=$data;
            $this->RepeaterArmorTypeMedium->dataBind();

            $criteria = new TActiveRecordCriteria;
            $criteria->Condition = 'armorType = :type';
            $criteria->Parameters[':type'] = ArmorTypeRecord::HEAVY;
            $criteria->OrdersBy['name'] = 'asc';
            $data = ArmorTypeRecord::finder()->findAll($criteria);
            $this->RepeaterArmorTypeHeavy->DataSource=$data;
            $this->RepeaterArmorTypeHeavy->dataBind();
        }
    }
}
?>