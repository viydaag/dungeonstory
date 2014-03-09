<?php
class LevelRecord extends TActiveRecord
{
    const TABLE='level';
 
    public $niveau;
	public $maxExperience;
	public $bonusFeat;
	public $bonusAbilityScore;
	public $maxClassSkillRanks;
	public $maxCrossClassSkillRanks;
	
	public static $RELATIONS=array
    (
		'levelBonus' => array(self::HAS_MANY, 'ClassLevelBonusRecord', 'levelId')
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>