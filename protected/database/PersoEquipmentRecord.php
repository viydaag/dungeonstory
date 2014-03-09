<?php
class PersoEquipmentRecord extends TActiveRecord
{
    const TABLE='persoequipment';
 
    public $persoId;
    public $equipmentId;
	public $quantity;
    public $sellableValue;
 
 	public static $RELATIONS=array
    (
        'perso' => array(self::BELONGS_TO, 'PersoRecord', 'persoId'),
		'equipment' => array(self::BELONGS_TO, 'EquipmentRecord', 'equipmentId')
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>