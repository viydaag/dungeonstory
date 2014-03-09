<?php
class ClassLevelBonusRecord extends TActiveRecord
{
    const TABLE='classlevelbonus';
 
    public $classId;
    public $levelId;
	public $baseAttackBonus;
	public $fortSave;
	public $refSave;
	public $willSave;
	public $acBonus;
	public $speedBonus;
	public $spellPerDay0;
	public $spellPerDay1;
	public $spellPerDay2;
	public $spellPerDay3;
	public $spellPerDay4;
	public $spellPerDay5;
	public $spellPerDay6;
	public $spellPerDay7;
	public $spellPerDay8;
	public $spellPerDay9;
	
	public static $RELATIONS=array
    (
        'class' => array(self::BELONGS_TO, 'ClasseRecord', 'classeId'),
		'level' => array(self::BELONGS_TO, 'LevelRecord', 'levelId')
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>
