<?php
	class Decisionspersonnespcgs66Controller extends AppController
	{
		public $name = 'Decisionspersonnespcgs66';
		/**
		* @access public
		*/

		public $components = array( 'Default', 'Gedooo' );
		public $helpers = array( 'Default2', 'Ajax' );
		public $uses = array( 'Decisionpersonnepcg66', 'Option', 'Pdf'  );

		public $commeDroit = array(
			'view' => 'Decisionspersonnespcgs66:index',
			'add' => 'Decisionspersonnespcgs66:edit'
		);

		/**
		*
		*/

		protected function _setOptions() {
			$options = array();
			$options = $this->Decisionpersonnepcg66->Decisionpdo->find( 'list' );
			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		public function index( $personnepcg66_id = null ) {
			//Récupération des informations de la personne concernée par le dossier
			$personnepcg66 = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->Personnepcg66->find(
				'first',
				array(
					'conditions' => array(
						'Personnepcg66.id' => $personnepcg66_id
					),
					'contain' => array(
						'Personnepcg66Situationpdo' => array(
							'Situationpdo',
							'Decisionpersonnepcg66'
						),
						'Dossierpcg66'
					)
				)
			);
			$dossierpcg66_id = $personnepcg66['Personnepcg66']['dossierpcg66_id'];
			$personne_id = $personnepcg66['Personnepcg66']['personne_id'];
			$this->set( 'etatdossierpcg', $personnepcg66['Dossierpcg66']['etatdossierpcg'] );

			// Récupération du nom de l'allocataire
			$personne = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->Personnepcg66->Personne->find(
				'first',
				array(
					'fields' => array( 'nom_complet' ),
					'conditions' => array(
						'Personne.id' => $personnepcg66['Personnepcg66']['personne_id']
					),
					'contain' => false
				)
			);
			$nompersonne = Set::classicExtract( $personne, 'Personne.nom_complet' );
			$this->set( compact( 'nompersonne' ) );

			//Récuipération des propositions de décisions
			$listeDecisions = $this->Decisionpersonnepcg66->listeDecisionsParPersonnepcg66( $personnepcg66_id, $dossierpcg66_id );
			$this->set( compact ('listeDecisions' ) );

			$this->set( 'personne_id', $personne_id );
			$this->set( 'personnepcg66_id', $personnepcg66_id );
			$this->set( 'dossierpcg66_id', $dossierpcg66_id );

			$this->set( 'urlmenu', '/traitementspcgs66/index/'.$personne_id );
			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
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

			$this->Decisionpersonnepcg66->begin();

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$personnepcg66_id = $id;

// 				$personnepcg66 = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->Personnepcg66->findById( $id, null, null, -1 );
				$personnepcg66 = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->Personnepcg66->find(
					'first',
					array(
						'conditions' => array(
							'Personnepcg66.id' => $personnepcg66_id
						),
						'contain' => array(  'Personne' )
					)
				);

				$this->set( 'personnepcg66', $personnepcg66 );
				$personne_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.personne_id' );
				$dossierpcg66_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.dossierpcg66_id' );
				$dossier_id = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->Personnepcg66->Personne->dossierId( $personne_id );
			}
			else if( $this->action == 'edit' ) {
				$decisionpersonnepcg66_id = $id;
				$decisionpersonnepcg66 = $this->Decisionpersonnepcg66->findById( $decisionpersonnepcg66_id, null, null, 1 );
				$this->assert( !empty( $decisionpersonnepcg66 ), 'invalidParameter' );

				$personnepcg66_id = Set::classicExtract( $decisionpersonnepcg66, 'Personnepcg66Situationpdo.personnepcg66_id' );
				$personnepcg66 = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->Personnepcg66->find(
					'first',
					array(
						'conditions' => array(
							'Personnepcg66.id' => $personnepcg66_id
						),
						'contain' => array( 'Personne' )
					)
				);
				$this->set( 'personnepcg66', $personnepcg66 );
				$personne_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.personne_id' );
				$dossierpcg66_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.dossierpcg66_id' );
				$dossier_id = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->Personnepcg66->Personne->dossierId( $personne_id );
			}

			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'personnepcg66_id', $personnepcg66_id );
			$this->set( 'dossierpcg66_id', $dossierpcg66_id );
			$this->set( 'personne_id', $personne_id );

			//Récupération de la liste des motifs de l'allocataire concerné
			$personnespcgs66Situationspdos = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->listeMotifsPourDecisions($personnepcg66_id);
			$this->set( compact( 'personnespcgs66Situationspdos' ) );

			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Decisionpersonnepcg66->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			if( !empty( $this->data ) ){
				if( $this->Decisionpersonnepcg66->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = true;
					
					$saved = $this->Decisionpersonnepcg66->save( $this->data );

// 					if ( $saved ) {
// 						$saved = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->Personnepcg66->Dossierpcg66->updateEtatViaPersonne( $dossierpcg66_id ) && $saved;
// 					}
// 
// 					if ( $saved ) {
// 						$saved = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->Personnepcg66->Dossierpcg66->updateEtatViaDecisionPersonnepcg( $dossierpcg66_id ) && $saved;
// 					}
					
					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Decisionpersonnepcg66->commit(); // FIXME
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'decisionspersonnespcgs66', 'action' => 'index', $personnepcg66_id ) );
					}
					else {
						$this->Decisionpersonnepcg66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$this->Decisionpersonnepcg66->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			elseif( $this->action == 'edit' ){
				$this->data = $decisionpersonnepcg66;
			}

			$this->Decisionpersonnepcg66->commit();

			$this->_setOptions();
			$this->set( 'urlmenu', '/traitementspcgs66/index/'.$personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}


		/**
		*   Enregistrement du courrier de proposition lors de l'enregistrement de la proposition
		*/
		public function decisionproposition( $id ) {
			$this->assert( !empty( $id ), 'error404' );

			$pdf = $this->Decisionpersonnepcg66->getStoredPdf( $id );

			$this->assert( !empty( $pdf ), 'error404' );
			$this->assert( !empty( $pdf['Pdf']['document'] ), 'error500' ); // FIXME: ou en faire l'impression ?

			$this->Gedooo->sendPdfContentToClient( $pdf['Pdf']['document'], "Proposition_decision.pdf" );
		}

		/**
		*
		*/

		public function view( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$decisionpersonnepcg66 = $this->Decisionpersonnepcg66->find(
				'first',
				array(
					'conditions' => array(
						'Decisionpersonnepcg66.id' => $id,
					),
					'contain' => array(
						'Personnepcg66Situationpdo' => array(
							'fields' => array( 'personnepcg66_id' )
						),
						'Decisionpdo' => array(
							'fields' => array( 'libelle' )
						)
					)
				)
			);

			$this->assert( !empty( $decisionpersonnepcg66 ), 'invalidParameter' );

			$this->set( 'dossier_id', $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->Personnepcg66->Personne->dossierId( $id ) );
			$personnepcg66_id = Set::classicExtract( $decisionpersonnepcg66, 'Personnepcg66Situationpdo.personnepcg66_id' );
			$personnepcg66 = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->Personnepcg66->find(
				'first',
				array(
					'conditions' => array(
						'Personnepcg66.id' => $personnepcg66_id
					),
					'contain' => false
				)
			);

			//Récupération de la liste des motifs de l'allocataire concerné
			$personnespcgs66Situationspdos = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->listeMotifsPourDecisions($personnepcg66_id);
			$this->set( compact( 'personnespcgs66Situationspdos' ) );

			$dossierpcg66_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.dossierpcg66_id' );
			$this->set( 'personnepcg66_id', $personnepcg66_id );
			$personne_id = Set::classicExtract( $personnepcg66, 'Personnepcg66.personne_id' );
			$this->set( 'personne_id', $personne_id );

			// Retour à la page d'édition de la PDO
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'decisionspersonnespcgs66', 'action' => 'index', $personnepcg66_id ) );
			}

			$this->set( compact( 'decisionpersonnepcg66' ) );
			$this->_setOptions();
			$this->set( 'urlmenu', '/traitementspcgs66/index/'.$personne_id );
		}

		/**
		* Suppression de la proposition de décision
		*/

		public function delete( $id ) {
			$decisionpersonnepcg66 = $this->Decisionpersonnepcg66->findById( $id, null, null, -1 );

			$personnepcg66_situationpdo_id = Set::classicExtract( $decisionpersonnepcg66, 'Decisionpersonnepcg66.personnepcg66_situationpdo_id' );


			$personnepcg66_situationpdo = $this->Decisionpersonnepcg66->Personnepcg66Situationpdo->findById( $personnepcg66_situationpdo_id, null, null, -1 );

			$personnepcg66_id = Set::classicExtract( $personnepcg66_situationpdo, 'Personnepcg66Situationpdo.personnepcg66_id' );


			$success = $this->Decisionpersonnepcg66->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'controller' => 'decisionspersonnespcgs66', 'action' => 'index', $personnepcg66_id ) );
		}
	}
?>