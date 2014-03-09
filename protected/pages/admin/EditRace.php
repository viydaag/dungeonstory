<?php
class EditRace extends DSPage
{
    public function onInit($param) 
    {
        parent::onInit($param);

        $raceRecord = $this->Race;
		
        if (!$this->User->IsAdmin) 
        {
            throw new DSException(500, 'admin_view_disallowed');
		}
 
        if (!$this->IsPostBack) 
        {
            $this->NomEdit->Text = $raceRecord->nom;
			$this->MFEdit->Text = $raceRecord->modifForce;
			$this->MDEdit->Text = $raceRecord->modifDexterite;
			$this->MCoEdit->Text = $raceRecord->modifConstitution;
			$this->MIEdit->Text = $raceRecord->modifIntelligence;
			$this->MSEdit->Text = $raceRecord->modifSagesse;
			$this->MChEdit->Text = $raceRecord->modifCharisme;
			$this->MinAgeEdit->Text = $raceRecord->minAge;
			$this->MaxAgeEdit->Text = $raceRecord->maxAge;
			$this->MAEdit->Text = $raceRecord->modifAge;
			$this->TMoyenneEdit->Text = $raceRecord->tailleMoyenne;
			$this->PMoyenEdit->Text = $raceRecord->poidsMoyen;
			$this->MTEdit->Text = $raceRecord->modifTaille;
			$this->MPEdit->Text = $raceRecord->modifPoids;
			$this->DescriptionCourteEdit->Text = $raceRecord->descriptionCourte;
            $this->DescriptionEdit->Text = $raceRecord->description;
        }
    }
 
    /**
     * Enregistre si toutes les validations sont Ok
     * cette méthode répond à l'évènement OnClick du bouton "Enregistrer".
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function saveButtonClicked($sender,$param) 
    {
        if ($this->IsValid) 
        {
            $raceRecord = $this->Race;
            $raceRecord->nom = $this->NomEdit->SafeText;
			$raceRecord->modifForce = TPropertyValue::ensureInteger($this->MFEdit->Text); 
			$raceRecord->modifDexterite = TPropertyValue::ensureInteger($this->MDEdit->Text);
			$raceRecord->modifConstitution = TPropertyValue::ensureInteger($this->MCoEdit->Text); 
			$raceRecord->modifIntelligence = TPropertyValue::ensureInteger($this->MIEdit->Text);
			$raceRecord->modifSagesse = TPropertyValue::ensureInteger($this->MSEdit->Text); 
			$raceRecord->modifCharisme = TPropertyValue::ensureInteger($this->MChEdit->Text);
			$raceRecord->minAge = TPropertyValue::ensureInteger($this->MinAgeEdit->Text);
			$raceRecord->maxAge = TPropertyValue::ensureInteger($this->MaxAgeEdit->Text);
			$raceRecord->modifAge = $this->MAEdit->Text ;
			$raceRecord->tailleMoyenne = TPropertyValue::ensureInteger($this->TMoyenneEdit->Text);
			$raceRecord->poidsMoyen = TPropertyValue::ensureInteger($this->PMoyenEdit->Text);
			$raceRecord->modifTaille = $this->MTEdit->Text;
			$raceRecord->modifPoids = $this->MPEdit->Text;
			$raceRecord->descriptionCourte = $this->DescriptionCourteEdit->SafeText;
            $raceRecord->description = $this->DescriptionEdit->SafeText;
            $raceRecord->save();
            $this->goToPage('admin.ListRace');
        }
    }
 
    /**
     * retourne les données du message devant être modifiées.
     * @return PostRecord les données devant être modifiés.
     * @throws THttpException si le message est inexistant.
     */
    protected function getRace() 
    {
        // l'ID du message devant être modifié passé par un paramètre GET 
        $raceID = (int)$this->Request['raceId'];
        // utilise Active Record pour lire le message correspondant à cet ID
        $raceRecord = RaceRecord::finder()->findByPk($raceID);
        if ($raceRecord === null) 
        {
            throw new DSException(500, 'race_id_invalid', $raceID);
		}
        return $raceRecord;
    }
}
?>