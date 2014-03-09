<?php
class NewUser extends DSPage
{
    /**
     * Vérifie si le nom d'utilisateur existe dans la base de données.
     * Cette méthode répond à l'évènement OnServerValidate du validateur username.
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function checkUsername($sender,$param) {
        // valide si l'utilisateur existe
        $param->IsValid=UserRecord::finder()->findBy_pseudo($this->Username->Text) === null;
    }
 
    /**
     * Créer un nouveau compte utilisateur si tous les champs sont valides.
     * Cette méthode répond à l'évènement OnClick du bouton "create".
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function createButtonClicked($sender,$param) {
        if($this->IsValid) {
            // rempli l'objet UserRecord avec les données saisies
            $userRecord = new UserRecord;
            $userRecord->pseudo  = $this->Username->Text;
            $userRecord->password = md5($this->Password->Text);
            $userRecord->roleId = 2;
            $userRecord->nom = $this->Name->Text;
			$userRecord->email = $this->Email->Text;
			$userRecord->statut = 0;
 
            // l'enregistre dans la base de données par la méthode save de l'Active Record
            $userRecord->save();
 
            // redirige l'utilisateur vers la page d'accueil
			$this->goToDefaultPage();
        }
    }
}
?>