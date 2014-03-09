<?php
Prado::using('Application.Portlets.DSPortlet');

class LoginPortlet extends DSPortlet
{
    /**
     * Vérifie la validité du nom d'utilisateur et du mot de passe.
     * Cette méthode implémente l'évènement <tt>OnServerValidate</tt> du validateur <tt>TCustomValidator</tt>.
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function validateUser($sender, $param) {
		$user = $this->Username->Text;
		$pass = $this->Password->Text;
		$authManager = $this->Application->getModule('auth');
		$param->IsValid = $authManager->login($user, $pass);
		if ($param->IsValid == false) {
			$userRecord = UserRecord::finder()->findBy_pseudo($user);
			if ($userRecord !== NULL) {
				if ($userRecord->isDeactivated()) {
					throw new DSException(500, 'profile_deactivated');
				} else {
					$sender->ErrorMessage = 'Vous avez saisi un mot de passe invalide';
				}
			} else {
				$sender->ErrorMessage = 'Vous avez saisi un pseudo invalide';
			}
		}
    }
 
    /**
     * Rédirige le navigateur vers l'URL originellement demandée si la validation est Ok.
     * Cette méthode implémente l'évènement <tt>OnClick</tt> du bouton "envoyer".
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function loginButtonClicked($sender,$param) {
        if ($this->Page->IsValid)  {
            // récupère l'URL de la page protégée qui avait été demandée par l'utilisateur
            $url = $this->Application->getModule('auth')->ReturnUrl;
            if (empty($url)) { // l'utilisateur à accéder à la page de connexion directement
                $url = $this->Service->DefaultPageUrl;
			}
            $this->Response->redirect($url);
        }
    }
}
?>