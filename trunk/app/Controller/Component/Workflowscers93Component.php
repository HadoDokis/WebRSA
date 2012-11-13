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
		 * Retourne l'id technique de la structure référente à laquelle est liée
		 * l'utilisateur connecté, que ce soit par le lien direct User.structurereferente_id
		 * ou par le lien indirect User.referent_id.
		 *
		 * @param boolean $readReferent Peut-on passer par le lien indirect User.referent_id ?
		 * @param boolean $checkStructurereferente Doit-on s'assurer d'être lié à une structure référente ?
		 * @return integer
		 */
		public function getStructurereferenteId( $readReferent, $checkStructurereferente ) {
			$structurereferente_id = $this->Session->read( 'Auth.User.structurereferente_id' );

			if( $readReferent && empty( $structurereferente_id ) ) {
				$referent_id = $this->Session->read( 'Auth.User.referent_id' );
				if( !empty( $referent_id ) ) {
					$this->Controller->User->Referent->id = $referent_id;
					$structurereferente_id = $this->Controller->User->Referent->field( 'structurereferente_id' );
				}
			}

			if( $checkStructurereferente && empty( $structurereferente_id ) ) {
				$this->Session->setFlash( 'L\'utilisateur doit etre rattaché à une structure référente.', 'flash/error' );
				$this->Controller->cakeError( 'error403' );
			}

			return $structurereferente_id;
		}
	}
?>