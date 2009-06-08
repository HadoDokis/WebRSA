<?php
    class AppController extends Controller
    {
        var $components = array( 'Session', 'Auth', 'Acl', 'Droits', 'Cookie' );
        var $helpers = array( 'Html', 'Form', 'Javascript', 'Permissions' );
        var $uses = array( 'Group', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'User', 'Zonegeographique' );

        //*********************************************************************

        // INFO: n'est réellement exécuté que la première fois
        function _loadPermissions() {
            // FIXME:à bouger dans un composant ?
            $Auth = $this->Session->read( 'Auth' );
            // http://dsi.vozibrale.com/articles/view/all-cakephp-acl-permissions-for-your-views
            // http://www.neilcrookes.com/2009/02/26/get-all-acl-permissions/
            if( !empty( $Auth ) && !empty( $Auth['User'] ) && !$this->Session->check( 'Auth.Permissions' ) ) {
                $Aro = $this->Acl->Aro->find(
                    'first',
                    array(
                        'conditions' => array(
                            'Aro.foreign_key' => $Auth['User']['id']
                        )
                    )
                );

                // FIXME: trié par parent / fils ? .. un seul niveau
                $sql = 'SELECT acos.alias AS aco, aros_acos._create, aros.alias AS aro
                            FROM aros_acos
                                LEFT OUTER JOIN acos ON ( aros_acos.aco_id = acos.id )
                                LEFT OUTER JOIN aros ON ( aros_acos.aro_id = aros.id )
                            WHERE aros_acos.aro_id IN ( '.$Aro['Aro']['id'].','.$Aro['Aro']['parent_id'].' )
                            ORDER BY aco, aro ASC';
                $data = $this->User->query( $sql ); // FIXME: c'est sale ?

                $permissions = Set::combine( $data, '{n}.0.aco', '{n}.0._create' );
                foreach( $permissions as $key => $permission ) {
                    $permissions[$key] = ( $permission != -1 );
                }
                $this->Session->write( 'Auth.Permissions', $permissions );
            }
        }

        //---------------------------------------------------------------------

        // INFO: n'est réellement exécuté que la première fois
        function _loadZonesgeographiques() {
            $Auth = $this->Session->read( 'Auth' );
            if( !empty( $Auth ) && !empty( $Auth['User'] ) && !$this->Session->check( 'Auth.Zonegeographique' ) ) {
                $sql = 'SELECT users_zonesgeographiques.zonegeographique_id, zonesgeographiques.codeinsee
                            FROM users_zonesgeographiques
                                LEFT JOIN zonesgeographiques
                                    ON users_zonesgeographiques.zonegeographique_id = zonesgeographiques.id
                            WHERE users_zonesgeographiques.user_id='.$Auth['User']['id'].';';
                $results = $this->Dossier->query( $sql ); // FIXME: c'est sale ?
                if( count( $results ) > 0 ) {
                    $zones = array();
                    foreach( $results as $result ) {
                        $zones[$result[0]['zonegeographique_id']] = $result[0]['codeinsee'];
                    }
                    $this->Session->write( 'Auth.Zonegeographique', $zones ); // FIXME: vide -> rééxécute ?
                }
            }
        }

        //*********************************************************************

        function beforeFilter(){
            //$this->Cookie->time = 0;  // http://book.cakephp.org/fr/view/179/Controller-Setup -> pb Citrix

            $this->Auth->autoRedirect = false;
           // $this->Auth->loginRedirect = array('controller' => 'pages', 'action' => 'home');
            parent::beforeFilter();
            $this->_loadPermissions();
            $this->_loadZonesgeographiques();

            if((substr($_SERVER['REQUEST_URI'], strlen($this->base)) != '/users/login')) {
                if( !$this->Session->check( 'Auth' ) ) {
                    //le forcer a se connecter
                    $this->redirect("/users/login");
                    exit();
                }
                else {
                    $user = $this->Session->read( 'Auth' );
                    if( empty( $user['User'] ) ) {
                        $this->redirect("/users/login");
                    }
                    $controllerAction = $this->name . ':' . ($this->name == 'Pages' ? $this->params['pass'][0] : $this->action);

                    $this->assert( $this->Droits->check( $user['User']['aroAlias'], $controllerAction ), 'error403' );
                }
            }

            $this->disableCache(); // Disable browser
        }

        //*********************************************************************

        function assert( $condition, $error = 'error500', $parameters = array() ) {
            if( $condition !== true )
                $this->cakeError( $error, $parameters );
        }
    }
?>
