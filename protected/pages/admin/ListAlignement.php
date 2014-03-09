<?php
class ListAlignement extends DSPage
{
    public function onLoad($param) {
        parent::onLoad($param);
        if(!$this->IsPostBack) {
			$data = AlignementRecord::finder()->findAll();
            $this->RepeaterAlignement->DataSource=$data;
            $this->RepeaterAlignement->dataBind();
        }
    }
}
?>