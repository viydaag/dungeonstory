<?php
class FeatRecord extends TActiveRecord
{
    const TABLE='feat';
 
    public $id;
    public $name;
	public $description;
	public $type;
	
	//type
	const GENERAL = 0;
	const ITEM_CREATION = 1;
	const METAMAGIC = 2;
	
	public static $RELATIONS=array
    (
		'prerequisites' => array(self::HAS_MANY, 'FeatPrerequisiteRecord', 'featId')
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
	
	public function getStringType() {
		switch ($this->statut) {
			case GENERAL : $s = 'Général';
					 break;
			case ITEM_CREATION : $s = "Création d'item";
					 break;
			case METAMAGIC : $s = 'Metamagique';
					 break;
		}
		return $s;
	}
}
?>