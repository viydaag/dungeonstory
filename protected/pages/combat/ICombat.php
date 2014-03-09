<?php
interface ICombat
{
	public function getName();
	public function getLife();
	public function getArmorClass();
	public function getInitiative();
	public function getDamage();
    public function getAttackBonus();
    public function getAttributeBonus($attributeValue);

    public function getTotalForce();	
	public function getTotalDexterite();
	public function getTotalConstitution();
	public function getTotalIntelligence();
	public function getTotalSagesse();
	public function getTotalCharisme();
}
?>