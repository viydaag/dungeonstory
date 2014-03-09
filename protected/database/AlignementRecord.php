<?php
class AlignementRecord extends TActiveRecord
{
    const TABLE='alignement';
 
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