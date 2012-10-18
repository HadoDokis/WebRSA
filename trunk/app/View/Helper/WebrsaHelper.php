<?php
	/**
	 * Fichier source de la classe WebrsaHelper.
	 *
	 * PHP 5.3
	 *
	 * @package app.View.Helper
	 */

	/**
	 * La classe WebrsaHelper ...
	 *
	 * @package app.View.Helper
	 */
	class WebrsaHelper extends AppHelper
	{
		public $helpers = array( 'Html' );

		/**
		 *
		 */
		public function blocAdresse( $data, $options ) {
			$default = array(
				'separator' => '<br />',
				'options' => array(),
				'alias' => 'Adresse',
				'ville' => false
			);
			$options = array_merge( $default, $options );
			
			$return = Set::classicExtract( $data, "{$options['alias']}.numvoie" )
				.' '.Set::enum( Set::classicExtract( $data, "{$options['alias']}.typevoie" ), $options['options'] )
				.' '.Set::classicExtract( $data, "{$options['alias']}.nomvoie" )
				.$options['separator'].Set::classicExtract( $data, "{$options['alias']}.compladr" )
				.$options['separator'].Set::classicExtract( $data, "{$options['alias']}.complideadr" );
				
			if( $options['ville'] ) {
				$return .= $options['separator'].Set::classicExtract( $data, "{$options['alias']}.codepos" )
						.' '.Set::classicExtract( $data, "{$options['alias']}.locaadr" );
			}

			return $return;
		}
	}
?>