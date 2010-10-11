<?php
	/**
	* Behavior with useful functionality around models containing an enum type field
	*
	* Copyright (c) Debuggable, http://debuggable.com
	*
	* @package default
	* @access public
	* @url http://www.debuggable.com/posts/How_to_Fetch_the_ENUM_Options_of_a_Field_The_CakePHP_Enumerable_Behavior:4a977c9b-1bdc-44b4-b027-1a54cbdd56cb
    *
    *  Requête permettant d'obtenir la liste des types d'ENUM utilisés en base de données:
    *   SELECT DISTINCT(udt_name)
    *    FROM information_schema.columns
    *    WHERE table_catalog = 'cg66_newapre'
    *        AND table_schema = 'public'
    *        AND udt_name ILIKE 'type_%'
    *    ORDER BY udt_name ASC;
    *
    *  Affichage des valeurs des enum -->  SELECT enum_range(null::type_...);
    *
    *
	*/

	class EnumerableBehavior extends ModelBehavior
	{
		public $settings = array();

		public $defaultSettings = array(
			// FIXME: en Anglais
			'validationRule' => 'Veuillez entrer une valeur parmi %s',
			'addValidation' => true,
			'validationRuleSeparator' => ', ',
			'validationRuleAllowEmpty' => true,
			'fields' => array()
		);

		/**
		*
		*	Add validation rule for model->field
		*
		*/

		function _addValidationRule( &$model, $field ) {
			$options = $this->enumOptions( $model, $field );
			if( !empty( $options ) ) {
				$model->validate[$field][] = array(
					'rule'		 => array( 'inList', $options ),
					'message'	 => sprintf(
						__( $this->settings[$model->alias]['validationRule'], true ),
						implode( $this->settings[$model->alias]['validationRuleSeparator'], $options )
					),
					'allowEmpty' => $this->settings[$model->alias]['validationRuleAllowEmpty']
				);
			}
		}

		/**
		*
		*	Read settings, add validation rules if needed.
		*
		*/

		function setup( &$model, $settings ) {
			// Setup ... FIXME: case insensitive
			/*$default = array(
				// FIXME: en Anglais
				'validationRule' => 'Veuillez entrer une valeur parmi %s',
				'validationDomain' => 'default',
				'addValidation' => true,
				'validationRuleSeparator' => ', ',
				'validationRuleAllowEmpty' => true
			);*/
			$settings = Set::merge( $this->defaultSettings, $settings );

			if (!isset($this->settings[$model->alias])) {
				$this->settings[$model->alias] = array();
			}

			$settings = Set::normalize( $settings );
			$this->settings[$model->alias] = array_merge(
				$this->settings[$model->alias],
				(array) $settings
			);

			// Setup fields
			if( !empty( $this->settings[$model->alias]['fields'] ) ) {
				$enumFields = array();
				foreach( $this->settings[$model->alias]['fields'] as $field => $options ) {
					if( is_int( $field ) && !empty( $options ) && is_string( $options ) ) {
						$field = $options;
						$options = array();
					}
					else if( is_string( $field ) && is_string( $options ) ) {
						$options = array( 'type' => $options );
					}

					$default = array(
						'type' => $field,
						'domain' => Inflector::underscore( Inflector::variable( $model->name ) )
					);

					$enumFields[$field] = Set::merge( $default, $options );

					/// Load from config
					if( Set::check( $enumFields[$field], 'type' ) ) {
						$config = Configure::read( "Enumerable.{$enumFields[$field]['type']}" );
						if( is_array( $config ) && !empty( $config ) ) {
							$enumFields[$field] = Set::merge( $enumFields[$field], $config );
						}
					}

					$enumFields[$field]['type'] = strtoupper( $enumFields[$field]['type'] );
				}
				$this->settings[$model->alias]['fields'] = $enumFields;
			}

			// Add validation rules if needed
			if( $this->settings[$model->alias]['addValidation'] && !empty( $this->settings[$model->alias]['fields'] ) ) {
				foreach( $this->settings[$model->alias]['fields'] as $field => $data ) {
					$this->_addValidationRule( $model, $field );
				}
			}
		}

		/**
		*	Fetches the enum type options for a specific field for mysql and
		*	mysqli drivers.
		*
		*	@param string $field
		*	@return void
		*	@access public
		*/

		function _mysqlEnumOptions( $model, $field ) {
			$options = false;
			$sql = "SHOW COLUMNS FROM `{$model->useTable}` LIKE '{$field}'";
			$enumData = $model->query($sql);
			if(!empty($enumData)) {
				$patterns = array('enum(', ')', '\'');
				$enumData = r($patterns, '', $enumData[0]['COLUMNS']['Type']);
				$options = explode(',', $enumData);
			}
			return $options;
		}

		/**
		*	Fetches the enum type options for a specific field for postgres
		*	driver.
		*
		*	@param string $field
		*	@return void
		*	@access public
		*/

		function _postgresEnumOptions( $model, $field ) {
			$options = false;
			$sql = "SELECT udt_name FROM information_schema.columns WHERE table_name = '{$model->useTable}' AND column_name = '{$field}';";
			$enumType = $model->query( $sql );
			if(!empty($enumType)) {
				$enumType = Set::extract( $enumType, '0.0.udt_name' );
				if( $enumType != 'text' ) {
					$sql = "SELECT enum_range(null::$enumType);";
					$enumData = $model->query($sql);
					if(!empty($enumData)) {
						$patterns = array( '{', '}' );
						$enumData = r( $patterns, '', Set::extract( $enumData, '0.0.enum_range' ) );
						$options = explode( ',', $enumData );
					}
				}
			}
			return $options;
		}

		/**
		* 	Fetches the enum type options for a specific field
		*
		*	@param string $field
		*	@return void
		*	@access public
		*/

		function enumOptions( $model, $field ) {
			$cacheKey = $model->alias . '_' . $field . '_enum_options';
			$options = Cache::read($cacheKey);

			if( !$options ) {
				$options = Set::extract( $this->settings, "{$model->name}.fields.{$field}.values" );
			}

			if( !$options ) {
				$options = false;
				$conn = ConnectionManager::getInstance();
				$driver = $conn->config->{$model->useDbConfig}['driver'];
				switch( $driver ) {
					case 'postgres':
						$options = $this->_postgresEnumOptions( $model, $field );
						break;
					case 'mysql':
					case 'mysqli':
						$options = $this->_mysqlEnumOptions( $model, $field );
						break;
					default:
						trigger_error( sprintf( __( 'SQL driver (%s) not supported in enumerable behavior.', true ), $driver ), E_USER_WARNING );
				}
// debug($options);
				if( empty( $options ) ) {
					trigger_error( sprintf( __( 'Requête de recherche de type inutile pour le champ (%s).', true ), "{$model->alias}.{$field}" ), E_USER_WARNING );
				}

				Cache::write($cacheKey, $options);
			}

			return $options;
		}

		/**
		*	Fetches the enum translated list for a field
		*/

		function enumList( $model, $field ) {
			$options = array();
			$tmpOptions = self::enumOptions( $model, $field );
			if( !empty( $tmpOptions ) ) {
				foreach( $tmpOptions as $key ) {
					$domain = $this->settings[$model->alias]['fields'][$field]['domain'];
					$msgid = implode( '::', array( 'ENUM', $this->settings[$model->alias]['fields'][$field]['type'], $key ) );
					if( empty( $domain ) || ( $domain == 'default' ) ) {
						$options[$key] = __( $msgid, true );
					}
					else {
						$options[$key] = __d( $this->settings[$model->alias]['fields'][$field]['domain'], $msgid, true );
					}
				}
			}
			return $options;
		}

		/**
		* 	Fetches the enum lists for all the $enumFields of the model
		*/

		function allEnumLists( $model ) {
			$options = array();
			if( !empty( $this->settings[$model->alias]['fields'] ) ) {
				foreach( $this->settings[$model->alias]['fields'] as $field => $data ) {
					$options[$field] = $this->enumList( $model, $field );
				}
			}
			return $options;
		}

		/**
		* 	Fetches the enum lists for all the $enumFields of the model
		*/

		function enums( $model ) {
			$options = array();
			if( !empty( $this->settings[$model->alias]['fields'] ) ) {
				foreach( $this->settings[$model->alias]['fields'] as $field => $data ) {
					$options[$field] = $this->enumList( $model, $field );
				}
			}
			return array( $model->alias => $options );
		}
	}
?>