<?php
class ViewMessage extends DSPage
{
    private $_message;
	
    /**
     * lis les données du message.
     * cette méthode est appelée lors de l'initialisation de la page
     * @param mixed param : paramètres de l'évènement
     */
    public function onInit($param) {
        parent::onInit($param);
        // id du message passé par un paramètre GET
        $messageId = (int)$this->Request['messageId'];
        // lis le message ainsi que les données correspondantes à l'auteur
        $this->_message = MessageRecord::finder()->withPerso()->withAventure()->findByPk($messageId);
        if ($this->_message===null) {  // si l'id du message est invalide
            throw new DSException(500, 'message_id_invalid', $messageId);
		}
        // défini le titre de la page comme étant celui du message
        $this->Title=$this->_message->titre;
    }
 
    /**
     * @return MessageRecord retourne l'objet PostRecord correspondant au message
     */
    public function getMessage() {
        return $this->_message;
    }
 
    /**
     * supprime le message actuellement visualisé
     * cette méthode est appelée par l'évènement OnClick du bouton "Supprimer"
     */
    public function deleteMessage($sender, $param) {
        // seul l'auteur ou un administrateur peuvent supprimer le message
        if ($this->canEdit()) {
			// le supprime de la base de données
			$this->_message->delete();
			// redirige le navigateur vers la page d'accueil
			$this->goToDefaultPage();
		} else {
            throw new DSException(500, 'message_delete_disallowed', $this->Message->id);
		}
        
    }
	
	public function goToEditMessage($sender, $param) {
        if ($this->canEdit()) {
			$this->goToPage('aventures.EditMessage', array('aventureId'=>$this->Message->aventureId, 'messageId'=>$this->Message->id));	
		} else {
            throw new DSException(500, 'message_edit_disallowed', $this->Message->id);
		}
    }
 
    /**
     * @return boolean infiquant si le message peut être modifier ou supprimer par l'utilisateur actuel
     */
    public function canEdit() {
        // seul l'auteur ou un administrateur peuvent modifier/supprimer le message
        return $this->User->Perso->id === $this->Message->persoId || $this->User->IsAdmin;
    }
	
	public function addXP() {
		$persoRecord = PersoRecord::finder()->findByPk($this->_message->persoId);
		if ($persoRecord === null) {
            throw new DSException(500, 'perso_is_invalid', $this->_message->persoId);
		}
		$persoRecord->gainXP(100);
		$this->_message->isXpGiven = true;
		$this->_message->save();
		
		$this->goToPage('aventures.ViewMessage', array('aventureId'=>$this->Message->aventureId, 'messageId'=>$this->Message->id));	
	}
}
?>