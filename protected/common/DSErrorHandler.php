<?php

Prado::using('System.Exceptions.TErrorHandler');
Prado::using('Application.Common.DSException');

class DSErrorHandler extends TErrorHandler
{
    /**
     * Renvoi le fichier gabarit utilisé pour afficher l'erreur.
     * Cette méthode surcharge la méthode originale.
     */
	 /*
    protected function getErrorTemplate($statusCode, $exception) {
        // on utilise notre propre gabarit pour BlogException
        if ($exception instanceof DSException) {
            // récupère le chemin du fichier de gabarit : protected/error.html
            $templateFile=Prado::getPathOfNamespace('Application.Common.DSErrorReport','.page');
            return file_get_contents($templateFile);
        }
        else {// sinon on utilise le gabarit par défaut.
            return parent::getErrorTemplate($statusCode, $exception);
		}
    }
 	*/
	
    /**
     * Gère les erreurs causées par les utilisateurs.
     * Cette méthode surcharge la méthode originale.
     * Elle est appelée lorsqu'une exception utilisateur est générée.
     */
    protected function handleExternalError($statusCode, $exception) {
        // Journaliser l'erreur (seulement pour DSException)
        if ($exception instanceof DSException) {			
			$message = $exception->getErrorMessage();
			Prado::log($message, TLogger::ERROR, 'DSApplication');
			$message = $this->getApplication()->getSecurityManager()->hashData($message);
			$this->Response->redirect($this->Service->constructUrl('DSErrorReport', array('errormsg'=>$message), false));
		} else {
			// appelle l'implémentation de la classe parente
			parent::handleExternalError($statusCode, $exception);
		}
    }
}
?>