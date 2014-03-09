<?php
include_once("combat/ICombat.php");

class MonsterRecord extends TActiveRecord implements ICombat
{
    const TABLE = 'monster';
 
    public $id;
    public $name;
	public $description;
	public $image;
	public $level;
	public $challengeRating;
	public $levelAdjustment;
	public $xpAward;
	public $alignmentId;
	public $baseAC;
	public $life;
	public $strength;
	public $dexterity;
	public $constitution;
	public $intelligence;
	public $wisdom;
	public $charisma;
	public $initiative;
	public $baseAttackBonus;
	public $damage;
	
	public static $RELATIONS=array
    (
		'alignment' => array(self::BELONGS_TO, 'AlignementRecord', 'alignmentId'),
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }

    public function getName()
    {
    	return $this->name;
    }

    public function getArmorClass()
    {
    	return $this->baseAC;
    }

    public function getLife()
    {
    	return $this->life;
    }

    public function getAttributeBonus($attributeValue) 
    {
		return floor($attributeValue / 2) - 5;			
	}

    public function getTotalForce() 
    {
		return $this->strength;
	}
	
	public function getTotalDexterite() 
	{
		return $this->dexterity;
	}
	
	public function getTotalConstitution() 
	{
		return $this->constitution;
	}
	
	public function getTotalIntelligence() 
	{
		return $this->intelligence;
	}
	
	public function getTotalSagesse() 
	{
		return $this->wisdom;
	}
	
	public function getTotalCharisme() 
	{
		return $this->charisma;
	}

	public function getAttackBonus() 
	{
		$attackBonusStringList = array();	
		$attackBonusArray = explode("/", $this->baseAttackBonus);

		if (is_array($attackBonusArray))
		{
			$attackBonusStringList = $attackBonusArray;
		}
		else
		{
			$attackBonusStringList[] = $attackBonusArray;
		}

		return $attackBonusStringList;
	}

	public function getDamage()
	{
		$dice = new DSDice($this->damage);
		return $dice->roll();
	}

	public function getInitiative()
	{
		$dice = new DSDice("1d20+1");
		return $dice->roll();
	}

}
?>