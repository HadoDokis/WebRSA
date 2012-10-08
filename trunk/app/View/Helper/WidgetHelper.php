<?php
	class WidgetHelper extends AppHelper
	{
		public $helpers = array( 'Xhtml', 'Html', 'Session', 'Form' );

		// --------------------------------------------------------------------

		public function booleanRadio( $fieldName, $attributes = array() ) {
			$error = Set::classicExtract( $this->Form->validationErrors, $fieldName );
			$class = 'radio'.( !empty( $error ) ? ' error' : '' );

			$value = Set::classicExtract( $this->request->data, $fieldName );
			if( !is_null( $value ) && ( ( is_string( $value ) && !in_array( $value, array( 'O', 'N' ) ) && ( strlen( trim( $value ) ) > 0 ) ) || is_bool( $value ) ) ) {
				$this->Form->data = Set::insert( $this->Form->data, $fieldName, ( $value ? 'O' : 'N' ) );
			}

			$ret = '<div class="'.$class.'"><fieldset class="boolean">';
			$ret .= '<legend>'.$attributes['legend'].'</legend>';
			$attributes['legend'] = false;
			$ret .= '<div>'.$this->Form->radio( $fieldName, array( 'O' => 'Oui', 'N' => 'Non' ), $attributes ).'</div>';
			$ret .= ( !empty( $error ) ? '<div class="error-message">'.$error.'</div>' : '' );
			$ret .= '</fieldset></div>';
			return $ret;
		}
	}
?>