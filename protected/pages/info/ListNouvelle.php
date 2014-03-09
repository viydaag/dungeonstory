<?php
class ListNouvelle extends DSPage
{
    /**
     * Initialise le TRepeater.
     * Cette m�thode est appel� par le framework lors de l'initialisation de la page
     * @param mixed param : param�tres de l'�v�nement
     */
    public function onInit($param) {
        parent::onInit($param);
        if (!$this->IsPostBack) {
            $this->RepeaterNouvelle->VirtualItemCount = NouvelleRecord::finder()->count();
            $this->populateData();
        }
    }
 
    /**
     * Gestionnaire d'�v�nement pour OnPageIndexChanged du TPager.
     * Cette m�thode est appel�e lors du changement de page
     */
    public function pageChanged($sender,$param) {
        $this->RepeaterNouvelle->CurrentPageIndex = $param->NewPageIndex;
        $this->populateData();
    }
 
    /**
     * d�termine quelle page doit �tre affich�e et remplie
     * TRepeater avec les donn�es lues
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
     * lis les donn�es � partir de la base de donn�es en utilisant les fonctionnalit�s offset et limit.
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