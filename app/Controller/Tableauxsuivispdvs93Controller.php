<?php
	/**
	 * Code source de la classe Tableauxsuivispdvs93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Tableauxsuivispdvs93Controller ...
	 *
	 * @package app.Controller
	 */
	class Tableauxsuivispdvs93Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Tableauxsuivispdvs93';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array( 'Workflowscers93' );

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Tableausuivipdv93', 'Structurereferente' );

		public $aucunDroit = array( 'tableau1b3', 'tableau1b4', 'tableau1b5' ); // FIXME

		/**
		 *
		 * @param integer $user_structurereferente_id
		 */
		protected function _setOptions( $user_structurereferente_id ) {
			// TODO: dans le beforeFilter ?
			$years = array_reverse( range( 2009, date( 'Y' ) ) );
			$options = array(
				'Search' => array(
					'annee' => array_combine( $years, $years ),
					'structurereferente_id' => $this->Structurereferente->find( 'list' ) // TODO: cache + conditions
				),
				'problematiques' => $this->Tableausuivipdv93->problematiques(),
				'acteurs' => $this->Tableausuivipdv93->acteurs(),
			);
			$userIsCg = empty( $user_structurereferente_id );
			$this->set( compact( 'options', 'userIsCg' ) );
		}

		/**
		 * Moteur de recherche pour le tableau 1 B3: Problématiques des bénéficiaires de l'opération
		 */
		public function tableau1b3() {
			$user_structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId( false );

			if( !empty( $this->request->data ) ) {
				$search = $this->request->data;

				if( !empty( $user_structurereferente_id ) ) {
					$search = Hash::insert( $search, 'Search.structurereferente_id', $user_structurereferente_id );
				}

				$this->set( 'results', $this->Tableausuivipdv93->tableau1b3( $search ) );
			}

			$this->_setOptions( $user_structurereferente_id );
		}

		/**
		 * Moteur de recherche pour le tableau 1 B4: Prescriptions vers les acteurs
		 * sociaux, culturels et de sante
		 */
		public function tableau1b4() {
			$user_structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId( false );

			if( !empty( $this->request->data ) ) {
				$search = $this->request->data;

				if( !empty( $user_structurereferente_id ) ) {
					$search = Hash::insert( $search, 'Search.structurereferente_id', $user_structurereferente_id );
				}

				$this->set( 'results', $this->Tableausuivipdv93->tableau1b4( $search ) );
			}

			$this->_setOptions( $user_structurereferente_id );
		}

		/**
		 * Moteur de recherche pour le tableau 1 B4: Prescriptions vers les acteurs
		 * sociaux, culturels et de sante
		 */
		public function tableau1b5() {
			$user_structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId( false );

			if( !empty( $this->request->data ) ) {
				$search = $this->request->data;

				if( !empty( $user_structurereferente_id ) ) {
					$search = Hash::insert( $search, 'Search.structurereferente_id', $user_structurereferente_id );
				}

				$this->set( 'totaux', $this->Tableausuivipdv93->tableau1b5totaux( $search ) );
				$this->set( 'results', $this->Tableausuivipdv93->tableau1b5( $search ) );
			}

			$this->_setOptions( $user_structurereferente_id );
		}
	}
?>
