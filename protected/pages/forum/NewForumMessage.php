<?php
class NewForumMessage extends DSPage
{
    public function createButtonClicked($sender, $param)
    {
        if ($this->IsValid) {
            $forumMessageRecord = new ForumMessageRecord;
			$user = UserRecord::finder()->findBy_pseudo($this->User->Name);
			if ($user === null) {
				throw new THttpException(500, 'User is not logged in.');
			} 
            $forumMessageRecord->texte = $this->Content->SafeText;
			$forumMessageRecord->userId = $user->id;
			$forumMessageRecord->discussionId = $this->Request['forumDiscussionId'];
			//$discussionRecord->dateCreation=date('Y/m/d h:i:s');
            $forumMessageRecord->save();
 
            $this->goToPage('forum.ListForumMessage', array('forumCategorieId'=>$this->Request['forumCategorieId'], 'forumDiscussionId'=>$this->Request['forumDiscussionId']));
        }
    }
	
	protected function getDiscussion() {
        $discussionID = (int)$this->Request['forumDiscussionId'];
        $discussionRecord = ForumDiscussionRecord::finder()->findByPk($discussionID);
        if ($discussionRecord === NULL) {
            throw new THttpException(500, 'forum_discussion_id_invalid', $discussionID);
		}
        return $discussionRecord;
    }
 
}
?>
