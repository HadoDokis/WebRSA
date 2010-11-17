<?php
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
		* Returns a list of all application models (including plugins)
		*
		* @return array List of models
		* @access public
		* @url http://variable3.com/blog/2010/05/list-all-the-models-and-plugins-of-a-cakephp-application/
		*/

		protected function _getModels() {
			$models = Configure::listObjects( 'model' );
			/*$plugins = Configure::listObjects('plugin');
			if (!empty($plugins)) {
				foreach ($plugins as $plugin) {
					$pPath = APP . 'plugins' . DS . Inflector::underscore($plugin) . DS . 'models' . DS;
					$pluginModels = Configure::listObjects('model', $pPath, false);
					if (!empty($pluginModels)) {
						foreach ($pluginModels as $model) {
							$models[] = "$plugin.$model";
						}
					}
				}
			}*/
			return $models;
		}

		/**
		*
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
			$tables = $missing = $this->_listTables();

			$models = $this->_getModels();
			foreach( $models as $model ) {
				App::import( 'Model', $model );

				$init = true;
				$attributes = get_class_vars( $model );
				if( $attributes['useTable'] === false ) {
					$init = false;
				}
// 				else if( !is_null( $attributes['useTable'] ) ) {
// 					$init = false;
// 				}
				else if( $attributes['useDbConfig'] != 'default' ) {
					$init = false;
				}

				if( $init ) {
					$initialized[] = $model;
					ClassRegistry::init( $model );
				}
				else {
					$uninitialized[] = $model;
				}

				$key = array_search( Inflector::tableize( $model ), $missing );
				if( $key !== false ) {
					unset( $missing[$key] );
				}
			}

			$this->set( compact( 'initialized', 'uninitialized', 'missing' ) );

			// Traductions
			App::import( 'Core', 'Folder' );
			$folder = new Folder();
			$folder->cd( APP.'locale/fre/LC_MESSAGES/' );
			$files = $folder->find('.+\.po$');
			$domaines = array();

			foreach( $files as $file ) {
				$domain = preg_replace( '/\.po$/', '', $file );
				if( $domain != 'default' ) {
					__d( $domain, 'Foo::bar', true );
				}
				else {
					__( 'January', true );
				}
				$domaines[] = $domain;
			}

			$this->set( compact( 'domaines' ) );
		}
	}
?>