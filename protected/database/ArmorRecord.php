<?php
class ArmorRecord extends EquipmentRecord
{
    const TABLE = 'armor';
 
    //public $id;
    //public $name;
	//public $description;
	public $acBonus;
	public $armorCheckPenalty;
	public $arcaneSpellFailure;
	//public $weight;
	//public $isMagical;
	public $magicalACBonus;
	public $armorTypeId;
	
	public static $RELATIONS=array
    (
        'armorType' => array(self::BELONGS_TO, 'ArmorTypeRecord', 'armorTypeId'),
		'equipment' => array(self::BELONGS_TO, 'EquipmentRecord', 'id')
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
		
		// Prado::varDump($rs);
		// var_dump($rs);
		//var_dump($cmd);
		//return $param;
		
		if (stristr($cmd->getText(), 'select') != false)
		{
			$result = array();

			if ($rs instanceof TDbDataReader)
			{
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
				// foreach ($rs as $row)
				// {
				// 	var_dump($row);
				// 	$item = EquipmentRecord::finder()->findByPK($rs['id']);
				// 	$rs['name'] = $item->name;
				// 	$rs['description'] = $item->description;
				// 	$rs['weight'] = $item->weight;
				// 	$rs['isMagical'] = $item->isMagical;
				// 	$result[] = $rs;
				// // }

				// //usort($result, array("ArmorRecord", "cmp"));

				// return $param->setResult($result);
				return $param;
			}
		}		
		else
		{
			return $param;
		}
	}

	public function onInsert($param)
	{
		parent::onInsert($param);
	}
}
?>
