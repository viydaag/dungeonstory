<?php
class DSDice
{
	const ALWAYS_MISS = 1;
	const ALWAYS_HIT = 20;

	//private $_result;
	private $_nb_dice;		//nombre de dés à lancer
	private $_dice;			//nombre de face du dé
	private $_modifier;		//nombre à ajouter/enlever au résultat
	
	/**
	 * Construit un objet dé.
	 */
	public function __construct() 
	{
		$num = func_num_args();
 
		switch($num) 
		{
			case 1:
				$string = func_get_arg(0);
				$this->_nb_dice = strtok($string, "d");
				$this->_dice = strtok("+-");
				if (strpos($string, '-') != false)
				{
					$this->_modifier = -intval(strtok("+-"));
				}
				else
				{
					$this->_modifier = intval(strtok("+-"));
				}				
				break;
			case 2:
				$this->_nb_dice = func_get_arg(0);
				$this->_dice = func_get_arg(1);
				$this->_modifier = 0;
				break;
			case 3:
				$this->_nb_dice = func_get_arg(0);
				$this->_dice = func_get_arg(1);
				$this->_modifier = func_get_arg(2);
				break;
		  default:
	   }		
	}
    
	/**
	 * Lance le(les) dés et retourne la somme de chaque dé.
	 */
    public function roll() 
    {
		$result = 0;
        for ($i = 1; $i <= $this->_nb_dice; $i++) 
        {
			$result += (rand(1, $this->_dice) + $this->_modifier);
		}
		return $result;
    }

    public function toString()
    {
    	$plus = '';
    	if ($this->_modifier >= 0)
    	{
    		$plus = '+';
    	}
    	return "dice = " . $this->_nb_dice . "d" . $this->_dice . $plus . $this->_modifier;
    }		
}
?>