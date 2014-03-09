<?php
class ViewAventure extends DSPage
{
    private $_aventure;
	
    /**
     * lis les données du message.
     * cette méthode est appelée lors de l'initialisation de la page
     * @param mixed param : paramètres de l'évènement
     */
    public function onInit($param) {
        parent::onInit($param);
        // id du message passé par un paramètre GET
        $aventureId=(int)$this->Request['aventureId'];
        // lis le message ainsi que les données correspondantes à l'auteur
        $this->_aventure=AventureRecord::finder()->findByPk($aventureId);
        if ($this->_aventure===null) {  // si l'id du message est invalide
            throw new DSException(500, 'aventure_id_invalid', $aventureId);
		}
        // défini le titre de la page comme étant celui du message
        $this->Title=$this->_aventure->nom;
    }
 
    /**
     * @return MessageRecord retourne l'objet PostRecord correspondant au message
     */
    public function getAventure() {
        return $this->_aventure;
    }
 
    /**
     * @return boolean indiquant si l'aventure peut être modifié par l'utilisateur actuel
     */
    public function canEdit() {
        // seul l'auteur ou un administrateur peuvent modifier/supprimer l'aventure
        return $this->User->Name === $this->Aventure->user->pseudo || $this->User->IsAdmin;
    }
	
	public function isUserAllowToJoinAventure() {
		$ok = false;
		if (!$this->User->IsGuest && $this->User->hasPerso() && $this->User->Profile->isActive()) {
			if ($this->_aventure->StringStatut == 'ouverte' && !$this->User->Perso->isInAventure()) {
				$ok = true;
			}
		}
		return $ok;
	}
	
	public function isUserAllowToQuitAventure() {
		$ok = false;
		if (!$this->User->IsGuest && $this->User->hasPerso() && $this->User->Profile->isActive()) {
			if ($this->User->Perso->isInThisAventure($this->_aventure->id)) {
				$ok = true;
			}
		}
		return $ok;
	}
	
	public function joinAventure() {
		$this->goToPage('aventures.NewMessage', array('aventureId'=>$this->_aventure->id, 'messageType'=>'join'));
	}
	
	public function quitAventure() {
		$this->goToPage('aventures.NewMessage', array('aventureId'=>$this->_aventure->id, 'messageType'=>'quit'));
	}
}
?>