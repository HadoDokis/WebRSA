<?php
	class ValidateTranslateBehavior extends ModelBehavior
	{
		/**
		* Contains configuration settings for use with individual model objects.  This
		* is used because if multiple models use this Behavior, each will use the same
		* object instance.  Individual model settings should be stored as an
		* associative array, keyed off of the model name.
		*
		* @var array
		* @access public
		* @see Model::$alias
		*/

		var $settings = array();

		/**
		* Setup this behavior with the specified configuration settings.
		*
		* @param object $model Model using this behavior
		* @param array $settings Configuration settings for $model
		* @access public
		*/

		function setup( &$model, $settings ) {
			if (!isset($this->settings[$model->alias])) {
				$this->settings[$model->alias] = array();
			}
			$this->settings[$model->alias] = array_merge($this->settings[$model->alias], (array) $settings);
		}

		/**
		* Before validate callback, translate validation messages
		*
		* @param object $model Model using this behavior
		* @return boolean True if validate operation should continue, false to abort
		* @access public
		*/

		function beforeValidate( &$model ) {
			$modelDomain = Set::classicExtract( $this->settings, "{$model->alias}.domain" );

            if( is_array( $model->validate ) ) {
                foreach( $model->validate as $field => $rules ) {
                    foreach( $rules as $key => $rule ) {
                        if( empty( $model->validate[$field][$key]['message'] ) ) {
                            $validateRule = $model->validate[$field][$key]['rule'];
							if( is_array( $validateRule ) ) {
								$ruleName = $validateRule[0];
								$ruleParams = array_slice( $validateRule, 1 );
							}
							else {
								$ruleName = $validateRule;
								$ruleParams = array();
							}

                            $model->validate[$field][$key]['message'] = "Validate::{$ruleName}";

							$ruleDomain = Set::classicExtract( $rule, 'domain' );
							if( !empty( $ruleDomain ) ) {
								$domain = $ruleDomain;
							}
							else if( !empty( $modelDomain ) ) {
								$domain = $modelDomain;
							}
							else {
								$domain = null;
							}

							if( empty( $domain ) ) {
								$sprintfParams = Set::merge( array( __( $model->validate[$field][$key]['message'], true ) ), $ruleParams );
							}
							else {
								$sprintfParams = Set::merge( array( __d( $domain, $model->validate[$field][$key]['message'], true ) ), $ruleParams );
							}
							$model->validate[$field][$key]['message'] = call_user_func_array( 'sprintf', $sprintfParams );
                        }
                    }
                }
            }

			return true;
		}
	}
?>