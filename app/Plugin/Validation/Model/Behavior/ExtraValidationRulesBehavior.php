<?php
	/**
	 * Source file for the Validation.ExtraValidationRulesBehavior behavior class.
	 *
	 * PHP 5.3
	 *
	 * @package Validation
	 * @subpackage Model.Behavior
	 */
	App::uses( 'ExtraBasicValidationRulesBehavior', 'Validation.Model/Behavior' );

	/**
	 * Validation.ExtraValidationRulesBehavior behavior class.
	 *
	 * Cette classe ajout plusieurs règles de validation.
	 *
	 * @fixme: alphaNumeric
	 *
	 * @package Validation
	 * @subpackage Model.Behavior
	 */
	class Validation.ExtraValidationRulesBehavior extends ExtraBasicValidationRulesBehavior
	{
		/**
		 * Permet de s'assurer qu'une chaîne est d'une longueur donnée.
		 *
		 * @param Model $Model
		 * @param mixed $check
		 * @param integer $length
		 * @return boolean
		 */
		public function exactLength( Model $Model, $check, $length ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$result = true;
			foreach( Set::normalize( $check ) as $value ) {
				$result = ( strlen( $value ) == $length ) && $result;
			}
			return $result;
		}

		/**
		 * Vérification que la date saisie est postérieure ou égale à celle du jour
		 *
		 * @todo transformer en dateFuture
		 *
		 * @param Model $Model
		 * @param mixed $check
		 * @return boolean
		 */
		public function futureDate( Model $Model, $check ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$return = true;
			foreach( $check as $field => $value ) {
				$return = ( strtotime( $value ) >= strtotime( date( 'Y-m-d' ) ) ) && $return;
			}

			return $return;
		}

		/**
		 * Vérification que la date saisie est antérieure ou égale à celle du jour.
		 *
		 * @param Model $Model
		 * @param mixed $check
		 * @return boolean
		 */
		public function datePassee( Model $Model, $check ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$return = true;
			foreach( $check as $field => $value ) {
				$return = ( strtotime( $value ) <= strtotime( date( 'Y-m-d' ) ) ) && $return;
			}

			return $return;
		}

		/**
		 * Vérification que la valeur soir celle d'un numéro de téléphone
		 * français (sur 10 chiffres).
		 *
		 * @param Model $Model
		 * @param array $check
		 * @return boolean
		 */
		public function phoneFr( Model $Model, $check ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$return = true;
			foreach( $check as $field => $value ) {
				$value = preg_replace( array( '/\./', '/ /' ), array(), $value );
				$return = preg_match( '/^([0-9]{10})$/', $value ) && $return;
			}

			return $return;
		}

		/**
		 * Vérifie que l'ensemble des champs soit vide.
		 *
		 * @todo $reference2, ....
		 *
		 * @param Model $Model
		 * @param array $check
		 * @param string $reference
		 * @return boolean
		 */
		public function allEmpty( Model $Model, $check, $reference ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$check = array_values( $check );
			$value = ( isset( $check[0] ) ? $check[0] : null );

			$reference = Set::extract( $Model->data, $Model->name.'.'.$reference );

			return ( empty( $value ) == empty( $reference )  );
		}

		/**
		 * Exemple: 'dateentreeemploi' => notEmptyIf( $check, 'activitebeneficiaire', true, array( 'P' ) )
		 *
		 * @param Model $Model
		 * @param array $check
		 * @param string $reference
		 * @param type $condition
		 * @param type $values
		 * @return boolean
		 */
		public function notEmptyIf( Model $Model, $check, $reference, $condition, $values ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$check = array_values( $check );
			$check_value = ( isset( $check[0] ) ? $check[0] : null );

			$reference_value = Set::extract( $Model->data, $Model->name.'.'.$reference );

			$return = true;

			foreach($values as $value) {
				if ( ( $value == $reference_value ) == $condition ) {
					(empty($check_value)) ? $return = false : $return = true;
				}
			}

			return $return;
		}

		/**
		 *
		 * @param Model $Model
		 * @param array $check
		 * @param string $reference
		 * @return boolean
		 */
		public function greaterThanIfNotZero( Model $Model, $check, $reference ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$check = array_values( $check );
			$check_value = ( isset( $check[0] ) ? $check[0] : null );

			$reference_value = Set::extract( $Model->data, $Model->name.'.'.$reference );

			$return = true;
			if ( $check_value > 0 ) {
				( $check_value < $reference_value ) ? $return = false : $return = true;
			}

			return $return;
		}

		/**
		 *
		 * @param Model $Model
		 * @param type $check
		 * @param type $reference
		 * @param type $comparator
		 * @return boolean
		 */
		public function compareDates( Model $Model, $check, $reference, $comparator ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$check = array_values( $check );

			$check_value = strtotime( isset( $check[0] ) ? $check[0] : null );
			$reference_value = strtotime( Set::extract( $Model->data, $Model->alias.'.'.$reference ) );

			if( empty( $reference_value ) || empty( $check_value ) ) {
				return true;
			}

			if ( in_array( $comparator, array( '>', '<', '==', '<=', '>=' ) ) ) {
				if ( !( eval( "return \$check_value $comparator \$reference_value ;" ) ) ) {
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
		 *
		 * @param Model $Model
		 * @param type $check Value to check
		 * @param type $lower Lower limit
		 * @param type $upper Upper limit
		 * @return boolean
		 */
		public function inclusiveRange( Model $Model, $check, $lower = null, $upper = null ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$return = true;
			foreach( $check as $field => $value ) {
				if( isset( $lower ) && isset( $upper ) ) {
					$return = ( $value >= $lower && $value <= $upper ) && $return;
				}
			}
			return $return;
		}
	}
?>