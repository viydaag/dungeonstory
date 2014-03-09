<?php
class AventureRecord extends TActiveRecord
{
    const TABLE='aventure';
 
    public $id;
    public $nom;
	public $description;
	public $dateCreation;
	public $createurId;
	public $statut;
	
	public static $RELATIONS=array
    (
        'user' => array(self::BELONGS_TO, 'UserRecord', 'createurId'),
		'perso' => array(self::BELONGS_TO, 'PersoRecord', 'persoId'),
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
	
	public function getStringStatut() {
		switch ($this->statut) {
			case 0 : $s = 'ouverte';
					 break;
			case 1 : //$s = 'démarr&eacute;e';
					$s = "démarrée";
					 break;
			case 2 : $s = 'terminé';
					 break;
			case 3 : $s = 'annulée';
					 break;
			default : $s = 'ouverte';
		}
		return $s;
	}
	
	public function isNotTerminated() {
		if ($this->statut != 2 && $this->statut != 3) {
			return true;
		} else {
			return false;
		}
	} 
}
?>