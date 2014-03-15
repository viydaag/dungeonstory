<?php
include("MarketHelper.php");

class Buy extends DSPage
{
	private $_shop;

    public function onInit($param) {
        parent::onInit($param);
		$this->_shop = MarketHelper::getShop($this->Request['shopId']);
        if (!$this->IsPostBack) 
        {
			//$data = MarketHelper::getAllPurchasableWeapons();
			//$data = ShopEquipmentRecord::finder()->withEquipment('type = ?', EquipmentRecord::WEAPON_TYPE)->findAllByShopId($this->Shop->id);
			//$data = EquipmentRecord::finder()->withShopEquipment('shopId = ?', $this->Shop->id)->findAllByType(EquipmentRecord::WEAPON_TYPE);
			$this->bindData();
			
			//$this->debug("allo");
			//$this->info("allo2");

        }
    }

    public function bindData()
    {
    	$data = MarketHelper::getAllPurchasableItemsForShop($this->Shop->id, EquipmentRecord::WEAPON_TYPE);
		$this->WeaponTable->DataSource = $data;
		$this->WeaponTable->dataBind();
		$this->WeaponTable->SelectedItemIndex=-1;
    }
	
	public function itemCreated($sender, $param) 
	{
        $item = $param->Item;
        if($item->ItemType==='Item' || $item->ItemType==='AlternatingItem' || $item->ItemType==='EditItem') 
        {
			//$item->ItemQuantityColumn->ItemQuantity->Text = 0;
			//$item->ItemQuantityColumn->Text = 0;
			//$this->onItemChanged($item);
			//$item->AddItemColumn->Button->Enabled = true;
        }
    }
	
	public function itemCommand($sender, $param) 
	{
		$item = $param->Item;
		if ($item instanceof TDataGridPager) return;
		// if (strcmp($param->CommandName, "addItem") == 0) 
		// {
		// 	echo "add";
		// 	$this->addItem($item);
		// } 
		// else if (strcmp($param->CommandName, "removeItem") == 0) 
		// {
		// 	$this->removeItem($item);
		// } 
		// else 
		if (strcmp($param->CommandName, "buyItem") == 0) 
		{
			$this->buyItem($item);
		}
	}
	
	// public function addItem($item) 
	// {
	// 	$this->WeaponTable->SelectedItemIndex = -1;
	// 	$equipmentId = $this->WeaponTable->DataKeys->itemAt($item->ItemIndex);
	// 	$item->ItemQuantityColumn->ItemQuantity->Text = (int)$item->ItemQuantityColumn->ItemQuantity->Text + 1;
	// 	//$item->ItemQuantityColumn->Text = (int)$item->ItemQuantityColumn->Text + 1;
	// 	$this->onItemChanged($item);
	// }
	
	// public function removeItem($item) 
	// {
	// 	$this->WeaponTable->SelectedItemIndex = -1;
	// 	$equipmentId = $this->WeaponTable->DataKeys->itemAt($item->ItemIndex);
	// 	$item->ItemQuantityColumn->ItemQuantity->Text = (int)$item->ItemQuantityColumn->ItemQuantity->Text - 1;
	// 	//$item->ItemQuantityColumn->Text = (int)$item->ItemQuantityColumn->Text - 1;
	// 	$this->onItemChanged($item);
	// }

	// public function selectItem($sender, $param)
 //    {
 //        $data = MarketHelper::getAllPurchasableWeapons();
	// 	$this->WeaponTable->DataSource = $data;
 //        $this->WeaponTable->dataBind();
 //    }
	
	public function buyItem($item) 
	{
		//$this->WeaponTable->SelectedItemIndex = -1;
		$equipmentId = $this->WeaponTable->DataKeys->itemAt($item->ItemIndex);
		$persoId = $this->User->Perso->id;
		$shopId = $this->Shop->id;
		$quantity = $item->SliderColumn->QuantitySlider->Value;
		$price = $item->ItemCostColumn->ItemCost->Text;
		
		$finder = ShopEquipmentRecord::finder();
		$finder->DbConnection->Active = true;
		$transaction = $finder->DbConnection->beginTransaction();

		if ($quantity > 0)
		{
			try
			{
				//add X item to perso inventory
				$persoEquipmentRecord = PersoEquipmentRecord::finder()->findByPersoIdAndEquipmentId($persoId, $equipmentId);
				//var_dump($persoEquipmentRecord);
				if ($persoEquipmentRecord === NULL) 
				{
					$persoEquipmentRecord = new PersoEquipmentRecord();
					$persoEquipmentRecord->persoId = $persoId;
					$persoEquipmentRecord->equipmentId = $equipmentId;
					$persoEquipmentRecord->quantity = $quantity;
					$persoEquipmentRecord->sellableValue = $price - ($price * 0.1);
				} 
				else 
				{
					Prado::log("add item quantity ".$quantity, TLogger::INFO, "DS");
					$persoEquipmentRecord->quantity = $persoEquipmentRecord->quantity + $quantity;
				}		
				$persoEquipmentRecord->save();
				
				//remove X item from shop inventory
				$shopEquipmentRecord = $finder->findByShopIdAndEquipmentId($shopId, $equipmentId);
				if ($shopEquipmentRecord !== NULL) 
				{
					//if ($shopEquipmentRecord->quantity - $quantity > 0) 
					//{
						$shopEquipmentRecord->quantity = $shopEquipmentRecord->quantity - $quantity;
						$shopEquipmentRecord->save();
					//}
					// else
					// {
					// 	ShopEquipmentRecord::finder()->deleteByPk($shopEquipmentRecord->shopId, $shopEquipmentRecord->equipmentId);
					// }
				}
				$transaction->commit();
			} 
			catch (Exception $e) 
			{
				$transaction->rollBack();
				throw new DSException(500, $e->getMessage());
			}
		}
		$this->bindData();
	}
	
	public function getShop() 
	{
		return $this->_shop;
	}

	public function getWeaponTypeString($type)
    {
        switch($type)
        {
            case WeaponTypeRecord::LIGHT_TYPE:
                return "Léger";
            case WeaponTypeRecord::ONE_HANDED_TYPE:
                return "Une main";
            case WeaponTypeRecord::TWO_HANDED_TYPE:
                return "Deux mains";
            default:
                return "";
        }
    }
}
?>