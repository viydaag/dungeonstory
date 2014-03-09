<?php
class ListCategorie extends DSPage
{	
	public function onLoad($param) {
		parent::onLoad($param);
        if(!$this->IsPostBack) {
            $categoriefinder = ForumCategorieRecord::finder();
			$categories = $categoriefinder->findAll();
			$this->CategorieRepeater->DataSource = $categories;
			$this->CategorieRepeater->dataBind();
		}
	}
}
?>