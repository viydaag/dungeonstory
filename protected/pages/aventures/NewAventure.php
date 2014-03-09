<?php
class NewAventure extends DSPage
{
    /**
     * Enregistre si toutes les validations sont Ok
     * cette méthode répond à l'évènement OnClick du bouton "Enregistrer".
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function createButtonClicked($sender,$param)
    {
        if ($this->IsValid) {
            $aventureRecord = new AventureRecord;
			$user = UserRecord::finder()->findBy_pseudo($this->User->Name);
			if ($user === null) {
				throw new THttpException(500,'User is not logged in.');
			} 
            $aventureRecord->nom = $this->Nom->SafeText;
            $aventureRecord->description = $this->Description->SafeText;
			$aventureRecord->statut = 0;
			$aventureRecord->createurId = $user->id;
			$aventureRecord->dateCreation=date('Y/m/d h:i:s');
			if ($this->Participation->SelectedValue == 0) {
				$user->aventureId = $aventureRecord->id;
			}
            // enregistre les données par la méthode save de l'Active Record
            $aventureRecord->save();
 
            // redirige le navigateur vers la page ViewAventure
            $this->goToPage('aventures.ViewAventure', array('aventureId'=>$aventureRecord->id));
        }
    }
 
}
?>