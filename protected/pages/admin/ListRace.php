<?php
class ListRace extends DSPage
{
    public function onLoad($param) {
        parent::onLoad($param);
        if(!$this->IsPostBack) {
			$criteria=new TActiveRecordCriteria;
			$criteria->OrdersBy['nom']='asc';
			$data = RaceRecord::finder()->findAll($criteria);
            $this->RepeaterRace->DataSource=$data;
            $this->RepeaterRace->dataBind();
        }
    }
}
?>