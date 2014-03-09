<?php
class FeatPrerequisiteRecord extends TActiveRecord
{
    const TABLE = 'featprerequisite';
 
    public $id;
    public $featId;
	public $type;
	public $featPrerequisiteId;
	public $abilityId;
	public $abilityValue;
	public $baseAttackBonus;
	public $level;
	public $classId;
	public $skillId;
	public $skillRank;
	
	
	//type
	const FEAT = 0;
	const ABILITY = 1;
	const ATTACK_BONUS = 2;
	const LEVEL = 3;
	const CLASSE = 4;
	const SKILL = 5;
	
	public static $RELATIONS=array
    (
		'feat' => array(self::BELONGS_TO, 'FeatRecord', 'featId'),
        'ability' => array(self::BELONGS_TO, 'AbilityRecord', 'abilityId'),
		'featPrerequisite' => array(self::BELONGS_TO, 'FeatRecord', 'featPrequisiteId'),
		'class' => array(self::BELONGS_TO, 'ClasseRecord', 'classId'),
		'skill' => array(self::BELONGS_TO, 'SkillRecord', 'skillId')
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>