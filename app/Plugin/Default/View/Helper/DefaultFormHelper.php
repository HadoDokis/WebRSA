<?php
	/**
	 * Code source de la classe DefaultFormHelper.
	 *
	 * PHP 5.4
	 *
	 * @package Default
	 * @subpackage View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'FormHelper', 'View/Helper' );

	/**
	 * La classe DefaultFormHelper étend la classe FormHelper de CakePHP
	 * dans le cadre de son utilisation dans le plugin Default.
	 *
	 * @package Default
	 * @subpackage View.Helper
	 */
	class DefaultFormHelper extends FormHelper
	{
		/**
		 *
		 * @param array $buttons
		 * @return string
		 */
		public function buttons( array $buttons ) {
			$return = null;

			if( !empty( $buttons ) ) {
				$submit = '';

				foreach( Hash::normalize( $buttons ) as  $buttonName => $buttonParams ) {
					$buttonLabel = ( isset( $buttonParams['label'] ) && !empty( $buttonParams['label'] ) ? $buttonParams['label'] : __( $buttonName ) );
					$submit .= $this->submit( $buttonLabel, array( 'div' => false, 'name' => $buttonName ) );
				}

				$return = $this->Html->tag( 'div', $submit, array( 'class' => 'submit' ) );
			}

			return $return;
		}
	}
?>