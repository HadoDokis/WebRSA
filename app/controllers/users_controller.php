<?php
    class UsersController extends AppController
    {

        var $name = 'Users';
        var $uses = array('Group');
        var $aucunDroit = array('login', 'logout');

        /**
        *  The AuthComponent provides the needed functionality
        *  for login, so you can leave this function blank.
        */
        function login() {
           if ($this->Auth->user()) {
		/* Lecture de l'utilisateur authentifié */
		$authUser = $this->Auth->user();
		/* lecture du service de l'utilisateur authentifié */
		$this->Utilisateur->Service->recursive=-1;
                $group =  $this->Group->findById($authUser['User']['group_id']);
		//$authUser['aroAlias'] = $group['Group']['name'].':'. $authUser['User']['username'];
		$authUser['aroAlias'] = 'Utilisateur:'. $authUser['User']['username'];
		/* lecture de la collectivite de l'utilisateur authentifié */
		$this->Session->write('Auth.User', $authUser);
	        $this->redirect($this->Auth->redirect());
    	    }
        }

        function logout() {
            $this->redirect($this->Auth->logout());
        }
    }
?>
