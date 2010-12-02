<?php
	/**
	* Gestion des séances d'équipes pluridisciplinaires.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class SeancesepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		/**
		* FIXME: evite les droits
		*/

		public function beforeFilter() {}

		/**
		*
		*/

		protected function _setOptions() {
			/// TODO: plus générique
			$options = Set::merge(
				$this->Seanceep->Dossierep->Saisineepreorientsr93->Nvsrepreorientsr93->enums(),
				$this->Seanceep->Dossierep->Saisineepbilanparcours66->Nvsrepreorient66->enums(),
				$this->Seanceep->enums()
			);
			$options['Seanceep']['ep_id'] = $this->Seanceep->Ep->find( 'list' );
			if( !in_array( $this->action, array( 'add', 'edit' ) ) ) {
				/// TODO: est-ce que ça a  du sens ?
				$options['Seanceep']['typeorient_id'] = $this->Seanceep->Structurereferente->Typeorient->listOptions();
				$options['Seanceep']['structurereferente_id'] = $this->Seanceep->Structurereferente->list1Options();
				$options['Seanceep']['decisionpdo_id'] = $this->Seanceep->Dossierep->Saisineepdpdo66->Nvsepdpdo66->Decisionpdo->find('list');
			}
			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		public function index() {
			$fields = array(
				'Seanceep.id',
				'Ep.name',
				'Seanceep.dateseance',
				'Seanceep.finalisee'
			);
			foreach( $this->Seanceep->Ep->themes() as $theme ) {
				$fields[] = "Ep.{$theme}";
			}

			$this->paginate = array(
				'fields' => $fields,
				'contain' => array(
					'Ep'
				),
				'limit' => 10,
				'order' => array( 'Seanceep.dateseance DESC' )
			);
			
			$seanceseps = $this->paginate( $this->Seanceep );
			
			foreach($seanceseps as &$seanceep) {
				$nbdossiers = $this->Seanceep->Dossierep->find(
					'count',
					array(
						'conditions' => array(
							'Dossierep.seanceep_id' => $seanceep['Seanceep']['id']
						)
					)
				);
				if ($nbdossiers=='0')
					$seanceep['Seanceep']['existe_dossier']=false;
				else
					$seanceep['Seanceep']['existe_dossier']=true;
				
				$seanceep['Seanceep']['cloture'] = $this->Seanceep->clotureSeance($seanceep);
			}

			$this->set( compact( 'seanceseps' ) );
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			if( !empty( $this->data ) ) {
				$this->Seanceep->create( $this->data );
				$success = $this->Seanceep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Seanceep->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Seanceep.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
			}

			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Seanceep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}

		/**
		* Traitement d'une séance à un certain niveau de décision.
		*/

		protected function _traiter( $seanceep_id, $niveauDecision ) {
			$seanceep = $this->Seanceep->find(
				'first',
				array(
					'conditions' => array(
						'Seanceep.id' => $seanceep_id,
					),
					'contain' => array(
						'Ep'
					)
				)
			);

			$this->assert( !empty( $seanceep ), 'error404' );

			if( !empty( $this->data ) ) {
				$this->Seanceep->begin();
				$success = $this->Seanceep->saveDecisions( $seanceep_id, $this->data, $niveauDecision );

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Seanceep->commit();
					$this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Seanceep->rollback();
				}
			}

			$dossiers = $this->Seanceep->dossiersParListe( $seanceep_id, $niveauDecision );

			if( empty( $this->data ) ) {
				$this->data = $this->Seanceep->prepareFormData( $seanceep_id, $dossiers, $niveauDecision );
			}

			$this->set( compact( 'seanceep', 'dossiers' ) );
			$this->_setOptions();
		}

		/**
		* Traitement d'une séance au niveau de décision EP
		*/

		public function traiterep( $seanceep_id ) {
			$this->_traiter( $seanceep_id, 'ep' );
		}

		/**
		*
		*/

		protected function _finaliser( $seanceep_id, $niveauDecision ) {
			$this->Seanceep->begin();
			$success = $this->Seanceep->finaliser( $seanceep_id, $niveauDecision );

			$this->_setFlashResult( 'Save', $success );
			if( $success ) {
				$this->Seanceep->commit();
			}
			else {
				$this->Seanceep->rollback();
			}
			$this->redirect( array( 'action' => 'index' ) );
		}

		/**
		* Finalisation de la séance au niveau EP
		*/

		public function finaliserep( $seanceep_id ) {
			$this->_finaliser( $seanceep_id, 'ep' );
		}

		/**
		* Traitement d'une séance au niveau de décision CG
		* TODO: les dossiers qui ne doivent pas être traités par le CG ne doivent pas apparaître ici
		* TODO: si tous les thèmes se décident niveau EP, plus besoin de passer par ici.
		*/

		public function traitercg( $seanceep_id ) {
			$this->_traiter( $seanceep_id, 'cg' );
		}

		/**
		* Finalisation de la séance au niveau CG
		*/

		public function finalisercg( $seanceep_id ) {
			$this->_finaliser( $seanceep_id, 'cg' );
		}
	}
?>
