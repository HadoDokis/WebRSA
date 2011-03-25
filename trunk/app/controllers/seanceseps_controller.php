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
		public $helpers = array( 'Default', 'Default2', 'Ajax' );
		public $uses = array( 'Seanceep', 'Option' );
		public $components = array( 'Prg' => array( 'actions' => array( 'index', 'creationmodification', 'attributiondossiers', 'arbitrage', 'recherche' ) ), 'Gedooo' );
		public $aucunDroit = array( 'ajaxadresse' );

		/**
		*
		*/

		protected function _setOptions() {
			/// TODO: plus générique - scinder les CG
			$options = Set::merge(
				$this->Seanceep->Dossierep->Saisineepreorientsr93->Nvsrepreorientsr93->enums(),
				$this->Seanceep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->enums(),
				$this->Seanceep->Dossierep->Saisineepbilanparcours66->Nvsrepreorient66->enums(),
				$this->Seanceep->Dossierep->Nonrespectsanctionep93->Decisionnonrespectsanctionep93->enums(),
				$this->Seanceep->Dossierep->Nonrespectsanctionep93->enums(),
				$this->Seanceep->Dossierep->Defautinsertionep66->enums(),
				$this->Seanceep->Dossierep->Nonorientationpro58->Decisionnonorientationpro58->enums(),
				$this->Seanceep->Dossierep->Nonorientationpro93->Decisionnonorientationpro93->enums(),
				$this->Seanceep->Dossierep->Sanctionep58->Decisionsanctionep58->enums(),
				$this->Seanceep->Dossierep->enums(),
				$this->Seanceep->enums(),
				$this->Seanceep->MembreepSeanceep->enums(),
				array( 'Foyer' => array( 'sitfam' => $this->Option->sitfam() ) )
			);
			//$options['Seanceep']['ep_id'] = $this->Seanceep->Ep->find( 'list' );
			if( !in_array( $this->action, array( 'add', 'edit', 'index' ) ) ) {
				/// TODO: est-ce que ça a  du sens ?
				$options['Seanceep']['typeorient_id'] = $this->Seanceep->Dossierep->Personne->Orientstruct->Typeorient->listOptions();
// 				$options['Seanceep']['structurereferente_id'] = $this->Seanceep->Structurereferente->list1Options();
				$options['Seanceep']['referent_id'] = $this->Seanceep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Referent->listOptions();
				$options['Nvsepdpdo66']['decisionpdo_id'] = $this->Seanceep->Dossierep->Saisineepdpdo66->Nvsepdpdo66->Decisionpdo->find('list');
			}
			else{
				$options[$this->modelClass]['structurereferente_id'] = $this->{$this->modelClass}->Structurereferente->listOptions();
			}
			$options[$this->modelClass]['ep_id'] = $this->{$this->modelClass}->Ep->listOptions();
			$options['Ep']['regroupementep_id'] = $this->{$this->modelClass}->Ep->Regroupementep->find( 'list' );
			$options['Decisiondefautinsertionep66']['typeorient_id'] = $this->Seanceep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Typeorient->listOptions();
			$options['Decisiondefautinsertionep66']['structurereferente_id'] = $this->Seanceep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Structurereferente->list1Options();//listOptions
			$options['Decisiondefautinsertionep66']['referent_id'] = $this->Seanceep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Referent->listOptions();
			$this->set( compact( 'options' ) );
			$this->set( 'typevoie', $this->Option->typevoie() );

			$this->set( 'listesanctionseps58', $this->Seanceep->Dossierep->Sanctionep58->Listesanctionep58->find( 'list' ) );
		}

		/**
		*
		*/

		public function index( $etape = null ) {
			if( !empty( $this->data ) ) {
				$queryData = $this->Seanceep->search( $this->data );
				$queryData['limit'] = 10;
				$this->paginate = $queryData;
				$seanceseps = $this->paginate( $this->Seanceep );
				$this->set( 'seanceseps', $seanceseps );
// 				$this->set( 'etape', $etape );
			}
			$this->_setOptions();
			$this->render( null, null, 'index' );
		}

		/**
		*
		*/

		public function creationmodification() {
			$this->index( $this->action );
		}

		/**
		*
		*/

		public function attributiondossiers() {
			$this->index( $this->action );
		}

		/**
		*
		*/

		public function arbitrage() {
			$this->index( $this->action );
		}

		/**
		*
		*/

		public function recherche() {
			$this->index( $this->action );
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
					$this->redirect( array( 'action' => 'view', $this->Seanceep->id ) );
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

				if( !empty( $this->data['Seanceep']['finalisee'] ) ) {
					$this->Session->setFlash( 'Impossible de modifier une commission d\'EP lorsque celle-ci comporte déjà des avis ou des décisions.', 'default', array( 'class' => 'error' ) );
					$this->redirect( $this->referer() );
				}
			}

			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		 *
		 */

		function ajaxadresse( $structurereferente_id = null ) { // FIXME
			Configure::write( 'debug', 0 );
			$dataStructurereferente_id = Set::extract( $this->data, 'Seanceep.structurereferente_id' );
			$structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );

			$struct = $this->Seanceep->Structurereferente->findbyId( $structurereferente_id, null, null, -1 );
			$this->set( 'struct', $struct );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->render( 'ajaxadresse', 'ajax' );
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

			// Etape OK ?
			$etapePossible = (
				( ( $niveauDecision == 'ep' ) && empty( $seanceep['Seanceep']['finalisee'] ) ) // OK
				|| ( ( $niveauDecision == 'cg' ) && ( $seanceep['Seanceep']['finalisee'] == 'ep' ) ) // OK
				|| ( $seanceep['Seanceep']['finalisee'] != 'cg' ) // OK
			);

			if( !$etapePossible ) {
				$this->Session->setFlash( 'Impossible de traiter les dossiers d\'une commission d\'EP à une étape antérieure.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}

			if( !empty( $this->data ) ) {
				$this->Seanceep->begin();
				$success = $this->Seanceep->saveDecisions( $seanceep_id, $this->data, $niveauDecision );

				$this->_setFlashResult( 'Save', $success );
				if( /*false && */$success ) {
// 					$this->Seanceep->rollback();
					$this->Seanceep->commit();
					$this->redirect( array( 'action' => 'view', $seanceep_id, '#dossiers' ) );
				}
				else {
					$this->Seanceep->rollback();
				}
			}

			$dossiers = $this->Seanceep->dossiersParListe( $seanceep_id, $niveauDecision );

			if( empty( $this->data ) ) {
				$this->data = $this->Seanceep->prepareFormData( $seanceep_id, $dossiers, $niveauDecision );
			}
// debug( $this->data );
			$this->set( compact( 'seanceep', 'dossiers' ) );
			$this->set( 'seanceep_id', $seanceep_id);
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
			$seanceep = $this->Seanceep->find(
				'first',
				array(
					'conditions' => array(
						'Seanceep.id' => $seanceep_id,
					),
					'contain' =>false
				)
			);

			$this->assert( !empty( $seanceep ), 'error404' );

			// Etape OK ?
			$etapePossible = (
				( ( $niveauDecision == 'ep' ) && empty( $seanceep['Seanceep']['finalisee'] ) ) // OK
				|| ( ( $niveauDecision == 'cg' ) && ( $seanceep['Seanceep']['finalisee'] == 'ep' ) ) // OK
				|| ( $seanceep['Seanceep']['finalisee'] != 'cg' ) // OK
			);

			if( !$etapePossible ) {
				$this->Session->setFlash( 'Impossible de finaliser les décisions des dossiers d\'une commission d\'EP à une étape antérieure.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}

			$this->Seanceep->begin();
			$success = $this->Seanceep->finaliser( $seanceep_id, $niveauDecision, $this->Session->read( 'Auth.User.id' ) );

			$this->_setFlashResult( 'Save', $success );
			if( $success ) {
				$this->Seanceep->commit();
// 				$this->Seanceep->rollback();
			}
			else {
				$this->Seanceep->rollback();
			}
			$this->redirect( array( 'action' => 'view', $seanceep_id, '#dossiers' ) );
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


		/**
		* Affiche la séance EP avec la liste de ses membres.
		* @param integer $seanceep_id
		*/
		public function view($seanceep_id = null) {
			$seanceep = $this->Seanceep->find(
				'first', array(
					'conditions' => array( 'Seanceep.id' => $seanceep_id ),
					'contain' => array(
	// 					'Structurereferente',
	// 					'Dossierep' => array(
	// 						'Personne'
	// 					),
						'Ep' => array( 'Regroupementep')
					)
				)
			);
// 			debug( $seanceep );
			$this->set('seanceep', $seanceep);
			$this->_setOptions();

			// Dossiers à passer en séance, par thème traité
			$themes = array_keys( $this->Seanceep->themesTraites( $seanceep_id ) );
			$this->set(compact('themes'));
			$dossiers = array();
			$countDossiers = 0;
			foreach( $themes as $theme ) {
				$class = Inflector::classify( $theme );
				$dossiers[$theme] = $this->Seanceep->Dossierep->find(
					'all',
					array(
						'conditions' => array(
							'Dossierep.seanceep_id' => $seanceep_id,
							'Dossierep.themeep' => Inflector::tableize( $class )
						),
						'contain' => array(
							'Personne' => array(
								'Foyer' => array(
									'Adressefoyer' => array(
										'conditions' => array(
											'Adressefoyer.rgadr' => '01'
										),
										'Adresse'
									)
								)
							)
						),
					)
				);
				$countDossiers += count($dossiers[$theme]);
			}
//debug($dossiers);
			$this->set(compact('dossiers'));
			$this->set(compact('countDossiers'));

			$fields = array(
				'MembreepSeanceep.id',
				'MembreepSeanceep.seanceep_id',
				'MembreepSeanceep.membreep_id',
				'MembreepSeanceep.reponse',
				'MembreepSeanceep.presence',
				'Membreep.qual',
				'Membreep.nom',
				'Membreep.prenom',
				'Membreep.suppleant_id',
				'Membreep.fonctionmembreep_id'
			);

			$membresepsseanceseps = $this->Seanceep->MembreepSeanceep->find( 'all', array(
				'fields' => $fields,
				'conditions'=> array(
					'Seanceep.id' => $seanceep_id
				),
				'contain' => array(
					'Seanceep',
					'Membreep' => array( 'Fonctionmembreep')
				)
			));
			foreach($membresepsseanceseps as &$membreepseanceep) {
				if (!empty($membreepseanceep['Membreep']['suppleant_id'])) {
					$remplacant = $this->Seanceep->Membreep->find( 'first', array(
						'conditions'=> array(
							'Membreep.id' => $membreepseanceep['Membreep']['suppleant_id']
						),
						'contain' => false
					));
					$membreepseanceep['Membreep']['suppleant'] = implode(' ', array($remplacant['Membreep']['qual'], $remplacant['Membreep']['nom'], $remplacant['Membreep']['prenom']));
				}
			}
			$this->set('membresepsseanceseps', $membresepsseanceseps);
		}

		/**
		*
		*/

		public function impressionpv( $seanceep_id ) {
			$seanceep = $this->Seanceep->find(
				'first',
				array(
					'fields' => array(
						'Seanceep.finalisee'
					),
					'conditions' => array(
						'Seanceep.id' => $seanceep_id
					)
				)
			);

			$presencesNonIndiquees = $this->Seanceep->MembreepSeanceep->find(
				'count',
				array(
					'conditions' => array(
						'MembreepSeanceep.seanceep_id' => $seanceep_id,
						'MembreepSeanceep.presence IS NULL'
					)
				)
			);

			if( empty( $seanceep['Seanceep']['finalisee'] ) || ( $presencesNonIndiquees > 0 ) ) {
				if( empty( $seanceep['Seanceep']['finalisee'] ) ) {
					$this->Session->setFlash( 'Impossible d\'imprimer le PV avant de finaliser la commission au niveau EP.', 'default', array( 'class' => 'error' ) );
				}
				else {
					$this->Session->setFlash( 'Impossible d\'imprimer le PV avant d\'avoir pris les présences de la commission d\'EP.', 'default', array( 'class' => 'error' ) );
				}

				$this->redirect( $this->referer() );
			}

 			$pdf = $this->Seanceep->getPdfPv( $seanceep_id );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'pv' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le PV de la commission d\'EP', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		*
		*/

		public function ordredujour( $seanceep_id ) {
			$reponsesNonIndiquees = $this->Seanceep->MembreepSeanceep->find(
				'count',
				array(
					'conditions' => array(
						'MembreepSeanceep.seanceep_id' => $seanceep_id,
						'MembreepSeanceep.reponse' => 'nonrenseigne'
					)
				)
			);

			$nombreDossierseps = $this->Seanceep->Dossierep->find(
				'count',
				array(
					'conditions' => array(
						'Dossierep.seanceep_id' => $seanceep_id
					)
				)
			);

			if( ( $reponsesNonIndiquees > 0 ) || ( $nombreDossierseps == 0 ) ) {
				if( $reponsesNonIndiquees > 0 ) {
					$this->Session->setFlash( 'Impossible d\'imprimer l\'ordre du jour avant d\'avoir indiqué la réponse des participants.', 'default', array( 'class' => 'error' ) );
				}
				if( $nombreDossierseps == 0 ) {
					$this->Session->setFlash( 'Impossible d\'imprimer l\'ordre du jour avant d\'avoir attribué des dossiers.', 'default', array( 'class' => 'error' ) );
				}
				$this->redirect( $this->referer() );
			}

 			$pdf = $this->Seanceep->getPdfOrdreDuJour( $seanceep_id );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'OJ' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer l\'ordre du jour de la commission d\'EP', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>
