<?php
class NewNouvelle extends DSPage
{
	public function saveNouvelle($sender,$param) {
        if ($this->IsValid) {
			$nouvelleRecord = new NouvelleRecord();
            $nouvelleRecord->titre = $this->Titre->SafeText;
            $nouvelleRecord->description = $this->Description->SafeText;
            $nouvelleRecord->save();
            $this->goToPage('info.ListNouvelle');
        }
    }
}
?>