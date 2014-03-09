<?php
class PersoClasseRecord extends TActiveRecord
{
    const TABLE='persoclasse';
 
    public $persoId;
    public $classeId;
	public $niveauClasse;
 
 	public static $RELATIONS=array
    (
        'perso' => array(self::BELONGS_TO, 'PersoRecord', 'persoId'),
		'classe' => array(self::BELONGS_TO, 'ClasseRecord', 'classeId')
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>