<?php
	class Decisionstraitementspcgs66Controller extends AppController
	{
		public $name = 'Decisionstraitementspcgs66';
		/**
		* @access public
		*/

		public $components = array( 'Default', 'Gedooo.Gedooo' );
		public $helpers = array( 'Default2', 'Ajax' );
		public $uses = array( 'Decisiontraitementpcg66', 'Option', 'Pdf'  );

		public $commeDroit = array(
			'view' => 'Decisionstraitementspcgs66:index',
			'add' => 'Decisionstraitementspcgs66:edit'
		);

		/**
		*
		*/

		protected function _setOptions() {
			$options = array();
			$options['Decisiontraitementpcg66'] = $this->Decisiontraitementpcg66->allEnumLists();
			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		public function index( $traitementpcg66_id = null ) {
			//Récupération des informations de la personne concernée par le dossier
			$traitementpcg66 = $this->Decisiontraitementpcg66->Traitementpcg66->find(
				'first',
				array(
					'conditions' => array(
						'Traitementpcg66.id' => $traitementpcg66_id
					),
					'contain' => array(
						'Personnepcg66',
						'Descriptionpdo'
					)
				)
			);

			$listeDecisions = $this->Decisiontraitementpcg66->find(
				'all',
				array(
					'conditions' => array(
						'Decisiontraitementpcg66.traitementpcg66_id' => $traitementpcg66_id
					),
					'contain' => false,
					'order' => array(
						'Decisiontraitementpcg66.created DESC'
					)
				)
			);
			$this->set( compact( 'listeDecisions', 'traitementpcg66' ) );

			$this->set( 'urlmenu', '/traitementspcgs66/index/'.$traitementpcg66['Personnepcg66']['personne_id'] );
			$this->_setOptions();
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$this->Decisiontraitementpcg66->begin();

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$traitementpcg66_id = $id;

				$traitementpcg66 = $this->Decisiontraitementpcg66->Traitementpcg66->find(
					'first',
					array(
						'conditions' => array(
							'Traitementpcg66.id' => $traitementpcg66_id
						),
						'contain' => array(
							'Descriptionpdo',
							'Personnepcg66'
						)
					)
				);
				$this->set( compact( 'traitementpcg66' ) );
				$personne_id = Set::classicExtract( $traitementpcg66_id, 'Personnepcg66.personne_id' );
			}
			/*else if( $this->action == 'edit' ) {
				$decisionpersonnepcg66_id = $id;
				$decisionpersonnepcg66 = $this->Decisiontraitementpcg66->findById( $decisionpersonnepcg66_id, null, null, 1 );
				$this->assert( !empty( $decisionpersonnepcg66 ), 'invalidParameter' );

				$personnepcg66_id = Set::classicExtract( $decisionpersonnepcg66, 'Personnepcg66Situationpdo.personnepcg66_id' );
				$personnepcg66 = $this->Decisiontraitementpcg66->Personnepcg66Situationpdo->Personnepcg66->findById( $personnepcg66_id, null, null, -1 );
				$personne_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.personne_id' );
				$dossierpcg66_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.dossierpcg66_id' );
				$dossier_id = $this->Decisiontraitementpcg66->Personnepcg66Situationpdo->Personnepcg66->Personne->dossierId( $personne_id );;
			}*/

			$this->set( 'personnepcg66_id', $traitementpcg66['Personnepcg66']['id'] );
			$this->set( 'dossierpcg66_id', $traitementpcg66['Personnepcg66']['dossierpcg66_id'] );
			$this->set( 'personne_id', $traitementpcg66['Personnepcg66']['personne_id'] );

			if( !empty( $this->data ) ){
				$saved = $this->Decisiontraitementpcg66->save( $this->data );

				///FIXME: à remettre pour gérer les états du dossierpcg66
				if ( $saved ) {
					$saved = $this->Decisiontraitementpcg66->Traitementpcg66->Personnepcg66->Dossierpcg66->updateEtatViaDecisionTraitement( $traitementpcg66['Personnepcg66']['dossierpcg66_id'] ) && $saved;
				}

				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->Decisiontraitementpcg66->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'decisionstraitementspcgs66', 'action' => 'index', $traitementpcg66['Traitementpcg66']['id'] ) );
				}
				else {
					$this->Decisiontraitementpcg66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			elseif( $this->action == 'edit' ){
				$this->data = $decisionpersonnepcg66;
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/traitementspcgs66/index/'.$personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}
	}
?>