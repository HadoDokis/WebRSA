<?php
	App::import( 'Core', array( 'Validation' ) );

	class Xvalidation extends Validation
	{
		function &getInstance() {
			static $instance = array( );

			if( !$instance ) {
				$instance[0] = & new Xvalidation();
			}
			return $instance[0];
		}

		function integer( $check ) {
			$_this = & Xvalidation::getInstance();
			$_this->__reset();
			$_this->check = $check;

			if( is_array( $check ) ) {
				$_this->_extract( $check );
			}

			if( empty( $_this->check ) && $_this->check != '0' ) {
				return false;
			}
			$_this->regex = '/^[0-9]+$/mu';
			return $_this->_check();
		}

		function string( $check ) {
			$_this = & Xvalidation::getInstance();
			$_this->__reset();
			$_this->check = $check;

			if( is_array( $check ) ) {
				$_this->_extract( $check );
			}

			if( empty( $_this->check ) && $_this->check != '0' ) {
				return false;
			}

			return is_string( $check );
		}

		function boolean( $check ) {
			$_this = & Xvalidation::getInstance();
			$_this->__reset();
			$_this->check = $check;

			if( is_array( $check ) ) {
				$_this->_extract( $check );
			}

			if( empty( $_this->check ) && $_this->check != '0' ) {
				return false;
			}

			return is_bool( $check );
		}

		function isarray( $check ) {
			$_this = & Xvalidation::getInstance();
			$_this->__reset();
			$_this->check = $check;

			if( is_array( $check ) ) {
				$_this->_extract( $check );
			}

			if( empty( $_this->check ) && $_this->check != '0' ) {
				return false;
			}

			return is_array( $check );
		}

		function dir( $check ) {
			$_this = & Xvalidation::getInstance();
			$_this->__reset();
			$_this->check = $check;

			if( is_array( $check ) ) {
				$_this->_extract( $check );
			}

			if( empty( $_this->check ) && $_this->check != '0' ) {
				return false;
			}

			return is_dir( $check ) && is_readable( $check );
		}

		function writableDir( $check ) {
			$_this = & Xvalidation::getInstance();
			$_this->__reset();
			$_this->check = $check;

			if( is_array( $check ) ) {
				$_this->_extract( $check );
			}

			if( empty( $_this->check ) && $_this->check != '0' ) {
				return false;
			}

			return is_dir( $check ) && is_writable( $check );
		}

		function _check() {
			$_this = & Xvalidation::getInstance();
			if( preg_match( $_this->regex, $_this->check ) ) {
				$_this->error[] = false;
				return true;
			}
			else {
				$_this->error[] = true;
				return false;
			}
		}

		/**
		 * Modification de la méthode de CakePHP 1.2.11.
		 * Accepte localhost en plus en TLD.
		 *
		 * @see http://cakephp.lighthouseapp.com/projects/42648/tickets/2544-url-validation-fails-on-localhost
		 * @see http://www.w3.org/Addressing/URL/url-spec.txt
		 *
		 * @param mixed $check
		 * @param boolean $strict
		 * @return boolean
		 */
		function url( $check, $strict = false ) {
			$_this =& Xvalidation::getInstance();
			$_this->check = $check;
			$validChars = '([' . preg_quote('!"$&\'()*+,-.@_:;=') . '\/0-9a-z]|(%[0-9a-f]{2}))';
			$_this->regex = '/^(?:(?:https?|ftps?|file|news|gopher):\/\/)' . (!empty($strict) ? '' : '?') .
				'(?:' . $_this->__pattern['ip'] . '|' . $_this->__pattern['hostname'] .'|localhost' .')(?::[1-9][0-9]{0,3})?' .
				'(?:\/?|\/' . $validChars . '*)?' .
				'(?:\?' . $validChars . '*)?' .
				'(?:#' . $validChars . '*)?$/i';
			return $_this->_check();
		}
	}
?>