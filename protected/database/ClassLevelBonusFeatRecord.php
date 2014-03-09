<?php
class ClassLevelBonusFeatRecord extends TActiveRecord
{
    const TABLE='classlevelbonusfeat';
 
    public $classId;
    public $levelId;
	public $featId;
	
	public static $RELATIONS=array
    (
        'class' => array(self::BELONGS_TO, 'ClasseRecord', 'classeId'),
		'level' => array(self::BELONGS_TO, 'LevelRecord', 'levelId'),
		'feat' => array(self::BELONGS_TO, 'FeatRecord', 'featId'),
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>
