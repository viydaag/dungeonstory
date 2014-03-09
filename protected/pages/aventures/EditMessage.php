<?php
class EditMessage extends DSPage
{
    /**
     * initialise les contrôles de saisies avec les données du message.
     * cette méthode est appelée lors de l'initialisation de la page
     * @param mixed param : paramètres de l'évènement
     */
    public function onInit($param) {
        parent::onInit($param);

        $messageRecord = $this->Message;
		$aventureRecord = $this->Aventure;

        // vérification des droits: seul l'auteur ou un administrateur peuvent modifier le message
        if(!$this->User->IsAdmin && $this->User->Perso->id != $messageRecord->persoId) {
            throw new DSException(500, 'message_edit_disallowed', $this->Message->id);
		}
 
        if(!$this->IsPostBack) {
            // rempli les contrôles avec les données du message
            $this->TitleEdit->Text = $messageRecord->titre;
            $this->ContentEdit->Text = $messageRecord->texte;
        }
    }
 
    /**
     * Enregistre si toutes les validations sont Ok
     * cette méthode répond à l'évènement OnClick du bouton "Enregistrer".
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function saveButtonClicked($sender,$param) {
        if($this->IsValid) {
            // récupère les données de l'utilisateur. Equivalent à:
            $messageRecord=$this->Message;
 
            // affecte les données saisies aux champs de la BDD
            $messageRecord->titre=$this->TitleEdit->SafeText;
            $messageRecord->texte=$this->ContentEdit->SafeText;
 
            // enregistre les données par la méthode save de l'Active Record
            $messageRecord->save();
 
            // redirige le navigateur vers la page ReadPost
            $this->goToPage('aventures.ListMessage',array('aventureId'=>$messageRecord->aventureId));
        }
    }
 
    /**
     * retourne les données du message devant être modifiées.
     * @return PostRecord les données devant être modifiés.
     * @throws THttpException si le message est inexistant.
     */
    protected function getMessage() {
        // l'ID du message devant être modifié passé par un paramètre GET 
        $messageID = (int)$this->Request['messageId'];
        // utilise Active Record pour lire le message correspondant à cet ID
        $messageRecord = MessageRecord::finder()->findByPk($messageID);
        if ($messageRecord === null) {
            throw new DSException(500, 'message_id_invalid', $messageID);
		}
        return $messageRecord;
    }
	
	protected function getAventure() {
        // l'ID du aventure devant être modifié passé par un paramètre GET 
        $aventureID = (int)$this->Request['aventureId'];
        // utilise Active Record pour lire l'aventure correspondant à cet ID
        $aventureRecord = aventureRecord::finder()->findByPk($aventureID);
        if ($aventureRecord === null) {
            throw new DSException(500, 'aventure_id_invalid', $aventureID);
		}
        return $aventureRecord;
    }
}
?>