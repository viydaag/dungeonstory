<?php
class WeaponTypeRecord extends TActiveRecord
{
    const TABLE = 'weapontype';
 
    public $id;
    public $name;
	public $baseDescription;
	public $proficiency;
	public $type;
	public $use;
	public $baseDamage;
	public $damageTypeId;
	public $rangeIncrement;
	public $rangeType;
	public $isDouble;
	public $isReach;
	public $baseWeight;
	
	//use
	const MELEE_USE = 0;
	const RANGE_USE = 1;
	
	//type
	const LIGHT_TYPE = 0;
	const ONE_HANDED_TYPE = 1;
	const TWO_HANDED_TYPE = 2;
	
	//profiency
	const SIMPLE_PROF = 0;
	const MARTIAL_PROF = 1;
	const EXOTIC_PROF = 2;
	
	//range type
	const PROJECTILE_RANGETYPE = 0;
	const THROWN_RANGETYPE = 1;
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }

    public static $RELATIONS=array
    (
        'damageType' => array(self::BELONGS_TO, 'DamageTypeRecord', 'damageTypeId'),
    );
}
?>
