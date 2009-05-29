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
                if( !$this->Session->Check( 'Auth' ) ) {
                    //le forcer a se connecter
                    $this->redirect("/users/login");
                    exit();
                }
                else {
                    $user = $this->Session->read( 'Auth' );
                    if( empty( $user['User'] ) ) {
                        $this->redirect("/users/login");
                    }

                    // FIXME: ailleurs pour ne le faire qu'une fois
                    $sql = 'SELECT users_zonesgeographiques.zonegeographique_id FROM users_zonesgeographiques WHERE users_zonesgeographiques.user_id='.$user['User']['id'].';';
                    $results = $this->Dossier->query( $sql ); // FIXME: c'est sale
                    if( count( $results ) > 0 ) {
                        $zones = array();
                        foreach( $results as $result ) {
                            $zones[] = $result[0]['zonegeographique_id'];
                        }
                        $this->Session->write( 'Auth.User.Zonegeographique', $zones );
                    }

                    $controllerAction = $this->name . ':' . ($this->name == 'Pages' ? $this->params['pass'][0] : $this->action);
                    if( !$this->Droits->check( $user['User']['aroAlias'], $controllerAction ) )
                        die( "Vous n'avez pas les droits suffisants -> ".$controllerAction );
                }
            }
        }

        function assert( $condition, $error = 'error500' ) {
            if( $condition !== true )
                $this->cakeError( $error );
        }
    }
?>
