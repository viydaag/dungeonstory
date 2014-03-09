<?php
class EditAlignement extends DSPage
{
    public function onInit($param) 
    {
        parent::onInit($param);

        $alignementRecord = $this->Alignement;
		
        if (!$this->User->IsAdmin)
        {
            throw new DSException(500, 'admin_view_disallowed');
		}
 
        if (!$this->IsPostBack) 
        {
            $this->NomEdit->Text = $alignementRecord->nom;
            $this->DescriptionEdit->Text = $alignementRecord->description;
			$this->DescriptionCourteEdit->Text = $alignementRecord->descriptionCourte;
        }
    }
 
    /**
     * Enregistre si toutes les validations sont Ok
     * cette méthode répond à l'évènement OnClick du bouton "Enregistrer".
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function saveButtonClicked($sender, $param) 
    {
        if($this->IsValid) 
        {
            $alignementRecord = $this->Alignement;
            $alignementRecord->nom = $this->NomEdit->SafeText;
            $alignementRecord->description = $this->DescriptionEdit->SafeText;
			$alignementRecord->descriptionCourte = $this->DescriptionCourteEdit->SafeText;
            $alignementRecord->save();
            $this->goToPage('admin.ListAlignement');
        }
    }
 
    /**
     * retourne les données du message devant être modifiées.
     * @return PostRecord les données devant être modifiés.
     * @throws THttpException si le message est inexistant.
     */
    protected function getAlignement() 
    {
        // l'ID du message devant être modifié passé par un paramètre GET 
        $alignementID = (int)$this->Request['alignementId'];
        // utilise Active Record pour lire le message correspondant à cet ID
        $alignementRecord = AlignementRecord::finder()->findByPk($alignementID);
        if ($alignementRecord === null) 
        {
            throw new DSException(500, 'alignement_id_invalid', $alignementID);
		}
        return $alignementRecord;
    }
}
?>