<?php
class NewMessage extends DSPage
{
    /**
     * création d'un nouveau message si toutes les données sont valides.
     * cette méthode est appelée par l'évènement OnClick du bouton "Ajouter".
     * @param mixed sender : celui qui a généré l'évènement
     * @param mixed param : paramètres de l'évènement
     */
    public function createButtonClicked($sender,$param) {
        if($this->IsValid) {
            // créer un nouvel objet PostRecord avec les données du formulaire
            $messageRecord = new MessageRecord;
            // utiliser SafeText à la place de Text évite les attaques XSS
            $messageRecord->titre=$this->Title->SafeText;
            $messageRecord->texte=$this->Content->SafeText;
			$messageRecord->aventureId=$this->Request['aventureId'];
            $messageRecord->dateCreation=date('Y/m/d h:i:s');
			$messageRecord->isXpGiven = false;
			
			$messageType = $this->Request['messageType'];
			$persoRecord = $this->User->Perso;
			$maitre = $this->MaitreDeLaventure;
			
			if ($persoRecord === NULL) {
				throw new THttpException(500, 'perso_not_found');
			}			
			
			if ($messageType == NULL) {				
				if ($this->isAllowToWriteAnonymeMessage() && $this->isAllowToWritePersoMessage()) {
					if ($this->TypeMessage->SelectedValue == 0) {
						$messageRecord->persoId = $this->User->Perso->id;
					} else {
						$messageRecord->persoId = $maitre->id;
					}
				} else if ($this->isAllowToWriteAnonymeMessage() && !$this->isAllowToWritePersoMessage()) {
					$messageRecord->persoId = $maitre->id;
				} /*else if (!$this->isAllowToWriteAnonymeMessage() && $this->isAllowToWritePersoMessage()) {
					$messageRecord->persoId = $this->User->Perso->id;
				}*/
			} else if ($messageType == 'join') {
				$persoRecord->aventureId = $this->Aventure->id;
				$messageRecord->texte = $messageRecord->texte.'<br/> <p>'.$persoRecord->nom.' a joint l\'aventure. </p>';				
			} else if ($messageType == 'quit') {
				$persoRecord->aventureId = NULL;
				$messageRecord->texte = $messageRecord->texte.'<br/> <p>'.$persoRecord->nom.' a quitté l\'aventure. </p>';			
			}
 
            // enregistre les données dans la BDD par la méthode save de l'Active Record
			$messageRecord->persoId = $persoRecord->id;
            $messageRecord->save();
			$persoRecord->save();
			
			// envoie un emails aux autres joueurs pour avertir que l'aventure continue
			//$this->sendMail();
 
            // redirige le navigateur vers le message nouvellement créé
            $this->goToPage('aventures.ListMessage', array('aventureId'=>$messageRecord->aventureId));
        }
    }
	
	public function isAllowToWriteAnonymeMessage() {
		$ok = false;
		$messageType = $this->Request['messageType'];
		$userRecord = $this->User->Profile;
        if ($userRecord === NULL) {
			throw new THttpException(500, 'profile_not_found');
		}
		if ($userRecord->id == $this->Aventure->createurId && $messageType == NULL) {
			$ok = true;
		}
		return $ok;
    }
	
	public function isAllowToWritePersoMessage() {
		$ok = false;
		$messageType = $this->Request['messageType'];
		if ($this->User->hasPerso() && $this->User->Perso->aventureId == $this->Aventure->id && $messageType == NULL) {
			$ok = true;
		}
		return $ok;
    }
	
	/**
     * retourne les données du aventure devant être modifiées.
     * @return PostRecord les données devant être modifiés.
     * @throws THttpException si le aventure est inexistant.
     */
    protected function getAventure() {
        // l'ID du aventure devant être modifié passé par un paramètre GET 
        $aventureID = (int)$this->Request['aventureId'];
        // utilise Active Record pour lire l'aventure correspondant à cet ID
        $aventureRecord = AventureRecord::finder()->findByPk($aventureID);
        if ($aventureRecord === NULL) {
            throw new THttpException(500, 'aventure_id_invalid', $aventureID);
		}
        return $aventureRecord;
    }
	
	protected function getMaitreDeLaventure() {
        $maitre = PersoRecord::finder()->findBy_nom("Maitre de l'aventure");
        if ($maitre === NULL) {
            throw new THttpException(500, 'perso_id_invalid');
		}
        return $maitre;
    }
	
	private function sendMail() {
		// Instantiate your new class
		$mail = new DSMailer();
		
		// Now you only need to add the necessary stuff
		$mail->AddAddress("fortier.jc@gmail.com", "JC");
		$mail->Subject = "DS sujet";
		$mail->Body    = "Ceci est un message de dungeon story";
		//$mail->AddAttachment("c:/temp/11-10-00.zip", "new_name.zip");  // optional name
		
		if(!$mail->Send())
		{
		   $this->test->Text = "erreur email : ".$mail->ErrorInfo;
		  // exit;
		} else {
			$this->test->Text =  "Message was sent successfully";
		}
	}

		
}
?>