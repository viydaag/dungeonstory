<?php
class ListWeaponType extends DSPage
{
    public function onInit($param) {
        parent::onInit($param);
        if(!$this->IsPostBack) {
			$criteria = new TActiveRecordCriteria;
			$criteria->OrdersBy['name'] = 'asc';
			$data = WeaponTypeRecord::finder()->findAll($criteria);
            $this->RepeaterWeaponType->DataSource=$data;
            $this->RepeaterWeaponType->dataBind();
        }
    }
}
?>