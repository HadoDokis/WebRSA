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
		/**
		*
		*/

		function setup( &$Model, $settings ) {
			if( !empty( $Model->enumFields ) ) {
				foreach( $Model->enumFields as $field ) {
					$options = $this->enumOptions( $Model, $field );
					$Model->validate[$field][] = array(
						'rule' => array( 'inList', $options ),
						// FIXME: message
						'message' => sprintf( __( 'Veuillez entrer une valeur parmi %s', true ), implode( ', ', $options ) ),
						'allowEmpty' => true
					);
				}
			}
		}

		/**
		* Fetches the enum type options for a specific field
		*
		* @param string $field
		* @return void
		* @access public
		*/

		function enumOptions( $model, $field ) {
			$cacheKey = $model->alias . '_' . $field . '_enum_options';
			$options = Cache::read($cacheKey);

			if (!$options) {
				$options = false;
				$conn = ConnectionManager::getInstance();
				switch( $conn->config->{$model->useDbConfig}['driver'] ) {
					case 'postgres':
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
						break;
					case 'mysql':
					case 'mysqli':
						$sql = "SHOW COLUMNS FROM `{$model->useTable}` LIKE '{$field}'";
						$enumData = $model->query($sql);
						if(!empty($enumData)) {
							$patterns = array('enum(', ')', '\'');
							$enumData = r($patterns, '', $enumData[0]['COLUMNS']['Type']);
							$options = explode(',', $enumData);
						}
						break;
				}

				Cache::write($cacheKey, $options);
			}

			return $options;
		}

		/**
		* Fetches the enum type for all the $enumFields of the model
		*
		* @param array $fields
		* @return array
		* @access public
		*/

		function allEnumOptions( $model, $domain = 'default' ) {
			$options = array();
			if( !empty( $model->enumFields ) ) {
				foreach( $model->enumFields as $field ) {
					$options[$field] = array();
					$tmpOptions = self::enumOptions( $model, $field );
					foreach( $tmpOptions as $key ) {
						// FIXME: dans la fonction enumOptions
						$msgid = 'ENUM::'.strtoupper( $field ).'::'.$key;
						if( empty( $domain ) || ( $domain == 'default' ) ) {
							$options[$field][$key] = __( $msgid, true );
						}
						else {
							$options[$field][$key] = __d( 'dsp', $msgid, true );
						}
					}
				}
			}
			return $options;
		}
	}
?>