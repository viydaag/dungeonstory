<?php
class ShopEquipmentRecord extends TActiveRecord
{
    const TABLE='shopequipment';
 
    public $shopId;
    public $equipmentId;
	public $quantity;
    public $unitPrice;
 
	public static $RELATIONS=array
    (
        'shop' => array(self::BELONGS_TO, 'ShopRecord', 'shopId'),
		'equipment' => array(self::BELONGS_TO, 'EquipmentRecord', 'equipmentId')
    );

    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>