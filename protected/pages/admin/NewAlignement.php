<?php
class NewAlignement extends DSPage
{
	public function saveButtonClicked($sender, $param) {
        if ($this->IsValid) {
			$alignementRecord = new AlignementRecord();
            $alignementRecord->nom = $this->Nom->SafeText;
            $alignementRecord->description = $this->Description->SafeText;
			$alignementRecord->descriptionCourte = $this->DescriptionCourte->SafeText;
            $alignementRecord->save();
            $this->goToPage('admin.ListAlignement');
        }
    }
}
?>