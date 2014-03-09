<?php
class ListEnemy extends DSPage
{
	public function onLoad($param) {
        parent::onLoad($param);
        if(!$this->IsPostBack) {
			$data = MonsterRecord::finder()->findAll();
            $this->EnemyTable->DataSource=$data;
            $this->EnemyTable->dataBind();
        }
    }
}
?>