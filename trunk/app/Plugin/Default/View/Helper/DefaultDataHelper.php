<?php
	/**
	 * Code source de la classe DefaultDataHelper.
	 *
	 * PHP 5.4
	 *
	 * @package Default
	 * @subpackage View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'DefaultUtility', 'Default.Utility' );

	/**
	 * La classe DefaultDataHelper ...
	 *
	 * @package Default
	 * @subpackage View.Helper
	 */
	class DefaultDataHelper extends AppHelper
	{
		/**
		 * La liste des types de champs, au sens CakePHP, par nom de modèle.
		 *
		 * @var array
		 */
		protected $_cache = array();

		protected $_cacheChanged = false;

		protected $_cacheConfig = 'default'; // FIXME: pas d'erreur et pas utilisé si pas défini (ex: 'fast') ?

		/**
		 * Retourne le com de la clé de cache qui sera utilisée par ce helper.
		 *
		 * @return string
		 */
		public function cacheKey() {
			return implode(
				'_',
				Hash::filter(
					array(
						Inflector::camelize( __class__ ),
						Inflector::camelize( $this->request->params['plugin'] ),
						Inflector::camelize( $this->request->params['controller'] ),
						$this->request->params['action'],
					)
				)
			);
		}

		/**
		 * Lecture du cache.
		 *
		 * @param string $viewFile The view file that is going to be rendered
		 * @return void
		 */
		public function beforeRender( $viewFile ) {
			parent::beforeRender( $viewFile );
			$cacheKey = $this->cacheKey();
			$cache = Cache::read( $cacheKey, $this->_cacheConfig );

			if( $cache !== false ) {
				$this->_cache = $cache;
			}
		}

		/**
		 * Sauvegarde du cache.
		 *
		 * @param string $layoutFile The layout file that was rendered.
		 * @return void
		 */
		public function afterLayout( $layoutFile ) {
			parent::afterLayout( $layoutFile );

			if( $this->_cacheChanged ) {
				$cacheKey = $this->cacheKey();
				$cache = Cache::write( $cacheKey, $this->_cache, $this->_cacheConfig );
			}
		}

		/**
		 * Retourne le type d'un champ (au sens CakePHP).
		 *
		 * @param string $modelField
		 */
		public function type( $modelField ) {
			list( $modelName, $fieldName ) = model_field( $modelField );

			if( !isset( $this->_cache[$modelName] ) ) {
				try {
					$Model = ClassRegistry::init( $modelName );
					$schema = $Model->schema();
					$schema = array_combine( array_keys( $schema ), Hash::extract( $schema, '{s}.type' ) );
					$this->_cache[$modelName] = $schema;
					$this->_cacheChanged = true;
				} catch( Exception $e ) {
					$this->_cache[$modelName] = array();
				}
			}

			if( isset( $this->_cache[$modelName][$fieldName] ) ) {
				return $this->_cache[$modelName][$fieldName];
			}

			return null;
		}

		/**
		 *
		 * @param type $value
		 * @param type $type
		 * @return type
		 */
		public function format( $value, $type ) {
			$return = null;

			if( !is_null( $value ) ) {
				switch( $type ) {
					// TODO: traduction
					case 'boolean':
						$return = ( empty( $value ) ? 'Non' : 'Oui' );
						break;
					// TODO: _formatDatetime( $value )
					case 'date':
						$return = strftime( '%d/%m/%Y', strtotime( $value ) );
						break;
					case 'datetime':
						$return = strftime( '%d/%m/%Y à %H:%M:%S', strtotime( $value ) );
						break;
					case 'integer':
						$return = number_format( $value );
						break;
					case 'text':
					default:
						$return = $value;
				}
			}

			return $return;
		}

		/**
		 *
		 * @param type $value
		 * @param type $type
		 * @return type
		 */
		public function attributes( $value, $type ) {
			$attributes = array( 'class' => "data {$type}" );

			// TODO: le faire dans une sous-classe
			if( $value === null ) {
				$this->addClass( $attributes, 'null' );
			}
			else {
				$class = null;

				switch( $type ) {
					case 'boolean':
						$class = ( empty( $value ) ? 'false' : 'true' );
						break;
					case 'integer':
					case 'numeric':
						$class = null;
						if( $value === 0 ) {
							$class = 'zero';
						}
						else if( $value > 0 ) {
							$class = 'positive';
						}
						else if( $value < 0 ) {
							$class = 'negative';
						}
						break;
//					case 'datetime':
//					case 'text':
//					default:
				}

				$attributes = $this->addClass( $attributes, $class );
			}

			return $attributes;
		}
	}
?>