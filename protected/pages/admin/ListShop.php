<?php
class ListShop extends DSPage
{
    public function onLoad($param) {
        parent::onLoad($param);
        if(!$this->IsPostBack) {
			$criteria = new TActiveRecordCriteria;
			$criteria->OrdersBy['name']='asc';
			$data = ShopRecord::finder()->findAll($criteria);
            $this->ShopRepeater->DataSource = $data;
            $this->ShopRepeater->dataBind();
        }
    }
}
?>