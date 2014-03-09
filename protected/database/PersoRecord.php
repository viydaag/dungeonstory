<?php
include_once("combat/ICombat.php");

class PersoRecord extends TActiveRecord implements ICombat
{
    const TABLE='perso';
 
    public $id;
    public $nom;
	public $sexe;
	public $niveau;
	public $experience;
	public $userId;
	//public $classeId;
	public $alignementId;
	public $raceId;
	public $aventureId;
	public $force;
	public $dexterite;
	public $constitution;
	public $intelligence;
	public $sagesse;
	public $charisme;
	public $image;
	public $vie;
	public $baseAC;
	public $regionId;
	public $taille;
	public $poids;
	public $age;
	public $histoire;
	public $apparence;
	public $personnalite;
 
 	public static $RELATIONS=array
    (
        'user' => array(self::BELONGS_TO, 'UserRecord', 'userId'),
		//'classe'  => array(self::BELONGS_TO, 'ClasseRecord', 'classeId'),
		'race'  => array(self::BELONGS_TO, 'RaceRecord', 'raceId'),
		'alignement'  => array(self::BELONGS_TO, 'AlignementRecord', 'alignementId'),
		'region' => array(self::BELONGS_TO, 'RegionRecord', 'regionId'),
		'aventure'  => array(self::BELONGS_TO, 'AventureRecord', 'aventureId'),
		'classes' => array(self::MANY_TO_MANY, 'ClasseRecord', 'persoclasse'),
		'persoclasse' => array(self::HAS_MANY, 'PersoClasseRecord', 'persoId'),
		'niveauXp' => array(self::BELONGS_TO, 'LevelRecord', 'niveau'),
		'feats' => array(self::MANY_TO_MANY, 'FeatRecord', 'persofeat'),
		'skills' => array(self::MANY_TO_MANY, 'SkillRecord', 'persoskill'),
		'persoskills' => array(self::HAS_MANY, 'PersoSkillRecord', 'persoId'),
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }

    public function getName()
    {
    	return $this->nom;
    }

    public function getArmorClass()
    {
    	//baseAC + armor bonus + dex bonus
    	return $this->baseAC;
    }
	
	public function getSexeString() 
	{
		$s = '';
		if ($this->sexe == 'M') 
		{
			$s = 'Homme';
		} 
		else if ($this->sexe == 'F') 
		{
			$s = 'Femme';
		}
		return $s;
	}
	
	public function getClasseNiveauString() 
	{
		$string = '';
		$persoclasse = PersoClasseRecord::finder()->findAllBy_persoId($this->id);
		//print_r($persoclasse);
		foreach ($persoclasse as $value) 
		{
		//print_r($value);
		
			$classeId = $value->classeId;
			$niveau = $value->niveauClasse;
			$classe = ClasseRecord::finder()->findByPk($classeId);
			$string .= $classe->nom.' '.$niveau.' / ';
			
		}
		$string = substr($string, 0, strlen($string) - 3);
		return $string;
	}
	
	public function isInAventure()
	{
		return $this->aventureId != null;
	}
	
	public function isInThisAventure($iAventureId) 
	{
		return $this->aventureId == $iAventureId;
	}

	public function getLife()
    {
    	return $this->vie;
    }
	
	public function getTotalForce() 
	{
		return $this->force + $this->race->modifForce;
	}
	
	public function getTotalDexterite() 
	{
		return $this->dexterite + $this->race->modifDexterite;
	}
	
	public function getTotalConstitution() 
	{
		return $this->constitution + $this->race->modifConstitution;
	}
	
	public function getTotalIntelligence() 
	{
		return $this->intelligence + $this->race->modifIntelligence;
	}
	
	public function getTotalSagesse() 
	{
		return $this->sagesse + $this->race->modifSagesse;
	}
	
	public function getTotalCharisme() 
	{
		return $this->charisme + $this->race->modifCharisme;
	}
	
	public function getAttributeBonus($attributeValue) 
	{
		return floor($attributeValue / 2) - 5;			
	}
	
	public function getPositiveAttributeBonus($attributeValue) 
	{
		if ($attributeValue < 10) 
		{
			return 0;
		} 
		else 
		{
			return floor($attributeValue / 2) - 5;
		}		
	}

	public function getRefSave()
	{
		$persoclasseList = PersoClasseRecord::finder()->findAllBy_persoId($this->id);
		$refSave = 0;

		//sum all bonus from all classes
		foreach ($persoclasseList as $persoClasse) 
		{
			$classeId = $persoClasse->classeId;
			$level = $persoClasse->niveauClasse;			
			$classLevelBonus = ClassLevelBonusRecord::finder()->findBy_classId_and_levelId($classeId, $level);
			
			$refSave += $classLevelBonus->refSave;
		}
	}

