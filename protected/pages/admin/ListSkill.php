<?php
class ListSkill extends DSPage
{
    public function onInit($param) {
        parent::onInit($param);
        if(!$this->IsPostBack) {
			$data = SkillRecord::finder()->findAll();
            $this->RepeaterSkill->DataSource=$data;
            $this->RepeaterSkill->dataBind();
        }
    }
}
?>