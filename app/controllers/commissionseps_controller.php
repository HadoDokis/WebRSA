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
		public $components = array( 'Prg' => array( 'actions' => array( 'index', 'creationmodification', 'attributiondossiers', 'arbitrageep', 'arbitragecg', 'recherche', 'decisions' ) ), 'Gedooo' );
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
				'membreseps::printConvocationParticipant',
				'membreseps::printConvocationsParticipants',
			),
			'associe' => array(
				'commissionseps::ordredujour',
				'dossierseps::choose',
				'commissionseps::printConvocationBeneficiaire',
				'commissionseps::printConvocationsBeneficiaires',
				'membreseps::printConvocationParticipant',
				'membreseps::printConvocationsParticipants',
				'commissionseps::printOrdreDuJour',
				'membreseps::editliste',
				'membreseps::editpresence',
				'commissionseps::edit',
				'commissionseps::delete',
			),
			'valide' => array(
				'commissionseps::ordredujour',
				'membreseps::editpresence',
				'commissionseps::printConvocationBeneficiaire',
				'commissionseps::printConvocationsBeneficiaires',
// 				'membreseps::printConvocationParticipant',
// 				'membreseps::printConvocationsParticipants',
				'commissionseps::printOrdreDuJour',
				'commissionseps::delete',
			),
			'quorum' => array(
				'membreseps::editpresence',
				'commissionseps::delete',
				'membreseps::printConvocationParticipant',
				'membreseps::printConvocationsParticipants',
				'commissionseps::printConvocationBeneficiaire',
				'commissionseps::printConvocationsBeneficiaires',
			),
			'presence' => array(
// 				'commissionseps::ordredujour',
				'membreseps::editpresence',
				'commissionseps::traiterep',
				'commissionseps::delete',
// 				'commissionseps::printConvocationBeneficiaire',
				'commissionseps::printOrdreDuJour',
				'membreseps::printConvocationParticipant',
				'membreseps::printConvocationsParticipants',
				'commissionseps::printConvocationBeneficiaire',
				'commissionseps::printConvocationsBeneficiaires',
			),
			'decisionep' => array(
// 				'commissionseps::ordredujour',
				/*'commissionseps::printConvocationBeneficiaire',*/
				'commissionseps::printOrdreDuJour',
				'commissionseps::edit',
				'commissionseps::traiterep',
				'commissionseps::finaliserep',
				'commissionseps::delete',
				'membreseps::printConvocationParticipant',
				'membreseps::printConvocationsParticipants',
				'commissionseps::printConvocationBeneficiaire',
				'commissionseps::printConvocationsBeneficiaires',
			),
			'traiteep' => array(
				'membreseps::printConvocationParticipant',
				'membreseps::printConvocationsParticipants',
				'commissionseps::printOrdreDuJour',
// 				'commissionseps::ordredujour',
				'commissionseps::impressionpv',
				'commissionseps::traitercg',
				'commissionseps::printConvocationBeneficiaire',
				'commissionseps::printConvocationsBeneficiaires',
			),
			'decisioncg' => array(
				'membreseps::printConvocationParticipant',
				'membreseps::printConvocationsParticipants',
				'commissionseps::printOrdreDuJour',
// 				'commissionseps::ordredujour',
				'commissionseps::impressionpv',
				'commissionseps::traitercg',
				'commissionseps::finalisercg',
				'commissionseps::printConvocationBeneficiaire',
				'commissionseps::printConvocationsBeneficiaires',
			),
			'traite' => array(
				'membreseps::printConvocationParticipant',
				'membreseps::printConvocationsParticipants',
				'commissionseps::printOrdreDuJour',
// 				'commissionseps::ordredujour',
				'commissionseps::impressionpv',
				'commissionseps::printDecision',
				'commissionseps::printConvocationBeneficiaire',
				'commissionseps::printConvocationsBeneficiaires',
			),
			'annule' => array()
		);

		/**
		* TODO:
		* 	- plus générique - scinder les CG
		*	- est-ce que ça a  du sens de mettre typeorient/structurereferente/referent dans $options['Commissionep']['xxxx']
		*/

		protected function _setOptions() {
			$options = Set::merge(
				$this->Commissionep->Passagecommissionep->Dossierep->enums(),
				$this->Commissionep->enums(),
				$this->Commissionep->CommissionepMembreep->enums(),
				$this->Commissionep->Passagecommissionep->enums(),
				array( 'Foyer' => array( 'sitfam' => $this->Option->sitfam() ) )
			);

			$options[$this->modelClass]['ep_id'] = $this->{$this->modelClass}->Ep->listOptions(
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				$this->Session->read( 'Auth.Zonegeographique' )
			);
			$options['Ep']['regroupementep_id'] = $this->{$this->modelClass}->Ep->Regroupementep->find( 'list' );

			// Ajout des enums pour les thématiques du CG uniquement
			foreach( $this->Commissionep->Ep->Regroupementep->themes() as $theme ) {
				/*$model = Inflector::classify( $theme );
				if( in_array( 'Enumerable', $this->Passagecommissionep->Dossierep->{$model}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Passagecommissionep->Dossierep->{$model}->enums() );
				}*/

				$modeleDecision = Inflector::classify( "decision{$theme}" );
				if( in_array( 'Enumerable', $this->Commissionep->Passagecommissionep->{$modeleDecision}->Behaviors->attached() ) ) {
					$options = Set::merge( $options, $this->Commissionep->Passagecommissionep->{$modeleDecision}->enums() );
				}
			}

			// Suivant l'action demandée
			if( !in_array( $this->action, array( 'add', 'edit', 'index' ) ) ) {
// 				$options['Commissionep']['typeorient_id'] = $this->Commissionep->Passagecommissionep->Dossierep->Personne->Orientstruct->Typeorient->listOptions();
// 				$options['Commissionep']['structurereferente_id'] = $this->Commissionep->Passagecommissionep->Dossierep->Personne->Orientstruct->Structurereferente->list1Options();
// 				$options['Commissionep']['referent_id'] = $this->Commissionep->Passagecommissionep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Referent->listOptions();
				$typesorients = $this->Commissionep->Passagecommissionep->Dossierep->Personne->Orientstruct->Typeorient->listOptions();
				$structuresreferentes = $this->Commissionep->Passagecommissionep->Dossierep->Personne->Orientstruct->Structurereferente->list1Options();
				$referents = $this->Commissionep->Passagecommissionep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Referent->listOptions();
				if( Configure::read( 'Cg.departement' ) == 66 ) {
					$options['Decisionsaisinepdoep66']['decisionpdo_id'] = $this->Commissionep->Passagecommissionep->Dossierep->Saisinepdoep66->Decisionsaisinepdoep66->Decisionpdo->find('list');
				}
			}

			$liste_typesorients = $this->Commissionep->Passagecommissionep->Dossierep->Personne->Orientstruct->Typeorient->find( 'list' );
			$liste_structuresreferentes = $this->Commissionep->Passagecommissionep->Dossierep->Personne->Orientstruct->Structurereferente->find( 'list' );
			$liste_referents = $this->Commissionep->Passagecommissionep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Referent->find( 'list' );

			$this->set( 'liste_typesorients', $liste_typesorients );
			$this->set( 'liste_structuresreferentes', $liste_structuresreferentes );
			$this->set( 'liste_referents', $liste_referents );

			// Suivant le CG
			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$listeTypesorients = $this->Commissionep->Passagecommissionep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Typeorient->find( 'list' );
				$listeStructuresreferentes = $this->Commissionep->Passagecommissionep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Structurereferente->find( 'list' );
				$listeReferents = $this->Commissionep->Passagecommissionep->Dossierep->Defautinsertionep66->Decisiondefautinsertionep66->Referent->find( 'list' );
				$this->set( compact( 'listeTypesorients' ) );
				$this->set( compact( 'listeStructuresreferentes' ) );
				$this->set( compact( 'listeReferents' ) );
				$options = Set::merge(
					$options,
					$this->Commissionep->Passagecommissionep->Dossierep->Defautinsertionep66->enums()
				);
				$options = Set::merge(
					$options,
					$this->Commissionep->Passagecommissionep->Dossierep->Saisinebilanparcoursep66->enums()
				);
			}
			else if( Configure::read( 'Cg.departement' ) == 93 ) {
				$options = Set::merge(
					$options,
					$this->Commissionep->Passagecommissionep->Dossierep->Nonrespectsanctionep93->enums()
				);
				$options = Set::merge(
					$options,
					$this->Commissionep->Passagecommissionep->Dossierep->Signalementep93->Contratinsertion->enums()
				);
				$this->set( 'duree_engag_cg93', $this->Option->duree_engag_cg93() );
			}
			else if( Configure::read( 'Cg.departement' ) == 58 ) {
				$this->set( 'listesanctionseps58', $this->Commissionep->Passagecommissionep->Decisionsanctionep58->Listesanctionep58->find( 'list' ) );
				$this->set( 'typesrdv', $this->Commissionep->Passagecommissionep->Dossierep->Sanctionrendezvousep58->Rendezvous->Typerdv->find( 'list' ) );
			}

			$this->set( compact( 'options' ) );
			$this->set( compact( 'typesorients' ) );
			$this->set( compact( 'structuresreferentes' ) );
			$this->set( compact( 'referents' ) );
			$this->set( 'typevoie', $this->Option->typevoie() );
		}

		/**
		*
		*/

		public function index( $etape = null ) {
			if( !empty( $this->data ) ) {
				$this->paginate['Commissionep'] = $this->Commissionep->search(
					$this->data,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->Session->read( 'Auth.Zonegeographique' )
				);

				$this->paginate['Commissionep']['limit'] = 10;
				$this->paginate['Commissionep']['order'] = array( 'Commissionep.dateseance DESC' );

				switch( $etape ) {
					case 'creationmodification':
						$this->paginate['Commissionep']['conditions']['etatcommissionep'] = array( 'cree', 'associe' );
						break;
					case 'attributiondossiers':
						$this->paginate['Commissionep']['conditions']['etatcommissionep'] = array( 'cree', 'associe' );
						break;
					case 'arbitrageep':
						$this->paginate['Commissionep']['conditions']['etatcommissionep'] = array( 'associe', 'valide', 'quorum', 'presence', 'decisionep', 'traiteep'/*, 'decisioncg', 'traite'*/ );
						break;
					case 'arbitragecg':
						$this->paginate['Commissionep']['conditions']['etatcommissionep'] = array( 'traiteep', 'decisioncg'/*, 'traite'*/ );
						break;
					case 'decisioncg':
						$this->paginate['Commissionep']['conditions']['etatcommissionep'] = array( 'traite' );
						break;
				}

				$commissionseps = $this->paginate( $this->Commissionep );

				foreach( $commissionseps as $key => $commissionep ){
					//Calcul du nombre de participants
					$nbparticipants = $this->Commissionep->Membreep->CommissionepMembreep->find(
						'count',
						array(
							'conditions' => array(
								'CommissionepMembreep.commissionep_id' => Set::classicExtract( $commissionep, 'Commissionep.id' ),
								'CommissionepMembreep.membreep_id IS NOT NULL'
							),
							'contain' => false
						)
					);
					$commissionseps[$key]['Commissionep']['nbparticipants'] = $nbparticipants;

					//Calcul du nombre d'absents parmi les participants
					$nbabsents = $this->Commissionep->Membreep->CommissionepMembreep->find(
						'count',
						array(
							'conditions' => array(
								'CommissionepMembreep.commissionep_id' => Set::classicExtract( $commissionep, 'Commissionep.id' ),
								'CommissionepMembreep.membreep_id IS NOT NULL',
								'CommissionepMembreep.presence <> \'present\''
							),
							'contain' => false
						)
					);
					$commissionseps[$key]['Commissionep']['nbabsents'] = $nbabsents;
				}
				$this->set( 'commissionseps', $commissionseps );
			}

// debug($commissionseps);
			$this->_setOptions();
			$compteurs = array(
				'Ep' => $this->Commissionep->Ep->find( 'count' )
			);
			$this->set( compact( 'compteurs' ) );
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

		public function arbitrageep() {
			$this->index( $this->action );
		}

		/**
		*
		*/

		public function arbitragecg() {
			$this->index( $this->action );
		}


		/**
		*
		*/

		public function decisions() {
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
			else if( $this->action == 'add' ) {
				$this->data['Commissionep']['etatcommissionep'] = 'cree';
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
			if( isset( $this->params['form']['Valider'] ) ) {
				$this->_finaliser( $commissionep_id, $niveauDecision );
/*				$this->redirect( array( 'controller' => 'commissionseps', 'action' => 'traiter'.$niveauDecision, $commissionep_id ) );*/
			}

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

			if( !empty( $this->data ) && !isset( $this->params['form']['Valider'] ) ) {
				$this->Commissionep->begin();
				$success = $this->Commissionep->saveDecisions( $commissionep_id, $this->data, $niveauDecision );

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Commissionep->commit();
					$this->redirect( array( 'controller' => 'commissionseps', 'action' => 'traiter'.$niveauDecision, $commissionep_id ) );
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

			if( !$this->_checkGedooo( true ) ) {
				$this->Session->setFlash( 'Le serveur d\'impression n\'est pas disponible ou ne fonctionne pas correctement.', 'default', array( 'class' => 'error' ) );
			}
			else {
				$this->Commissionep->begin();
				$success = $this->Commissionep->finaliser( $commissionep_id, $this->data, $niveauDecision, $this->Session->read( 'Auth.User.id' ) );

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Commissionep->commit();
					$this->redirect( array( 'action' => "decision{$niveauDecision}", $commissionep_id ) );
				}
				else {
					$this->Commissionep->rollback();
				}
			}
		}

		/**
		* Finalisation de la séance au niveau EP
		*/

		/*public function finaliserep( $commissionep_id ) {
			$this->_finaliser( $commissionep_id, 'ep' );
		}*/

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

		/*public function finalisercg( $commissionep_id ) {
			$this->_finaliser( $commissionep_id, 'cg' );
		}*/


		/**
		* Affiche la séance EP avec la liste de ses membres.
		* @param integer $commissionep_id
		*/
		public function view( $commissionep_id = null ) {
			$commissionep = $this->Commissionep->find(
				'first', array(
					'conditions' => array( 'Commissionep.id' => $commissionep_id ),
					'contain' => array(
	// 					'Structurereferente',
	// 					'Dossierep' => array(
	// 						'Personne'
	// 					),
						'Ep' => array( 'Regroupementep'/*, 'Membreep'*/),
						'CommissionepMembreep'
					)
				)
			);
// 			debug( $commissionep );
			if ( Configure::read( 'Cg.departement' ) == 66 ) {
				$listeMembrePresentRemplace = array();
				foreach( $commissionep['CommissionepMembreep'] as $membre ) {
					if ( $membre['reponse'] == 'confirme' || $membre['reponse'] == 'remplacepar' ) {
						$listeMembrePresentRemplace[] = $membre['membreep_id'];
					}
				}

				$compositionValide = $this->Commissionep->Ep->Regroupementep->Compositionregroupementep->compositionValide( $commissionep['Ep']['regroupementep_id'], $listeMembrePresentRemplace );
				if( !$compositionValide['check'] && isset( $compositionValide['error'] ) && !empty( $compositionValide['error'] ) ) {
					$message = null;
					if ( $compositionValide['error'] == 'obligatoire' ) {
						$message = "Pour une commission de ce regroupement, il faut au moins un membre occupant la fonction : ".implode( ' ou ', $this->Commissionep->Ep->Regroupementep->Compositionregroupementep->listeFonctionsObligatoires( $commissionep['Ep']['regroupementep_id'] ) ).".";
					}
					elseif ( $compositionValide['error'] == 'nbminmembre' ) {
						$message = "Il n'y a pas assez de membres qui ont accepté de venir ou qui se font remplacer pour que la commission puisse avoir lieu.";
					}
					elseif ( $compositionValide['error'] == 'nbmaxmembre' ) {
						$message = "Il y a trop de membres qui ont accepté de venir ou qui se font remplacer pour que la commission puisse avoir lieu.";
					}
					$this->set( 'messageQuorum', $message );
				}
			}

			list( $jourCommission, $heureCommission ) = explode( ' ', $commissionep['Commissionep']['dateseance'] );
			$presencesPossible = ( date( 'Y-m-d' ) >= $jourCommission );
			$this->set( compact( 'presencesPossible' ) );

			$this->set( 'commissionep', $commissionep );
			$this->_setOptions();

			// Dossiers à passer en séance, par thème traité
			$themes = array_keys( $this->Commissionep->themesTraites( $commissionep_id ) );
			$this->set(compact('themes'));
			$dossiers = array();
			$countDossiers = 0;
			foreach( $themes as $theme ) {
				$class = Inflector::classify( $theme );

				$qdListeDossier = $this->Commissionep->Passagecommissionep->Dossierep->{$class}->qdListeDossier();

				if ( isset( $qdListeDossier['fields'] ) ) {
					$qd['fields'] = $qdListeDossier['fields'];
				}
				$qd['conditions'] = array( 'Passagecommissionep.commissionep_id' => $commissionep_id, 'Dossierep.themeep' => Inflector::tableize( $class ) );
				$qd['joins'] = $qdListeDossier['joins'];
				$qd['contain'] = false;

				$dossiers[$theme] = $this->Commissionep->Passagecommissionep->Dossierep->find(
					'all',
					$qd
				);

				$countDossiers += count($dossiers[$theme]);
			}
// debug($dossiers);
			$dossierseps = $this->Commissionep->Passagecommissionep->find(
				'all',
				array(
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $commissionep_id
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
					)
				)
			);

			if( !empty( $dossierseps ) ){
				$this->set( compact( 'dossierseps' ) );
			}
			$this->set(compact('dossiers'));
			$this->set(compact('countDossiers'));

			$fields = array(
				'CommissionepMembreep.id',
				'CommissionepMembreep.commissionep_id',
				'CommissionepMembreep.membreep_id',
				'CommissionepMembreep.reponse',
				'CommissionepMembreep.presence',
				'CommissionepMembreep.reponsesuppleant_id',
				'CommissionepMembreep.presencesuppleant_id',
				'Membreep.id',
				'Membreep.qual',
				'Membreep.nom',
				'Membreep.prenom',
				'Membreep.tel',
				'Membreep.mail',
				'Membreep.organisme',
				'Membreep.fonctionmembreep_id',
				'Fonctionmembreep.name'
			);

			$membresepsseanceseps = $this->Commissionep->find(
				'all',
				array(
					'fields' => $fields,
					'conditions'=> array(
						'Commissionep.id' => $commissionep_id
					),
					'joins' => array(
						array(
							'alias' => 'Ep',
							'table' => 'eps',
							'type' => 'INNER',
							'conditions' => array(
								'Commissionep.ep_id = Ep.id'
							)
						),
						array(
							'alias' => 'EpMembreep',
							'table' => 'eps_membreseps',
							'type' => 'INNER',
							'conditions' => array(
								'EpMembreep.ep_id = Ep.id'
							)
						),
						array(
							'alias' => 'Membreep',
							'table' => 'membreseps',
							'type' => 'INNER',
							'conditions' => array(
								'Membreep.id = EpMembreep.membreep_id'
							)
						),
						array(
							'alias' => 'Fonctionmembreep',
							'table' => 'fonctionsmembreseps',
							'type' => 'INNER',
							'conditions' => array(
								'Membreep.fonctionmembreep_id = Fonctionmembreep.id'
							)
						),
						array(
							'alias' => 'CommissionepMembreep',
							'table' => 'commissionseps_membreseps',
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'CommissionepMembreep.commissionep_id = Commissionep.id',
								'CommissionepMembreep.membreep_id = Membreep.id'
							)
						)
					),
					'contain' => false
				)
			);
			$this->set('membresepsseanceseps', $membresepsseanceseps);

			$membreseps = $this->Commissionep->CommissionepMembreep->Membreep->find(
				'all',
				array(
					'fields' => array(
						'Membreep.id',
						'Membreep.qual',
						'Membreep.nom',
						'Membreep.prenom',
						'Membreep.fonctionmembreep_id'
					),
					'conditions' => array(
						'Membreep.id NOT IN ( '.$this->Commissionep->CommissionepMembreep->Membreep->EpMembreep->sq(
							array(
								'fields' => array(
									'eps_membreseps.membreep_id'
								),
								'alias' => 'eps_membreseps',
								'conditions' => array(
									'eps_membreseps.ep_id' => $commissionep['Commissionep']['ep_id']
								)
							)
						).' )'
					),
					'contain' => false
				)
			);

			$listemembreseps = array();
			foreach( $membreseps as $membreep ) {
				$listemembreseps[$membreep['Membreep']['id']] = implode( ' ', array( $membreep['Membreep']['qual'], $membreep['Membreep']['nom'], $membreep['Membreep']['prenom'] ) );
			}
			$this->set( compact( 'listemembreseps' ) );

			$this->set('etatsActions', $this->etatsActions);
		}

		/**
		* Passe une commission dont l'id est passé en paramètre en validé
		*/
		public function validecommission( $commissionep_id ) {
			$this->Commissionep->id = $commissionep_id;
			$this->Commissionep->saveField( 'etatcommissionep', 'valide' );
			$this->_setFlashResult( 'Save', true );
			$this->redirect( $this->referer() );
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

		/**
		* Génération du document d'invitation à une  EP au participant.
		* Courrier contenant le lieu, date et heure de la commission EP
		*/

		public function printConvocationParticipant( $commissionep_id, $membreep_id ) {
			$pdf = $this->Commissionep->getPdfConvocationParticipant( $commissionep_id, $membreep_id );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'ConvocationEPParticipant' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier d\'information', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		* Génération des documents d'invitation à une commission d'EP pour les participants.
		* Courrier contenant le lieu, date et heure de la commission EP
		*/

		public function printConvocationsParticipants( $ep_id, $commissionep_id ) {
			$liste = $this->Commissionep->Membreep->EpMembreep->find(
				'list',
				array(
					'fields' => array(
						'EpMembreep.id',
						'EpMembreep.membreep_id'
					),
					'conditions' => array(
						'EpMembreep.ep_id' => $ep_id,
					),
					'recursive' => -1
				)
			);

			$pdfs = array();
			foreach( array_values( $liste ) as $membreep_id ) {
				$pdfs[] = $this->Commissionep->getPdfConvocationParticipant( $commissionep_id, $membreep_id );
			}

			$pdfs = $this->Gedooo->concatPdfs( $pdfs, 'ConvocationEPParticipant' );

			if( $pdfs ) {
				$this->Gedooo->sendPdfContentToClient( $pdfs, 'ConvocationEPParticipant' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer les invitations pour les participants de cette commission.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}


		/**
		* Génération du document de convocation du passage en EP à l'allocataire.
		* Courrier contenant le lieu, date et heure de la commission EP
		*/

		public function printConvocationBeneficiaire( $passagecommissionep_id ) {
			$pdf = $this->Commissionep->Passagecommissionep->Dossierep->getConvocationBeneficiaireEpPdf( $passagecommissionep_id );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'ConvocationEPBeneficiaire' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier d\'information', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		* Génération de la cohorte des convocations de passage en commission d'EP aux allocataires.
		*/

		public function printConvocationsBeneficiaires( $commissionep_id ) {
			$liste = $this->Commissionep->Passagecommissionep->find(
				'list',
				array(
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $commissionep_id,
					),
					'recursive' => -1
				)
			);

			$pdfs = array();
			foreach( array_keys( $liste ) as $passagecommissionep_id ) {
				$pdfs[] = $this->Commissionep->Passagecommissionep->Dossierep->getConvocationBeneficiaireEpPdf( $passagecommissionep_id );
			}

			$pdfs = $this->Gedooo->concatPdfs( $pdfs, 'Passagecommissionep' );

			if( $pdfs ) {
				$this->Gedooo->sendPdfContentToClient( $pdfs, 'ConvocationsEPsBeneficiaire' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer les convocations aux bénéficiaires pour cette commission.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		* Vérification, pour une commission donnée, si on peut imprimer l'ordre du jour.
		* Sinon, ajout d'un message d'erreur et redirection vers la page précédente.
		*/

		protected function _checkPrintOrdreDuJour( $commissionep_id ) {
			$this->assert( !empty( $commissionep_id ), 'invalidParameter' );

			// Réponses prévisionnelles de participation
			$reponsesNonIndiquees = $this->Commissionep->CommissionepMembreep->find(
				'count',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id,
						'CommissionepMembreep.reponse' => 'nonrenseigne'
					)
				)
			);

			// Dossiers devant passer dans cette commission
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
		}

		/**
		*   Impression des convocations pour les participants à la commission d'EP
		*/

		public function printOrdreDuJour( $commissionep_membreep_id ) {
			$commissionep_id = $this->Commissionep->CommissionepMembreep->field( 'commissionep_id', array( 'CommissionepMembreep.id' => $commissionep_membreep_id ) );

			$this->_checkPrintOrdreDuJour( $commissionep_id );

			$pdf = $this->Commissionep->getPdfOrdredujour( $commissionep_membreep_id );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'ConvocationepParticipant' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer les convocations du participant à la commission d\'EP', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		*   Impression des convocations pour les participants à la commission d'EP
		*/

		public function printOrdresDuJour( $commissionep_id ) {
			$this->_checkPrintOrdreDuJour( $commissionep_id );

			$liste = $this->Commissionep->CommissionepMembreep->find(
				'list',
				array(
					'conditions' => array(
						'CommissionepMembreep.commissionep_id' => $commissionep_id,
					),
					'recursive' => -1
				)
			);

			$pdfs = array();
			foreach( array_keys( $liste ) as $commissionep_membreep_id ) {
				$pdfs[] = $this->Commissionep->getPdfOrdredujour( $commissionep_membreep_id );
			}

			$pdfs = $this->Gedooo->concatPdfs( $pdfs, 'ConvocationepParticipant' );

			if( $pdfs ) {
				$this->Gedooo->sendPdfContentToClient( $pdfs, 'ConvocationepParticipant' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer les convocations du participant à la commission d\'EP', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		* Impression des décisions émises par la commission de l'EP
		* Représente le point 11 du processus de l'EP
		*/

		public function printDecision( $commissionep_id ) {
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

			$pdf = $this->Commissionep->getPdfDecision( $commissionep_id );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'DecisionEP' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer les décisions émises par la commission d\'EP', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		* Affichage des décisions de la commission d'EP niveau EP
		*/

		public function decisionep( $commissionep_id ) {
			$this->_decision( $commissionep_id, 'ep' );
		}

		/**
		* Affichage des décisions de la commission d'EP niveau CG
		*/

		public function decisioncg( $commissionep_id ) {
			$this->_decision( $commissionep_id, 'cg' );
		}

		/**
		* Affichage des décisions de la commission d'EP
		*/

		protected function _decision( $commissionep_id, $niveauDecision ) {
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

			$dossiers = $this->Commissionep->dossiersParListe( $commissionep_id, $niveauDecision );

			if( in_array( Configure::read( 'Cg.departement' ), array( 58, 93 ) ) ) {
				$syntheses = $this->Commissionep->Passagecommissionep->find(
					'all',
					array(
						'conditions' => array(
							'Passagecommissionep.commissionep_id' => $commissionep_id
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
						)
					)
				);

				$this->set( compact( 'syntheses' ) );
				$this->set('etatsActions', $this->etatsActions);
			}

			$this->set( compact( 'commissionep', 'dossiers' ) );
			$this->set( 'commissionep_id', $commissionep_id);
			$this->_setOptions();
		}

		/**
		* Génération du PDF concernant la décision suite au passage en commission
		* d'un dossier d'EP pour un certain niveau de décision.
		*/

		public function impressionDecision( $passagecommissionep_id ) {
			$pdf = $this->Commissionep->Passagecommissionep->Dossierep->getDecisionPdf( $passagecommissionep_id  );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, "CourrierDecision-{$passagecommissionep_id}.pdf" );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier de décision', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		* Génération des décisions pour tous les dossiers d'EP d'une commission.
		*/

		public function impressionsDecisions( $commissionep_id ) {
			$liste = $this->Commissionep->Passagecommissionep->find(
				'list',
				array(
					'fields' => array(
						'Passagecommissionep.id',
						'Passagecommissionep.dossierep_id',
					),
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $commissionep_id,
					),
					'recursive' => -1
				)
			);

			$pdfs = array();
			foreach( array_keys( $liste ) as $passagecommissionep_id ) {
				$pdfs[] = $this->Commissionep->Passagecommissionep->Dossierep->getDecisionPdf( $passagecommissionep_id  );
			}

			// INFO: pour le CG 66, on n'a pas de PDF de décision pour toutes les thématiques
			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$pdfs = Set::filter( $pdfs );
			}

			$pdfs = $this->Gedooo->concatPdfs( $pdfs, 'DecisionsEPsBeneficiaire' );

			if( $pdfs ) {
				$this->Gedooo->sendPdfContentToClient( $pdfs, 'DecisionsEPsBeneficiaire' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer les courriers de décision pour cette commission.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		* Génération de la fiche de synthèse des différents dossiers d'EP
		*/

		public function fichesynthese( $commissionep_id, $dossierep_id, $anonymiser = false ) {
			$pdf = $this->Commissionep->getFicheSynthese( $commissionep_id, $dossierep_id, $anonymiser );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'FicheSynthetique' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier d\'information', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		* Génération de la cohorte des convocations de passage en commission d'EP aux allocataires.
		*/

		public function fichessynthese( $commissionep_id, $anonymiser = false ) {
			$liste = $this->Commissionep->Passagecommissionep->find(
				'list',
				array(
					'fields' => array(
						'Passagecommissionep.id',
						'Passagecommissionep.dossierep_id',
					),
					'conditions' => array(
						'Passagecommissionep.commissionep_id' => $commissionep_id,
					),
					'recursive' => -1
				)
			);

			$pdfs = array();
			foreach( array_values( $liste ) as $dossierep_id ) {
				$pdfs[] = $this->Commissionep->getFicheSynthese( $commissionep_id, $dossierep_id, $anonymiser );
			}

			$pdfs = $this->Gedooo->concatPdfs( $pdfs, 'Fichessynthese' );

			if( $pdfs ) {
				$this->Gedooo->sendPdfContentToClient( $pdfs, 'Fichessynthese' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer les fiches de synthèse pour cette commission.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>