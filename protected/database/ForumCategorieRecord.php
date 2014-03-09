<?php
class ForumCategorieRecord extends TActiveRecord
{
    const TABLE='forum_categorie';
 
    public $id;
    public $nom;
	public $description;
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>