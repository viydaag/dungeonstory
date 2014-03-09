<?php
class NouvelleRecord extends TActiveRecord
{
    const TABLE='nouvelle';
 
    public $id;
    public $titre;
	public $description;
	public $datePublication;
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>