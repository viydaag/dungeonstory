<?php
class DamageTypeRecord extends TActiveRecord
{
    const TABLE='damagetype';
 
    public $id;
    public $name;
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>