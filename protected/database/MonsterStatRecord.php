<?php
class MonsterStatRecord extends TActiveRecord
{
    const TABLE = 'monsterstat';
 
    public $id;
    public $monsterId;
	public $level;
	public $challengeRating;
	public $levelAdjustment;
	public $xpAward;
	public $alignmentId;
	public $baseAC;
	public $life;
	public $strength;
	public $dexterity;
	public $constitution;
	public $intelligence;
	public $wisdom;
	public $charisma;
	public $initiative;
	
	public static $RELATIONS=array
    (
        'monster' => array(self::BELONGS_TO, 'MonsterRecord', 'monsterId'),
		'alignment' => array(self::BELONGS_TO, 'AlignementRecord', 'alignmentId'),
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>
