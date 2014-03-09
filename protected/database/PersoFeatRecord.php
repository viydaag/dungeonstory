<?php
class PersoFeatRecord extends TActiveRecord
{
    const TABLE = 'persofeat';
 
    public $persoId;
    public $featId;
 
 	public static $RELATIONS=array
    (
        'perso' => array(self::BELONGS_TO, 'PersoRecord', 'persoId'),
		'feat' => array(self::BELONGS_TO, 'FeatRecord', 'featId')
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>