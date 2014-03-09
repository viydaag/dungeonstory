<?php
// Include TDbUserManager.php file which defines TDbUser
Prado::using('System.Security.TDbUserManager');
 
/**
 * La classe DSUser.
 * DSUser représente les données utilisateurs à conserver en session.
 * L'implémentation par défaut conserve le nom et le rôle de l'utilisateur.
 */
class DSUser extends TDbUser
{
    /**
     * Créer un objet de type DSUser basé sur le nom de l'utilisateur.
     * Cette méthode est requise par TDbUser. Cet objet vérifie si l'utilisateur
     * est bien présent en base de données. Si oui, un objet BlogUser
     * est créé et initialisé.
     * @param string le nom de l'utilisateur
     * @return l'objet BlogUser, null si le nom de l'utilisateur est invalide.
     */
    public function createUser($username) {
        // utilise l'Active Record  UserRecord pour chercher l'utilisateur username
        $userRecord = UserRecord::finder()->findBy_pseudo($username);
        if($userRecord instanceof UserRecord) { // si trouvé        
            $user = new DSUser($this->Manager);
            $user->Name = $username;  // enregistre le nom de l'utilisateur
			//$user->Roles=($userRecord->roleId==1?'admin':'user'); // et son rôle
			$role = RoleRecord::finder()->findByPk($userRecord->roleId);
			$user->Roles = $role->nom;
            $user->IsGuest = false;   // l'utilisateur n'est pas un invité
            return $user;
        } else {
            return null;
		}
    }
 
    /**
     * Vérifie que le nom d'utilisateur et son mot de passe sont correct.
     * Cette méthode est requise par TDbUser.
     * @param string le nom de l'utilisateur
     * @param string le mot de passe
     * @return boolean en fonction de la validité de la vérification.
     */
    public function validateUser($username, $password) {
        // utilise l'Active Record UserRecord pour vérifier le nom d'utilisateur couplé au mot de passe.
        //return UserRecord::finder()->findBy_pseudo_AND_password($username, $password) !== null;
		$userRecord = UserRecord::finder()->findBy_pseudo($username);
		if ($userRecord !== null) {
			return $userRecord->password === md5($password) && $userRecord->statut != UserRecord::STATUT_DESACTIVE;
		} else {
			return false;
		}
    }
 
    /**
     * @return boolean indiquant si l'utilisateur est un administrateur.
     */
    public function getIsAdmin() {
        return $this->isInRole('admin');
    }
	
	public function hasPerso() {
		$userRecord = $this->Profile;
		if ($userRecord instanceof UserRecord) { // si trouvé
			return PersoRecord::finder()->findBy_userId($userRecord->id) !== null;
		} else {
			return false;
		}		
	}
	
	public function getPerso() {
		$userRecord = $this->Profile;
		if ($userRecord instanceof UserRecord) { // si trouvé
			$persoRecord = PersoRecord::finder()->findBy_userId($userRecord->id);
			if ($persoRecord instanceof PersoRecord) {
				return $persoRecord;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}
	
	public function getProfile() {
		return UserRecord::finder()->findBy_pseudo($this->Name);
	}		
		
}
?>