	public function getFortSave()
	{
		$persoclasseList = PersoClasseRecord::finder()->findAllBy_persoId($this->id);
		$fortSave = 0;

		//sum all bonus from all classes
		foreach ($persoclasseList as $persoClasse) 
		{
			$classeId = $persoClasse->classeId;
			$level = $persoClasse->niveauClasse;			
			$classLevelBonus = ClassLevelBonusRecord::finder()->findBy_classId_and_levelId($classeId, $level);
			
			$fortSave += $classLevelBonus->fortSave;
		}
	}

	public function getWillSave()
	{
		$persoclasseList = PersoClasseRecord::finder()->findAllBy_persoId($this->id);
		$willSave = 0;

		//sum all bonus from all classes
		foreach ($persoclasseList as $persoClasse) 
		{
			$classeId = $persoClasse->classeId;
			$level = $persoClasse->niveauClasse;			
			$classLevelBonus = ClassLevelBonusRecord::finder()->findBy_classId_and_levelId($classeId, $level);
			
			$willSave += $classLevelBonus->willSave;
		}
	}
	
	public function getAttackBonus() 
	{
		$attackBonusStringList = array();
		$persoclasseList = PersoClasseRecord::finder()->findAllBy_persoId($this->id);
		
		//get an array of attack bonus array
		foreach ($persoclasseList as $persoClasse) 
		{
			$classeId = $persoClasse->classeId;
			$level = $persoClasse->niveauClasse;			
			$classLevelBonus = ClassLevelBonusRecord::finder()->findBy_classId_and_levelId($classeId, $level);
			
			$attackBonusArray = explode("/", $classLevelBonus->baseAttackBonus);

			if (is_array($attackBonusArray))
			{
				$attackBonusStringList[] = $attackBonusArray;
			}
			else
			{
				$tmp = array();
				$tmp[] = $attackBonusArray;
				$attackBonusStringList[] = $tmp;
			}
		}
		
		//get the sum of each corresponding attack bonus
		$sumArray = array_shift($attackBonusStringList);
		foreach ($attackBonusStringList as $array) 
		{
			if (is_array($array))
			{
				foreach ($array as $key => $val) 
				{
					$sumArray[$key] += $val;
				}
			}
			else
			{
				$sumArray[0] += $val;
			}
		}
		
		
		return $sumArray;
	}

	public function getDamage()
	{
		//replace by weapon damage
		$dice = new DSDice('1d6');
		return $dice->roll();
	}

	public function getInitiative()
	{
		$dice = new DSDice("1d20");
		return $dice->roll() + $this->getPositiveAttributeBonus($this->getTotalDexterite());
	}

	public function gainXP($xp)
	{
		//multiclass penalty
		$penalty = false;
		foreach ($this->classes as $persoClass)
		{
			if ($persoClass->classId != $this->race->favoredClass)
			{
				foreach ($this->classes as $persoClass2)
				{
					if ($persoClass->classId != $persoClass2->classId &&
						$persoClass2->classId != $this->race->favoredClass &&
						abs($persoClass->niveauClasse - $persoClass2->niveauClasse) > 2)
					{
						$penalty = true;
					}
				}
			}			
		}
		if ($penalty)
		{
			$xp -= ($xp * 0.2);
		}

		//until next level
		if (($this->experience + $xp) > $this->getNextLevel()->maxExperience)
		{
			$this->experience =  $this->getNextLevel()->maxExperience - 1;
		}
		else
		{
			$this->experience += $xp;
		}
		$this->save();
	}

	public function canLevelUp() 
	{
		return $this->niveauXp->maxExperience < $this->experience;
	}

	public function getNextLevel() 
	{
		$levelRecord = LevelRecord::finder()->findByPk($this->niveau + 1);
        if ($levelRecord === null) 
        {
            throw new DSException(500, 'level_id_invalid', $classId);
		}
		return $levelRecord;
	}

	public function getNextClassLevel($classId) 
	{
		$classeRecord = ClasseRecord::finder()->findByPk($classId);
        if ($classeRecord === null) 
        {
            throw new DSException(500, 'classe_id_invalid', $classId);
		}
		$persoClasseRecord = PersoClasseRecord::finder()->findBy_persoId_and_classeId($this->id, $classId);
		if ($persoClasseRecord === NULL) 
		{
			return 1;
		} 
		else
		{
			return $persoClasseRecord->niveauClasse + 1;
		}
	}

	public function getClassLevels()
	{
		$classLevels = array();
		foreach ($this->classes as $persoClass)
		{
			$classLevels[] = $persoClass->niveauClasse;
		}
		return $classLevels;
	}
}
?>