<?php
	/**
	 * TODO: le parent_id des Groupes dans les Aros des groupes
	 */
	App::import( 'Core', 'Controller' );
	App::import( 'Component', 'Acl' );
	App::import( 'Component', 'Dbdroits' );
	App::import( 'Controller', 'App' );
	App::import( 'Controller', 'User' );
	App::import( 'Core', 'Router' );
	include CONFIGS.'routes.php';
	class PermissionsDeveloppementShell extends AppShell
	{

		public $defaultParams = array(
			'connection' => 'default',
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false,
			'limit' => null
		);
		public $verbose;
		public $limit;
		public $connection = null;

		/**
		 * Initialisation: lecture des paramètres, on s'assure d'avoir une connexion
		 * valide
		 */
		public function initialize() {
			parent::initialize();

			$this->Controller = & new Controller();
			$this->Controller->components = array( 'Acl', 'Dbdroits' );
			$this->Controller->uses = array( );
			$this->Controller->constructClasses();
			$this->Controller->Component->initialize( $this->Controller );

			$this->User = ClassRegistry::init( 'User' );
		}

		/**
		 * Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		 */
		public function _welcome() {
			$this->hr();
		}

		/**
		 *
		 */
		protected function _clean() {
			$this->out( 'Suppression des données de la table aros.' );
			$this->Controller->Acl->Aro->query( 'DELETE FROM aros;' );

			$this->out( 'Suppression des données de la table acos.' );
			$this->Controller->Acl->Aco->query( 'DELETE FROM acos;' );

			$this->out( 'Suppression des données de la table aros_acos.' );
			$this->Controller->Acl->Aro->Permission->query( 'DELETE FROM aros_acos;' );

			foreach( array( 'aros', 'acos', 'aros_acos' ) as $table ) {
				$sql = "SELECT pg_catalog.setval( '{$table}_id_seq', ( CASE WHEN ( SELECT max({$table}.id) FROM {$table} ) IS NOT NULL THEN ( SELECT max({$table}.id) + 1 FROM {$table} ) ELSE 1 END ), false);";
				$this->Controller->Acl->Aro->query( $sql );
			}
			return true;
		}

		/**
		 *
		 */
		protected function _addGroups() {
			$success = true;

			$groups = $this->User->Group->find( 'all', array( 'order' => 'Group.parent_id ASC' ) );
			foreach( $groups as $group ) {
				$parent_id = 0;
				if( !empty( $group['Group']['parent_id'] ) ) {
					$parent_id = $this->Controller->Acl->Aro->field( 'id', array( 'model' => 'Group', 'foreign_key' => $group['Group']['parent_id'] ) );
				}

				$this->Controller->Acl->Aro->create(
						array(
							'Aro' => array(
								'parent_id' => $parent_id,
								'model' => 'Group',
								'foreign_key' => $group['Group']['id'],
								'alias' => $group['Group']['name'],
							)
						)
				);
				if( $success = $this->Controller->Acl->Aro->save() && $success ) {
					$this->out( "Le groupe {$group['Group']['name']} a été ajouté aux Aros" );
				}
			}
		}

		/**
		 *
		 */
		protected function _addUsers() {
			$success = true;

			$users = $this->User->find(
					'all', array(
				'fields' => array(
					'User.id',
					'User.username',
					'User.group_id',
					'Group.id',
					'Group.name'
				),
				'recursive' => 0
					)
			);

			foreach( $users as $id => $user ) {
				$qd_userAro = array(
					'conditions' => array(
						'Aro.alias' => $user['User']['username']
					)
				);
				$userAro = $this->Controller->Acl->Aro->find( 'first', $qd_userAro );

				$qd_groupAro = array(
					'conditions' => array(
						'Aro.alias' => $user['Group']['name']
					)
				);
				$groupAro = $this->Controller->Acl->Aro->find( 'first', $qd_groupAro );

				if( !empty( $groupAro ) ) {
					if( empty( $userAro ) ) {
						$qd_user = array(
							'conditions' => array(
								'User.username' => $user['User']['username']
							)
						);
						$user = $this->User->find( 'first', $qd_user );
						
						$this->Controller->Acl->Aro->create(
								array(
									'Aro' => array(
										'parent_id' => $groupAro['Aro']['id'],
										'model' => 'Utilisateur',
										'foreign_key' => $user['User']['id'],
										'alias' => $user['User']['username'],
									)
								)
						);
						if( $tmp = $this->Controller->Acl->Aro->save() ) {
							$this->out( "L'utilisateur {$user['User']['username']} a été ajouté aux Aros" );
						}
						else {
							$this->err( "Impossible d'ajouter l'utilisateur {$user['User']['username']} aux Aros" );
						}
						$success = $tmp && $success;
					}
					else {
						$this->out( "L'utilisateur {$user['User']['username']} figurait déjà dans les Aros" );
					}
				}
				else {
					$this->out( "Le groupe {$user['Group']['name']} ne figure pas dans les Aros" );
				}
			}

			return $success;
		}

		// http://book.cakephp.org/view/647/An-Automated-tool-for-creating-ACOs
		function build_acl() {
			if( !Configure::read( 'debug' ) ) {
				return $this->_stop();
			}
			$log = array( );

			$aco = & $this->Controller->Acl->Aco;

			App::import( 'Core', 'File' );
			$Controllers = App::objects( 'controller' );
			$appIndex = array_search( 'App', $Controllers );
			if( $appIndex !== false ) {
				unset( $Controllers[$appIndex] );
			}
			$baseMethods = get_class_methods( 'Controller' );
			$baseMethods[] = 'buildAcl';

			// look at each controller in app/controllers
			foreach( $Controllers as $ctrlName ) {
				$methods = $this->_getClassMethods( $this->_getPluginControllerPath( $ctrlName ) );
				// find / make controller node
				$controllerNode = $aco->node( 'Module:'.$ctrlName );
				if( !$controllerNode ) {
					if( $this->_isPlugin( $ctrlName ) ) {

					}
					else {
						$aco->create(
								array(
									'parent_id' => 0,
									'model' => null,
									'alias' => 'Module:'.$ctrlName
								)
						);
						$controllerNode = $aco->save();
						$controllerNode['Aco']['id'] = $aco->id;
						$log[] = 'Created Aco node for '.$ctrlName;
					}
				}
				else {
					$controllerNode = $controllerNode[0];
				}

				//clean the methods. to remove those in Controller and private actions.
				foreach( $methods as $k => $method ) {
					if( strpos( $method, '_', 0 ) === 0 ) {
						unset( $methods[$k] );
						continue;
					}
					if( in_array( $method, $baseMethods ) ) {
						unset( $methods[$k] );
						continue;
					}
					$methodNode = $aco->node( $ctrlName.':'.$method );
					if( !$methodNode ) {
						$aco->create(
								array(
									'parent_id' => $controllerNode['Aco']['id'],
									'model' => null,
									'alias' => $ctrlName.':'.$method
								)
						);
						$methodNode = $aco->save();
						$log[] = 'Created Aco node for '.$method;
					}
				}
			}
			if( count( $log ) > 0 ) {
				debug( $log );
			}
		}

		protected function _getClassMethods( $ctrlName = null ) {
			App::import( 'Controller', $ctrlName );
			if( strlen( strstr( $ctrlName, '.' ) ) > 0 ) {
				// plugin's controller
				$num = strpos( $ctrlName, '.' );
				$ctrlName = substr( $ctrlName, $num + 1 );
			}
			$ctrlclass = $ctrlName.'Controller';
			$methods = get_class_methods( $ctrlclass );

			// Add scaffold defaults if scaffolds are being used
			$properties = get_class_vars( $ctrlclass );
			if( array_key_exists( 'scaffold', $properties ) ) {
				if( $properties['scaffold'] == 'admin' ) {
					$methods = array_merge( $methods, array( 'admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete' ) );
				}
				else {
					$methods = array_merge( $methods, array( 'add', 'edit', 'index', 'view', 'delete' ) );
				}
			}
			return $methods;
		}

		protected function _isPlugin( $ctrlName = null ) {
			$arr = String::tokenize( $ctrlName, '/' );
			if( count( $arr ) > 1 ) {
				return true;
			}
			else {
				return false;
			}
		}

		protected function _getPluginControllerPath( $ctrlName = null ) {
			$arr = String::tokenize( $ctrlName, '/' );
			if( count( $arr ) == 2 ) {
				return $arr[0].'.'.$arr[1];
			}
			else {
				return $arr[0];
			}
		}

		protected function _getPluginName( $ctrlName = null ) {
			$arr = String::tokenize( $ctrlName, '/' );
			if( count( $arr ) == 2 ) {
				return $arr[0];
			}
			else {
				return false;
			}
		}

		protected function _getPluginControllerName( $ctrlName = null ) {
			$arr = String::tokenize( $ctrlName, '/' );
			if( count( $arr ) == 2 ) {
				return $arr[1];
			}
			else {
				return false;
			}
		}

		/**
		 *
		 */
		protected function _addAcos() {
			$this->Controller->Dbdroits->majActions();
			return true;
		}

		/**
		 * Par défaut, on affiche l'aide
		 */
		public function main() {
			$success = true;
			$success = $this->_clean();
			$success = $this->_addGroups() && $success;
			$success = $this->_addUsers() && $success;
			$success = $this->_addAcos() && $success;

			$acos = $this->Controller->Acl->Aco->find(
					'list', array(
				'fields' => array( 'id', 'alias' ),
				'conditions' => array( 'Aco.alias LIKE' => 'Module:%' )
					)
			);

			foreach( $acos as $id => $moduleAlias ) {
				$this->Controller->Acl->allow( 'Administrateurs', $moduleAlias );
			}

			// Soyons certains de donner l'accès à la page d'accueil
			$slash = Router::parse( '/' );
			$slashAlias = Inflector::camelize( $slash['controller'] ).':'.$slash['action'];
			$this->Controller->Acl->allow( 'Administrateurs', $slashAlias );

			$this->_stop( !$success );
		}

		/**
		 * Aide
		 */
		public function help() {
			$this->log = false;
			$this->out( "Usage: cake/console/cake {$this->script} <commande> <paramètres>" );
			$this->_stop( 0 );
		}

	}
?>
