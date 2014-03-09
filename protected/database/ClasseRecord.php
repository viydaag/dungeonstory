<?php
class ClasseRecord extends TActiveRecord
{
    const TABLE='classe';
 
    public $id;
    public $nom;
	public $description;
	public $descriptionCourte;
	public $vie;
	
	public static $RELATIONS=array
    (
		'skills' => array(self::MANY_TO_MANY, 'SkillRecord', 'classeskill'),
		'classLevelBonus' => array(self::HAS_MANY, 'ClassLevelBonusRecord', 'classId')
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
	
	public function getSkillString() 
	{
		$string = '';		
		foreach ($this->skills as $value) {
			$name = $value->name;
			$string .= $name.', ';			
		}
		$string = substr($string, 0, strlen($string) - 2);
		return $string;
	}
}
?>