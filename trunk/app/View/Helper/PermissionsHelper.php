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
	 * La classe PermissionsHelper permet de vérifier les droits d'accès de
	 * l'utilisateur.
	 *
	 * @package app.View.Helper
	 */
	class PermissionsHelper extends AppHelper
	{
		/**
		 * Helpers utilisés par ce helper.
		 *
		 * @var array
		 */
		public $helpers = array( 'Session' );

		/**
		 * Le chemin vers les données "Acl" dans la session.
		 *
		 * @var string
		 */
		public $sessionKey = 'Auth.Permissions';

		/**
		 * Vérifie les droits d'accès à un couple controller/action par-rapport
		 * aux droits stockés en session.
		 *
		 * Ex: si on dans la session, sous la clé "Auth.Permissions":
		 * <pre>
		 * [Module:Users] => false
		 * [Users:index] => true
		 * [Users:add] => false
		 * </pre>
		 * alors
		 * <pre>
		 * $this->check( 'users', 'index' ); // vaudra true
		 * $this->check( 'users', 'add' ); // vaudra false
		 * $this->check( 'users', 'view' ); // vaudra false
		 * </pre>
		 *
		 * @see PermissionsHelper::sessionKey
		 *
		 * @param string $controller
		 * @param string $action
		 * @return boolean
		 */
		public function check( $controller, $action ) {
			$controller = Inflector::camelize( $controller );
			$action = strtolower( $action );

			$permissions = $this->Session->read( $this->sessionKey );

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
