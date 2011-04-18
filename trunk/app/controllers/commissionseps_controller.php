<?php
	/**
	* Gestion des séances d'équipes pluridisciplinaires.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class CommissionsepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Ajax' );
		public $uses = array( 'Commissionep', 'Option' );
		public $components = array( 'Prg' => array( 'actions' => array( 'index', 'creationmodification', 'attributiondossiers', 'arbitrage', 'recherche' ) ), 'Gedooo' );
		public $aucunDroit = array( 'ajaxadresse' );

		/**
		*
		*/

		public $etatsActions = array(
			'cree' => array(
				'dossierseps::choose',
				'membreseps::editliste',
				'commissionseps::edit',
				'commissionseps::delete',
			),
			'associe' => array(
				'commissionseps::ordredujour',
				'dossierseps::choose',
				'membreseps::editliste',
				'commissionseps::edit',
				'membreseps::editpresence',
				'commissionseps::delete',
			),
			'presence' => array(
				'commissionseps::ordredujour',
				'commissionseps::edit',
				'membreseps::editpresence',
				'commissionseps::traiterep',
				'commissionseps::delete',
			),
			'decisionep' => array(
				'commissionseps::ordredujour',
				'commissionseps::edit',
				'commissionseps::traiterep',
				'commissionseps::finaliserep',
				'commissionseps::delete',
			),
			'traiteep' => array(
				'commissionseps::ordredujour',
				'commissionseps::impressionpv',
				'commissionseps::traitercg',
			),
			'decisioncg' => array(
				'commissionseps::ordredujour',
				'commissionseps::impressionpv',
				'commissionseps::traitercg',
				'commissionseps::finalisercg',
			),
			'traite' => array(
				'commissionseps::ordredujour',
				'commissionseps::impressionpv',
			),
			'annule' => array()
		);

		/**
		*
		*/

		protected function _setOptions() {
			/// TODO: plus générique - scinder les CG
			$options = Set::merge(
				$this->Commissionep->Passagecommissionep->Decisionreorientationep93->enums(),
				/*$this->Commissionep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->enums(),
				$this->Commissionep->Dossierep->Saisinebilanparcoursep66->Decisionsaisinebilanparcoursep66->enums(),
				$this->Commissionep->Dossierep->Nonrespectsanctionep93->Decisionnonrespectsanctionep93->enums(),
				$this->Commissionep->Dossierep->Nonrespectsanctionep93->enums(),
				$this->Commissionep->Dossierep->Defautinsertionep66->enums(),
				$this->Commissionep->Dossierep->Nonorientationproep58->Decisionnonorientationproep58->enums(),
				$this->Commissionep->Dossierep->Nonorientationproep93->Decisionnonorientationproep93->enums(),
				$this->Commissionep->Dossierep->Sanctionep58->Decisionsanctionep58->enums(),*/
				$this->Commissionep->Passagecommissionep->Dossierep->enums(),
				$this->Commissionep->enums(),
				$this->Commissionep->CommissionepMembreep->enums(),
				$this->Commissionep->Passagecommissionep->enums(),
				array( 'Foyer' => array( 'sitfam' => $this->Option->sitfam() ) )
			);
			//$options['Commissionep']['ep_id'] = $this->Commissionep->Ep->find( 'list' );
			if( !in_array( $this->action, array( 'add', 'edit', 'index' ) ) ) {
				/// TODO: est-ce que ça a  du sens ?
				$options['Commissionep']['typeorient_id'] = $this->Commissionep->Passagecommissionep->Dossierep->Personne->Orientstruct->Typeorient->listOptions();
				$options['Commissionep']['structurereferente_id'] = $this->Commissionep->Passagecommissionep->Dossierep->Personne->Orientstruct->Structurereferente->list1Options();
				$options['Commissionep']['referent_id'] = $this->Commissionep->Passagecommissionep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Referent->listOptions();
				$options['Decisionsaisinepdoep66']['decisionpdo_id'] = $this->Commissionep->Passagecommissionep->Dossierep->Saisinepdoep66->Decisionsaisinepdoep66->Decisionpdo->find('list');
			}/*
			else{
				$options[$this->modelClass]['structurereferente_id'] = $this->{$this->modelClass}->Dossierep->Personne->Orientstruct->Structurereferente->listOptions();
			}*/
			$options[$this->modelClass]['ep_id'] = $this->{$this->modelClass}->Ep->listOptions();
			$options['Ep']['regroupementep_id'] = $this->{$this->modelClass}->Ep->Regroupementep->find( 'list' );
			$options['Decisiondefautinsertionep66']['typeorient_id'] = $this->Commissionep->Passagecommissionep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Typeorient->listOptions();
			$options['Decisiondefautinsertionep66']['structurereferente_id'] = $this->Commissionep->Passagecommissionep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Structurereferente->list1Options();//listOptions
			$options['Decisiondefautinsertionep66']['referent_id'] = $this->Commissionep->Passagecommissionep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Referent->listOptions();
			$this->set( compact( 'options' ) );
			$this->set( 'typevoie', $this->Option->typevoie() );

			$this->set( 'listesanctionseps58', $this->Commissionep->Passagecommissionep->Dossierep->Sanctionep58->Listesanctionep58->find( 'list' ) );
		}

		/**
		*
		*/

		public function index( $etape = null ) {
			if( !empty( $this->data ) ) {
				$this->paginate['Commissionep'] = $this->Commissionep->search( $this->data );
				$this->paginate['Commissionep']['limit'] = 10;

				switch( $etape ) {
					case 'creationmodification':
						$this->paginate['Commissionep']['conditions']['etatcommissionep'] = array( 'cree', 'associe' );
						break;
					case 'attributiondossiers':
						$this->paginate['Commissionep']['conditions']['etatcommissionep'] = array( 'cree', 'associe' );
						break;
					case 'arbitrage':
						$this->paginate['Commissionep']['conditions']['etatcommissionep'] = array( 'associe', 'decisionep', 'decisioncg' );
						break;
				}

				$commissionseps = $this->paginate( $this->Commissionep );
				$this->set( 'commissionseps', $commissionseps );
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
				$this->Commissionep->create( $this->data );
				$success = $this->Commissionep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'view', $this->Commissionep->id ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Commissionep->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Commissionep.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );

				if( in_array( $this->data['Commissionep']['etatcommissionep'], array( 'decisionep', 'decisioncg', 'annulee' ) ) ) {
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
			$dataStructurereferente_id = Set::extract( $this->data, 'Commissionep.structurereferente_id' );
			$structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );

			$struct = $this->Commissionep->Structurereferente->findbyId( $structurereferente_id, null, null, -1 );
			$this->set( 'struct', $struct );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->render( 'ajaxadresse', 'ajax' );
		}

		/**
		* Fonction de suppression de la commission d'ep
		* Passe tous ses dossiers liés dans l'état reporté et son état à annulé
		*/

		public function delete( $commissionep_id ) {
			if ( !empty( $this->data ) ) {
				$success = true;
				$this->Commissionep->begin();
				$this->Commissionep->id = $commissionep_id;
				
				$commissionep = array(
					'Commissionep' => array(
						'id' => $commissionep_id,
						'etatcommissionep' => 'annule',
						'raisonannulation' => $this->data['Commissionep']['raisonannulation']
					)
				);
				$this->Commissionep->create( $commissionep );
				$success = $this->Commissionep->save() && $success;
				
				$this->Commissionep->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.etatdossierep' => '\'reporte\'' ),
					array(
						'"Passagecommissionep"."commissionep_id"' => $commissionep_id
					)
				);
				
				$this->_setFlashResult( 'Delete', $success );
				if ( $success ) {
					$this->Commissionep->commit();
					$this->redirect( array( 'controller' => 'commissionseps', 'action' => 'view', $commissionep_id ) );
				}
				else {
					$this->Commissionep->rollback();
				}
			}
			$this->set( 'commissionep_id', $commissionep_id );
		}

		/**
		* Traitement d'une séance à un certain niveau de décision.
		*/

		protected function _traiter( $commissionep_id, $niveauDecision ) {
			$commissionep = $this->Commissionep->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id,
					),
					'contain' => array(
						'Ep'
					)
				)
			);

			$this->assert( !empty( $commissionep ), 'error404' );

			// Etape OK ?
			$etapePossible = (
				( ( $niveauDecision == 'ep' ) && empty( $commissionep['Commissionep']['etatcommissionep'] ) ) // OK
				|| ( ( $niveauDecision == 'cg' ) && ( $commissionep['Commissionep']['etatcommissionep'] == 'ep' ) ) // OK
				|| ( $commissionep['Commissionep']['etatcommissionep'] != 'cg' ) // OK
			);

			if( !$etapePossible ) {
				$this->Session->setFlash( 'Impossible de traiter les dossiers d\'une commission d\'EP à une étape antérieure.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}

			if( !empty( $this->data ) ) {
				$this->Commissionep->begin();
				$success = $this->Commissionep->saveDecisions( $commissionep_id, $this->data, $niveauDecision );

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Commissionep->commit();
					$this->redirect( array( 'action' => 'view', $commissionep_id, '#dossiers' ) );
				}
				else {
					$this->Commissionep->rollback();
				}
			}

			$dossiers = $this->Commissionep->dossiersParListe( $commissionep_id, $niveauDecision );

			if( empty( $this->data ) ) {
				$this->data = $this->Commissionep->prepareFormData( $commissionep_id, $dossiers, $niveauDecision );
			}

			$this->set( compact( 'commissionep', 'dossiers' ) );
			$this->set( 'commissionep_id', $commissionep_id);
			$this->_setOptions();
		}

		/**
		* Traitement d'une séance au niveau de décision EP
		*/

		public function traiterep( $commissionep_id ) {
			$this->_traiter( $commissionep_id, 'ep' );
		}

		/**
		*
		*/

		protected function _finaliser( $commissionep_id, $niveauDecision ) {
			$commissionep = $this->Commissionep->find(
				'first',
				array(
					'conditions' => array(
						'Commissionep.id' => $commissionep_id,
					),
					'contain' =>false
				)
			);

			$this->assert( !empty( $commissionep ), 'error404' );

			// Etape OK ?
			$etapePossible = (
				( ( $niveauDecision == 'ep' ) && empty( $commissionep['Commissionep']['etatcommissionep'] ) ) // OK
				|| ( ( $niveauDecision == 'cg' ) && ( $commissionep['Commissionep']['etatcommissionep'] == 'ep' ) ) // OK
				|| ( $commissionep['Commissionep']['etatcommissionep'] != 'cg' ) // OK
			);

			if( !$etapePossible ) {
				$this->Session->setFlash( 'Impossible de finaliser les décisions des dossiers d\'une commission d\'EP à une étape antérieure.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}

			$this->Commissionep->begin();
			$success = $this->Commissionep->finaliser( $commissionep_id, $niveauDecision, $this->Session->read( 'Auth.User.id' ) );

			$this->_setFlashResult( 'Save', $success );
			if( $success ) {
				$this->Commissionep->commit();
// 				$this->Commissionep->rollback();
			}
			else {
				$this->Commissionep->rollback();
			}
			$this->redirect( array( 'action' => 'view', $commissionep_id, '#dossiers' ) );
		}

		/**
		* Finalisation de la séance au niveau EP
		*/

		public function finaliserep( $commissionep_id ) {
			$this->_finaliser( $commissionep_id, 'ep' );
		}

		/**
		* Traitement d'une séance au niveau de décision CG
		* TODO: les dossiers qui ne doivent pas être traités par le CG ne doivent pas apparaître ici
		* TODO: si tous les thèmes se décident niveau EP, plus besoin de passer par ici.
		*/

		public function traitercg( $commissionep_id ) {
			$this->_traiter( $commissionep_id, 'cg' );
		}

		/**
		* Finalisation de la séance au niveau CG
		*/

		public function finalisercg( $commissionep_id ) {
			$this->_finaliser( $commissionep_id, 'cg' );
		}


		/**
		* Affiche la séance EP avec la liste de ses membres.
		* @param integer $commissionep_id
		*/
		public function view($commissionep_id = null) {
			$commissionep = $this->Commissionep->find(
				'first', array(
					'conditions' => array( 'Commissionep.id' => $commissionep_id ),
					'contain' => array(
	// 					'Structurereferente',
	// 					'Dossierep' => array(
	// 						'Personne'
	// 					),
						'Ep' => array( 'Regroupementep')
					)
				)
			);
// 			debug( $commissionep );
			$this->set('commissionep', $commissionep);
			$this->_setOptions();

			// Dossiers à passer en séance, par thème traité
			$themes = array_keys( $this->Commissionep->themesTraites( $commissionep_id ) );
			$this->set(compact('themes'));
			$dossiers = array();
			$countDossiers = 0;
			foreach( $themes as $theme ) {
				$class = Inflector::classify( $theme );
				$dossiers[$theme] = $this->Commissionep->Passagecommissionep->find(
					'all',
					array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id,
							'Dossierep.themeep' => Inflector::tableize( $class )
						),
						'contain' => array(
							'Dossierep' => array(
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
							)
						),
					)
				);
				$countDossiers += count($dossiers[$theme]);
			}

			$this->set(compact('dossiers'));
			$this->set(compact('countDossiers'));

			$fields = array(
				'CommissionepMembreep.id',
				'CommissionepMembreep.commissionep_id',
				'CommissionepMembreep.membreep_id',
				'CommissionepMembreep.reponse',
				'CommissionepMembreep.presence',
				'Membreep.qual',
				'Membreep.nom',
				'Membreep.prenom',
				'Membreep.suppleant_id',
				'Membreep.fonctionmembreep_id'
			);

			$membresepsseanceseps = $this->Commissionep->CommissionepMembreep->find( 'all', array(
				'fields' => $fields,
				'conditions'=> array(
					'Commissionep.id' => $commissionep_id
				),
				'contain' => array(
					'Commissionep',
					'Membreep' => array( 'Fonctionmembreep')
				)
			));
			foreach($membresepsseanceseps as &$membreepseanceep) {
				if (!empty($membreepseanceep['Membreep']['suppleant_id'])) {
					$remplacant = $this->Commissionep->Membreep->find( 'first', array(
						'conditions'=> array(
							'Membreep.id' => $membreepseanceep['Membreep']['suppleant_id']
						),
						'contain' => false
					));
					$membreepseanceep['Membreep']['suppleant'] = implode(' ', array($remplacant['Membreep']['qual'], $remplacant['Membreep']['nom'], $remplacant['Membreep']['prenom']));
				}
			}
			$this->set('membresepsseanceseps', $membresepsseanceseps);
			$this->set('etatsActions', $this->etatsActions);
		}

		/**
		*
		*/

		public function impressionpv( $commissionep_id ) {
			$commissionep = $this->Commissionep->find(
				'first',
				array(
					'fields' => array(
						'Commissionep.etatcommissionep'
					),
					'conditions' => array(
						'Commissionep.id' => $commissionep_id
					)
				)
			);

			$presencesNonIndiquees = $this->Commissionep->CommissionepMembreep->find(
				'count',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id,
						'CommissionepMembreep.presence IS NULL'
					)
				)
			);

			if( empty( $commissionep['Commissionep']['etatcommissionep'] ) || ( $presencesNonIndiquees > 0 ) ) {
				if( empty( $commissionep['Commissionep']['etatcommissionep'] ) ) {
					$this->Session->setFlash( 'Impossible d\'imprimer le PV avant de finaliser la commission au niveau EP.', 'default', array( 'class' => 'error' ) );
				}
				else {
					$this->Session->setFlash( 'Impossible d\'imprimer le PV avant d\'avoir pris les présences de la commission d\'EP.', 'default', array( 'class' => 'error' ) );
				}

				$this->redirect( $this->referer() );
			}

 			$pdf = $this->Commissionep->getPdfPv( $commissionep_id );

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

		public function ordredujour( $commissionep_id ) {
			$reponsesNonIndiquees = $this->Commissionep->CommissionepMembreep->find(
				'count',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id,
						'CommissionepMembreep.reponse' => 'nonrenseigne'
					)
				)
			);

			$nombreDossierseps = $this->Commissionep->Passagecommissionep->find(
				'count',
				array(
					'contain' => array(
						'Dossierep'
					),
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $commissionep_id
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

 			$pdf = $this->Commissionep->getPdfOrdreDuJour( $commissionep_id );

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
