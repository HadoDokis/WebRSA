<?php
	App::import( 'Core', 'HttpSocket' );

	ini_set( 'session.gc_maxlifetime', readTimeout() );
	class AppController extends Controller
	{

		public $components = array( 'Session', 'Auth', 'Acl', 'Jetons', 'Default', 'Gedooo.Gedooo' );
		public $helpers = array( 'Xhtml', 'Form', 'Javascript', 'Permissions', 'Locale', 'Default', 'Xpaginator', 'Gestionanomaliebdd' );
		public $uses = array( 'User', 'Connection' );

		/**
		 * Permet de rajouter des conditions aux conditions de recherches suivant
		 * le paramétrage des service référent dont dépend l'utilisateur connecté.
		 *
		 * Nécessite la mise à true du paramètre 'Recherche.qdFilters.Serviceinstructeur'
		 * ainsi que l'ajout de conditions au service instructeur de l'utilisateur
		 * connecté.
		 *
		 * @param array $querydata Les querydata dans lesquelles rajouter les conditionss
		 * @return array
		 * @access protected
		 */
		protected function _qdAddFilters( $querydata ) {
			if( Configure::read( 'Recherche.qdFilters.Serviceinstructeur' ) ) {
				// Injection de conditions pour la confidentialité au CG 58
				$sqrecherche = $this->Session->read( 'Auth.Serviceinstructeur.sqrecherche' );
				if( !empty( $sqrecherche ) ) {
					$querydata['conditions'][] = $sqrecherche;
				}
			}

			return $querydata;
		}

		/**
		 * @access protected
		 */
		protected function _checkHabilitations() {
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
		 * Utilisateurs concurrents, mise à jour du dernier accès pour la connection.
		 * Si la session a expiré, on redirige sur UsersController::logout
		 */
		protected function _updateConnection() {
			if( Configure::read( 'Utilisateurs.multilogin' ) == false ) {
				if( !( $this->name == 'Users' && in_array( $this->action, array( 'login', 'logout' ) ) ) ) {
					$connection_id = $this->Connection->field(
							'id', array(
						'user_id' => $this->Session->read( 'Auth.User.id' ),
						'php_sid' => $this->Session->id(),
						'( Connection.modified + INTERVAL \''.readTimeout().' seconds\' ) >= NOW()'
							)
					);

					if( !empty( $connection_id ) ) {
						$this->Connection->create( array( 'id' => $connection_id ) );
						if( $this->Connection->exists() ) {
							$this->Connection->saveField( 'modified', null );
						}
					}
					else {
						$this->redirect( array( 'controller' => 'users', 'action' => 'logout' ) );
					}
				}
			}
		}

		/**
		 * Vérifie que l'utilisateur a la permission d'accéder à la page
		 */
		protected function _checkPermissions() {
			// Vérification des droits d'accès à la page
			if( $this->name != 'Pages' && !( $this->name == 'Users' && ( $this->action == 'login' || $this->action == 'logout' ) ) ) {
				if( !( isset( $this->aucunDroit ) && is_array( $this->aucunDroit ) && in_array( $this->action, $this->aucunDroit ) ) ) {
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
				}
			}
		}

		/**
		 *
		 */
		public function beforeFilter() {
			/*
			  Désactivation du cache du navigateur: (quand on revient en arrière
			  dans l'historique de navigation, la page n'est pas cachée du côté du
			  navigateur, donc il ré-exécute la demande)
			 */
			$this->disableCache(); // Disable browser cache ?


			//Paramétrage du composant Auth
			if( CAKE_BRANCH == '1.2' ) {
				$this->Auth->autoRedirect = false;
			}
			else {
				$this->Auth->loginAction = array( 'controller' => 'users', 'action' => 'login' );
				$this->Auth->logoutRedirect = array( 'controller' => 'users', 'action' => 'login' );
				$this->Auth->loginRedirect = array( 'controller' => 'dossiers', 'action' => 'index' );

				$this->Auth->authorize = array(
					'Actions' => array( 'actionPath' => 'controllers' )
				);
			}

			$this->set( 'etatdosrsa', ClassRegistry::init( 'Option' )->etatdosrsa() );
			$return = parent::beforeFilter();

			// Fin du traitement pour les requestactions et les appels ajax
			if( isset( $this->params['requested'] ) ) {
				return $return;
			}

			if( ( substr( $_SERVER['REQUEST_URI'], strlen( $this->base ) ) != '/users/login' ) ) {
				if( !$this->Session->check( 'Auth' ) || !$this->Session->check( 'Auth.User' ) ) {
					//le forcer a se connecter
					$this->redirect( "/users/login" );
				}
				else {
					$this->_updateConnection();

					if( !isset( $this->params['isAjax'] ) ) {
						$this->_checkHabilitations();
						$this->_checkPermissions();
					}
				}
			}
			return $return;
		}

		/**
		 * INFO:
		 *   cake/libs/error.php
		 *   cake/libs/view/errors/
		 */
		public function assert( $condition, $error = 'error500', $parameters = array( ) ) {
			if( $condition !== true ) {
				$calledFrom = debug_backtrace();
				$calledFromFile = substr( str_replace( ROOT, '', $calledFrom[0]['file'] ), 1 );
				$calledFromLine = $calledFrom[0]['line'];

				$this->log( 'Assertion failed: '.$error.' in '.$calledFromFile.' line '.$calledFromLine.' for url '.$this->here );

				// Need to finish transaction ?
				if( isset( $this->{$this->modelClass} ) ) {
					$db = $this->{$this->modelClass}->getDataSource();
					if( CAKE_BRANCH != '1.2' || $db->_transactionStarted ) {
						$db->rollback( $this->{$this->modelClass} );
					}
				}

				$this->cakeError(
						$error, array_merge(
								array(
							'className' => Inflector::camelize( $this->params['controller'] ),
							'action' => $this->action,
							'url' => $this->params['url']['url'], // ? FIXME
							'file' => $calledFromFile,
							'line' => $calledFromLine
								), $parameters
						)
				);

				exit();
			}
		}

		/**
		 * Permet de savoir si la pagination progressive est définie dans le webrsa.inc:
		 * 	- pour ce contrôleur et cette action
		 * 	-  pour ce contrôleur
		 * 	- pour l'ensemble des contrôleurs
		 *
		 * @return boolean
		 */
		protected function _hasProgressivePagination() {
			// Pagination progressive pour ce contrôleur et cette action ?
			$progressivePaginate = Configure::read( "Optimisations.{$this->name}_{$this->action}.progressivePaginate" );

			// Pagination progressive pour ce contrôleur ?
			if( is_null( $progressivePaginate ) ) {
				$progressivePaginate = Configure::read( "Optimisations.{$this->name}.progressivePaginate" );
			}

			// Pagination progressive en général ?
			if( is_null( $progressivePaginate ) ) {
				$progressivePaginate = Configure::read( 'Optimisations.progressivePaginate' );
			}

			return $progressivePaginate;
		}

		/**
		 * Fait-on une pagination standard ou une pagination progressive ?
		 * @see Configure::write( 'Optimisations.progressivePaginate', true )
		 */
		public function paginate( $object = null, $scope = array( ), $whitelist = array( ), $progressivePaginate = null ) {
			if( is_null( $progressivePaginate ) ) {
				$progressivePaginate = $this->_hasProgressivePagination();
				/* // Pagination progressive pour ce contrôleur et cette action ?
				  $progressivePaginate = Configure::read( "Optimisations.{$this->name}_{$this->action}.progressivePaginate" );

				  // Pagination progressive pour ce contrôleur ?
				  if( is_null( $progressivePaginate ) ) {
				  $progressivePaginate = Configure::read( "Optimisations.{$this->name}.progressivePaginate" );
				  }

				  // Pagination progressive en général ?
				  if( is_null( $progressivePaginate ) ) {
				  $progressivePaginate = Configure::read( 'Optimisations.progressivePaginate' );
				  } */
			}

			if( $progressivePaginate ) {
				return $this->_progressivePaginate( $object, $scope, $whitelist );
			}
			else {
				return $this->_paginate( $object, $scope, $whitelist );
			}
		}

		/**
		 * Pagination progressive basée sur la pagination normale CakePHP avec tri possible sur les champs virtuels
		 */
		protected function _progressivePaginate( $object = null, $scope = array( ), $whitelist = array( ) ) {
			if( is_array( $object ) ) {
				$whitelist = $scope;
				$scope = $object;
				$object = null;
			}
			$assoc = null;

			if( is_string( $object ) ) {
				$assoc = null;

				if( strpos( $object, '.' ) !== false ) {
					list($object, $assoc) = explode( '.', $object );
				}

				if( $assoc && isset( $this->{$object}->{$assoc} ) ) {
					$object = $this->{$object}->{$assoc};
				}
				elseif( $assoc && isset( $this->{$this->modelClass} ) && isset( $this->{$this->modelClass}->{$assoc} ) ) {
					$object = $this->{$this->modelClass}->{$assoc};
				}
				elseif( isset( $this->{$object} ) ) {
					$object = $this->{$object};
				}
				elseif( isset( $this->{$this->modelClass} ) && isset( $this->{$this->modelClass}->{$object} ) ) {
					$object = $this->{$this->modelClass}->{$object};
				}
			}
			elseif( empty( $object ) || $object === null ) {
				if( isset( $this->{$this->modelClass} ) ) {
					$object = $this->{$this->modelClass};
				}
				else {
					$className = null;
					$name = $this->uses[0];
					if( strpos( $this->uses[0], '.' ) !== false ) {
						list($name, $className) = explode( '.', $this->uses[0] );
					}
					if( $className ) {
						$object = $this->{$className};
					}
					else {
						$object = $this->{$name};
					}
				}
			}

			if( !is_object( $object ) ) {
				trigger_error( sprintf( __( 'Controller::paginate() - can\'t find model %1$s in controller %2$sController', true ), $object, $this->name ), E_USER_WARNING );
				return array( );
			}
			$options = array_merge( $this->params, $this->params['url'], $this->passedArgs );

			if( isset( $this->paginate[$object->alias] ) ) {
				$defaults = $this->paginate[$object->alias];
			}
			else {
				$defaults = $this->paginate;
			}

			if( isset( $options['show'] ) ) {
				$options['limit'] = $options['show'];
			}

			if( isset( $options['sort'] ) ) {
				$direction = null;
				if( isset( $options['direction'] ) ) {
					$direction = strtolower( $options['direction'] );
				}
				if( $direction != 'asc' && $direction != 'desc' ) {
					$direction = 'asc';
				}
				$options['order'] = array( $options['sort'] => $direction );
			}

			if( !empty( $options['order'] ) && is_array( $options['order'] ) ) {
				$alias = $object->alias;
				$key = $field = key( $options['order'] );

				if( strpos( $key, '.' ) !== false ) {
					list($alias, $field) = explode( '.', $key );
				}
				$value = $options['order'][$key];
				unset( $options['order'][$key] );

				if( isset( $object->{$alias} ) && $object->{$alias}->hasField( $field ) ) {
					$options['order'][$alias.'.'.$field] = $value;
				}
				elseif( $object->hasField( $field ) ) {
					$options['order'][$alias.'.'.$field] = $value;
				}
				else {
					// INFO: permet de trier sur d'autres champs que ceux du modèle que l'on pagine
					$joinAliases = Set::extract( $defaults, '/joins/alias' );
					if( in_array( $alias, $joinAliases ) ) {
						$options['order'][$alias.'.'.$field] = $value;
					}
				}
			}

			$vars = array( 'fields', 'order', 'limit', 'page', 'recursive' );
			$keys = array_keys( $options );
			$count = count( $keys );

			for( $i = 0; $i < $count; $i++ ) {
				if( !in_array( $keys[$i], $vars, true ) ) {
					unset( $options[$keys[$i]] );
				}
				if( empty( $whitelist ) && ($keys[$i] === 'fields' || $keys[$i] === 'recursive') ) {
					unset( $options[$keys[$i]] );
				}
				elseif( !empty( $whitelist ) && !in_array( $keys[$i], $whitelist ) ) {
					unset( $options[$keys[$i]] );
				}
			}
			$conditions = $fields = $order = $limit = $page = $recursive = null;

			if( !isset( $defaults['conditions'] ) ) {
				$defaults['conditions'] = array( );
			}

			$type = 'all';

			if( isset( $defaults[0] ) ) {
				$type = $defaults[0];
				unset( $defaults[0] );
			}

			$options = array_merge( array( 'page' => 1, 'limit' => 20 ), $defaults, $options );
			$options['limit'] = (int) $options['limit'];
			if( empty( $options['limit'] ) || $options['limit'] < 1 ) {
				$options['limit'] = 1;
			}

			extract( $options );

			if( is_array( $scope ) && !empty( $scope ) ) {
				$conditions = array_merge( $conditions, $scope );
			}
			elseif( is_string( $scope ) ) {
				$conditions = array( $conditions, $scope );
			}
			if( $recursive === null ) {
				$recursive = $object->recursive;
			}

			$extra = array_diff_key( $defaults, compact(
							'conditions', /* 'fields', */ 'order', 'limit', 'page', 'recursive'
					) );

			if( $type !== 'all' ) {
				$extra['type'] = $type;
			}

			$parameters = compact( 'conditions' );
			if( $recursive != $object->recursive ) {
				$parameters['recursive'] = $recursive;
			}

			$parameters['order'] = $order;
			$parameters['limit'] = ( $limit + 1 );
			$parameters['offset'] = ( max( 0, $page - 1 ) * $limit );
			$results = $object->find( $type, array_merge( $parameters, $extra ) );

			$count = count( $results ) + ( ( $page - 1 ) * $limit );
			$pageCount = intval( ceil( $count / $limit ) );

			if( $page === 'last' || $page >= $pageCount ) {
				$options['page'] = $page = $pageCount;
			}
			elseif( intval( $page ) < 1 ) {
				$options['page'] = $page = 1;
			}
			$page = $options['page'] = (integer) $page;

			$paging = array(
				'page' => $page,
				'current' => count( $results ),
				'count' => $count,
				'prevPage' => ($page > 1),
				'nextPage' => ($count > ($page * $limit)),
				'pageCount' => $pageCount,
				'defaults' => array_merge( array( 'limit' => 20, 'step' => 1 ), $defaults ),
				'options' => $options
			);

			$this->params['paging'][$object->alias] = $paging;

			if( !in_array( 'Paginator', $this->helpers ) && !array_key_exists( 'Paginator', $this->helpers ) ) {
				$this->helpers[] = 'Paginator';
			}
			return array_slice( $results, 0, $limit );
		}

		/**
		 * Pagination normale CakePHP avec tri possible sur les champs virtuels
		 */
		protected function _paginate( $object = null, $scope = array( ), $whitelist = array( ) ) {
			if( is_array( $object ) ) {
				$whitelist = $scope;
				$scope = $object;
				$object = null;
			}
			$assoc = null;

			if( is_string( $object ) ) {
				$assoc = null;

				if( strpos( $object, '.' ) !== false ) {
					list($object, $assoc) = explode( '.', $object );
				}

				if( $assoc && isset( $this->{$object}->{$assoc} ) ) {
					$object = $this->{$object}->{$assoc};
				}
				elseif( $assoc && isset( $this->{$this->modelClass} ) && isset( $this->{$this->modelClass}->{$assoc} ) ) {
					$object = $this->{$this->modelClass}->{$assoc};
				}
				elseif( isset( $this->{$object} ) ) {
					$object = $this->{$object};
				}
				elseif( isset( $this->{$this->modelClass} ) && isset( $this->{$this->modelClass}->{$object} ) ) {
					$object = $this->{$this->modelClass}->{$object};
				}
			}
			elseif( empty( $object ) || $object === null ) {
				if( isset( $this->{$this->modelClass} ) ) {
					$object = $this->{$this->modelClass};
				}
				else {
					$className = null;
					$name = $this->uses[0];
					if( strpos( $this->uses[0], '.' ) !== false ) {
						list($name, $className) = explode( '.', $this->uses[0] );
					}
					if( $className ) {
						$object = $this->{$className};
					}
					else {
						$object = $this->{$name};
					}
				}
			}

			if( !is_object( $object ) ) {
				trigger_error( sprintf( __( 'Controller::paginate() - can\'t find model %1$s in controller %2$sController', true ), $object, $this->name ), E_USER_WARNING );
				return array( );
			}
			$options = array_merge( $this->params, $this->params['url'], $this->passedArgs );

			if( isset( $this->paginate[$object->alias] ) ) {
				$defaults = $this->paginate[$object->alias];
			}
			else {
				$defaults = $this->paginate;
			}

			if( isset( $options['show'] ) ) {
				$options['limit'] = $options['show'];
			}

			if( isset( $options['sort'] ) ) {
				$direction = null;
				if( isset( $options['direction'] ) ) {
					$direction = strtolower( $options['direction'] );
				}
				if( $direction != 'asc' && $direction != 'desc' ) {
					$direction = 'asc';
				}
				$options['order'] = array( $options['sort'] => $direction );
			}

			if( !empty( $options['order'] ) && is_array( $options['order'] ) ) {
				$alias = $object->alias;
				$key = $field = key( $options['order'] );

				if( strpos( $key, '.' ) !== false ) {
					list($alias, $field) = explode( '.', $key );
				}
				$value = $options['order'][$key];
				unset( $options['order'][$key] );

				if( isset( $object->{$alias} ) && $object->{$alias}->hasField( $field ) ) {
					$options['order'][$alias.'.'.$field] = $value;
				}
				elseif( $object->hasField( $field ) ) {
					$options['order'][$alias.'.'.$field] = $value;
				}
				else {
					// INFO: permet de trier sur d'autres champs que ceux du modèle que l'on pagine
					$joinAliases = Set::extract( $defaults, '/joins/alias' );
					if( in_array( $alias, $joinAliases ) ) {
						$options['order'][$alias.'.'.$field] = $value;
					}
				}
			}

			$vars = array( 'fields', 'order', 'limit', 'page', 'recursive' );
			$keys = array_keys( $options );
			$count = count( $keys );

			for( $i = 0; $i < $count; $i++ ) {
				if( !in_array( $keys[$i], $vars, true ) ) {
					unset( $options[$keys[$i]] );
				}
				if( empty( $whitelist ) && ($keys[$i] === 'fields' || $keys[$i] === 'recursive') ) {
					unset( $options[$keys[$i]] );
				}
				elseif( !empty( $whitelist ) && !in_array( $keys[$i], $whitelist ) ) {
					unset( $options[$keys[$i]] );
				}
			}
			$conditions = $fields = $order = $limit = $page = $recursive = null;

			if( !isset( $defaults['conditions'] ) ) {
				$defaults['conditions'] = array( );
			}

			$type = 'all';

			if( isset( $defaults[0] ) ) {
				$type = $defaults[0];
				unset( $defaults[0] );
			}

			$options = array_merge( array( 'page' => 1, 'limit' => 20 ), $defaults, $options );
			$options['limit'] = (int) $options['limit'];
			if( empty( $options['limit'] ) || $options['limit'] < 1 ) {
				$options['limit'] = 1;
			}

			extract( $options );

			if( is_array( $scope ) && !empty( $scope ) ) {
				$conditions = array_merge( $conditions, $scope );
			}
			elseif( is_string( $scope ) ) {
				$conditions = array( $conditions, $scope );
			}
			if( $recursive === null ) {
				$recursive = $object->recursive;
			}

			$extra = array_diff_key( $defaults, compact(
							'conditions', 'fields', 'order', 'limit', 'page', 'recursive'
					) );

			if( $type !== 'all' ) {
				$extra['type'] = $type;
			}

			if( method_exists( $object, 'paginateCount' ) ) {
				$count = $object->paginateCount( $conditions, $recursive, $extra );
			}
			else {
				$parameters = compact( 'conditions' );
				if( $recursive != $object->recursive ) {
					$parameters['recursive'] = $recursive;
				}
				$count = $object->find( 'count', array_merge( $parameters, $extra ) );
			}
			$pageCount = intval( ceil( $count / $limit ) );

			if( $page === 'last' || $page >= $pageCount ) {
				$options['page'] = $page = $pageCount;
			}
			elseif( intval( $page ) < 1 ) {
				$options['page'] = $page = 1;
			}
			$page = $options['page'] = (integer) $page;

			if( method_exists( $object, 'paginate' ) ) {
				$results = $object->paginate( $conditions, $fields, $order, $limit, $page, $recursive, $extra );
			}
			else {
				$parameters = compact( 'conditions', 'fields', 'order', 'limit', 'page' );
				if( $recursive != $object->recursive ) {
					$parameters['recursive'] = $recursive;
				}
				$results = $object->find( $type, array_merge( $parameters, $extra ) );
			}
			$paging = array(
				'page' => $page,
				'current' => count( $results ),
				'count' => $count,
				'prevPage' => ($page > 1),
				'nextPage' => ($count > ($page * $limit)),
				'pageCount' => $pageCount,
				'defaults' => array_merge( array( 'limit' => 20, 'step' => 1 ), $defaults ),
				'options' => $options
			);

			$this->params['paging'][$object->alias] = $paging;

			if( !in_array( 'Paginator', $this->helpers ) && !array_key_exists( 'Paginator', $this->helpers ) ) {
				$this->helpers[] = 'Paginator';
			}
			return $results;
		}

		/**
		 *
		 */
		protected function _setFlashResult( $message, $result ) {
			$class = ( $result ? 'success' : 'error' );
			$this->Session->setFlash(
					__( "{$message}->{$class}", true ), 'default', array( 'class' => $class )
			);
		}
	}
?>