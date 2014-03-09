<?php
class EditSkill extends DSPage
{
    public function onInit($param) {
        parent::onInit($param);

        $skillRecord = $this->Skill;
		
        if(!$this->User->IsAdmin) {
            throw new DSException(500, 'admin_view_disallowed');
		}
 
        if(!$this->IsPostBack) {
			$abilityfinder = AbilityRecord::finder();
			$abilities = $abilityfinder->findAll();
			$this->AbilityListEdit->DataSource = $abilities;
			$this->AbilityListEdit->dataBind();	
		
            $this->NameEdit->Text = $skillRecord->name;
            $this->DescriptionEdit->Text = $skillRecord->description;
			$this->AbilityListEdit->SelectedValue = $skillRecord->keyAbilityId;
			$this->ArmorCheckPenaltyEdit->Checked = $skillRecord->armorCheckPenalty;
			$this->TryAgainEdit->Checked = $skillRecord->tryAgain;
			$this->TrainedOnlyEdit->Checked = $skillRecord->trained;	
		}
    }
 
    public function saveButtonClicked($sender, $param) {
        if ($this->IsValid) {
			$skillRecord = $this->Skill;
            $skillRecord->name = $this->NameEdit->SafeText;
            $skillRecord->description = $this->DescriptionEdit->SafeText;
			$skillRecord->keyAbilityId = $this->AbilityListEdit->SelectedValue;
			$skillRecord->armorCheckPenalty = $this->ArmorCheckPenaltyEdit->Checked;
			$skillRecord->tryAgain = $this->TryAgainEdit->Checked;
			$skillRecord->trained = $this->TrainedOnlyEdit->Checked;
            $skillRecord->save();
			
            $this->goToPage('admin.ListSkill');
        }
    }
 
    protected function getSkill() {
        // l'ID du message devant être modifié passé par un paramètre GET 
        $skillID = (int)$this->Request['skillId'];
        // utilise Active Record pour lire le message correspondant à cet ID
        $skillRecord = SkillRecord::finder()->findByPk($skillID);
        if ($skillRecord === null) {
            throw new DSException(500, 'skill_id_invalid', $skillID);
		}
        return $skillRecord;
    }
}
?>