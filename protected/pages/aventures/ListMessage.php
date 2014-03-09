<?php
class ListMessage extends DSPage
{

	private $_aventure;
	
    /**
     * Initialise le TRepeater.
     * Cette méthode est appelé par le framework lors de l'initialisation de la page
     * @param mixed param : paramètres de l'évènement
     */
    public function onInit($param) {
        parent::onInit($param);
		//echo (int)$this->Request['aventureId'];
		$this->_aventure = AventureRecord::finder()->findByPk((int)$this->Request['aventureId']);
		if ($this->_aventure === NULL) {
			throw new DSException(500, 'aventure_id_invalid', $this->Request['aventureId']);
		}
        if (!$this->IsPostBack) {
            // récupère le nombre total de messages
            $this->RepeaterMessage->VirtualItemCount=MessageRecord::finder()->count();
            // rempli le TRepeater avec les données
            $this->populateData();
        }
    }
 
    /**
     * Gestionnaire d'évènement pour OnPageIndexChanged du TPager.
     * Cette méthode est appelée lors du changement de page
     */
    public function pageChanged($sender,$param) {
        // change l'index de la page courante par le nouvel index
        $this->RepeaterMessage->CurrentPageIndex=$param->NewPageIndex;
        // rempli de nouveau le TRepeater
        $this->populateData();
    }
 
    /**
     * détermine quelle page doit être affichée et remplie
     * TRepeater avec les données lues
     */
    protected function populateData() {
        $offset=$this->RepeaterMessage->CurrentPageIndex*$this->RepeaterMessage->PageSize;
        $limit=$this->RepeaterMessage->PageSize;
        if($offset+$limit>$this->RepeaterMessage->VirtualItemCount)
            $limit=$this->RepeaterMessage->VirtualItemCount-$offset;
        $this->RepeaterMessage->DataSource=$this->getMessages($offset,$limit);
        $this->RepeaterMessage->dataBind();
    }
	
	/**
     * lis les données à partir de la base de données en utilisant les fonctionnalités offset et limit.
     */
    protected function getMessages($offset, $limit) {
        // construit les critères de la requête
        $criteria=new TActiveRecordCriteria;
		$criteria->Condition = 'aventureId = :aventure';
		$criteria->Parameters[':aventure'] = $this->Request['aventureId'];
        $criteria->OrdersBy['dateCreation']='desc';
        $criteria->Limit=$limit;
        $criteria->Offset=$offset;
        // lit les messages en fonction des critères précédents
        return MessageRecord::finder()->withPerso()->findAll($criteria);
    }
	
	public function getAventure() {
		return $this->_aventure;
	}
	
	public function isUserAllowToCreateMessage() {
		$ok = false;
		if (!$this->User->IsGuest && $this->User->hasPerso() && $this->User->Profile->isActive()) {
			if ($this->_aventure->isNotTerminated() 
				&& ($this->User->Perso->aventureId == $this->_aventure->id || $this->User->Profile->id == $this->_aventure->createurId)) {
				$ok = true;
			}
		}
		return $ok;
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
		if (!$this->User->IsGuest && $this->User->hasPerso()) {
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