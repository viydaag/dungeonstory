<?php
class NewSkill extends DSPage
{
	public function onInit($param) {
        parent::onInit($param);

        if(!$this->User->IsAdmin) {
            throw new DSException(500, 'admin_view_disallowed');
		}
 
        if(!$this->IsPostBack) {			
			$abilityfinder = AbilityRecord::finder();
			$abilities = $abilityfinder->findAll();
			$this->AbilityList->DataSource = $abilities;
			$this->AbilityList->dataBind();			
		}
    }

	public function saveButtonClicked($sender, $param) {
        if ($this->IsValid) {
			$skillRecord = new SkillRecord();
            $skillRecord->name = $this->Name->SafeText;
            $skillRecord->description = $this->Description->SafeText;
			$skillRecord->keyAbilityId = $this->AbilityList->SelectedValue;
			$skillRecord->armorCheckPenalty = $this->ArmorCheckPenalty->Checked;
			$skillRecord->tryAgain = $this->TryAgain->Checked;
			$skillRecord->trained = $this->TrainedOnly->Checked;
            $skillRecord->save();
			
            $this->goToPage('admin.ListSkill');
        }
    }
}
?>