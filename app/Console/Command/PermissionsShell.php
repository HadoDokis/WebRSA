<?php
	/**
	 * Fichier source de la classe PermissionsShell.
	 *
	 * PHP 5.3
	 *
	 * @package app.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'XShell', 'Console/Command' );
	App::uses( 'Controller', 'Controller' );
	App::uses( 'AppController', 'Controller' );
	App::uses( 'Component', 'Controller/Component' );
	App::uses( 'AclComponent', 'Controller/Component' );
	App::uses( 'DbdroitsComponent', 'Controller/Component' );
	App::uses( 'ComponentCollection', 'Controller' );

	/**
	 * La classe PermissionsShell permet de réparer et de compléter les tables
	 * aros et acos.
	 *
	 * @package app.Console.Command
	 */
	class PermissionsShell extends XShell
	{
		/**
		 * Modèles utilisés par ce shell
		 *
		 * @var array
		 */
		public $uses = array( );

		/**
		 * La collection de composants utilisée pour pouvoir instancier
		 * proprement les composants Acl et Dbdroits.
		 *
		 * @var ComponentCollection
		 */
		public $ComponentCollection = null;

		/**
		 * Le composant Acl que l'on va utiliser.
		 *
		 * @var AclComponent
		 */
		public $Acl = null;

		/**
		 * Le composant Dbdroits que l'on va utiliser.
		 *
		 * @var DbdroitsComponent
		 */
		public $Dbdroits = null;

		/**
		 * Paramètres par défaut pour ce shell
		 *
		 * @var array
		 */
		public $defaultParams = array(
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false,
		);

		/**
		 * Initialisation du shell.
		 */
		public function initialize() {
			parent::initialize();

			$this->ComponentCollection = new ComponentCollection();
			$this->Acl = new AclComponent( $this->ComponentCollection );
			$this->Dbdroits = new DbdroitsComponent( $this->ComponentCollection );
		}

		/**
		 * Affiche l'en-tête du shell
		 */
		public function _welcome() {
			$this->out();
			$this->out( 'Shell de nettoyage et de remise en forme des droits' );
			$this->out();
			$this->hr();
		}


		/**
		 * Méthode principale: mise à jour de la table acos, récupération des
		 * arbres des aros et des acos.
		 */
		public function main() {
			$this->out( 'Mise à jour de la table acos' );
			$this->Dbdroits->majActions();

			$this->out( 'Récupération de l\'arbre des aros' );
			$this->Acl->Aro->recover( 'parent', null );

			$this->out( 'Récupération de l\'arbre des acos' );
			$this->Acl->Aco->recover( 'parent', null );

			$this->_stop( 0 );
		}
	}
?>