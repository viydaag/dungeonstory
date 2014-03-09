<?php
class ViewUser extends DSPage
{
	public function onInit($param) 
	{
		parent::onInit($param);
		$userRecord = $this->User->Profile;
		if ($userRecord === null) 
		{
			throw new DSException(500, 'profile_id_invalid', $this->User->Profile->id);
		}

		if (!$this->IsPostBack) 
		{
			$this->Pseudo->Text = $userRecord->pseudo;
			$this->Name->Text = $userRecord->nom;
			$this->Email->Text = $userRecord->email;
			$this->Role->Text = $userRecord->role->nom;
			$this->Since->Text = $userRecord->dateCreation;
		}
	}
}
?>