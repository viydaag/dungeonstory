<?php
class MessageRecord extends TActiveRecord
{
    const TABLE='message';
 
    public $id;
	public $titre;
    public $texte;
	public $aventureId;
	public $persoId;
	public $dateCreation;
	public $isXpGiven;
	
	public static $RELATIONS=array
    (
        'perso' => array(self::BELONGS_TO, 'PersoRecord', 'persoId'),
		'aventure' => array(self::BELONGS_TO, 'AventureRecord', 'aventureId'),
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }

}
?>