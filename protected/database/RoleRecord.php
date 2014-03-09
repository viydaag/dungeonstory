<?php
class RoleRecord extends TActiveRecord
{
    const TABLE='role';
 
    public $id;
    public $nom;
	
	public static $RELATIONS=array
    (
        'users' => array(self::HAS_MANY, 'UserRecord', 'roleId'),
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>