<?php
class DSSelectionList extends TCompositeControl
{
    private $_label1;
    private $_list1;
	private $_label2;
    private $_list2;
	private $_button_addToList1;
	private $_button_addToList2;
	private $_table;
 
    public function createChildControls() {
	
		$this->_label1 = new TActiveLabel;
        $this->_label1->setID('Label1');
        //$this->getControls()->add($this->_label1);
        //$this->getControls()->add('&nbsp;');
		
		$this->_label2 = new TActiveLabel;
        $this->_label2->setID('Label2');
        //$this->getControls()->add($this->_label2);
        //$this->getControls()->add('&nbsp;');
		
        $this->_list1 = new TActiveListBox;
        $this->_list1->setID('List1');
		$this->_list1->OnTextChanged = array($this, "unselect2");
        //$this->getControls()->add($this->_list1);
		//$this->_label1->setForControl('List1');
		
		$this->_list2 = new TActiveListBox;
        $this->_list2->setID('List2');
		$this->_list2->OnTextChanged = array($this, "unselect1");
        //$this->getControls()->add($this->_list2);
		//$this->_label2->setForControl('List2');
		
		$this->_button_addToList1 = new TActiveButton;
        $this->_button_addToList1->setID('AddToList1Button');
		$this->_button_addToList1->setText('<');
		//$this->_button_addToList1->getEventHandlers("onClick")->add(array($this, "two2one"));
		$this->_button_addToList1->OnClick = array($this, "two2one"); 
        //$this->getControls()->add($this->_button_addToList1);
		
		$this->_button_addToList2 = new TActiveButton;
        $this->_button_addToList2->setID('AddToList2Button');
		$this->_button_addToList2->setText('>');
		//$this->_button_addToList1->getEventHandlers("onClick")->add(array($this, "one2two"));
		$this->_button_addToList2->OnClick = array($this, "one2two"); 
        //$this->getControls()->add($this->_button_addToList2);
		
		$this->_table = new TTable;
		$row = new TTableRow;
        $this->_table->Rows->add($row);
 
        $cell = new TTableHeaderCell;
		$cell->Controls->add($this->_label1);
        $row->Cells->add($cell);
 		
		$cell = new TTableHeaderCell;
        $row->Cells->add($cell);
		
        $cell = new TTableHeaderCell;
        $cell->Controls->add($this->_label2);
       	$row->Cells->add($cell);
 
        $row = new TTableRow;
        $this->_table->Rows->add($row);
 
        $cell = new TTableCell;
        $cell->Controls->add($this->_list1);
        $row->Cells->add($cell);
		
		$cell = new TTableCell;
        $cell->Controls->add($this->_button_addToList1);
		$cell->Controls->add('<br/>');
		$cell->Controls->add($this->_button_addToList2);
        $row->Cells->add($cell);
 
        $cell = new TTableCell;
        $cell->Controls->add($this->_list2);
        $row->Cells->add($cell);
		
		$this->getControls()->add($this->_table);
    }

 
    public function getLabel1() 
    {
        $this->ensureChildControls();
        return $this->_label1;
    }
	
	public function getLabel2() 
	{
        $this->ensureChildControls();
        return $this->_label2;
    }
 
    public function getList1() 
    {
        $this->ensureChildControls();
        return $this->_list1;
    }
	
	public function getList2() 
	{
        $this->ensureChildControls();
        return $this->_list2;
    }
	
	public function getAddToList1Button() 
	{
        $this->ensureChildControls();
        return $this->_button_addToList1;
    }
	
	public function getAddToList2Button() 
	{
        $this->ensureChildControls();
        return $this->_button_addToList2;
    }
	
	public function setDisplay($value) 
	{
		$this->_label1->setDisplay($value);
		$this->_label2->setDisplay($value);
		$this->_list1->setDisplay($value);
		$this->_list2->setDisplay($value);
		$this->_button_addToList1->setDisplay($value);
		$this->_button_addToList2->setDisplay($value);
	}
	
	public function one2two($sender, $param) 
	{
		if (($index = $this->_list1->getSelectedIndex()) >= 0) 
		{
			$data1 = $this->getData1();
			$data2 = $this->getData2();
			$data2[] = $data1[$index];
			unset($data1[$index]);
		  	$this->setData1(array_values($data1));
		  	$this->setData2(array_values($data2));
		}
		$this->_list1->setSelectedIndex(-1);
		$this->_list2->setSelectedIndex(-1);
	}

	public function two2one($sender, $param) 
	{
		if (($index = $this->_list2->getSelectedIndex()) >= 0) 
		{
			$data1 = $this->getData1();
			$data2 = $this->getData2();
			$data1[] = $data2[$index];
			unset($data2[$index]);
			$this->setData1(array_values($data1));
			$this->setData2(array_values($data2));
		}
		$this->_list1->setSelectedIndex(-1);
		$this->_list2->setSelectedIndex(-1);
	}
	
	public function getData1() 
	{
    	return $this->getViewState('data1');
  	}

	public function setData1($value) 
	{
		$this->_list1->setDataSource(TPropertyValue::ensureArray($value));
    	$this->setViewState('data1', TPropertyValue::ensureArray($value));
		$this->_list1->databind();
		if (count(TPropertyValue::ensureArray($value)) == 0) 
		{
			$this->_list1->Rows = 2;
		} 
		else 
		{
			$this->_list1->Rows = count(TPropertyValue::ensureArray($value)) + 1;
		}
	}

	public function getData2() 
	{
    	return $this->getViewState('data2');
	}

	public function setData2($value) {
		$this->_list2->setDataSource(TPropertyValue::ensureArray($value));
    	$this->setViewState('data2', TPropertyValue::ensureArray($value));
		$this->_list2->databind();
		if (count(TPropertyValue::ensureArray($value)) == 0) 
		{
			$this->_list2->Rows = 2;
		} 
		else 
		{
			$this->_list2->Rows = count(TPropertyValue::ensureArray($value)) + 1;
		}
	}
	
	public function unselect1($sender, $param) 
	{
		$this->_list1->setSelectedIndex(-1);
		//$this->_button_addToList1->Enabled = true;
		//$this->_button_addToList2->Enabled = false;
	}
	
	public function unselect2($sender, $param) 
	{
		$this->_list2->setSelectedIndex(-1);
		//$this->_button_addToList1->Enabled = false;
		//$this->_button_addToList2->Enabled = true;
	}
	
}
?>