<?php
class ViewPerso extends DSPage
{
    private $_perso;
	
    public function onInit($param) 
    {
        parent::onInit($param);
        $persoId = (int)$this->Request['persoId']; 
        $this->_perso = PersoRecord::finder()->findByPk($persoId);
        if ($this->_perso === null) 
        {  
            throw new DSException(500, 'perso_id_invalid', $persoId);
		}
		//if (!$this->isPostBack) {
			$this->SkillTable->DataSource = $this->getPersoSkills();
			$this->SkillTable->databind();
			//echo print_r($this->getPersoSkills());
			
			$this->FeatTable->DataSource = $this->getPersoFeats();
			$this->FeatTable->databind();
			
			$this->EquipmentTable->DataSource = $this->getPersoEquipment();
			$this->EquipmentTable->databind();
		//}
    }
 
    public function getPerso() 
    {
        return $this->_perso;
    }    
	
	public function goToLevelUp($sender, $param) 
	{
		if ($this->canLevelUp()) 
		{
			$this->goToPage('persos.LevelUp', array('persoId'=>$this->Perso->id));	
		} 
		else 
		{
            throw new DSException(500, 'perso_levelup_disallowed', $persoId);
		}
	}
	
	public function canLevelUp() 
	{
		return $this->Perso->canLevelUp();
	}
	
	protected function getPersoSkills() 
	{
		$finder = PersoSkillRecord::finder();
		$skills = $finder->findAllByPersoId($this->Perso->id);
		return $skills;
	}
	
	protected function getPersoFeats() 
	{
		$finder = PersoFeatRecord::finder();
		$feats = $finder->findAllByPersoId($this->Perso->id);
		return $feats;
	}
	
	protected function getPersoEquipment() 
	{
		$finder = PersoEquipmentRecord::finder();
		$equipments = $finder->findAllByPersoId($this->Perso->id);
		return $equipments;
	}	
}
?>