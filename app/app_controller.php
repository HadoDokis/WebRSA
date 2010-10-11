<?php
    class AppController extends Controller
    {
        var $components = array( 'Session', 'Auth', 'Acl', 'Droits', 'Cookie', 'Jetons'/*, 'Xcontroller'*/, 'Default' );
        var $helpers = array( 'Html', 'Form', 'Javascript', 'Permissions', 'Widget', 'Locale', 'Theme', 'Default', 'Number' );
        var $uses = array( 'User', 'Connection' );

 		//public $persistModel = true;

		/**
		* Chargement et mise en cache (session) des permissions de l'utilisateur
        * INFO:
		*	- n'est réellement exécuté que la première fois
		* 	- http://dsi.vozibrale.com/articles/view/all-cakephp-acl-permissions-for-your-views
		* 	- http://www.neilcrookes.com/2009/02/26/get-all-acl-permissions/
		*/

        protected function _loadPermissions() {
            // FIXME:à bouger dans un composant ?
            if( $this->Session->check( 'Auth.User' ) && !$this->Session->check( 'Auth.Permissions' ) ) {
                $Aro = $this->Acl->Aro->find(
                    'first',
                    array(
                        'conditions' => array(
							'model' => 'Utilisateur',
                            'Aro.foreign_key' => $this->Session->read( 'Auth.User.id' )
                        )
                    )
                );

                // Recherche des droits pour les sous-groupes
                $parent_id = Set::extract( $Aro, 'Aro.parent_id' );
                $parentAros = array();
                while( !empty( $parent_id ) && ( $parent_id != 0 ) ) {
                    $parentAro = $this->Acl->Aro->find(
                        'first',
                        array(
                            'conditions' => array(
                                'Aro.id' => $parent_id
                            )
                        )
                    );
                    $parentAros[] = $parentAro;
                    $parent_id = Set::extract( $parentAro, 'Aro.parent_id' );
                }

				$permissions = array();
                if( !empty( $parentAros ) && !empty( $parentAros['Aro'] ) && !empty( $parentAros['Aco'] ) ) {
	                $permissions = Set::combine( $parentAros, '/Aco/alias', '/Aco/Permission/_create' );
                }
                if( !empty( $Aro ) ) {
                    // FIXME: trié par parent / fils ? .. un seul niveau
                    $sql = 'SELECT acos.alias AS aco, aros_acos._create, aros.alias AS aro
                                FROM aros_acos
                                    LEFT OUTER JOIN acos ON ( aros_acos.aco_id = acos.id )
                                    LEFT OUTER JOIN aros ON ( aros_acos.aro_id = aros.id )
                                WHERE aros_acos.aro_id IN ( '.$Aro['Aro']['id'].','.$Aro['Aro']['parent_id'].' )
                                ORDER BY aco, aro ASC';
                    $data = $this->Connection->query( $sql ); // FIXME: c'est sale ?

                    $permissions = Set::merge( $permissions, Set::combine( $data, '{n}.0.aco', '{n}.0._create' ) );
                    foreach( $permissions as $key => $permission ) {
                        $permissions[$key] = ( $permission != -1 );
                    }
                    $this->Session->write( 'Auth.Permissions', $permissions );
                }
            }
        }

		/**
		* Chargement et mise en cache (session) des zones géographiques associées à l'utilisateur
        * INFO: n'est réellement exécuté que la première fois
		*/

        protected function _loadZonesgeographiques() {
            if( $this->Session->check( 'Auth.User' ) && $this->Session->read( 'Auth.User.filtre_zone_geo' ) && !$this->Session->check( 'Auth.Zonegeographique' ) ) {
                $sql = 'SELECT users_zonesgeographiques.zonegeographique_id, zonesgeographiques.codeinsee
                            FROM users_zonesgeographiques
                                LEFT JOIN zonesgeographiques
                                    ON users_zonesgeographiques.zonegeographique_id = zonesgeographiques.id
                            WHERE users_zonesgeographiques.user_id='.$this->Session->read( 'Auth.User.id' ).';';
                $results = $this->Connection->query( $sql ); // FIXME: c'est sale ?
                if( count( $results ) > 0 ) {
                    $zones = array();
                    foreach( $results as $result ) {
                        $zones[$result[0]['zonegeographique_id']] = $result[0]['codeinsee'];
                    }
                    $this->Session->write( 'Auth.Zonegeographique', $zones ); // FIXME: vide -> rééxécute ?
                }
            }
        }

        /**
        * @access protected
        */

        function _checkHabilitations() {
            $habilitations = array(
                'date_deb_hab' => $this->Session->read( 'Auth.User.date_deb_hab' ),
                'date_fin_hab' => $this->Session->read( 'Auth.User.date_fin_hab' ),
            );

            if( !empty( $habilitations['date_deb_hab'] ) && ( strtotime( $habilitations['date_deb_hab'] ) >= mktime() ) ) {
                $this->cakeError( 'dateHabilitationUser', array( 'habilitations' => $habilitations ) );
            }

			/// FIXME: si la date d'habilitation est celle du jour il n'est plus habilité du tout
            if( !empty( $habilitations['date_fin_hab'] ) && ( strtotime( $habilitations['date_fin_hab'] ) < mktime() ) ) {
                $this->cakeError( 'dateHabilitationUser', array( 'habilitations' => $habilitations ) );
            }
        }

		/**
		*
		*/

// 		public function afterFilter() {
// 			echo '<pre>';var_dump( byteSize( memory_get_peak_usage( true ) ) );echo '</pre>';
// 		}

		/**
		* Chargement du service instructeur de l'utilisateur connecté, lancement
		* d'une erreur si aucun service instructeur n'est associé à l'utilisateur
		* FIXME: créer un nouveau type d'erreur plutôt qu'une erreur 500
		*/

        protected function _loadServiceInstructeur() {
            if( !$this->Session->check( 'Auth.Serviceinstructeur' ) ) {
				$service = $this->User->Serviceinstructeur->findById( $this->Session->read( 'Auth.User.serviceinstructeur_id' ), null, null, -1 );
				$this->assert( !empty( $service ), 'error500' );
				$this->Session->write( 'Auth.Serviceinstructeur', $service['Serviceinstructeur'] );
			}
		}

		/**
		* Chargement du groupe de l'utilisateur connecté, lancement
		* d'une erreur si aucun groupe n'est associé à l'utilisateur
		* FIXME: créer un nouveau type d'erreur plutôt qu'une erreur 500
		*/

        protected function _loadGroup() {
            if( !$this->Session->check( 'Auth.Group' ) ) {
				$group = $this->User->Group->findById( $this->Session->read( 'Auth.User.group_id' ), null, null, -1 );
				$this->assert( !empty( $group ), 'error500' );
				$this->Session->write( 'Auth.Group', $group['Group'] );
			}
		}

		/**
		* Utilisateurs concurrents, mise à jour du dernier accès pour la connection
		*/

		protected function _updateConnection() {
				if( Configure::read( 'Utilisateurs.multilogin' ) == false ) {
					$connection_id = $this->Session->read( 'Auth.Connection.id' );

					if( empty( $connection_id ) ) {
						$connection_id = $this->Connection->field( 'id', array( 'user_id' => $this->Session->read( 'Auth.User.id' ) ) );
						$this->Session->write( 'Auth.Connection.id', $connection_id );
					}

					if( !empty( $connection_id ) ) {
						$this->Connection->create( array( 'id' => $connection_id ) );
						$this->Connection->saveField( 'modified', null );
					}
				}
		}

		/**
		* Vérifie que l'utilisateur a la permission d'accéder à la page
		*/

		protected function _checkPermissions() {
			// Vérification des droits d'accès à la page
			if( $this->name != 'Pages' && !( $this->name == 'Users' && ( $this->action == 'login' || $this->action == 'logout' ) ) ) {
				/// Ancienne manière, génère 4 requètes SQL
				/*$controllerAction = $this->name . ':' . $this->action;
				$this->assert( $this->Droits->check( $this->Session->read( 'Auth.User.aroAlias' ), $controllerAction ), 'error403' );*/

				/// Nouvelle manière, accès au cache se trouvant dans la session
				$permissions = $this->Session->read( 'Auth.Permissions' );
				if( isset( $permissions["{$this->name}:{$this->action}"] ) ) {
					$this->assert( !empty( $permissions["{$this->name}:{$this->action}"] ), 'error403' );
				}
				else if( isset( $permissions["Module:{$this->name}"] ) ) {
					$this->assert( !empty( $permissions["Module:{$this->name}"] ), 'error403' );
				}
				else {
					$this->cakeError( 'error403' );
				}

				/// FIXME: inverser la logique dans le Helper Permissions ?
				/*if( isset( $permissions["Module:{$this->name}"] ) ) {
					$this->assert( !empty( $permissions["Module:{$this->name}"] ), 'error403' );
				}
				else {
					$this->assert( Set::classicExtract( $permissions, "{$this->name}:{$this->action}" ), 'error403' );
				}*/
			}
		}

		/**
		*
		*/

		function beforeFilter() {
			/*
				Désactivation du cache du navigateur: (quand on revient en arrière
				dans l'historique de navigation, la page n'est pas cachée du côté du
				navigateur, donc il ré-exécute la demande)
			*/
			$this->disableCache(); // Disable browser cache ?
			$this->Auth->autoRedirect = false;

			$this->set( 'etatdosrsa', ClassRegistry::init( 'Option' )->etatdosrsa() );
			$return = parent::beforeFilter();

			// Fin du traitement pour les requestactions et les appels ajax
			if( isset( $this->params['requested'] ) || isset( $this->params['isAjax'] ) ) {
				return $return;
			}

			if( ( substr( $_SERVER['REQUEST_URI'], strlen( $this->base ) ) != '/users/login' ) ) {
				if( !$this->Session->check( 'Auth' ) || !$this->Session->check( 'Auth.User' ) ) {
					//le forcer a se connecter
					$this->redirect("/users/login");
				}
				else {

					$this->_loadPermissions();
					$this->_loadZonesgeographiques();

					$this->_updateConnection();

					$this->_loadGroup();
					$this->_loadServiceInstructeur();

					// Vérifications de l'état complet de certaines données dans la base
					$this->_checkHabilitations();

					$this->_checkPermissions();
				}
			}

			return $return;
		}

		/**
		* INFO:
		*   cake/libs/error.php
		*   cake/libs/view/errors/
		*/

		function assert( $condition, $error = 'error500', $parameters = array() ) {
			if( $condition !== true ) {
				$calledFrom = debug_backtrace();
				$calledFromFile = substr( str_replace( ROOT, '', $calledFrom[0]['file'] ), 1 );
				$calledFromLine = $calledFrom[0]['line'];

				$this->log( 'Assertion failed: '.$error.' in '.$calledFromFile.' line '.$calledFromLine.' for url '.$this->here );

				// Need to finish transaction ?
				if( isset( $this->{$this->modelClass} ) ) {
					$db = $this->{$this->modelClass}->getDataSource();
					if( $db->_transactionStarted ) {
						$db->rollback( $this->{$this->modelClass} );
					}
				}

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

			// made in gaëtan -> pour les tests unitaires
			$options['limit'] = (empty($options['limit']) || !is_numeric($options['limit'])) ? 1 : $options['limit'];
			extract($options);
			// fin made in gaëtan


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
	}
?>
