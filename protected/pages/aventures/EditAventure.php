<?php
class EditAventure extends DSPage
{
    /**
     * initialise les contrôles de saisies avec les données du Aventure.
     * cette méthode est appelée lors de l'initialisation de la page
     * @param mixed param : paramètres de l'évènement
     */
    public function onInit($param) 
    {
        parent::onInit($param);
        // récupère les données de l'utilisateur. Equivalent à:
        $user = $this->User->Profile;
		if ($user === null) 
        {
			throw new THttpException(500, 'profile_not_found');
		}
        // vérification des droits: seul l'auteur ou un administrateur peuvent modifier le aventure
        if (!$this->User->IsAdmin && $user->id != $this->Aventure->createurId) 
        {
            throw new DSException(500, 'aventure_edit_disallowed', $this->Aventure->id);
		}
 		$aventureRecord = $this->Aventure;
        if (!$this->IsPostBack) 
        {
            // rempli les contrôles avec les données de l'aventure
            $this->NomEdit->Text = $aventureRecord->nom;
            $this->DescriptionEdit->Text = $aventureRecord->description;
			$this->StatutEdit->SelectedValue = $aventureRecord->statut;
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
            $aventureRecord=$this->Aventure;
 
            // affecte les données saisies aux champs de la BDD
            $aventureRecord->nom = $this->NomEdit->SafeText;
            $aventureRecord->description = $this->DescriptionEdit->SafeText;
			$aventureRecord->statut = $this->StatutEdit->SelectedValue;
 
            // enregistre les données par la méthode save de l'Active Record
            $aventureRecord->save();
 
            // redirige le navigateur vers la page ReadPost
            $this->goToPage('aventures.ListAventure');
        }
    }
 
    /**
     * retourne les données du aventure devant être modifiées.
     * @return PostRecord les données devant être modifiés.
     * @throws THttpException si le aventure est inexistant.
     */
    protected function getAventure() 
    {
        // l'ID du aventure devant être modifié passé par un paramètre GET 
        $aventureID = (int)$this->Request['aventureId'];
        // utilise Active Record pour lire l'aventure correspondant à cet ID
        $aventureRecord=aventureRecord::finder()->findByPk($aventureID);
        if ($aventureRecord === null) 
        {
           throw new DSException(500, 'aventure_id_invalid', $aventureID);
		}
        return $aventureRecord;
    }
}
?>