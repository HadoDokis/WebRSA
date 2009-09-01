<?php
    class AppController extends Controller
    {
        var $components = array( 'Session', 'Auth', 'Acl', 'Droits', 'Cookie', 'Jetons' );
        var $helpers = array( 'Html', 'Form', 'Javascript', 'Permissions', 'Widget', 'Locale' );
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

                    // Groupe de l'utilisateur
                    $group = $this->User->Group->findById( $user['User']['group_id'], null, null, -1 );
                    $this->assert( !empty( $group ), 'error500' ); // FIXME: erreur de boulet -> en créer un nouveau type
                    $this->Session->write( 'Auth.Group', $group['Group'] );

                    // Service instructeur de l'utilisateur
                    $service = $this->User->Serviceinstructeur->findById( $user['User']['serviceinstructeur_id'], null, null, -1 );
                    $this->assert( !empty( $service ), 'error500' ); // FIXME: erreur de boulet -> en créer un nouveau type
                    $this->Session->write( 'Auth.Serviceinstructeur', $service['Serviceinstructeur'] );

                    // Données utilisateur et service instructeur correctement remplies
                    $name = Inflector::underscore( $this->name );
                    if( ( $name != 'droits' ) && ( $name != 'parametrages' ) && ( $name != 'servicesinstructeurs' ) && ( $name != 'users' ) ) {
                        $service = $this->Serviceinstructeur->findById( $user['User']['serviceinstructeur_id'] );
                        $missing = array(
                            'user' => array(
                                __( 'nom', true )                   => empty( $user['User']['nom'] ),
                                __( 'prenom', true )                => empty( $user['User']['prenom'] ),
                                __( 'service instructeur', true )   => empty( $user['User']['serviceinstructeur_id'] ),
                                __( 'date_deb_hab', true )          => empty( $user['User']['date_deb_hab'] ),
                                __( 'date_fin_hab', true )          => empty( $user['User']['date_fin_hab'] )
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

                        // Habilitations -> FIXME!
                        $habilitations = array(
                            'date_deb_hab' => $this->Session->read( 'Auth.User.date_deb_hab' ),
                            'date_fin_hab' => $this->Session->read( 'Auth.User.date_fin_hab' ),
                        );
                        if( !empty( $habilitations['date_deb_hab'] ) && ( strtotime( $habilitations['date_deb_hab'] ) >= mktime() ) ) {
                            $this->cakeError( 'dateHabilitationUser', array( 'habilitations' => $habilitations ) );
                        }
                        if( !empty( $habilitations['date_fin_hab'] ) && ( strtotime( $habilitations['date_fin_hab'] ) < mktime() ) ) {
                            $this->cakeError( 'dateHabilitationUser', array( 'habilitations' => $habilitations ) );
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

        /**
        *
        *
        *
        *
        *
        */

        function paginate( $object = null, $scope = array(), $whitelist = array() ) {
            if (is_array($object)) {
                $whitelist = $scope;
                $scope = $object;
                $object = null;
            }
            $assoc = null;

            if (is_string($object)) {
                $assoc = null;

                if (strpos($object, '.') !== false) {
                    list($object, $assoc) = explode('.', $object);
                }

                if ($assoc && isset($this->{$object}->{$assoc})) {
                    $object = $this->{$object}->{$assoc};
                } elseif ($assoc && isset($this->{$this->modelClass}) && isset($this->{$this->modelClass}->{$assoc})) {
                    $object = $this->{$this->modelClass}->{$assoc};
                } elseif (isset($this->{$object})) {
                    $object = $this->{$object};
                } elseif (isset($this->{$this->modelClass}) && isset($this->{$this->modelClass}->{$object})) {
                    $object = $this->{$this->modelClass}->{$object};
                }
            } elseif (empty($object) || $object === null) {
                if (isset($this->{$this->modelClass})) {
                    $object = $this->{$this->modelClass};
                } else {
                    $className = null;
                    $name = $this->uses[0];
                    if (strpos($this->uses[0], '.') !== false) {
                        list($name, $className) = explode('.', $this->uses[0]);
                    }
                    if ($className) {
                        $object = $this->{$className};
                    } else {
                        $object = $this->{$name};
                    }
                }
            }

            if (!is_object($object)) {
                trigger_error(sprintf(__('Controller::paginate() - can\'t find model %1$s in controller %2$sController', true), $object, $this->name), E_USER_WARNING);
                return array();
            }
            $options = array_merge($this->params, $this->params['url'], $this->passedArgs);

            if (isset($this->paginate[$object->alias])) {
                $defaults = $this->paginate[$object->alias];
            } else {
                $defaults = $this->paginate;
            }

            if (isset($options['show'])) {
                $options['limit'] = $options['show'];
            }

            if (isset($options['sort'])) {
                $direction = null;
                if (isset($options['direction'])) {
                    $direction = strtolower($options['direction']);
                }
                if ($direction != 'asc' && $direction != 'desc') {
                    $direction = 'asc';
                }
                $options['order'] = array($options['sort'] => $direction);
            }

            if (!empty($options['order']) && is_array( $options['order'] ) ) {
                $alias = $object->alias ;
                $key = $field = key($options['order']);

                if (strpos($key, '.') !== false) {
                    list($alias, $field) = explode('.', $key);
                }
                $value = $options['order'][$key];
                unset($options['order'][$key]);

                if( isset($object->{$alias}) && $object->{$alias}->hasField( $field ) ) {
                    $options['order'][$alias . '.' . $field] = $value;
                } elseif( $object->hasField( $field ) ) {
                    $options['order'][$alias . '.' . $field] = $value;
                } else {
                    // INFO: permet de trier sur d'autres champs que ceux du modèle que l'on pagine
                    $joinAliases = Set::extract( $defaults, '/joins/alias' );
                    if( in_array( $alias, $joinAliases ) ) {
                        $options['order'][$alias . '.' . $field] = $value;
                    }
                }
            }

            $vars = array('fields', 'order', 'limit', 'page', 'recursive');
            $keys = array_keys($options);
            $count = count($keys);

            for ($i = 0; $i < $count; $i++) {
                if (!in_array($keys[$i], $vars, true)) {
                    unset($options[$keys[$i]]);
                }
                if (empty($whitelist) && ($keys[$i] === 'fields' || $keys[$i] === 'recursive')) {
                    unset($options[$keys[$i]]);
                } elseif (!empty($whitelist) && !in_array($keys[$i], $whitelist)) {
                    unset($options[$keys[$i]]);
                }
            }
            $conditions = $fields = $order = $limit = $page = $recursive = null;

            if (!isset($defaults['conditions'])) {
                $defaults['conditions'] = array();
            }

            $type = 'all';

            if (isset($defaults[0])) {
                $type = $defaults[0];
                unset($defaults[0]);
            }
            extract($options = array_merge(array('page' => 1, 'limit' => 20), $defaults, $options));

            if (is_array($scope) && !empty($scope)) {
                $conditions = array_merge($conditions, $scope);
            } elseif (is_string($scope)) {
                $conditions = array($conditions, $scope);
            }
            if ($recursive === null) {
                $recursive = $object->recursive;
            }

            $extra = array_diff_key($defaults, compact(
                'conditions', 'fields', 'order', 'limit', 'page', 'recursive'
            ));

            if ($type !== 'all') {
                $extra['type'] = $type;
            }

            if (method_exists($object, 'paginateCount')) {
                $count = $object->paginateCount($conditions, $recursive, $extra);
            } else {
                $parameters = compact('conditions');
                if ($recursive != $object->recursive) {
                    $parameters['recursive'] = $recursive;
                }
                $count = $object->find('count', array_merge($parameters, $extra));
            }
            $pageCount = intval(ceil($count / $limit));

            if ($page === 'last' || $page >= $pageCount) {
                $options['page'] = $page = $pageCount;
            } elseif (intval($page) < 1) {
                $options['page'] = $page = 1;
            }
            $page = $options['page'] = (integer)$page;

            if (method_exists($object, 'paginate')) {
                $results = $object->paginate($conditions, $fields, $order, $limit, $page, $recursive, $extra);
            } else {
                $parameters = compact('conditions', 'fields', 'order', 'limit', 'page');
                if ($recursive != $object->recursive) {
                    $parameters['recursive'] = $recursive;
                }
                $results = $object->find($type, array_merge($parameters, $extra));
            }
            $paging = array(
                'page'      => $page,
                'current'   => count($results),
                'count'     => $count,
                'prevPage'  => ($page > 1),
                'nextPage'  => ($count > ($page * $limit)),
                'pageCount' => $pageCount,
                'defaults'  => array_merge(array('limit' => 20, 'step' => 1), $defaults),
                'options'   => $options
            );

            $this->params['paging'][$object->alias] = $paging;

            if (!in_array('Paginator', $this->helpers) && !array_key_exists('Paginator', $this->helpers)) {
                $this->helpers[] = 'Paginator';
            }
            return $results;
        }



        /** ********************************************************************
        *
        *** *******************************************************************/

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }


        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }
    }
?>
