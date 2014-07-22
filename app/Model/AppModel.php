<?php
	/**
	 * Code source de la classe AppModel.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Model', 'Model' );
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe AppModel est la classe parente de toutes les classes de modèle
	 * de l'application.
	 *
	 * @package app.Model
	 */
	class AppModel extends Model
	{
		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array( 'Containable', 'DatabaseTable' );

		/**
		 * Champs virtuels pour ce modèle
		 *
		 * @var array
		 */
		public $virtualFields = array( );

		/**
		 * Permet de forcer l'utilisation des champs virtuels pour les modèles liés
		 */
		public $forceVirtualFields = false;

		/**
		 * Contient la liste des modules (au sens applicatif) auxquels ce modèle est lié.
		 *
		 * @var array
		 */
		protected $_modules = array( );

		/**
		 * Surcharge du constructeur pour les champs virtuels.
		 * Si un driver a été fourni, on utilise la sous-requête correspondante.
		 *
		 * @param integer|string|array $id Set this ID for this model on startup, can also be an array of options, see above.
		 * @param string $table Name of database table to use.
		 * @param string $ds DataSource connection name.
		 */
		public function __construct( $id = false, $table = null, $ds = null ) {
			parent::__construct( $id, $table, $ds );

			if( isset( $this->virtualFields ) && !empty( $this->virtualFields ) ) {
				$driver = $this->driver();

				foreach( $this->virtualFields as $name => $value ) {
					if( is_array( $value ) && isset( $value[$driver] ) ) {
						$this->virtualFields[$name] = $value[$driver];
					}
					$this->virtualFields[$name] = str_replace( '%s', $this->alias, $this->virtualFields[$name] );
				}
			}
		}

		/**
		 * Exécute les différentes méthods du modèle permettant la mise en cache.
		 * Utilisé au préchargement de l'application (/prechargements/index).
		 *
		 * @return boolean true en cas de succès, false en cas d'erreur,
		 * 	null pour les fonctions vides.
		 */
		public function prechargement() {
			return null;
		}

		/**
		 * Débute une transaction.
		 *
		 * INFO: en CakePHP 2.x, il n'y a pas de paramètre à passer.
		 *
		 * @return boolean
		 */
		public function begin() {
			$return = $this->getDataSource()->begin( $this );
			return $return;
		}

		/**
		 * Valide une transaction.
		 *
		 * INFO: en CakePHP 2.x, il n'y a pas de paramètre à passer.
		 *
		 * @return boolean
		 */
		public function commit() {
			$return = $this->getDataSource()->commit( $this );
			return $return;
		}

		/**
		 * Utilisation des champs virtuels dans les modèles liés (même CakePHP 2.x ne les gère pas)
		 * lorsque l'attribut forceVirtualFields est à true.
		 *
		 * @param array $queryData
		 * @return mixed
		 */
		public function beforeFind( $queryData ) {
			$return = parent::beforeFind( $queryData );
			if( is_bool( $return ) ) {
				if( $return === false ) {
					return false;
				}
			}
			else {
				$queryData = $return;
			}

			if( $this->forceVirtualFields ) {
				$dbo = $this->getDataSource();
				$linkedModels = Set::extract( $queryData, '/joins/alias' );
				$contains = Set::extract( $queryData, '/contain' );
				if( !empty( $contains ) && is_array( $contains ) ) {
					$linkedModels += array_keys( (array)Set::normalize( $contains ) );
				}

				if( !empty( $linkedModels ) ) {
					foreach( $linkedModels as $linkedModel ) {
						$linkedModel = ClassRegistry::init( $linkedModel );
						if( !empty( $linkedModel->virtualFields ) ) {
							$replacements = array();
							$replacementsFields = array();

							foreach( $linkedModel->virtualFields as $fieldName => $query ) {
								//$regex = "/(?<!\.)(?<!\w)({$linkedModel->alias}\.){0,1}{$fieldName}(?!\w)/";
								$regex = "/(?<!\.)(?<!\w){$linkedModel->alias}\.{$fieldName}(?!\w)/";
								$alias = "{$query} {$dbo->alias} {$dbo->startQuote}{$linkedModel->alias}__{$fieldName}{$dbo->endQuote}";
								$replacementsFields[$regex] = $alias;

								$replacements[$regex] = $query;
							}

							$queryData['fields'] = recursive_key_value_preg_replace( (array)$queryData['fields'], $replacementsFields );

							foreach( array( 'conditions', 'order', 'group' ) as $type ) {
								if( !empty( $queryData[$type] ) ) {
									$queryData[$type] = recursive_key_value_preg_replace( (array)$queryData[$type], $replacements );
								}
							}
						}
					}
				}
			}

			return $queryData;
		}

		/**
		 * Annule une transaction.
		 *
		 * INFO: en CakePHP 2.x, il n'y a pas de paramètre à passer.
		 *
		 * @return boolean
		 */
		public function rollback() {
			$return = $this->getDataSource()->rollback( $this );
			return $return;
		}

		/**
		 * Remplace le caractère * par le caractère % pour les requêtes SQL.
		 *
		 * @param type $value
		 * @return type
		 */
		public function wildcard( $value ) {
			return str_replace( '*', '%', Sanitize::escape( $value ) );
		}

		/**
		 * Permet d'unbinder toutes les associations d'un modèle en une fois.
		 *
		 * @param type $reset Si true, les associations seront rebindées après le find.
		 * @return void
		 * @see http://bakery.cakephp.org/articles/view/unbindall
		 */
		public function unbindModelAll( $reset = true ) {
			$unbind = array( );
			foreach( $this->belongsTo as $model => $info ) {
				$unbind['belongsTo'][] = $model;
			}
			foreach( $this->hasOne as $model => $info ) {
				$unbind['hasOne'][] = $model;
			}
			foreach( $this->hasMany as $model => $info ) {
				$unbind['hasMany'][] = $model;
			}
			foreach( $this->hasAndBelongsToMany as $model => $info ) {
				$unbind['hasAndBelongsToMany'][] = $model;
			}
			parent::unbindModel( $unbind, $reset );
		}

		// FIXME
		/**
		 * Surcharge de la méthode saveAll: on retourne un booléen true / false dans tous les cas.
		 * En CakePHP 2.0, les options ne sont pas les mêmes, donc on reprend les options par défaut de
		 * CakePHP 1.2.
		 *
		 * @param array $data
		 * @param array $options
		 * @return boolean
		 */
		/*public function saveAll( $data = NULL, $options = array( ) ) {
			$options = array_merge( array( 'validate' => true, 'atomic' => true ), $options );

			$return = parent::saveAll( $data, $options );

			if( is_array( $return ) && !empty( $return ) ) {
				$return = Hash::flatten( $return );
				$return = ( array_sum( $return ) == count( $return ) );
			}
			else if( is_array( $return ) && empty( $return ) ) {
				return false;
			}
			return $return;
		}*/

		/**
		 * Retourne un booléen dans tous les cas.
		 *
		 * @param array $data Data to save.
		 * @param boolean|array $validate Either a boolean, or an array.
		 *   If a boolean, indicates whether or not to validate before saving.
		 *   If an array, allows control of validate, callbacks, and fieldList
		 * @param array $fieldList List of fields to allow to be written
		 * @return boolean
		 */
//		public function save( $data = null, $validate = true, $fieldList = array( ) ) {
//			return ( parent::save( $data, $validate, $fieldList ) !== false );
//		}

		/**
		 * Retourne les résultats d'une opération de sauvegarde sous forme d'un
		 * booléen.
		 *
		 * @param mixed $result
		 * @return boolean
		 */
		public function saveResultAsBool( $result ) {
			if( is_array( $result ) ) {
				foreach( Hash::flatten( $result ) as $boolean ) {
					if( $boolean === false ) {
						return false;
					}
				}

				return true;
			}
			else {
				return $result;
			}
		}

		/**
		 * Retourne un booléen dans tous les cas.
		 *
		 * @param array $data Record data to save. This can be either a numerically-indexed array (for saving multiple
		 *     records of the same type), or an array indexed by association name.
		 * @param array $options Options to use when saving record data, See $options above.
		 * @return boolan
		 */
		public function saveAll( $data = array( ), $options = array( ) ) {
			$result = parent::saveAll( $data, $options );
			return $this->saveResultAsBool( $result );
		}

		/**
		 * Retourne un booléen dans tous les cas.
		 *
		 * @param array $data Record data to save. This should be an array indexed by association name.
		 * @param array $options Options to use when saving record data, See $options above.
		 * @return boolean
		 */
//		public function saveAssociated( $data = null, $options = array( ) ) {
//			$result = parent::saveAssociated( $data, $options );
//			return $this->saveResultAsBool( $result );
//		}

		/**
		 * Retourne un booléen dans tous les cas.
		 *
		 * @param array $data Record data to save. This should be a numerically-indexed array
		 * @param array $options Options to use when saving record data, See $options above.
		 * @return boolean
		 */
//		public function saveMany( $data = null, $options = array( ) ) {
//			$result = parent::saveMany( $data, $options );
//			return $this->saveResultAsBool( $result );
//		}

		/**
		 * Filtre zone géographique
		 * FIXME: à supprimer et utiliser le ConditionnableBehavior
		 */
		public function conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee ) {
			if( $filtre_zone_geo ) {
				// Si on utilise la table des cantons plutôt que la table zonesgeographiques
				if( Configure::read( 'CG.cantons' ) ) { // FIXME: est-ce bien la signification de la variable ?
					return ClassRegistry::init( 'Canton' )->queryConditionsByZonesgeographiques( array_keys( $mesCodesInsee ) );
				}
				else {
					$mesCodesInsee = (!empty( $mesCodesInsee ) ? $mesCodesInsee : array( null ) );
					return '( Adresse.numcom IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' ) /*OR ( Situationdossierrsa.etatdosrsa = \'Z\' ) */ )'; ///FIXME: passage de OR à AND car les dossiers à Z mais non présents dans le code insee apparaissaient !!!!!!!
				}
			}
		}


		/**
		 * FIXME: remplace la fonction ci-dessus
		 *
		 * @param array $keys
		 * @return array
		 */
		protected function _checkPostgresqlIntervals( $keys, $asBoolean = false ) {
			if( !$this->Behaviors->attached( 'Pgsqlcake.PgsqlSchema' ) ) {
				$this->Behaviors->attach( 'Pgsqlcake.PgsqlSchema' );
			}

			$keys = (array)$keys;

			$results = array( );
			foreach( $keys as $key ) {
				$results[$key] = $this->pgCheckIntervalSyntax( Configure::read( $key ) );
			}

			if( $asBoolean ) {
				$booleans = Set::classicExtract( $results, '{s}.success' );
				$results = !in_array( false, $booleans, true );
			}

			return $results;
		}

		/**
		* Retourne la sous-requête d'un des champs virtuels se trouvant dans $this->virtualFields
		*
		* @return string
		*/
		public function sqVirtualField( $field, $alias = true ) {
			$virtualField = Set::classicExtract( $this->virtualFields, $field );

			if( empty( $virtualField ) ) {
				throw new Exception( "Virtual field \"{$field}\" does not exist in model \"{$this->alias}\"." );
				return null;
			}

			if( CAKE_BRANCH == '1.2' ) {
				$sq = preg_replace( $virtualField['regex'], $virtualField['alias'], "{$this->alias}.{$field}" );

				if( !$alias ) {
					$sq = preg_replace( '/ +AS +[^ ]+$/m', '', $sq );
				}
			}
			else {
				$sq = "( $virtualField )";
				if( $alias ) {
					$sq = "{$sq} AS \"{$this->alias}__{$field}\"";
				}
			}

			return $sq;
		}

		/**
		 * Retourne la liste des modules auxquels ce modèle est lié.
		 *
		 * @return array
		 */
		public function modules() {
			return $this->_modules;
		}

		/**
		 * Vérifie si ce modèle appartient à un module donné.
		 *
		 * @param string $name Le nom du module dont l'on veut tester l'appartenance.
		 * @param boolean $only Si vrai, on vérifie en plus que le module testé est
		 * 	le seul auquel appartient ce modèle.
		 * @return boolean
		 */
		public function inModule( $name, $only = false ) {
			if( in_array( $name, $this->_modules ) ) {
				if( $only === true ) {
					return ( count( $this->_modules ) == 1 );
				}
				return true;
			}
			return false;
		}

		/**
		 * Retourne le nom du driver utilisé par le modèle (postgres, mysql, ...).
		 *
		 * @return string
		 */
		public function driver() {
			$Dbo = $this->getDataSource();

			$class = get_class( $Dbo );
			$parent_class = get_parent_class( $class );

			while( !empty( $parent_class ) && ( $parent_class != 'DboSource' ) ) {
				$class = $parent_class;
				$parent_class = get_parent_class( $parent_class );
			}

			return strtolower( $class );
		}

		/**
		 * Chargement du behavior Validation.ExtraValidationRules.
		 *
		 * @param array $options
		 * @return boolean
		 */
		public function beforeValidate( $options = array( ) ) {
			$loaded = true;
			if( !$this->Behaviors->attached( 'Validation.ExtraValidationRules' ) ) {
				$loaded = $this->Behaviors->attach( 'Validation.ExtraValidationRules' );
			}

			return parent::beforeValidate( $options ) && $loaded;
		}

		/**
		 * Chargement du behavior Validation.ExtraValidationRules.
		 *
		 * @param array $options
		 * @return boolean
		 */
		public function beforeSave( $options = array( ) ) {
			$loaded = true;
			if( !$this->Behaviors->attached( 'Validation.ExtraValidationRules' ) ) {
				$loaded = $this->Behaviors->attach( 'Validation.ExtraValidationRules' );
			}

			return parent::beforeSave( $options ) && $loaded;
		}

		/**
		 * Retourne la liste des options venant de EnumerableBehavior, ainsi que
		 * des champs possédant la règle de validation inList.
		 *
		 * @return array
		 */
		public function enums() {
			$cacheKey = $this->useDbConfig.'_'.__CLASS__.'_enums_'.$this->alias;
			$options = Cache::read( $cacheKey );

			if( $options === false ) {
				// Dans enumerable ?
				if( $this->Behaviors->attached( 'Enumerable' ) ) {
					$options = $this->Behaviors->Enumerable->enums( $this );
				}
				else {
					$options = array();
				}

				// D'autres champs avec la règle inList ?
				$domain = Inflector::underscore( $this->alias );
				foreach( $this->validate as $field => $validate ) {
					foreach( $validate as $ruleName => $rule ) {
						if( ( $ruleName === 'inList' ) && !isset( $options[$this->alias][$field] ) ) {
							$fieldNameUpper = strtoupper( $field );

							$tmp = $rule['rule'][1];
							$list = array();

							foreach( $tmp as $value ) {
								$list[$value] = __d( $domain, "ENUM::{$fieldNameUpper}::{$value}" );
							}

							$options[$this->alias][$field] = $list;
						}
					}
				}

				Cache::write( $cacheKey, $options );
			}

			return $options;
		}

		/**
		 * Permet d'obtenir les valeurs d'un enum particulier, avec possibilité
		 * de tri sur les intitulés.
		 *
		 * @param string $field
		 * @param boolean $sort
		 * @return array
		 */
		public function enum( $field, $sort = false ) {
			$enums = $this->enums();
			$values = Hash::get( $enums, "{$this->alias}.{$field}" );

			if( $sort ) {
				asort( $values );
			}

			return $values;
		}

		/**
		 * Suppression des données du cache.
		 *
		 * INFO: on pourrait en faire un behavior / un plugin ?
		 *
		 * @return void
		 */
		protected function _clearModelCache() {
			$keys = ModelCache::read( $this->name );
			if( !empty( $keys ) ) {
				foreach( $keys as $key ) {
					Cache::delete( $key );
					ModelCache::delete( $key );
				}
			}
		}

		/**
		 * Après une sauvegarde, on supprime les données en cache.
		 *
		 * @param boolean $created True if this save created a new record
		 * @return void
		 */
		public function afterSave( $created ) {
			parent::afterSave( $created );
			$this->_clearModelCache();
		}

		/**
		 * Après une suppression, on supprime les données en cache.
		 *
		 * @param boolean $created True if this save created a new record
		 * @return void
		 */
		public function afterDelete() {
			parent::afterDelete();
			$this->_clearModelCache();
		}

		/**
		 * By default, updateAll() will automatically join any belongsTo
		 * association for databases that support joins. To prevent this,
		 * temporarily unbind the associations.
		 *
		 * @see http://book.cakephp.org/2.0/en/models/saving-your-data.html#model-updateall-array-fields-array-conditions
		 *
		 * @param array $fields
		 * @param mixed $conditions
		 * @return boolean
		 */
		public function updateAllUnBound($fields, $conditions = true) {
			$this->unbindModelAll();
			$success = parent::updateAll($fields, $conditions );
			$this->resetAssociations();

			return $success;
		}

		/**
		 * Les éléments de la liste sont triés et préfixés par une chaîne de caractères.
         *
         * @todo prefix/suffix pour avoir correctement le tiret et les retours à la ligne
		 *
		 * @param array $querydata
		 * @param string $prefix
		 * @param string $suffix
		 * @return string
		 */
		public function vfListe( array $querydata, $prefix = '\\n\r-', $suffix = '' ) {
            // FIXME: un seul champ est possible
            foreach( $querydata['fields'] as $i => $field ) {
                list( $modelName, $fieldName ) = model_field( $field );
                $fieldAlias = "{$modelName}__{$fieldName}";
                $querydata['fields'][$i] = "'{$prefix}' || \"{$modelName}\".\"{$fieldName}\" || '{$suffix}' AS \"{$fieldAlias}\"";
            }

            $sql = $this->sq( $querydata );
//			return "TRIM( TRAILING '{$suffix}' FROM ARRAY_TO_STRING( ARRAY( {$sql} ), '' ) )";
            return "TRIM( BOTH '\n\r' FROM TRIM( TRAILING '{$suffix}' FROM ARRAY_TO_STRING( ARRAY( {$sql} ), '' ) ) )";
		}

		/**
		 *
		 * @fixme le mettre ailleurs ... et ne pas oublier de lier éventuellement d'autres modèles
		 *
		 * @param string $prefixKeyField
		 * @param string $suffixKeyField
		 * @param string $displayField
		 * @param array $conditions
		 * @param array $modelNames
		 * @return array
		 */
		public function findListPrefixed( $prefixKeyField, $suffixKeyField, $displayField, array $conditions = array(), array $modelNames = array() ) {
			$query = array(
				'fields' => array(
					"{$this->alias}.{$prefixKeyField}",
					"{$this->alias}.{$suffixKeyField}",
					"{$this->alias}.{$displayField}"
				),
				'order' => array(
					"{$this->alias}.{$prefixKeyField}",
					"{$this->alias}.{$suffixKeyField}",
					"{$this->alias}.{$displayField}"
				),
				'contain' => false,
				'conditions' => $conditions
			);

			$cacheKey = Inflector::underscore( $this->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $query ) );
			$results = Cache::read( $cacheKey );

			if( $results === false ) {
				$results = $this->find( 'all', $query );

				$results = Hash::combine(
					$results,
					array( '%s_%s', "{n}.{$this->alias}.{$prefixKeyField}", "{n}.{$this->alias}.{$suffixKeyField}" ),
					"{n}.{$this->alias}.{$displayField}"
				);

				$modelNames[] = $this->name;
				$modelNames = array_unique( $modelNames );

				Cache::write( $cacheKey, $results );
				ModelCache::write( $cacheKey, $modelNames );
			}

			return $results;
		}
	}
?>
