<?php
	/**
	 * Fichier source de la classe PrechargementsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 */
	App::uses( 'Folder', 'Utility' );
	App::uses( 'File', 'Utility' );

	/**
	 * La classe PrechargementsController se charge du préchargement du cache de
	 * l'application.
	 *
	 * @package app.Controller
	 */
	class PrechargementsController extends AppController
	{
		public $uses = array( 'Connection' );

		/**
		 *
		 */
		public function beforeFilter() {
			$this->Auth->allow( '*' );
		}

		/**
		 *
		 * @return array
		 */
		protected function _listTables() {
			$tables = $this->Connection->query( 'SELECT table_name as name FROM INFORMATION_SCHEMA.tables WHERE table_schema = \'public\' ORDER BY name ASC;' );
			return Hash::extract( $tables, '{n}.0.name' );
		}

		/**
		 *
		 */
		public function index() {
			// Modèles
			$initialized = array();
			$uninitialized = array();
			$nonprechargements = array();
			$prechargements = array();
			$missing = $this->_listTables();

			$modelNames = App::objects( 'model' );
			foreach( $modelNames as $modelName ) {
				App::import( 'Model', $modelName );

				$init = true;
				$attributes = get_class_vars( $modelName );

				if( $attributes['useDbConfig'] != 'default' ) {
					$init = false;
				}

				if( $init ) {
					$initialized[] = $modelName;
					$Model = ClassRegistry::init( $modelName );

					$result = $Model->prechargement();
					if( $result === false ) {
						$nonprechargements[] = $Model->alias;
					}
					else if( $result !== null ) {
						$prechargements[] = $Model->alias;
					}
				}
				else {
					$uninitialized[] = $modelName;
				}

				$key = array_search( Inflector::tableize( $modelName ), $missing );
				if( $key !== false ) {
					unset( $missing[$key] );
				}
			}

			$this->set( compact( 'initialized', 'uninitialized', 'missing', 'prechargements', 'nonprechargements' ) );

			// Domaines
			$domaines = array();

			$messagesDir = APP.'Locale/fre/LC_MESSAGES/';
			$Folder = new Folder( $messagesDir, false, 0777 );

			foreach( $Folder->find('.+\.po$') as $fileName ) {
				$domain = preg_replace( '/\.po$/', '', $fileName );
				if( $domain != 'default' ) {
					__d( $domain, 'Foo::bar' );
				}
				else {
					__( 'January' );
				}
				$domaines[] = $domain;
			}

			$this->set( compact( 'domaines' ) );
		}
	}
?>