<?php
class NewDiscussion extends DSPage
{
    public function createButtonClicked($sender, $param)
    {
        if ($this->IsValid) {
            $discussionRecord = new ForumDiscussionRecord;
			$user = UserRecord::finder()->findBy_pseudo($this->User->Name);
			if ($user === null) {
				throw new THttpException(500, 'User is not logged in.');
			} 
            $discussionRecord->sujet = $this->Sujet->SafeText;
            $discussionRecord->description = $this->Description->SafeText;
			$discussionRecord->userId = $user->id;
			$discussionRecord->categorieId = $this->Request['forumCategorieId'];
			$discussionRecord->dernierModificateurId = $user->id;
			//$discussionRecord->dateCreation=date('Y/m/d h:i:s');
            $discussionRecord->save();
 
            $this->goToPage('forum.ListDiscussion', array('forumCategorieId'=>$this->Request['forumCategorieId']));
        }
    }
	
	protected function getCategorie() {
        $categorieID = (int)$this->Request['forumCategorieId'];
        $categorieRecord = ForumCategorieRecord::finder()->findByPk($categorieID);
        if ($categorieRecord === NULL) {
            throw new THttpException(500, 'forum_categorie_id_invalid', $categorieID);
		}
        return $categorieRecord;
    }
 
}
?>