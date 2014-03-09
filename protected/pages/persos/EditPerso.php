<?php
class EditPerso extends DSPage
{	
    public function onInit($param) {
        parent::onInit($param);
		if (!$this->IsPostBack) {
			$persoRecord = $this->Perso;
			
			$alignementfinder = AlignementRecord::finder();
			$alignements = $alignementfinder->findAll();
			$this->Alignement->DataSource = $alignements;
			$this->Alignement->dataBind();
			
			$this->Alignement->SelectedValue = $persoRecord->alignementId;
			$this->ApparenceTextBox->Text = $persoRecord->apparence;
			$this->PersonnaliteTextBox->Text = $persoRecord->personnalite;
			$this->BackgroundTextBox->Text = $persoRecord->histoire;
			
			$editType = $this->Request['edit'];
			if ($editType == 'image') {
				$this->goToImageView();
			} else if ($editType == 'details') {
				$this->goToDetailsView();
			}
		}
    }
	
	public function getPerso() {
        $persoId = (int)$this->Request['persoId']; 
		$perso = PersoRecord::finder()->findByPk($persoId);
		if ($perso === null) {  
			throw new DSException(500, 'perso_id_invalid', $persoId);
		}
		return $perso;
    }    
	
	public function goToImageView() {
		$this->PersoMultiView->ActiveView = $this->ImageView;	 
		$this->ImagePortlet->generateImageView($this->Perso->sexe, $this->Perso->race->nom);
	}
	
	public function goToDetailsView() {
		$this->PersoMultiView->ActiveView = $this->DetailsView;
	}

	public function saveImage($sender, $param) {
		$persoRecord = $this->Perso;
		foreach($this->ImagePortlet->ImageRepeater->Items as $item) {			
			if ($item->RadioButton->Checked) {
				$persoRecord->image = $item->RadioButton->InputAttributes['value'];
			}
        }
		$persoRecord->save();
		$this->goToPage('persos.ViewPerso', array('persoId'=>$persoRecord->id));
	}
	
	public function saveDetails($sender, $param) {
		$persoRecord = $this->Perso;
		$persoRecord->alignementId = $this->Alignement->SelectedValue;
		$persoRecord->apparence = $this->ApparenceTextBox->Text;
		$persoRecord->personnalite = $this->PersonnaliteTextBox->Text;
		$persoRecord->histoire = $this->BackgroundTextBox->Text;
		$persoRecord->save();
		$this->goToPage('persos.ViewPerso', array('persoId'=>$persoRecord->id));
	}
}
?>