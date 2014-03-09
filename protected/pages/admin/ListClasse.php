<?php
class ListClasse extends DSPage
{
    public function onLoad($param)
    {
        parent::onLoad($param);
        if (!$this->IsPostBack) 
        {        	
			$criteria = new TActiveRecordCriteria;
			$criteria->OrdersBy['nom'] = 'asc';
			$data = ClasseRecord::finder()->findAll($criteria);
// 			$data2 = array();
			
// 			foreach ($data as $cl)
// 			{
// 				$data2[] = $this->Service->constructUrl('admin.EditClasse', array('classeId'=>$cl->id));
// 			}
            $this->RepeaterClasse->DataSource = $data;
            $this->RepeaterClasse->dataBind();
        }
    }
}
?>