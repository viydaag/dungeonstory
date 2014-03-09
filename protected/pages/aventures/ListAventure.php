<?php
class ListAventure extends DSPage
{
    /**
     * Initialise le TRepeater.
     * Cette méthode est appelé par le framework lors de l'initialisation de la page
     * @param mixed param : paramètres de l'évènement
     */
    public function onInit($param) {
        parent::onInit($param);
        if(!$this->IsPostBack) {
            // récupère le nombre total de messages
            $this->RepeaterAventure->VirtualItemCount=AventureRecord::finder()->count();
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
        $this->RepeaterAventure->CurrentPageIndex=$param->NewPageIndex;
        // rempli de nouveau le TRepeater
        $this->populateData();
    }
 
    /**
     * détermine quelle page doit être affichée et remplie
     * TRepeater avec les données lues
     */
    protected function populateData() {
        $offset=$this->RepeaterAventure->CurrentPageIndex*$this->RepeaterAventure->PageSize;
        $limit=$this->RepeaterAventure->PageSize;
        if($offset+$limit>$this->RepeaterAventure->VirtualItemCount)
            $limit=$this->RepeaterAventure->VirtualItemCount-$offset;
        $this->RepeaterAventure->DataSource=$this->getAventures($offset,$limit);
        $this->RepeaterAventure->dataBind();
    }
	
	/**
     * lis les données à partir de la base de données en utilisant les fonctionnalités offset et limit.
     */
    protected function getAventures($offset, $limit) {
        // construit les critères de la requête
        $criteria=new TActiveRecordCriteria;
        $criteria->OrdersBy['dateCreation']='desc';
        $criteria->Limit=$limit;
        $criteria->Offset=$offset;
        // lit les messages en fonction des critères précédents
        return AventureRecord::finder()->withUser()->findAll($criteria);
    }

}
?>