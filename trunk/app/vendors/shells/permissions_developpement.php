<?php
	/**
	* TODO: le parent_id des Groupes dans les Aros des groupes
	*/

	App::import( 'Core', 'Controller' );
	App::import( 'Component', 'Acl' );
	App::import( 'Component', 'Dbdroits' );
	App::import( 'Controller', 'App' );
	App::import( 'Controller', 'User' );

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

			$this->Controller =& new Controller();
			$this->Controller->components = array( 'Acl', 'Dbdroits' );
			$this->Controller->uses = array();
			$this->Controller->constructClasses();
			$this->Controller->Component->initialize( $this->Controller );

			$this->User = ClassRegistry::init( 'User' );
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
			/*$this->out();
			$this->out( 'Détection des anomalies sur une BDD webrsa' );
			$this->out();
			$this->hr();
			$this->out();
			$this->out( 'Connexion : '. $this->connection->configKeyName );
			$this->out( 'Base de donnees : '. $this->connection->config['database'] );
			$this->out( 'Limite : '. $this->_valueToString( $this->limit ) );
			$this->out( 'Journalisation : '. $this->_valueToString( $this->log ) );
			$this->out( 'Fichiers de rapport : '. $this->_valueToString( $this->verbose ) );
			$this->out();*/
			$this->hr();
		}

		/**
		*
		*/

		protected function _clean() {
			$success = $this->Controller->Acl->Aro->deleteAll( array( '1 = 1' ) )
				&& $this->Controller->Acl->Aco->deleteAll( array( '1 = 1' ) )
				&& $this->Controller->Acl->Aro->Permission->deleteAll( array( '1 = 1' ) );


			foreach( array( 'aros', 'acos', 'aros_acos' ) as $table ) {
				$sql = "SELECT pg_catalog.setval( '{$table}_id_seq', ( CASE WHEN ( SELECT max({$table}.id) FROM {$table} ) IS NOT NULL THEN ( SELECT max({$table}.id) + 1 FROM {$table} ) ELSE 1 END ), false);";
				$success = $this->Controller->Acl->Aro->query( $sql ) && $success;
			}

			return $success;
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
				'all',
				array(
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
				$userAro = $this->Controller->Acl->Aro->findByAlias( $user['User']['username'] );
				$groupAro = $this->Controller->Acl->Aro->findByAlias( $user['Group']['name'] );

				if( !empty( $groupAro ) ) {
					if( empty( $userAro ) ) {
						$user = $this->User->findByUsername( $user['User']['username'] );
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
			if (!Configure::read('debug')) {
				return $this->_stop();
			}
			$log = array();

			$aco =& $this->Controller->Acl->Aco;

			App::import('Core', 'File');
			$Controllers = Configure::listObjects('controller');
			$appIndex = array_search('App', $Controllers);
			if ($appIndex !== false ) {
				unset($Controllers[$appIndex]);
			}
			$baseMethods = get_class_methods('Controller');
			$baseMethods[] = 'buildAcl';

			//$Plugins = $this->_getPluginControllerNames();
			//$Controllers = array_merge($Controllers, $Plugins);

			// look at each controller in app/controllers
			foreach ($Controllers as $ctrlName) {
				$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

				// Do all Plugins First
				/*if ($this->_isPlugin( $ctrlName ) ){
					$pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
					if (!$pluginNode) {
						$aco->create(
							array(
								'parent_id' => ( !empty( $root['Aco']['id'] ) ? $root['Aco']['id'] : 0 ),
								'model' => null,
								'alias' => $this->_getPluginName( $ctrlName )
							)
						);
						$pluginNode = $aco->save();
						$pluginNode['Aco']['id'] = $aco->id;
						$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
					}
				}*/

				// find / make controller node
				$controllerNode = $aco->node('Module:'.$ctrlName);
				if (!$controllerNode) {
					if ($this->_isPlugin($ctrlName)){
// 						$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
// 						$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
// 						$controllerNode = $aco->save();
// 						$controllerNode['Aco']['id'] = $aco->id;
// 						$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
					} else {
						$aco->create(
							array(
								'parent_id' => 0,
								'model' => null,
								'alias' => 'Module:'.$ctrlName
							)
						);
						$controllerNode = $aco->save();
						$controllerNode['Aco']['id'] = $aco->id;
						$log[] = 'Created Aco node for ' . $ctrlName;
					}
				} else {
					$controllerNode = $controllerNode[0];
				}

				//clean the methods. to remove those in Controller and private actions.
				foreach ($methods as $k => $method) {
					if (strpos($method, '_', 0) === 0) {
						unset($methods[$k]);
						continue;
					}
					if (in_array($method, $baseMethods)) {
						unset($methods[$k]);
						continue;
					}
					//$methodNode = $aco->node( 'controllers/'.$ctrlName.'/'.$method );
					$methodNode = $aco->node( $ctrlName.':'.$method );
					if (!$methodNode) {
						$aco->create(
							array(
								'parent_id' => $controllerNode['Aco']['id'],
								'model' => null,
								'alias' => $ctrlName.':'.$method
							)
						);
						$methodNode = $aco->save();
						$log[] = 'Created Aco node for '. $method;
					}
				}
			}
			if(count($log)>0) {
				debug($log);
			}
		}

		function _getClassMethods($ctrlName = null) {
			App::import('Controller', $ctrlName);
			if (strlen(strstr($ctrlName, '.')) > 0) {
				// plugin's controller
				$num = strpos($ctrlName, '.');
				$ctrlName = substr($ctrlName, $num+1);
			}
			$ctrlclass = $ctrlName . 'Controller';
			$methods = get_class_methods($ctrlclass);

			// Add scaffold defaults if scaffolds are being used
			$properties = get_class_vars($ctrlclass);
			if (array_key_exists('scaffold',$properties)) {
				if($properties['scaffold'] == 'admin') {
					$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
				} else {
					$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
				}
			}
			return $methods;
		}

		function _isPlugin($ctrlName = null) {
			$arr = String::tokenize($ctrlName, '/');
			if (count($arr) > 1) {
				return true;
			} else {
				return false;
			}
		}

		function _getPluginControllerPath($ctrlName = null) {
			$arr = String::tokenize($ctrlName, '/');
			if (count($arr) == 2) {
				return $arr[0] . '.' . $arr[1];
			} else {
				return $arr[0];
			}
		}

		function _getPluginName($ctrlName = null) {
			$arr = String::tokenize($ctrlName, '/');
			if (count($arr) == 2) {
				return $arr[0];
			} else {
				return false;
			}
		}

		function _getPluginControllerName($ctrlName = null) {
			$arr = String::tokenize($ctrlName, '/');
			if (count($arr) == 2) {
				return $arr[1];
			} else {
				return false;
			}
		}

	/**
	* Get the names of the plugin controllers ...
	*
	* This function will get an array of the plugin controller names, and
	* also makes sure the controllers are available for us to get the
	* method names by doing an App::import for each plugin controller.
	*
	* @return array of plugin names.
	*
	*/
		/*function _getPluginControllerNames() {
			App::import('Core', 'File', 'Folder');
			$paths = Configure::getInstance();
			$folder =& new Folder();
			$folder->cd(APP . 'plugins');

			// Get the list of plugins
			$Plugins = $folder->read();
			$Plugins = $Plugins[0];
			$arr = array();

			// Loop through the plugins
			foreach($Plugins as $pluginName) {
				// Change directory to the plugin
				$didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
				// Get a list of the files that have a file name that ends
				// with controller.php
				$files = $folder->findRecursive('.*_controller\.php');

				// Loop through the controllers we found in the plugins directory
				foreach($files as $fileName) {
					// Get the base file name
					$file = basename($fileName);

					// Get the controller name
					$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
					if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
						if (!App::import('Controller', $pluginName.'.'.$file)) {
							debug('Error importing '.$file.' for plugin '.$pluginName);
						} else {
							/// Now prepend the Plugin name ...
							// This is required to allow us to fetch the method names.
							$arr[] = Inflector::humanize($pluginName) . "/" . $file;
						}
					}
				}
			}
			return $arr;
		}*/

		/**
		*
		*/

		protected function _addAcos() {
			$this->Controller->Dbdroits->majActions();
			return true;
			//return $this->build_acl();
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
				'list',
				array(
					'fields' => array( 'id', 'alias' ),
					'conditions' => array( 'Aco.alias LIKE' => 'Module:%' )
				)
			);
			foreach( $acos as $id => $moduleAlias ) {
				$this->Controller->Acl->allow( 'Administrateurs', $moduleAlias );
			}

			$this->_stop( !$success );
		}

		/**
		* Aide
		*/

		public function help() {
			$this->log = false;

			$this->out("Usage: cake/console/cake {$this->script} <commande> <paramètres>");
			/*$this->hr();
			$this->out();
			$this->out('Commandes:');
			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-connection <connexion>\n\t\tLe nom d'une connexion PostgreSQL défini dans app/config/database.php\n\t\tPar défaut: ".$this->_defaultToString( 'connection' )."\n");
			$this->out("\t-limit <entier>\n\t\t...\n\t\tPar défaut: ".$this->_defaultToString( 'limit' )."\n");
			$this->out("\t-log <booléen>\n\t\tDoit-on journaliser la sortie du programme ?\n\t\tPar défaut: ".$this->_defaultToString( 'log' )."\n");
			$this->out("\t-logpath <répertoire>\n\t\tLe répertoire dans lequel enregistrer les fichiers de journalisation.\n\t\tPar défaut: ".$this->_defaultToString( 'logpath' )."\n");
			$this->out("\t-verbose <booléen>\n\t\t...\n\t\tPar défaut: ".$this->_defaultToString( 'verbose' )."\n");
			$this->out();*/

			$this->_stop( 0 );
		}
    }
?>
