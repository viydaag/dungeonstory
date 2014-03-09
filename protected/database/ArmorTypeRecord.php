<?php
class ArmorTypeRecord extends TActiveRecord
{
    const TABLE = 'armorType';
 
    public $id;
    public $name;
	public $baseDescription;
	public $armorType;
	public $maxDexBonus;
	public $baseAC;
	public $baseArmorCheckPenalty;
	public $baseArcaneSpellFailure;
	public $baseWeight;
	public $speed;
	
	//armorType
	const LIGHT = 0;
	const MEDIUM = 1;
	const HEAVY = 2;
	const SHIELD = 3;
	
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>
