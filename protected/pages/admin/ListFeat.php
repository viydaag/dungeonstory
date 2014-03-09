<?php
class ListFeat extends DSPage
{
    public function onInit($param) {
        parent::onInit($param);
        if(!$this->IsPostBack) {
			$data = FeatRecord::finder()->findAll();
            $this->RepeaterFeat->DataSource=$data;
            $this->RepeaterFeat->dataBind();
        }
    }
}
?>