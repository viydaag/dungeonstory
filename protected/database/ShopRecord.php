<?php
class ShopRecord extends TActiveRecord
{
    const TABLE='shop';
 
    public $id;
    public $name;
	public $description;
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>
