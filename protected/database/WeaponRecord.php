<?php
class WeaponRecord extends EquipmentRecord
{
    const TABLE = 'weapon';
 
    //public $id;
    //public $name;
	//public $description;
	//public $cost;
	//public $weight;
	public $damage;
	public $additionalDamage;
	public $additionalDamageTypeId;
	//public $isMagical;
	public $magicalBonus;
	public $weaponTypeId;
	
	public static $RELATIONS=array
    (
        'weaponType' => array(self::BELONGS_TO, 'WeaponTypeRecord', 'weaponTypeId'),
        'additionalDamageType' => array(self::BELONGS_TO, 'DamageTypeRecord', 'additionalDamageTypeId')
    );
 
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }

    public static function cmp($a, $b)
	{
	    return strcmp($a['name'], $b['name']);
	}

    public function onExecuteCommand($param)
	{
		parent::onExecuteCommand($param);
			
		$rs = $param->Result;
		$cmd = $param->Command;
		
		//var_dump($rs);
		//var_dump($cmd);

		if (stristr($cmd->getText(), 'select') != false)
		{
			if ($rs instanceof TDbDataReader)
			{
				$result = array();
			
				while ($row = $rs->read())
				{
					$item = EquipmentRecord::finder()->findByPK($row['id']);
					$row['name'] = $item->name;
					$row['description'] = $item->description;
					$row['weight'] = $item->weight;
					$row['isMagical'] = $item->isMagical;
					$result[] = $row;
				}

				usort($result, array("ArmorRecord", "cmp"));

				return $param->setResult($result);
			}
			else
			{
				return $param;
			}
		}
		else
		{
			return $param;
		}
	}
}
?>
