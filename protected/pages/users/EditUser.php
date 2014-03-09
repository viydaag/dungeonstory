<?php
class EditUser extends DSPage
{	
	/**
     * Initialise les champs avec les données de l'utilisateur.
     * Cette méthode est appelée par le framework lorsque la page est initialisée.
     * @param mixed param : paramètres de l'évènement
  	 */
    public function onInit($param) {
        parent::onInit($param);
        if (!$this->IsPostBack) {		
			$finder = RoleRecord::finder();
			$roles = $finder->findAll();
			$this->RoleList->DataSource = $roles;
			$this->RoleList->dataBind();

            $userRecord = $this->User->Profile;
 
            // Rempli les contrôles avec les données de l'utilisateur
            $this->PseudoTextBox->Text = $userRecord->pseudo;
			$this->PasswordTextBox->Text = 
            $this->RoleList->SelectedValue = $userRecord->roleId;
            $this->NomTextBox->Text = $userRecord->nom;
			$this->EmailTextBox->Text = $userRecord->email;
        }
    }
	
	public function checkUsername($sender,$param)  {
        // valide si l'utilisateur existe
        $param->IsValid = UserRecord::finder()->findBy_pseudo($this->PseudoTextBox->Text) === null;
    }

    /**
     * Enregistre les modifications si tous les validateurs sont Ok.
     * Cette méthode répond à l'évènement OnClick du bouton "Enregistrer".
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function saveButtonClicked($sender,$param) {
        if ($this->IsValid) {
            // Lit les informations de l'utilisateur.
            $userRecord = $this->User->Profile;
 
            // Enresgistre les valeurs dans les champs de la BDD
            $userRecord->pseudo = $this->PseudoTextBox->Text;
            $userRecord->password = md5($this->PasswordTextBox->Text);
			$userRecord->nom = $this->NomTextBox->Text;
			$userRecord->email = $this->EmailTextBox->Text;

            // mets à jour le rôle si l'utilisateur actuel est un administrateur
            if ($this->User->isAdmin) {// $userRecord->roleId != $this->RoleList->SelectedValue) {
				$userRecord->roleId = (int)$this->RoleList->SelectedValue;
			}
            
            // enregistre les modifications dans la BD
            $userRecord->save();

            // redirige vers la page d'accueil
			$this->goToPage('users.ViewUser', array('userId'=>$userRecord->id));
        }
    }
}
?>