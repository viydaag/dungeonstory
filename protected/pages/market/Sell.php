<?php
include("MarketHelper.php");

class Sell extends DSPage
{
	private $_shop;

	public function onInit($param) 
	{
        parent::onInit($param);
		$this->_shop = MarketHelper::getShop($this->Request['shopId']);
        if (!$this->IsPostBack) 
        {
			$this->bindData();
        }
		
    }

    private function bindData()
    {
    	$data = MarketHelper::getAllSellableEquipmentFromPerso($this->User->Perso->id);
		$this->SellTable->DataSource = $data;
		$this->SellTable->dataBind();
    }
	
	public function itemCommand($sender, $param) 
	{
		$item = $param->Item;
		if ($item instanceof TDataGridPager) return;
		if (strcmp($param->CommandName, "addItem") == 0) 
		{
			$this->addItem($item);
		} 
		else if (strcmp($param->CommandName, "removeItem") == 0)
		{
			$this->removeItem($item);
		} 
		else if (strcmp($param->CommandName, "sellItem") == 0) 
		{
			$this->sellItem($item);
		}
	}
	
	public function itemCreated($sender, $param) 
	{
        $item = $param->Item;
        if ($item->ItemType==='Item' || $item->ItemType==='AlternatingItem' || $item->ItemType==='EditItem') 
        {
			//$item->ItemTotalCostColumn->ItemTotalCost->Text = 0;
			//$this->onItemChanged($item);
			$item->AddItemColumn->Button->Enabled = true;
        }
    }
	
	public function addItem($item) {
		$equipmentId = $this->SellTable->DataKeys->itemAt($item->ItemIndex);
		$item->ItemQuantityColumn->ItemQuantity->Text = (int)$item->ItemQuantityColumn->ItemQuantity->Text + 1;
		//$item->ItemTotalCostColumn->ItemTotalCost->Text = (int)$item->ItemQuantityColumn->ItemQuantity->Text * (int)$item->ItemCostColumn->Text;
		$this->onItemChanged($item);
	}
	
	public function removeItem($item) {
		$equipmentId = $this->SellTable->DataKeys->itemAt($item->ItemIndex);
		$item->ItemQuantityColumn->ItemQuantity->Text = (int)$item->ItemQuantityColumn->ItemQuantity->Text - 1;
		//$item->ItemTotalCostColumn->ItemTotalCost->Text = (int)$item->ItemQuantityColumn->ItemQuantity->Text * (int)$item->ItemCostColumn->Text;
		$this->onItemChanged($item);
	}
	
	private function onItemChanged($item) {
		$item->ItemTotalCostColumn->ItemTotalCost->Text = (int)$item->ItemQuantityColumn->ItemQuantity->Text * (int)$item->ItemCostColumn->Text;
		$addButton = $item->AddItemColumn->Button;
		$removeButton = $item->RemoveItemColumn->Button;
		DSHelper::disableObjectOnValue($addButton, (int)$item->ItemQuantityColumn->ItemQuantity->Text, NULL, (int)$item->ItemQuantityOwnedColumn->Text);
		DSHelper::disableObjectOnValue($removeButton, (int)$item->ItemQuantityColumn->ItemQuantity->Text, 0, NULL);
	}
	
	public function sellItem($item) 
	{
		$equipmentId = $this->SellTable->DataKeys->itemAt($item->ItemIndex);
		$persoId = $this->User->Perso->id;
		$shopId = $this->Shop->id;
		//$quantity = (int)$item->ItemQuantityColumn->ItemQuantity->SafeText;
		$quantity = $item->SliderColumn->QuantitySlider->Value;
		$price = $item->ItemCostColumn->ItemCost->Text;

		if ($quantity > 0)
		{
			$finder = PersoEquipmentRecord::finder();
			$finder->DbConnection->Active = true;
			$transaction = $finder->DbConnection->beginTransaction();
			try 
			{
				//remove X item from perso inventory
				$persoEquipmentRecord = MarketHelper::getPersoEquipmentRecord($persoId, $equipmentId);
				//var_dump($persoEquipmentRecord);
				if ($persoEquipmentRecord != NULL) 
				{
					if ($persoEquipmentRecord->quantity - $quantity > 0) 
					{
						$persoEquipmentRecord->quantity = $persoEquipmentRecord->quantity - $quantity;
						$persoEquipmentRecord->save();
					} 
					else 
					{
						$finder->deleteByPk($persoEquipmentRecord->persoId, $persoEquipmentRecord->equipmentId);
					}
				}
				
				//add X item to shop inventory
				$shopEquipmentRecord = ShopEquipmentRecord::finder()->findByShopIdAndEquipmentId($shopId, $equipmentId);
				if ($shopEquipmentRecord === NULL) 
				{
					$shopEquipmentRecord = new ShopEquipmentRecord();
					$shopEquipmentRecord->shopId = $shopId;
					$shopEquipmentRecord->equipmentId = $equipmentId;
					$shopEquipmentRecord->quantity = $quantity;
					$shopEquipmentRecord->unitPrice = $price + ($price * 0.10); 
				}
				else 
				{
					$shopEquipmentRecord->quantity = $shopEquipmentRecord->quantity + $quantity;
				}		
				$shopEquipmentRecord->save();

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
		//return MarketHelper::getShop($this->Request['shopId']);
	}
}
?>
