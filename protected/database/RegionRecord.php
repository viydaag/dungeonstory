<?php
class RegionRecord extends TActiveRecord
{
    const TABLE='region';
 
    public $id;
    public $nom;
	public $description;
	public $descriptionCourte;
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>