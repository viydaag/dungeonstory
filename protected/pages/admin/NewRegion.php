<?php
class NewRegion extends DSPage
{
	public function saveButtonClicked($sender, $param) 
    {
        if ($this->IsValid) 
        {
			$regionRecord = new RegionRecord();
            $regionRecord->nom = $this->Nom->SafeText;
			$regionRecord->description = $this->DescriptionCourte->SafeText;
            $regionRecord->description = $this->Description->SafeText;
            $regionRecord->save();
            $this->goToPage('admin.ListRegion');
        }
    }
}
?>