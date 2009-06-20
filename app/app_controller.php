<?php
    class AppController extends Controller
    {
        var $components = array( 'Session', 'Auth', 'Acl', 'Droits', 'Cookie', 'Jetons' );
        var $helpers = array( 'Html', 'Form', 'Javascript', 'Permissions', 'Widget' );
        var $uses = array( 'Group', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'User', 'Zonegeographique', 'Connection', 'User', 'Serviceinstructeur' );

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

                if( !empty( $Aro ) ) {
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

        /**
        *
        *
        *
        */

        function beforeFilter() {
            // $this->Cookie->time = 0;  // ? http://book.cakephp.org/fr/view/179/Controller-Setup -> pb Citrix
            $this->disableCache(); // Disable browser
            $this->Auth->autoRedirect = false;
           // $this->Auth->loginRedirect = array('controller' => 'pages', 'action' => 'home');
            $return = parent::beforeFilter();
            $this->_loadPermissions();
            $this->_loadZonesgeographiques();

            if( ( substr( $_SERVER['REQUEST_URI'], strlen( $this->base ) ) != '/users/login' ) ) {
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

                    // Utilisateurs concurrents
                    if( Configure::read( 'Utilisateurs.multilogin' ) == false ) {
                        if( $connection = $this->Connection->findById( $user['User']['id'] ) ) {
                            unset( $connection['Connection']['modified'] );
                            $this->Connection->set( $connection );
                            $this->Connection->save( $connection );
                        }
                    }

                    // Données utilisateur et service instructeur correctement remplies
                    $name = Inflector::underscore( $this->name );
                    if( ( $name != 'droits' ) && ( $name != 'parametrages' ) && ( $name != 'servicesinstructeurs' ) && ( $name != 'users' ) ) {
                        $service = $this->Serviceinstructeur->findById( $user['User']['serviceinstructeur_id'] );
                        $missing = array(
                            'user' => array(
                                __( 'nom', true )                   => empty( $user['User']['nom'] ),
                                __( 'prenom', true )                => empty( $user['User']['prenom'] ),
                                __( 'service instructeur', true )   => empty( $user['User']['serviceinstructeur_id'] )
                            ),
                            'serviceinstructeur' => array(
                                __( 'lib_service', true )           => empty( $service['Serviceinstructeur']['lib_service'] ),
                                __( 'numdepins', true )             => empty( $service['Serviceinstructeur']['numdepins'] ),
                                __( 'typeserins', true )            => empty( $service['Serviceinstructeur']['typeserins'] ),
                                __( 'numcomins', true )             => empty( $service['Serviceinstructeur']['numcomins'] ),
                                __( 'numagrins', true )             => empty( $service['Serviceinstructeur']['numagrins'] ),
                            )
                        );

                        if( ( array_search( true, $missing['user'] ) !== false ) || ( array_search( true, $missing['serviceinstructeur'] ) !== false ) ) {
                            $this->cakeError( 'incompleteUser', array( 'missing' => $missing ) );
                        }
                    }

                    // Vérification des droits d'accès à la page
                    $controllerAction = $this->name . ':' . ($this->name == 'Pages' ? $this->params['pass'][0] : $this->action);
                    $this->assert( $this->Droits->check( $user['User']['aroAlias'], $controllerAction ), 'error403' );
                }
            }
            return $return;
        }

        //*********************************************************************

        /**
        *
        * INFO:
        *   cake/libs/error.php
        *   cake/libs/view/errors/
        *
        */

        function assert( $condition, $error = 'error500', $parameters = array() ) {
            if( $condition !== true ) {
                $calledFrom = debug_backtrace();
                $calledFromFile = substr( str_replace( ROOT, '', $calledFrom[0]['file'] ), 1 );
                $calledFromLine = $calledFrom[0]['line'];

                $this->log( 'Assertion failed: '.$error.' in '.$calledFromFile.' line '.$calledFromLine.' for url '.$this->here );

                $this->cakeError(
                    $error,
                    array_merge(
                        array(
                            'className' => Inflector::camelize( $this->params['controller'] ),
                            'action'    => $this->action,
                            'url'       => $this->params['url']['url'], // ? FIXME
                            'file'      => $calledFromFile,
                            'line'      => $calledFromLine
                        ),
                        $parameters
                    )
                );

                exit();
            }
        }
    }
?>
