<?php
class AbilityRecord extends TActiveRecord
{
    const TABLE='ability';
 
    public $id;
    public $name;
	public $description;
	public $abbreviation;
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>