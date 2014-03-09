<?php
class ListMonster extends DSPage
{
	public function onLoad($param) {
        parent::onLoad($param);
        if(!$this->IsPostBack) {
			$data = MonsterRecord::finder()->findAll();
            $this->RepeaterMonster->DataSource=$data;
            $this->RepeaterMonster->dataBind();
        }
    }
}
?>