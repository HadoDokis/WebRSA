<?php
	/**
	* TODO: dans une classe (AppHelper ?), un meilleur nom ?
	*/

	function dataTranslate( $data, $string ) {
		if( preg_match_all( '/#(?<!\w)(\w+)(\.|\.[0-9]+\.)(\w+)#/', $string, $matches, PREG_SET_ORDER ) ) {
			$matches = Set::extract( $matches, '{n}.0' );

			foreach( $matches as $match ) {
				$modelField = str_replace( '#', '', $match );
				if( Set::check( $data, $modelField ) ) {
					$value = Set::classicExtract( $data, $modelField );
					$value = ( is_bool( $value ) ? ( $value ? 1 : 0 ) : $value );
					$string = str_replace( $match, $value, $string );
				}
			}
		}

		return $string;
	}

	/**
	* Fournit des fonctions d'accès à un cache d'informations concernant les modèles,
	* ainsi que des fonctions de manipulation de données liées aux modèles.
	*
	* Cette classe est est abstraite car uniquement destinée à être sous-classée
	* (ou pas obligatoirement ?).
	*/

	abstract class ModelHelper extends AppHelper
	{
		/**
		* Cache for model informations
		*/

		protected $_modelInfos = array();

		/**
		*
		*/

		protected function _modelInfos( $modelName ) {
			if( !isset( $this->_modelInfos[$modelName] ) ) {
				$cacheKey = $this->_cacheKey( $modelName );

				$this->_modelInfos[$modelName] = Cache::read( $cacheKey );

				if( empty( $this->_modelInfos[$modelName] ) ) {
					$model = ClassRegistry::init( $modelName );

					$this->_modelInfos[$modelName] = array(
						'primaryKey' => $model->primaryKey,
						'displayField' => $model->displayField,
						'schema' => $model->schema(),
					);

					// MySQL enum ?
					foreach( $this->_modelInfos[$modelName]['schema'] as $field => $infos ) {
						if( strstr( $infos['type'], 'enum(' ) ) {
							$this->_modelInfos[$modelName]['schema'][$field]['type'] = 'string';
							if( preg_match_all( "/'([^']+)'/", $infos['type'], $matches ) ) {
								$this->_modelInfos[$modelName]['schema'][$field]['options'] = $matches[1];
							}
						}
					}

					Cache::write( $cacheKey, $this->_modelInfos[$modelName] );
				}
			}

			return $this->_modelInfos[$modelName];
		}

		/**
		*
		*/

		public function primaryKey( $modelName ) {
			$modelInfos = $this->_modelInfos( $modelName );
			return $modelInfos['primaryKey'];
		}

		/**
		*
		*/

		public function displayField( $modelName ) {
			$modelInfos = $this->_modelInfos( $modelName );
			return $modelInfos['displayField'];
		}

		/**
		*
		*/

		public function type( $modelName, $fieldName = null ) {
			if( is_null( $fieldName ) ) {
				list( $modelName, $fieldName ) = Xinflector::modelField( $modelName );
			}
			$modelInfos = $this->_modelInfos( $modelName );
			return $modelInfos['schema'][$fieldName]['type'];
		}

		/**
		* @param string $path ie. User.username, User.0.id
		* @retrun array ie.
		* 	array(
		* 		'type' => 'integer',
		* 		'null' => false,
		* 		'default' => null,
		* 		'length' => 11,
		* 		'key' => 'primary'
		* 	)
		*/

		protected function _typeInfos( $path ) {
			list( $modelName, $fieldName ) = Xinflector::modelField( $path );
			$modelInfos = $this->_modelInfos( $modelName );
			return Set::extract( $modelInfos, "schema.{$fieldName}" );
		}
	}
?>