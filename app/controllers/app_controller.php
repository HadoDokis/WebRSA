<?php
    class AppController extends Controller
    {
        var $components = array( 'Session', 'Auth', 'Acl', 'Droits' );
        var $helpers = array( 'Html', 'Form', 'Javascript' );
        var $uses     = array('Group', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'User');

        function beforeFilter(){
            $this->Auth->autoRedirect = false;
           // $this->Auth->loginRedirect = array('controller' => 'pages', 'action' => 'home');
	    parent::beforeFilter();

            if((substr($_SERVER['REQUEST_URI'], strlen($this->base)) != '/users/login')) {
                if (!$this->Session->Check('Auth')) {
                    //le forcer a se connecter
                    $this->redirect("/users/login");
                    exit();
                }
                else {
                    $user  = $this->Session->read('Auth');
                    $controllerAction = $this->name . ':' . ($this->name == 'Pages' ? $this->params['pass'][0] : $this->action);
                    if (!$this->Droits->check($user['User']['aroAlias'], $controllerAction)) 
                        die("Vous n'avez pas les droits suffisants -> ".$controllerAction);                  
                }
            }
        }          

        function assert( $condition, $error = 'error500' ) {
            if( $condition !== true )
                $this->cakeError( $error );
        }
    }
?>
