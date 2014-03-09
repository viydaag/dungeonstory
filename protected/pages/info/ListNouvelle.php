<?php
class ListNouvelle extends DSPage
{
    /**
     * Initialise le TRepeater.
     * Cette méthode est appelé par le framework lors de l'initialisation de la page
     * @param mixed param : paramètres de l'évènement
     */
    public function onInit($param) {
        parent::onInit($param);
        if (!$this->IsPostBack) {
            $this->RepeaterNouvelle->VirtualItemCount = NouvelleRecord::finder()->count();
            $this->populateData();
        }
    }
 
    /**
     * Gestionnaire d'évènement pour OnPageIndexChanged du TPager.
     * Cette méthode est appelée lors du changement de page
     */
    public function pageChanged($sender,$param) {
        $this->RepeaterNouvelle->CurrentPageIndex = $param->NewPageIndex;
        $this->populateData();
    }
 
    /**
     * détermine quelle page doit être affichée et remplie
     * TRepeater avec les données lues
     */
    protected function populateData() {
        $offset = $this->RepeaterNouvelle->CurrentPageIndex * $this->RepeaterNouvelle->PageSize;
        $limit = $this->RepeaterNouvelle->PageSize;
        if ($offset + $limit > $this->RepeaterNouvelle->VirtualItemCount) {
            $limit = $this->RepeaterNouvelle->VirtualItemCount - $offset;
		}
        $this->RepeaterNouvelle->DataSource = $this->getNouvelles($offset, $limit);
        $this->RepeaterNouvelle->dataBind();
    }
	
	/**
     * lis les données à partir de la base de données en utilisant les fonctionnalités offset et limit.
     */
    protected function getNouvelles($offset, $limit) {
        $criteria=new TActiveRecordCriteria;
        $criteria->OrdersBy['datePublication'] = 'desc';
        $criteria->Limit=$limit;
        $criteria->Offset=$offset;
        return NouvelleRecord::finder()->findAll($criteria);
    }
}
?>