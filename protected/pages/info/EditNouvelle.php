<?php
class EditNouvelle extends DSPage
{
	public function onInit($param) {
        parent::onInit($param);
		if (!$this->IsPostBack) {
			$nouvelleRecord = $this->Nouvelle;			
			$this->TitreEdit->Text = $nouvelleRecord->titre;
			$this->DescriptionEdit->Text = $nouvelleRecord->description;
		}
    }
	
	public function getNouvelle() {
        $nouvelleId = (int)$this->Request['nouvelleId']; 
		$nouvelle = NouvelleRecord::finder()->findByPk($nouvelleId);
		if ($nouvelle === null) {  
			throw new DSException(500, 'nouvelle_id_invalid', $nouvelleId);
		}
		return $nouvelle;
    }    

	public function saveNouvelle($sender,$param) {
        if ($this->IsValid) {
			$nouvelleRecord = $this->Nouvelle;
            $nouvelleRecord->titre = $this->TitreEdit->SafeText;
            $nouvelleRecord->description = $this->DescriptionEdit->SafeText;
            $nouvelleRecord->save();
            $this->goToPage('info.ListNouvelle');
        }
    }
	
	public function deleteNouvelle($sender, $param) {
        if ($this->User->IsAdmin) {
			$this->Nouvelle->delete();
			$this->goToPage('info.ListNouvelle');
		} else {
            throw new DSException(500, 'nouvelle_delete_disallowed', $this->Nouvelle->id);
		}
    }
}
?>