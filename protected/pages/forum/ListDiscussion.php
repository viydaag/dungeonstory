<?php
class ListDiscussion extends DSPage
{	
	public function onLoad($param) {
		parent::onLoad($param);
        if(!$this->IsPostBack) {
            $discussionfinder = ForumDiscussionRecord::finder();
			$discussions = $discussionfinder->findAllBy_categorieId($this->Request['forumCategorieId']);
			$this->DiscussionRepeater->DataSource = $discussions;
			$this->DiscussionRepeater->dataBind();
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