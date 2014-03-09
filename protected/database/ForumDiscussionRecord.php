<?php
class ForumDiscussionRecord extends TActiveRecord
{
    const TABLE='forum_discussion';
 
    public $id;
	public $sujet;
    public $description;
	public $categorieId;
	public $userId;
	public $dateCreation;
	public $dernierModificateurId;
	
	public static $RELATIONS=array
    (
        'user' => array(self::BELONGS_TO, 'UserRecord', 'userId'),
		'categorie' => array(self::BELONGS_TO, 'ForumCategorieRecord', 'categorieId'),
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }

}
?>