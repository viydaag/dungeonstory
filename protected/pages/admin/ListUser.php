<?php
 
class ListUser extends DSPage
{

	public function onLoad($param) 
	{
        parent::onLoad($param);
        if (!$this->IsPostBack) 
        {	
            $this->bindData();
       	}
    }
	
	public function bindData() 
	{
		$offset = $this->UserGrid->CurrentPageIndex*$this->UserGrid->PageSize;
		$limit = $this->UserGrid->PageSize;
		$this->UserGrid->VirtualItemCount = UserRecord::finder()->count();
		$this->UserGrid->DataSource = $this->getUserDataSource($offset, $limit);		
        $this->UserGrid->dataBind();
	}
 
    public function itemCreated($sender, $param) 
    {
        $item=$param->Item;
        if ($item->ItemType==='EditItem') 
        {
            // set column width
			$item->PasswordColumn->Width = "100px";
			$item->NomColumn->Width = "100px";
            $item->EditColumn->Width = "200px";
        }
        if ($item->ItemType==='Item' || $item->ItemType==='AlternatingItem' || $item->ItemType==='EditItem') 
        {
            // add an aleart dialog to reset password buttons
            $item->ResetPasswordColumn->Button->Attributes->onclick='if(!confirm(\'Êtes-vous sur?\')) return false;';
        }

    }
 
    public function editItem($sender, $param)
    {
        $this->UserGrid->EditItemIndex = $param->Item->ItemIndex;
        $this->bindData();
    }
 
    public function saveItem($sender, $param) 
    {
        $item = $param->Item;
		$id = $this->UserGrid->DataKeys[$item->ItemIndex];
        $userRecord = UserRecord::finder()->findByPk($id);
		//$userRecord->password = $item->PasswordColumn->TextBox->Text;
		$userRecord->roleId = TPropertyValue::ensureInteger($item->RoleColumn->DropDownList->SelectedValue);
		$userRecord->statut = TPropertyValue::ensureInteger($item->StatutColumn->DropDownList->SelectedValue);
		$userRecord->save();
        $this->UserGrid->EditItemIndex = -1;
        $this->bindData();
    }
 
    public function cancelItem($sender, $param) 
    {
        $this->UserGrid->EditItemIndex = -1;
        $this->bindData();		
    }
	
	public function getRoleDataSource() 
	{
		$finder = RoleRecord::finder();
		$roles = $finder->findAll();
		return new TList($roles);
	}
	
	public function getUserDataSource($offset, $limit) 
	{
		$criteria=new TActiveRecordCriteria;
        $criteria->OrdersBy['id']='asc';
        $criteria->Limit=$limit;
        $criteria->Offset=$offset;
		$finder = UserRecord::finder();
		$result = $finder->withRole()->findAll($criteria);
		return new TList($result);
	}
	
	public function resetPassword($sender, $param) 
	{
        $item = $param->Item;
		if ($item instanceof TDataGridPager) return;
		$id = $this->UserGrid->DataKeys[$item->ItemIndex];
		$userRecord = UserRecord::finder()->findByPk($id);
		$userRecord->password = md5($userRecord->pseudo);
		$userRecord->save();
		$this->UserGrid->EditItemIndex = -1;
        $this->bindData();
	}
	
	public function changePage($sender, $param) 
	{
		$this->UserGrid->CurrentPageIndex = $param->NewPageIndex;
		$this->bindData();
	}

	public function pagerCreated($sender, $param) 
	{
		$param->Pager->Controls->insertAt(0, 'Page: ');
	}

}
 
?>