<?php
class ListForumMessage extends DSPage
{	
	public function onLoad($param) {
		parent::onLoad($param);
        if(!$this->IsPostBack) {
            $forumMessagefinder = ForumMessageRecord::finder();
			$forumMessages = $forumMessagefinder->findAllBy_discussionId($this->Request['forumDiscussionId']);
			$this->ForumMessageRepeater->DataSource = $forumMessages;
			$this->ForumMessageRepeater->dataBind();
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
