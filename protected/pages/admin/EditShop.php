<?php
class EditShop extends DSPage
{
    public function onInit($param) 
    {
        parent::onInit($param);
		
        if (!$this->User->IsAdmin) 
        {
            throw new DSException(500, 'admin_view_disallowed');
		}
 
        if (!$this->IsPostBack) 
        {
            $shop = $this->getShop();
            $this->Name->Text = $shop->name;
            $this->Description->Text = $shop->description;

            $this->bindData();
            $this->DataGridItems->SelectedItemIndex=-1;
        }
    }

    protected function bindData()
    {
        $data = ShopEquipmentRecord::finder()->findAllByShopId($this->Shop->id);
        $this->DataGridItems->DataSource = $data;
        $this->DataGridItems->databind();

        $this->NewItemList->DataSource = $this->getAllPurchasableItems();
        $this->NewItemList->dataBind();
    }
 
    /**
     * Enregistre si toutes les validations sont Ok
     * cette méthode répond à l'évènement OnClick du bouton "Enregistrer".
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function saveButtonClicked($sender,$param) 
    {
        if ($this->IsValid) 
        {

        }
    }

    protected function getShop() 
    {
        // l'ID du message devant être modifié passé par un paramètre GET 
        $shopID = (int)$this->Request['shopId'];
        // utilise Active Record pour lire le message correspondant à cet ID
        $shopRecord = ShopRecord::finder()->findByPk($shopID);
        if ($shopRecord === null) 
        {
            throw new DSException(500, 'shop_id_invalid', $shopID);
        }
        return $shopRecord;
    }

    public function itemCreated($sender, $param) 
    {
        $item = $param->Item;
        if ($item->ItemType==='EditItem') 
        {
            $item->QuantityColumn->TextBox->CssClass = "input-center";
        }
        else if($item->ItemType==='Item' || $item->ItemType==='AlternatingItem') 
        {
            
        }
    }

    public function editItem($sender, $param)
    {
        $this->DataGridItems->EditItemIndex = $param->Item->ItemIndex;
        $this->bindData();
    }

    public function deleteItem($sender, $param) 
    {
        $item = $param->Item;
        $equipId = $this->DataGridItems->DataKeys[$item->ItemIndex];
        ShopEquipmentRecord::finder()->deleteByPk($this->Shop->id, $equipId);
        $this->DataGridItems->EditItemIndex = -1;
        $this->bindData();
    }
 
    public function saveItem($sender, $param) 
    {
        $item = $param->Item;
        $equipId = $this->DataGridItems->DataKeys[$item->ItemIndex];
        $shopEquipmentRecord = ShopEquipmentRecord::finder()->findByPk($this->Shop->id, $equipId);
        $shopEquipmentRecord->quantity = $item->QuantityColumn->TextBox->SafeText;
        $shopEquipmentRecord->save();
        $this->DataGridItems->EditItemIndex = -1;
        $this->bindData();
    }
 
    public function cancelItem($sender, $param) 
    {
        $this->DataGridItems->EditItemIndex = -1;
        $this->bindData();      
    }
    
    public function getAllPurchasableItems() 
    {
        $allPurchasables = EquipmentRecord::finder()->findAllByIsPurchasable(true);
        $allAssigned = ShopEquipmentRecord::finder()->with_equipment()->findAllByShopId($this->Shop->id);
        $purchasables = array();
        foreach ($allPurchasables as $itemPurchasable)
        {
            $found = false;
            foreach ($allAssigned as $itemAssigned)
            {
                if ($itemAssigned->equipment == $itemPurchasable)
                {
                    $found = true;
                    break;
                }
            }
            if (!$found)
            {
                $purchasables[] = $itemPurchasable;
            }
        }

        return $purchasables;
    }

    public function addNewRow($sender, $param)
    {
        $record = new ShopEquipmentRecord();
        $record->shopId = $this->Shop->id;
        $record->equipmentId = $this->NewItemList->SelectedValue;
        $record->quantity = $this->NewItemQuantity->SafeText;
        $record->save();

        $this->bindData();

        $this->NewItemList->clearSelection();
        $this->NewItemQuantity->Text = "";
    }
}
?>