<?php
class EquipmentRecord extends TActiveRecord
{
    const TABLE = 'equipment';
	
    public $id;
    public $name;
	public $description;
	public $cost;
	public $weight;
	public $isMagical;
	public $type;
	//public $armorTypeId;
	//public $weaponTypeId;
	public $isPurchasable;
	public $isSellable;
	
	//equipment type
	const ARMOR_TYPE = 0;
	const WEAPON_TYPE = 1;
	const RING_TYPE = 2;
	const AMULET_TYPE = 3;
	const BRACER_TYPE = 4;
	const BOOT_TYPE = 5;
	const BELT_TYPE= 6;
	const UTIL_TYPE = 7;
	
	public static $RELATIONS=array
    (
    	'shopEquipment' => array(self::HAS_MANY, 'ShopEquipmentRecord', 'equipmentId'),
        //'armorType' => array(self::BELONGS_TO, 'ArmorTypeRecord', 'armorTypeId'),
		//'weaponType' => array(self::BELONGS_TO, 'WeaponTypeRecord', 'weaponTypeId')
    );
	 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
	
	/*
	public function getTypeClass() {
		$typeClass = "";
		switch ($this->$type) {
			case EquipmentRecord::ARMOR_TYPE : 
				$typeTable = 'ArmorTypeRecord';
				break;
			case EquipmentRecord::WEAPON_TYPE : 
				$typeTable = 'WeaponTypeRecord';
				break;
			default : throw new DSException(500, 'equipment_type_invalid', $this->$type);
		}
		return $typeClass;
	}
	
	public function getTypeRecord() {
		$typeClass = $this->getTypeClass();
		$finder = ActiveRecord::finder($typeClass);
		$record = $finder->findByPk($this->typeId);
		if ($featRecord === null) {
            throw new DSException(500, 'equipment_type_invalid', $this->typeId);
		}
		return $record;
	}
	*/
}
?>
