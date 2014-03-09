<?php
class ForumMessageRecord extends TActiveRecord
{
    const TABLE='forum_message';
 
    public $id;
    public $texte;
	public $discussionId;
	public $userId;
	public $dateCreation;

	
	public static $RELATIONS=array
    (
        'user' => array(self::BELONGS_TO, 'UserRecord', 'userId'),
		'discussion' => array(self::BELONGS_TO, 'ForumDiscussionRecord', 'discussionId'),
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }

}
?>