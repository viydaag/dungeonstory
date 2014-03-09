<?php
class ClasseSkillRecord extends TActiveRecord
{
    const TABLE='classeskill';
 
    public $classeId;
    public $skillId;
 
 	public static $RELATIONS=array
    (
        'classe' => array(self::BELONGS_TO, 'ClasseRecord', 'classeId'),
		'skill' => array(self::BELONGS_TO, 'SkillRecord', 'skillId')
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>