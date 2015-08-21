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
	App::uses( 'DefaultFormHelper', 'Default.View/Helper' );

	/**
	 * La classe DefaultFormHelper étend la classe FormHelper de CakePHP
	 * dans le cadre de son utilisation dans le plugin Default.
	 *
	 * @package Default
	 * @subpackage View.Helper
	 */
	class ConfigurableQueryFormHelper extends DefaultFormHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'DefaultData' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryData'
			),
			'Html',
		);

		/**
		 * Réalise la traduction d'un label en utilisant la fontion __m() du
		 * plugin MultiDomainTranslator.
		 *
		 * @param string $fieldName
		 * @param string $text
		 * @param array $options
		 * @return string
		 */
		public function label( $fieldName = null, $text = null, $options = array( ) ) {
			return parent::label( $fieldName, $text === null ? $text : __m( $text ), $options );
		}
	}
?>