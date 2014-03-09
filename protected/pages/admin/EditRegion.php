<?php
class EditRegion extends DSPage
{
    public function onInit($param) {
        parent::onInit($param);

        $regionRecord = $this->Region;
		
        if(!$this->User->IsAdmin) {
            throw new DSException(500, 'admin_view_disallowed');
		}
 
        if(!$this->IsPostBack) {
            $this->NomEdit->Text = $regionRecord->nom;
            $this->DescriptionEdit->Text = $regionRecord->description;
			$this->DescriptionCourteEdit->Text = $regionRecord->descriptionCourte;
        }
    }
 
    /**
     * Enregistre si toutes les validations sont Ok
     * cette méthode répond à l'évènement OnClick du bouton "Enregistrer".
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function saveButtonClicked($sender,$param) {
        if($this->IsValid) {
            $regionRecord = $this->Region;
            $regionRecord->nom = $this->NomEdit->SafeText;
            $regionRecord->description = $this->DescriptionEdit->SafeText;
			$regionRecord->descriptionCourte = $this->DescriptionCourteEdit->SafeText;
            $regionRecord->save();
            $this->goToPage('admin.ListRegion');
        }
    }
 
    /**
     * retourne les données du message devant être modifiées.
     * @return PostRecord les données devant être modifiés.
     * @throws THttpException si le message est inexistant.
     */
    protected function getRegion() {
        // l'ID du message devant être modifié passé par un paramètre GET 
        $regionID = (int)$this->Request['regionId'];
        // utilise Active Record pour lire le message correspondant à cet ID
        $regionRecord = RegionRecord::finder()->findByPk($regionID);
        if ($regionRecord === null) {
            throw new DSException(500, 'region_id_invalid', $regionID);
		}
        return $regionRecord;
    }
}
?>