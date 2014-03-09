<?php
class SkillRecord extends TActiveRecord
{
    const TABLE='skill';
 
    public $id;
    public $name;
	public $description;
	public $keyAbilityId;
	public $armorCheckPenalty;
	public $tryAgain;
	public $trained;
	
	public static $RELATIONS=array
    (
        'keyAbility' => array(self::BELONGS_TO, 'AbilityRecord', 'keyAbilityId'),
		'classes' => array(self::MANY_TO_MANY, 'ClasseRecord', 'classeskill'),
		'persoSkills' => array(self::HAS_MANY, 'PersoSkillRecord', 'skillId'),
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
	
	public function isClassSkill($classId) 
	{
		$classeRecord = ClasseRecord::finder()->findByPk($classId);
        if ($classeRecord === null) 
        {
            throw new DSException(500, 'classe_id_invalid', $classID);
		}
		/*
		foreach ($this->classes as $class) {
			if ($class->id == $classeRecord->id) {
				return true;
			}			
		}*/
		$classSkill = ClasseSkillRecord::finder()->findByPk($classeRecord->id, $this->id);
		if ($classSkill !== null) 
		{
            return true;
		} 
		return false;
	}
}
?>