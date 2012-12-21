<?php
	/**
	 * Code source de la classe Workflowscers93Component.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * Classe Workflowscers93Component.
	 *
	 * @package app.Controller.Component
	 */
	class Workflowscers93Component extends Component
	{
		/**
		 * Contrôleur utilisant ce component.
		 *
		 * @var Controller
		 */
		public $Controller = null;

		/**
		 * Paramètres de ce component
		 *
		 * @var array
		 */
		public $settings = array( );

		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array( 'Session' );

		/**
		 *
		 * @var string
		 */
		protected $_assertErrorTemplate = 'L\'utilisateur doit etre %s pour pouvoir accéder à cette fonctionnalité.';

		/**
		 * Initialisation du component.
		 *
		 * @param Controller $controller Controller with components to initialize
		 * @return void
		 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
		 */
		public function initialize( Controller $controller ) {
			parent::initialize( $controller );
			$this->Controller = $controller;
		}

		/**
		 * Permet de récupérer la structure référente à laquelle est attaché
		 * l'utilisateur connecté.
		 *
		 * @param boolean $required Doit-on retourner une erreur 403 lorsque
		 *	l'utilisateur n'est pas attaché à une structure référente ?
		 * @return integer
		 * @throws Error403Exception
		 */
		public function getUserStructurereferenteId( $required = true ) {
			// Si l'utilisateur est directement lié à une structure référente
			$structurereferente_id = $this->Session->read( 'Auth.User.structurereferente_id' );

			// Si l'utilisateur est indirectement lié, via un référent
			if( empty( $structurereferente_id ) ) {
				$referent_id = $this->Session->read( 'Auth.User.referent_id' );
				if( !empty( $referent_id ) ) {
					$this->Controller->User->Referent->id = $referent_id;
					$structurereferente_id = $this->Controller->User->Referent->field( 'structurereferente_id' );
				}
			}

			// S'il est obligatoire d'être rattaché à une structure référente
			if( $required && empty( $structurereferente_id ) ) {
				$this->Session->setFlash( 'L\'utilisateur doit etre rattaché à une structure référente.', 'flash/error' );
				throw new Error403Exception( null );
			}

			return $structurereferente_id;
		}

		/**
		 * Permet de s'assurer que l'utilisateur connecté soit bien un CPDV.
		 *
		 * @throws Error403Exception
		 */
		public function assertUserCpdv() {
			if( $this->Session->read( 'Auth.User.type' ) !== 'externe_cpdv' ) {
				$this->Session->setFlash( sprintf( $this->_assertErrorTemplate, 'un responsable' ), 'flash/error' );
				throw new error403Exception( null );
			}
		}

		/**
		 * Permet de s'assurer que l'utilisateur connecté soit bien un CI.
		 *
		 * @throws Error403Exception
		 */
		public function assertUserCi() {
			if( $this->Session->read( 'Auth.User.type' ) !== 'externe_ci' ) {
				$this->Session->setFlash( sprintf( $this->_assertErrorTemplate, 'un chargé d\'insertion' ), 'flash/error' );
				throw new error403Exception( null );
			}
		}

		/**
		 * Permet de s'assurer que l'utilisateur connecté soit bien un utilisateur CG.
		 *
		 * @throws Error403Exception
		 */
		public function assertUserCg() {
			if( $this->Session->read( 'Auth.User.type' ) !== 'cg' ) {
				$this->Session->setFlash( sprintf( $this->_assertErrorTemplate, 'un utilisateur du conseil général' ), 'flash/error' );
				throw new error403Exception( null );
			}
		}

		/**
		 * Permet de s'assurer que l'utilisateur connecté soit bien un externe
		 * (CPDV ou un CI).
		 *
		 * @throws Error403Exception
		 */
		public function assertUserExterne() {
			if( !in_array( $this->Session->read( 'Auth.User.type' ), array( 'externe_cpdv', 'externe_ci' ) ) ) {
				$this->Session->setFlash( sprintf( $this->_assertErrorTemplate, 'un responsable ou un chargé d\'insertion' ), 'flash/error' );
				throw new error403Exception( null );
			}
		}
	}
?>