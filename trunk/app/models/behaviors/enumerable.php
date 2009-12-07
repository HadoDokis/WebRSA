<?php
	/**
	* Behavior with useful functionality around models containing an enum type field
	*
	* Copyright (c) Debuggable, http://debuggable.com
	*
	* @package default
	* @access public
	* @url http://www.debuggable.com/posts/How_to_Fetch_the_ENUM_Options_of_a_Field_The_CakePHP_Enumerable_Behavior:4a977c9b-1bdc-44b4-b027-1a54cbdd56cb
	*/

	class EnumerableBehavior extends ModelBehavior
	{
		var $settings = array();

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
						__( $this->settings['validationRule'], true ),
						implode( $this->settings['validationRuleSeparator'], $options )
					),
					'allowEmpty' => $this->settings['validationRuleAllowEmpty']
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
			$default = array(
				// FIXME: en Anglais
				'validationRule' => 'Veuillez entrer une valeur parmi %s',
				'addValidation' => true,
				'validationRuleSeparator' => ', ',
				'validationRuleAllowEmpty' => true
			);
			$this->settings = Set::merge( $default, $settings );

			// Setup fields
			if( !empty( $model->enumFields ) ) {
				$enumFields = array();
				foreach( $model->enumFields as $field => $options ) {
					if( valid_int( $field ) && !empty( $options ) && is_string( $options ) ) {
						$field = $options;
						$options = array();
					}

					$default = array(
						'type' => $field,
						'domain' => strtolower( $model->name )
					);

					$enumFields[$field] = Set::merge( $default, $options );
					$enumFields[$field]['type'] = strtoupper( $enumFields[$field]['type'] );
				}
				$model->enumFields = $enumFields;
			}

			// Add validation rules if needed
			if( $this->settings['addValidation'] && !empty( $model->enumFields ) ) {
				foreach( $model->enumFields as $field => $data ) {
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

				$sql = "SELECT enum_range(null::$enumType);";
				$enumData = $model->query($sql);
				if(!empty($enumData)) {
					$patterns = array( '{', '}' );
					$enumData = r( $patterns, '', Set::extract( $enumData, '0.0.enum_range' ) );
					$options = explode( ',', $enumData );
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
					$domain = $model->enumFields[$field]['domain'];
					$msgid = implode( '::', array( 'ENUM', $model->enumFields[$field]['type'], $key ) );
					if( empty( $domain ) || ( $domain == 'default' ) ) {
						$options[$key] = __( $msgid, true );
					}
					else {
						$options[$key] = __d( $model->enumFields[$field]['domain'], $msgid, true );
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
			if( !empty( $model->enumFields ) ) {
				foreach( $model->enumFields as $field => $data ) {
					$options[$field] = $this->enumList( $model, $field );
				}
			}
			return $options;
		}
	}
?>