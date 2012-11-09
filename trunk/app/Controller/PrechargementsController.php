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
			return Set::extract( $tables, '{n}.0.name' );
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
			$tables = $missing = $this->_listTables();

			$models = App::objects( 'model' );
			foreach( $models as $model ) {
				App::import( 'Model', $model );

				$init = true;
				$attributes = get_class_vars( $model );

				if( $attributes['useDbConfig'] != 'default' ) {
					$init = false;
				}

				if( $init ) {
					$initialized[] = $model;
					$modelClass = ClassRegistry::init( $model );
					$prechargement = $modelClass->prechargement();
					if( $prechargement === false ) {
						$nonprechargements[] = $modelClass->alias;
					}
					else if( $prechargement !== null ) {
						$prechargements[] = $modelClass->alias;
					}
				}
				else {
					$uninitialized[] = $model;
				}

				$key = array_search( Inflector::tableize( $model ), $missing );
				if( $key !== false ) {
					unset( $missing[$key] );
				}
			}

			$this->set( compact( 'initialized', 'uninitialized', 'missing', 'prechargements', 'nonprechargements' ) );

			$messagesDir = APP.'Locale/fre/LC_MESSAGES/';

			$folder = new Folder( false, false, 0777 );
			$folder->cd( $messagesDir );
			$files = $folder->find('.+\.po$');
			$domaines = array();

			foreach( $files as $file ) {
				$domain = preg_replace( '/\.po$/', '', $file );
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