<?php
	/**
	 * Fichier source de la classe PermissionsHelper.
	 *
	 * PHP 5.3
	 *
	 * @package app.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe PermissionsHelper ...
	 *
	 * @package app.View.Helper
	 */
	class PermissionsHelper extends AppHelper
	{
		public $helpers = array( 'Session' );

		// INFO: utiliser grep -R "permissions->" * dans le dossier views pour voir ce qui est déjà traité
		/**
			FIXME: http://localhost/adullact/webrsa/adressesfoyers/edit/1
			avec
				[Module:Adressefoyers] =>
				[Adressefoyers:index] => 1
				[Adressefoyers:view] => 1
		*/

		public function check( $controller, $action ) {
			$controller = Inflector::camelize( $controller );
			$action = strtolower( $action );

			$permissions = $this->Session->read( 'Auth.Permissions' );

			$returnController = Set::extract( $permissions, "Module:{$controller}" );
			$returnAction = Set::extract( $permissions, $controller.':'.$action );

			if( $returnAction !== null ) {
				return $returnAction;
			}
			else if( $returnController !== null ) {
				return $returnController;
			}
			else {
				return false;
			}
		}
	}
?>
