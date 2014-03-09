<?php
class Combat extends DSPage
{
	public function onInit($param) 
	{
		parent::onInit($param);
        if (!$this->IsPostBack) 
        {
			$monster = $this->getMonster();
			$this->MonsterLifePoints->Text = $this->Monster->life;
			$this->CharacterLifePoints->Text = $this->User->Perso->vie;
			$this->MonsterImage->ImageUrl = $this->getImagesUrl().'/monsters/'.$monster->image;
			$this->AttackButton->Enabled = false;
			
			$this->setCharacterAttackNumber(0);
			$this->setMonsterAttackNumber(0);
			$this->setRound(1);

			$this->CharacterRemainingLifeBar->Style->Width = "100%";
			$this->CharacterRemainingLifeBar->Text = $this->User->Perso->vie;
			$this->CharacterLifeBar->Style->CssClass = "progress-bar green";

			$this->MonsterRemainingLifeBar->Style->Width = "100%";
			$this->MonsterRemainingLifeBar->Text = $this->Monster->life;
			$this->MonsterLifeBar->Style->CssClass = "progress-bar green";
		}
	}
	
	public function beginCombat() 
	{
		$this->BeginButton->Enabled = false;
		$this->AttackButton->Enabled = true;
		$this->setCharacterAttackNumber(0);
		$this->setMonsterAttackNumber(0);
		$this->setRound(1);
	}
	
	public function fleeCombat() 
	{
		$this->goToPage('combat.ListEnemy');
	}
	
	public function endRound() 
	{
		//$this->_character_attack_num = 0;
		$this->setCharacterAttackNumber(0);
		$this->setMonsterAttackNumber(0);
		$this->setRound($this->getRound() + 1);
	}
	
	public function attackEnemy($sender, $param) 
	{
		//melee = 1d20 + base attack bonus + strengh bonus + size bonus
		//range = 1d20 + base attack bonus + dex bonus + size bonus + range penalty
		//the result must be higher or equal to the enemy AC
		$message = '';
		$win = false;
		$lost = false;
		$attackBonusArray = $this->User->Perso->getAttackBonus();
		$monterAtackArray = $this->Monster->getAttackBonus();
		$nbCharacterAttack = count($attackBonusArray);
		$nbMonsterAttack = count($monterAtackArray);

		//roll initiative
		if ($this->getCharacterAttackNumber() == 0)
		{
			$this->setCharacterInitiative($this->Character->getInitiative());
			$this->setMonsterInitiative($this->Monster->getInitiative());
		}

		if ($this->getCharacterInitiative() >= $this->getMonsterInitiative())
		{
			//character attack
			$attack = $this->attack($sender);
			$this->damage($sender, $attack);

			//monster attack
			$attack = $this->attack(null);
			$this->damage(null, $attack);
		}
		else
		{
			//monster attack
			$attack = $this->attack(null);
			$this->damage(null, $attack);

			//character attack
			$attack = $this->attack($sender);
			$this->damage($sender, $attack);
		}

		

		if ($this->getCharacterAttackNumber() >= $nbCharacterAttack && $this->getMonsterAttackNumber() >= $nbMonsterAttack) 
		{
			$this->endRound();
		}

		if (intval($this->MonsterLifePoints->Text) <= 0) 
		{
			$win = true;
			$message .= $this->formatMessage("Vous avez gagné!");
		}
		else if (intval($this->CharacterLifePoints->Text) <= 0) 
		{
			$lost = true;
			$message .= $this->formatMessage("Vous avez perdu...");
		}			

		$this->log($message);

		//win combat
		if ($win) 
		{
			$sender->Enabled = false;
			$this->AbortButton->Text = "Retour à a liste";
			
			//give XP
			$persoRecord = $this->User->Perso;
			$persoRecord->gainXP($this->Monster->xpAward);
		}
		//lost combat
		else if ($lost) 
		{
			
			$sender->Enabled = false;
			$this->AbortButton->Text = "Retour à a liste";
		}	
	}

	public function attack($sender)
	{
		$attackIndex = 0;
		if ($sender != null)
		{
			$attacker = $this->User->Perso;
			$defender = $this->Monster;
			$attackIndex = $this->getCharacterAttackNumber();
			$this->setCharacterAttackNumber($this->getCharacterAttackNumber() + 1);
		}
		else
		{
			$attacker = $this->Monster;
			$defender = $this->User->Perso;
			$attackIndex = $this->getMonsterAttackNumber();
			$this->setMonsterAttackNumber($this->getMonsterAttackNumber() + 1);
		}

		$attackBonusArray = $attacker->getAttackBonus();
		$nbAttack = count($attackBonusArray);
		$finalAttack = 0;
		$message = '';

		if ($attackIndex < $nbAttack)
		{
			//melee = 1d20 + base attack bonus + strengh bonus + size bonus
			//range = 1d20 + base attack bonus + dex bonus + size bonus + range penalty
			$attackBonus = intval($attackBonusArray[$attackIndex]);
			$strBonus = $attacker->getAttributeBonus($attacker->getTotalForce());
			$dexBonus = $attacker->getAttributeBonus($attacker->getTotalDexterite());

			$dice = new DSDice('1d20');
			$attack = $dice->roll();
			$statBonus = 0;
			$melee = true;

			if ($attack != DSDice::ALWAYS_MISS) 
			{
				if ($melee) 
				{
					$statBonus = $strBonus;
				} 
				else 
				{
					$statBonus = $dexBonus;
				}
				//$bonus = 20;
				$finalAttack = $attack + $attackBonus + $statBonus;
				$message .= $this->formatMessage($attacker->getName() . " : attaque = " . $attack . " + " . $attackBonus . " + " . $statBonus . " = " . $finalAttack);
			}
		}

		$this->log($message);

		return $finalAttack;		
	}

