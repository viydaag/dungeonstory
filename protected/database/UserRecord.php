<?php
class UserRecord extends TActiveRecord
{
    const TABLE='user';
 
    public $id;
    public $pseudo;
	public $password;
	public $roleId;
	public $nom;
	public $email;
	public $statut;
	public $dateCreation;
	
	const STATUT_ATTENTE = 0;
	const STATUT_ACTIVE = 1;
	const STATUT_DESACTIVE = 2;
	
	public static $RELATIONS=array
    (
        'role' => array(self::BELONGS_TO, 'RoleRecord', 'roleId'),
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
	
	public function getStringStatut() {
		switch ($this->statut) {
			case 0 : $s = 'En Attente';
					 break;
			case 1 : $s = 'Activé';
					 break;
			case 2 : $s = 'Désactivé';
					 break;
		}
		return $s;
	}
	
	public function isActive() {
		return $this->statut == UserRecord::STATUT_ACTIVE;
	}
	
	public function isDeactivated() {
		return $this->statut == UserRecord::STATUT_DESACTIVE;
	}

}
?>