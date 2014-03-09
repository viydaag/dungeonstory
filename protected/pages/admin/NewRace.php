<?php
class NewRace extends DSPage
{
	public function saveButtonClicked($sender, $param) 
	{
        if ($this->IsValid) 
        {
			$raceRecord = new RaceRecord();
			$raceRecord->nom = $this->Nom->SafeText;
			$raceRecord->description = $this->Description->SafeText;
			$raceRecord->descriptionCourte = $this->DescriptionCourte->SafeText;
			$raceRecord->modifForce = TPropertyValue::ensureInteger($this->ModifForce->Text);
			$raceRecord->modifDexterite = TPropertyValue::ensureInteger($this->ModifDex->Text);
			$raceRecord->modifConstitution = TPropertyValue::ensureInteger($this->ModifConst->Text);
			$raceRecord->modifIntelligence = TPropertyValue::ensureInteger($this->ModifIntel->Text);
			$raceRecord->modifSagesse = TPropertyValue::ensureInteger($this->ModifSag->Text);
			$raceRecord->modifCharisme = TPropertyValue::ensureInteger($this->ModifCha->Text);
			$raceRecord->minAge = TPropertyValue::ensureInteger($this->MinAge->Text);
			$raceRecord->maxAge = TPropertyValue::ensureInteger($this->MaxAge->Text);
			$raceRecord->tailleMoyenne = TPropertyValue::ensureInteger($this->TailleMoyenne->Text);
			$raceRecord->poidsMoyen = TPropertyValue::ensureInteger($this->PoidsMoyen->Text);
			$raceRecord->modifAge = $this->ModifAge->Text;
			$raceRecord->modifPoids = $this->ModifPoids->Text;
			$raceRecord->modifTaille = $this->ModifTaille->Text;
            $raceRecord->save();
            $this->goToPage('admin.ListRace');
        }
    }
}
?>