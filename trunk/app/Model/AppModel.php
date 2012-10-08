<?php
	/**
	 * AppModel fonctionnant en versions 1.2 et 2.x, à simplifier après la période transitoire de passage de version.
	 */

	// CakePHP 1.2
	if( CAKE_BRANCH == '1.2' ) {
		App::import( 'Lib', 'LazyModel.LazyModel' );
		App::import( 'Sanitize' );

		class AppBaseModel extends LazyModel
		{
			/**
			 *
			 * @var string
			 */
			public $_findQueryType = null;

			/**
			 *  INFO: ne semble plus être utilisée
			 *
			 * @param type $params
			 */
			/*public function nullify( $params ) {
				$fields = array_keys( $this->schema() );
				$fields = array_combine( $fields, array_fill( 0, count( $fields ), null ) );
				$this->data[$this->name] = Set::merge( $fields, nullify_empty_values( $this->data[$this->name] ) );

				$exceptions = Set::classicExtract( $params, 'exceptions' );
				if( !empty( $exceptions ) ) {
					foreach( $exceptions as $exception ) {
						$this->data = Set::remove( $this->data, $exception );
					}
				}
			}*/

			// *****************************************************************
			// début champs virtuels 1.2
			// *****************************************************************

			/**
			 *
			 * @param type $queryData
			 * @return type
			 */
			protected function queryFields( $queryData = array( ) ) {
				$recursive = Set::classicExtract( $queryData, 'recursive' );
				if( $recursive === null ) {
					$recursive = $this->recursive;
				}

				$fields = array( );

				foreach( array_keys( $this->schema() ) as $modelField ) {
					$fields[] = "{$this->alias}.{$modelField}";
				}

				if( $recursive >= 0 ) {
					$associationModels = Set::merge( $this->belongsTo, $this->hasOne );

					foreach( array_keys( $associationModels ) as $associationModel ) {
						$associationFields = $this->joinModel( $associationModel );
						$associationFields = $associationFields[1];

						$associationFields2 = array( );
						$associationModelInstance = ClassRegistry::init( $associationModel );
						if( !empty( $associationModelInstance->virtualFields ) && is_array( $associationModelInstance->virtualFields ) ) {
							$associationFields2 = Set::merge( $fields, Set::extract( $associationModelInstance->virtualFields, '{s}.alias' ) );

							// FIXME: Set::combine ?
							$replacements = array_combine(
									Set::extract( $associationModelInstance->virtualFields, '{s}.regex' ), Set::extract( $associationModelInstance->virtualFields, '{s}.alias' )
							);
							$associationFields2 = recursive_key_value_preg_replace( $associationFields2, $replacements );
						}

						foreach( $associationFields as $associationField ) {
							$fields[] = "{$associationModel}.{$associationField}";
						}

						$fields = Set::merge( $fields, $associationFields2 );
					}
				}

				return $fields;
			}

			/**
			 *
			 */
			public function initVirtualFields( $virtualFields ) {
				if( is_array( $virtualFields ) && !empty( $virtualFields ) ) {
					$this->dbo = $this->getDataSource();
					$driver = $this->driver();

					foreach( $virtualFields as $fieldName => $params ) {
						foreach( array( 'query', $driver ) as $key ) {
							if( Set::check( $params, $key ) ) {
								$occurences = count( strallpos( $params[$key], '%s' ) );
								if( $occurences > 0 ) {
									$aliases = array_fill( 0, $occurences, $this->alias );
									$params[$key] = vsprintf( $params[$key], $aliases );
								}
							}
						}

						if( empty( $params['query'] ) && !empty( $params[$driver] ) ) {
							$virtualFields[$fieldName]['query'] = $params[$driver];
						}
						else if( ( $driver == 'mysqli' ) && empty( $params['query'] ) && !empty( $params['mysql'] ) ) {
							$virtualFields[$fieldName]['query'] = $params['mysql'];
						}
						/// Empty query or empty type -> error, etc ...
						if( !in_array( 'query', array_keys( $virtualFields[$fieldName] ) ) || empty( $virtualFields[$fieldName]['query'] ) ) {
							trigger_error( "No query available for field {$fieldName}", E_USER_WARNING );
							return null;
						}
						if( !in_array( 'type', array_keys( $virtualFields[$fieldName] ) ) || empty( $virtualFields[$fieldName]['type'] ) ) {
							trigger_error( "No type specified for field {$fieldName}", E_USER_WARNING );
							return null;
						}
						/// TODO: ... = `User`.`id` + () et espaces
						$virtualFields[$fieldName]['regex'] = "/(?<!\.)(?<!\w)({$this->alias}\.){0,1}{$fieldName}(?!\w)/";
						$virtualFields[$fieldName]['alias'] = "{$virtualFields[$fieldName]['query']} {$this->dbo->alias} {$this->dbo->startQuote}{$this->alias}__{$fieldName}{$this->dbo->endQuote}";
					}
				}
				return $virtualFields;
			}

			/**
			 *
			 */
			public function __construct( $id = false, $table = NULL, $ds = NULL ) {
				parent::__construct( $id, $table, $ds );

				/// FIXME: protected _virtualFieldsIni = false
				$this->virtualFields = $this->initVirtualFields( $this->virtualFields );
			}

			/**
			 *
			 */
			protected function _beforeFindVirtualFields( $queryData ) {
				if( $this->forceVirtualFields || ( is_array( $this->virtualFields ) && !empty( $this->virtualFields ) ) ) {
					$this->dbo = $this->getDataSource();

					// Get the list of fields and virtual fields
					if( $this->findQueryType != 'count' ) {
						if( empty( $queryData['fields'] ) ) {
							$fields = $this->queryFields( $queryData );
							$aliases = Set::extract( $this->virtualFields, '{s}.alias' );
							$fields = Set::merge( $fields, $aliases );
							$queryData['fields'] = $fields;
						}
					}

					// Modèles liés aliasés
					$linkedModels = array( $this );
					foreach( $this->getAssociated() as $associatedAlias => $assocType ) {
						$linkedModels[] = ClassRegistry::init( array( 'class' => $this->{$assocType}[$associatedAlias]['className'], 'alias' => $associatedAlias, 'type' => 'Model' ) );
					}

					// Champs virtuels sur les tables de jointure
					$joinTables = Set::extract( $queryData, 'joins.{n}.alias' );
					foreach( $joinTables as $joinTable ) {
						$linkedModels[] = ClassRegistry::init( $joinTable );
					}

					// Translate virtual fields fieldnames
					if( $this->findQueryType != 'count' ) {
						foreach( $linkedModels as $linkedModel ) {
							$regexes = Set::filter( Set::extract( $linkedModel->virtualFields, '{s}.regex' ) );
							$aliases = Set::filter( Set::extract( $linkedModel->virtualFields, '{s}.alias' ) );

							if( !empty( $regexes ) && !empty( $aliases ) ) {
								if( count( $regexes ) == count( $aliases ) ) {
									// FIXME: Set::combine ?
									$replacements = array_combine( $regexes, $aliases );
									if( is_array( $queryData['fields'] ) ) {
										$queryData['fields'] = recursive_key_value_preg_replace( $queryData['fields'], $replacements );
									}
									else {
										$queryData['fields'] = preg_replace( $regexes, $replacements, $queryData['fields'] );
									}
								}
								else {
									trigger_error( "...", E_USER_WARNING );
									return false;
								}
							}
						}
					}

					// Translate virtual fields conditions, order and group
					foreach( $linkedModels as $linkedModel ) {
						$regexes = Set::filter( Set::extract( $linkedModel->virtualFields, '{s}.regex' ) );
						$queries = Set::filter( Set::extract( $linkedModel->virtualFields, '{s}.query' ) );
						if( !empty( $regexes ) && !empty( $queries ) ) {
							if( count( $regexes ) == count( $queries ) ) {
								// FIXME: Set::combine ?
								$replacements = array_combine( $regexes, $queries );

								foreach( array( 'conditions', 'order', 'group' ) as $type ) {
									if( isset( $queryData[$type] ) ) {
										if( is_array( $queryData[$type] ) ) {
											$queryData[$type] = recursive_key_value_preg_replace( $queryData[$type], $replacements );
										}
										else {
											foreach( $replacements as $pattern => $replacement ) {
												$queryData[$type] = preg_replace( $pattern, $replacement, $queryData[$type] );
											}
										}
									}
								}
							}
							else {
								trigger_error( "...", E_USER_WARNING );
								return false;
							}
						}
					}
				}

				return $queryData;
			}

			/**
			 *
			 */
			public function beforeFind( $queryData ) {
				$this->_findQueryType = $this->findQueryType;

				if( $return = parent::beforeFind( $queryData ) ) {
					return $this->_beforeFindVirtualFields( $queryData );
				}
				return $return;
			}

			/**
			 *
			 */
			protected function _afterFindVirtualFields( $results, $primary = false ) {
				/// Virtual fields
				if( !in_array( $this->_findQueryType, array( 'count' ) ) && is_array( $this->virtualFields ) && !empty( $this->virtualFields ) ) {
					$results = Xset::flatten( $results );

					foreach( $results as $key => $result ) {
						if( preg_match( "/^(.+)\.0\.(\w+)__(\w+)$/", $key, $matches ) ) {
							unset( $results[$key] );
							$key = "{$matches[1]}.{$matches[2]}.{$matches[3]}";
							$key = str_replace( ".{$matches[2]}.{$matches[2]}.", '.', $key );
							$results[$key] = $result;
						}
					}
					$results = Xset::bump( $results );
				}
				return $results;
			}

			/**
			 *
			 */
			public function afterFind( $results, $primary = false ) {
				$results = $this->_afterFindVirtualFields( $results, $primary = false );
				return $results;
			}

			/**
			 *
			 */
			public function getColumnType( $column, $virtual = false ) {
				$columnParams = $this->schema( $column, $virtual );
				if( !empty( $columnParams ) && is_array( $columnParams ) && isset( $columnParams['type'] ) ) {
					return $columnParams['type'];
				}
				return null;
			}

			/**
			 *
			 */
			public function getColumnTypes( $virtual = false ) {
				$columnsTypes = array( );

				$columnsParams = $this->schema( false, $virtual );
				if( !empty( $columnsParams ) && is_array( $columnsParams ) ) {
					foreach( $columnsParams as $columnName => $columnParams )
						$columnsTypes[$columnName] = $columnParams['type'];
				}
				return $columnsTypes;
			}

			/**
			 * Returns an array of table metadata (column names and types) from the database.
			 * $field => keys(type, null, default, key, length, extra)
			 *
			 * @param mixed $field Set to true to reload schema, or a string to return a specific field
			 * @return array Array of table metadata
			 * @access public
			 */
			// BEGIN cbuffin
			public function schema( $field = false, $virtual = false ) {
				if( !is_array( $this->_schema ) || $field === true ) {
					$db = $this->getDataSource();
					$db->cacheSources = ($this->cacheSources && $db->cacheSources);
					if( $db->isInterfaceSupported( 'describe' ) && $this->useTable !== false ) {
						$this->_schema = $db->describe( $this, $field );
					}
					elseif( $this->useTable === false ) {
						$this->_schema = array( );
					}
				}

				$schema = $this->_schema;
				if( $virtual ) {
					if( !empty( $this->virtualFields ) && is_array( $this->virtualFields ) ) {
						foreach( $this->virtualFields as $fieldName => $params ) {
							$schema[$fieldName] = array( 'type' => $params['type'], 'virtual' => true );
						}
					}
				}

				if( is_string( $field ) ) {
					if( isset( $schema[$field] ) ) {
						return $schema[$field];
					}
					else {
						return null;
					}
				}
				return $schema;
			}

			/**
			 * Returns true if the supplied field exists in the model's database table.
			 *
			 * @param mixed $name Name of field to look for, or an array of names
			 * @return mixed If $name is a string, returns a boolean indicating whether the field exists.
			 *               If $name is an array of field names, returns the first field that exists,
			 *               or false if none exist.
			 * @access public
			 */
			public function hasField( $name, $virtual = false ) {
				if( is_array( $name ) ) {
					foreach( $name as $n ) {
						if( $this->hasField( $n, $virtual ) ) {
							return $n;
						}
					}
					return false;
				}

				/// cbuffin begin
				if( $virtual ) {
					$schema = $this->schema( null, $virtual );
				}
				else {
					$schema = $this->schema();
				}

				if( $schema != null ) {
					return isset( $schema[$name] );
				}
				/// cbuffin end
				return false;
			}

			/**
			 * Build an array-based association from string.
			 *
			 * @param string $type 'belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany'
			 * @return void
			 * @access private
			 */
			function __generateAssociation( $type ) {
				foreach( $this->{$type} as $assocKey => $assocData ) {
					$class = $assocKey;
					$dynamicWith = false;

					foreach( $this->__associationKeys[$type] as $key ) {
						if( !isset( $this->{$type}[$assocKey][$key] ) || $this->{$type}[$assocKey][$key] === null ) {
							$data = '';

							switch( $key ) {
								case 'fields':
									// BEGIN cbuffin
									$associationModelInstance = ClassRegistry::init( array( 'class' => $this->{$type}[$assocKey]['className'], 'alias' => $assocKey, 'type' => 'Model' ) );
									$fields = array_keys( $associationModelInstance->schema() );

									if( !empty( $associationModelInstance->virtualFields ) && is_array( $associationModelInstance->virtualFields ) ) {
										$associationModelInstance->virtualFields = $associationModelInstance->initVirtualFields( $associationModelInstance->virtualFields );
										$associationFields2 = Set::merge( $fields, Set::extract( $associationModelInstance->virtualFields, '{s}.alias' ) );
										$replacements = array_combine(
												Set::extract( $associationModelInstance->virtualFields, '{s}.regex' ), Set::extract( $associationModelInstance->virtualFields, '{s}.alias' )
										);
										$associationFields2 = recursive_key_value_preg_replace( $associationFields2, $replacements );
										$fields = $associationFields2;
									}

									$data = $fields;
									// END cbuffin
									break;

								case 'foreignKey':
									$data = (($type == 'belongsTo') ? Inflector::underscore( $assocKey ) : Inflector::singularize( $this->table )).'_id';
									break;

								case 'associationForeignKey':
									$data = Inflector::singularize( $this->{$class}->table ).'_id';
									break;

								case 'with':
									$data = Inflector::camelize( Inflector::singularize( $this->{$type}[$assocKey]['joinTable'] ) );
									$dynamicWith = true;
									break;

								case 'joinTable':
									$tables = array( $this->table, $this->{$class}->table );
									sort( $tables );
									$data = $tables[0].'_'.$tables[1];
									break;

								case 'className':
									$data = $class;
									break;

								case 'unique':
									$data = true;
									break;
							}
							$this->{$type}[$assocKey][$key] = $data;
						}
					}

					if( !empty( $this->{$type}[$assocKey]['with'] ) ) {
						$joinClass = $this->{$type}[$assocKey]['with'];
						if( is_array( $joinClass ) ) {
							$joinClass = key( $joinClass );
						}
						$plugin = null;

						if( strpos( $joinClass, '.' ) !== false ) {
							list($plugin, $joinClass) = explode( '.', $joinClass );
							$plugin = $plugin.'.';
							$this->{$type}[$assocKey]['with'] = $joinClass;
						}

						if( !ClassRegistry::isKeySet( $joinClass ) && $dynamicWith === true ) {
							$this->{$joinClass} = new AppModel( array(
										'name' => $joinClass,
										'table' => $this->{$type}[$assocKey]['joinTable'],
										'ds' => $this->useDbConfig
									) );
						}
						else {
							$this->__constructLinkedModel( $joinClass, $plugin.$joinClass );
							$this->{$type}[$assocKey]['joinTable'] = $this->{$joinClass}->table;
						}

						if( count( $this->{$joinClass}->schema() ) <= 2 && $this->{$joinClass}->primaryKey !== false ) {
							$this->{$joinClass}->primaryKey = $this->{$type}[$assocKey]['foreignKey'];
						}
					}
				}
			}

			// *****************************************************************
			// fin champs virtuels 1.2
			// *****************************************************************

			// *****************************************************************
			// début règles de validation supplémentaires 1.2
			// *****************************************************************

			/**
			 *
			 */
			public function alphaNumeric( $check ) {
				$_this =  Validation::getInstance();
				$_this->__reset();
				$_this->check = $check;

				if( is_array( $check ) ) {
					$_this->_extract( $check );
					// FIXME: WTF 1 ?
					$t = array_values( $check );
					$check = $t[0];
					$_this->check = $check;
				}

				if( empty( $_this->check ) && $_this->check != '0' ) {
					return false;
				}

				// FIXME: WTF 2 ?
				//$_this->regex = '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]+$/mu';
				$_this->regex = '/^[a-z0-9]+$/mui';
				return $_this->_check();
			}

			// TODO: http://teknoid.wordpress.com/2008/09/29/dealing-with-calculated-fields-in-cakephps-find/

			/**
			 *   Vérification que la date saisie n'est pas inférieure à celle du jour
			 */
			public function futureDate( $check ) {
				$return = true;
				foreach( $check as $field => $value ) {
					$return = ( strtotime( $value ) >= strtotime( date( 'Y-m-d' ) ) ) && $return;
				}
				return $return;
			}

			/**
			 *   Vérification que la date saisie est antérieure à celle du jour
			 */
			public function datePassee( $check ) {
				$return = true;
				foreach( $check as $field => $value ) {
					$return = ( strtotime( $value ) <= strtotime( date( 'Y-m-d' ) ) ) && $return;
				}
				return $return;
			}

			/**
			 *
			 */
			public function integer( $check ) {
				// TODO: meilleure validation ?
				if( !is_array( $check ) ) {
					return false;
				}

				// TODO: meilleure validation ?
				$result = true;
				foreach( Set::normalize( $check ) as $key => $value ) {
					$result = preg_match( '/^[0-9]+$/', $value ) && $result;
				}
				return $result;
			}

			/**
			 *
			 */
			public function phoneFr( $check ) {
				$return = true;
				foreach( $check as $field => $value ) {
					$value = preg_replace( array( '/\./', '/ /' ), array( ), $value );
					$return = preg_match( '/^([0-9]{10})$/', $value ) && $return;
				}
				return $return;
			}

			/**
			 *
			 */
			public function allEmpty( array $data, $reference ) { // FIXME + $reference2, ....
				$data = array_values( $data );
				$value = ( isset( $data[0] ) ? $data[0] : null );

				$reference = Set::extract( $this->data, $this->name.'.'.$reference );

				return ( empty( $value ) == empty( $reference ) );
			}

			/**
			 * 'dateentreeemploi' => notEmptyIf( array $data, 'activitebeneficiaire', true, array( 'P' ) )
			 */
			public function notEmptyIf( array $data, $reference, $condition, $values ) {
				$data = array_values( $data );
				$data_value = ( isset( $data[0] ) ? $data[0] : null );

				$reference_value = Set::extract( $this->data, $this->name.'.'.$reference );

				$return = true;

				foreach( $values as $value ) {
					if( ( $value == $reference_value ) == $condition ) {
						(empty( $data_value )) ? $return = false : $return = true;
					}
				}

				return $return;
			}

			/**
			 *
			 */
			public function greaterThanIfNotZero( array $data, $reference ) {
				$data = array_values( $data );
				$data_value = ( isset( $data[0] ) ? $data[0] : null );

				$reference_value = Set::extract( $this->data, $this->name.'.'.$reference );

				$return = true;

				if( $data_value > 0 ) {
					( $data_value < $reference_value ) ? $return = false : $return = true;
				}

				return $return;
			}

			/**
			 *
			 */
			public function compareDates( array $data, $reference, $comparator ) {
				$data = array_values( $data );

				$data_value = strtotime( isset( $data[0] ) ? $data[0] : null  );
				$reference_value = strtotime( Set::extract( $this->data, $this->alias.'.'.$reference ) );

				if( empty( $reference_value ) || empty( $data_value ) ) {
					return true;
				}

				if( in_array( $comparator, array( '>', '<', '==', '<=', '>=' ) ) ) {
					if( !( eval( "return \$data_value $comparator \$reference_value ;" ) ) ) {
						return false;
					}
				}
				else {
					return false;
				}
				return true;
			}

			/**
			 * Validate that a number is in specified range.
			 * if $lower and $upper are not set, will return true if
			 * $check is a legal finite on this platform
			 * FIXME: signature + retour
			 *
			 * @param string $check Value to check
			 * @param integer $lower Lower limit
			 * @param integer $upper Upper limit
			 * @return boolean Success
			 * @access public
			 */
			public function inclusiveRange( $check, $lower = null, $upper = null ) {
				$return = true;
				foreach( $check as $field => $value ) {
					if( isset( $lower ) && isset( $upper ) ) {
						$return = ( $value >= $lower && $value <= $upper ) && $return;
					}
				}
				return $return;
			}

			/**
			 * Règle de validation équivalent à un index unique sur plusieurs colonnes.
			 *
			 * public $validate = array(
			 * 'name' => array(
			 * 		array(
			 * 			'rule' => array( 'checkUnique', array( 'name', 'modeletypecourrierpcg66_id' ) ),
			 * 			'message' => 'Cet intitulé de pièce est déjà utilisé avec ce modèle de courrier.'
			 * 		)
			 * 	),
			 * 	'modeletypecourrierpcg66_id' => array(
			 * 		array(
			 * 			'rule' => array( 'checkUnique', array( 'name', 'modeletypecourrierpcg66_id' ) ),
			 * 			'message' => 'Ce modèle de courrier est déjà utilisé avec cet intitulé de pièce.'
			 * 		),
			 * 	)
			 * );
			 *
			 * @param type $data
			 * @param type $fields
			 * @return boolean
			 */
			public function checkUnique( $data, $fields ) {
				if( !is_array( $fields ) ) {
					$fields = array( $fields );
				}

				$availableFields = array_keys( $this->data[$this->alias] );

				$allFieldsInThisData = true;
				foreach( $fields as $field ) {
					if( !in_array( $field, $availableFields ) ) {
						$allFieldsInThisData = false;
					}
				}

				// A°) Tous les fields sont dans this->data
				if( $allFieldsInThisData ) {
					$querydata = array( 'conditions' => array( ), 'recursive' => -1, 'contain' => false );
					foreach( $fields as $field ) {
						$querydata['conditions']["{$this->alias}.{$field}"] = $this->data[$this->alias][$field];
					}

					// 1°) Pas l'id -> SELECT COUNT(*) FROM table WHERE name = XXX and modeletypecourrierpcg66_id = XXXX == 0
					// 2°) On a l'id
					if( isset( $this->data[$this->alias][$this->primaryKey] ) && !empty( $this->data[$this->alias][$this->primaryKey] ) ) {
						// SELECT COUNT(*) FROM table WHERE name = XXX and modeletypecourrierpcg66_id = XXXX AND id <> XXXX == 0
						$querydata['conditions']["{$this->alias}.{$this->primaryKey} <>"] = $this->data[$this->alias][$this->primaryKey];
					}

					return ( $this->find( 'count', $querydata ) == 0 );
				}

				// B°) On n'a pas tous les fields dans this->data ... FIXME -> throw_error ou réfléchir ?
				return false;
			}

			// *****************************************************************
			// fin règles de validation supplémentaires 1.2
			// *****************************************************************
		}
	}
	// CakePHP 2.x
	else {
		App::uses( 'Model', 'Model' );
		App::uses( 'Sanitize', 'Utility' );

		class AppBaseModel extends Model
		{
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
						$linkedModels += array_keys( Set::normalize( $contains ) );
					}

					if( !empty( $linkedModels ) ) {
						foreach( $linkedModels as $linkedModel ) {
							$linkedModel = ClassRegistry::init( $linkedModel );
							if( !empty( $linkedModel->virtualFields ) ) {
								$replacements = array();
								$replacementsFields = array();

								foreach( $linkedModel->virtualFields as $fieldName => $query ) {
									$regex = "/(?<!\.)(?<!\w)({$linkedModel->alias}\.){0,1}{$fieldName}(?!\w)/";
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
		}
	}

	class AppModel extends AppBaseModel
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

		/**
		 * Surcharge de la méthode saveAll: on retourne un booléen true / false dans tous les cas.
		 * En CakePHP 2.0, les options ne sont pas les mêmes, donc on reprend les options par défaut de
		 * CakePHP 1.2.
		 *
		 * @param array $data
		 * @param array $options
		 * @return boolean
		 */
		public function saveAll( $data = NULL, $options = array( ) ) {
			$options = array_merge( array( 'validate' => true, 'atomic' => true ), $options );

			$return = parent::saveAll( $data, $options );

			if( is_array( $return ) && !empty( $return ) ) {
				$return = Set::flatten( $return );
				$return = ( array_sum( $return ) == count( $return ) );
			}
			else if( is_array( $return ) && empty( $return ) ) {
				return false;
			}
			return $return;
		}

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
					return '( Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' ) /*OR ( Situationdossierrsa.etatdosrsa = \'Z\' ) */ )'; ///FIXME: passage de OR à AND car les dossiers à Z mais non présents dans le code insee apparaissaient !!!!!!!
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
			if( !$this->Behaviors->attached( 'Pgsqlcake.Schema' ) ) {
				$this->Behaviors->attach( 'Pgsqlcake.Schema' );
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
			$ds = $this->getDataSource();

			if( isset( $ds->config['datasource'] ) ) {
				$driver = $ds->config['datasource'];
			}
			else if( isset( $ds->config['driver'] ) ) {
				$driver = $ds->config['driver'];
			}

			return strtolower( str_replace( 'Database/', '', $driver ) );
		}
	}
?>