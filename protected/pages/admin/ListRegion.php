<?php
class ListRegion extends DSPage
{
    public function onInit($param) 
    {
        parent::onInit($param);
        if (!$this->IsPostBack) 
        {
			$criteria = new TActiveRecordCriteria;
			$criteria->OrdersBy['nom'] = 'asc';
			$data = RegionRecord::finder()->findAll($criteria);
            $this->RepeaterRegion->DataSource = $data;
            $this->RepeaterRegion->dataBind();
        }
    }
}
?>