	public function damage($sender, $attack)
	{
		if ($sender != null)
		{
			$attacker = $this->User->Perso;
			$defender = $this->Monster;

			$lifePoints = $this->MonsterLifePoints;
			$remainingLifeBar = $this->MonsterRemainingLifeBar;
			$lifeBar = $this->MonsterLifeBar;
		}
		else
		{
			$attacker = $this->Monster;
			$defender = $this->User->Perso;

			$lifePoints = $this->CharacterLifePoints;
			$remainingLifeBar = $this->CharacterRemainingLifeBar;
			$lifeBar = $this->CharacterLifeBar;
		}

		$armorClass = $defender->getArmorClass();
		$message = '';

		if ($attack >= $armorClass) 
		{
			$damage = $attacker->getDamage();
			$strBonus = $attacker->getAttributeBonus($attacker->getTotalForce());
			$finalDamage = $damage + $strBonus;
			$message .= $this->formatMessage($attacker->getName() . " :  dommage = " . $damage . " + " . $strBonus . " = " . $finalDamage);
			$lifePoints->Text = intval($lifePoints->Text) - $finalDamage;
			
			$percent = intval(intval($lifePoints->Text)/intval($defender->getLife())*100);
			if ($percent < 0)
			{
				$percent = 0;
			}
			$remainingLifeBar->Style->Width = $percent . "%";
			$remainingLifeBar->Text = $lifePoints->Text;

			if ($percent > ((2/3) * 100))
			{
				$lifeBar->Style->CssClass = "progress-bar green";
			}
			else if ($percent < ((1/3) * 100))
			{
				$lifeBar->Style->CssClass = "progress-bar red";
			}
			else
			{
				$lifeBar->Style->CssClass = "progress-bar orange";
			}			
		}

		$this->log($message);
	}
	
	protected function formatMessage($message) 
	{
		$content = htmlspecialchars($message);
		return "<div><span>{$content}</span></div>";
	}
	
	protected function log($message) {
		if (strlen($message) > 0) 
		{
			$content = $message;
			$anchor = (string)time();
			$content .= "<a href=\"#\" id=\"{$anchor}\"></a>";
			$this->CallbackClient->appendContent("messages", $content);
			$this->CallbackClient->focus($anchor);
		}
	}
	
	public function getImageDir() 
	{
		return 'Application.Images';
	}
	
	protected function getMonster() 
	{
        $monsterID = (int)$this->Request['monsterId'];
        $monsterRecord = MonsterRecord::finder()->findByPk($monsterID);
        if ($monsterRecord === null) 
        {
            throw new DSException(500, 'monster_id_invalid', $monsterID);
		}
        return $monsterRecord;
    }
	
	public function getCharacterAttackNumber()
	{
		return intval($this->getViewState('CharacterAttackNumber'));
	}

	public function setCharacterAttackNumber($value)
	{
		$this->setViewState('CharacterAttackNumber', TPropertyValue::ensureInteger($value));
		$this->CharacterAttackNumberValue->Text = $value;
	}

	public function getCharacterInitiative()
	{
		return intval($this->getViewState('CharacterInitiative'));
	}

	public function setCharacterInitiative($value)
	{
		$this->setViewState('CharacterInitiative', TPropertyValue::ensureInteger($value));
	}
	
	public function getMonsterAttackNumber()
	{
		return intval($this->getViewState('MonsterAttackNumber'));
	}

	public function setMonsterAttackNumber($value)
	{
		$this->setViewState('MonsterAttackNumber', TPropertyValue::ensureInteger($value));
		$this->MonsterAttackNumberValue->Text = $value;
	}

	public function getMonsterInitiative()
	{
		return intval($this->getViewState('MonsterInitiative'));
	}

	public function setMonsterInitiative($value)
	{
		$this->setViewState('MonsterInitiative', TPropertyValue::ensureInteger($value));
	}

	public function getRound()
	{
		return intval($this->getViewState('Round'));
	}

	public function setRound($value)
	{
		$this->setViewState('Round', TPropertyValue::ensureInteger($value));
		$this->RoundNumberValue->Text = $value;
	}

	public function getCharacter()
	{
		return $this->User->Perso;
	}
}
?>