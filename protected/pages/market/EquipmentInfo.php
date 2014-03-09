<?php
include("MarketHelper.php");
class EquipmentInfo extends DSPage
{
    public function onInit($param) {
        parent::onInit($param);
        if(!$this->IsPostBack) {
			
        }
    }
	
	public function getEquipment() {
		return MarketHelper::getEquipmentRecord((int)$this->Request['equipmentId']);
	}
}
?>
