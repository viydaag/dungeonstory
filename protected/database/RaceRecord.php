<?php
class RaceRecord extends TActiveRecord
{
    const TABLE='race';
 
    public $id;
    public $nom;
	public $description;
	public $descriptionCourte;
	public $modifForce;
	public $modifDexterite;
	public $modifConstitution;
	public $modifIntelligence;
	public $modifSagesse;
	public $modifCharisme;
	public $minAge;
	public $maxAge;
	public $tailleMoyenne;
	public $poidsMoyen;
	public $modifAge;
	public $modifPoids;
	public $modifTaille;
	public $favoredClass;
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>