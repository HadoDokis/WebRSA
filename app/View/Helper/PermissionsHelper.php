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
		 * La liste des correspondances "comme droit".
		 *
		 * Si on a:
		 * <pre>
		 *	array(
		 *		'Actions:add' => 'Actions:edit',
		 *	)
		 * </pre>
		 * Alors, les droits de ActionsController::add() sont lus dans
		 * ActionsController::edit().
		 *
		 * @var array
		 */
		public $commeDroit = array();

		/**
		 * La liste des actions pour lesquelles aucun droit n'est requis (tout
		 * le monde a le droit).
		 *
		 * Exemple:
		 * <pre>
		 *	array(
		 *		'Users::login',
		 *	)
		 * </pre>
		 *
		 * @var array
		 */
		public $aucunDroit = array();

		/**
		 * Surcharge du constructeur
		 *
		 * @param View $View
		 * @param type $settings
		 */
		public function __construct( View $View, $settings = array( ) ) {
			parent::__construct( $View, $settings );

			$this->commeDroit = ControllerCache::commeDroit();
			$this->aucunDroit = ControllerCache::aucunDroit();
		}

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
		 * @see AppController::_checkPermissions()
		 *
		 * @param string $controllerName
		 * @param string $actionName
		 * @return boolean
		 */
		public function check( $controllerName, $actionName ) {
			$controllerName = Inflector::camelize( $controllerName );

			if( in_array( "{$controllerName}:{$actionName}", $this->aucunDroit ) ) {
				return true;
			}
			else if( isset( $this->commeDroit["{$controllerName}:{$actionName}"] ) ) {
				list( $controllerName, $actionName ) = explode( ':', $this->commeDroit["{$controllerName}:{$actionName}"] );
			}

			$permissionAction = $this->Session->read( "{$this->sessionKey}.{$controllerName}:{$actionName}" );

			if( !is_null( $permissionAction ) ) {
				return $permissionAction;
			}
			else {
				$permissionModule = $this->Session->read( "{$this->sessionKey}.Module:{$controllerName}" );

				if( !is_null( $permissionModule ) ) {
					return $permissionModule;
				}
			}

			return false;
		}
	}
?>
