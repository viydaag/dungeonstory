<?php
class PersoSkillRecord extends TActiveRecord
{
    const TABLE='persoskill';
 
    public $persoId;
    public $skillId;
	public $rank;
 
 	public static $RELATIONS=array
    (
        'perso' => array(self::BELONGS_TO, 'PersoRecord', 'persoId'),
		'skill' => array(self::BELONGS_TO, 'SkillRecord', 'skillId')
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>