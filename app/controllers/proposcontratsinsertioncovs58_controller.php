<?php

	App::import( 'Helper', 'Locale' );

	class Proposcontratsinsertioncovs58Controller extends AppController {

		public $name = "Proposcontratsinsertioncovs58";

		public $uses = array( 'Propocontratinsertioncov58', 'Option', 'Action' );
		public $helpers = array( 'Ajax' );
		public $components = array( 'RequestHandler' );
		public $aucunDroit = array( 'ajax', 'ajaxref', 'ajaxstruct', 'ajaxraisonci', 'notificationsop' );

		public $commeDroit = array(
			'add' => 'Contratsinsertion:edit'
		);

		/**
		*
		*/
		protected function _setOptions() {
			$options = $this->Propocontratinsertioncov58->allEnumLists();
			$this->set( 'options', $options );

			if( in_array( $this->action, array( 'index', 'add', 'edit', 'view', 'valider' ) ) ) {
				$this->set( 'decision_ci', $this->Option->decision_ci() );
				$forme_ci = array();
				if( Configure::read( 'nom_form_ci_cg' ) == 'cg93' ) {
					$forme_ci = array( 'S' => 'Simple', 'C' => 'Complexe' );
				}
				else if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) {
					$forme_ci = array( 'S' => 'Simple', 'C' => 'Particulier' );
				}
				$this->set( 'forme_ci', $forme_ci );
			}

			if( in_array( $this->action, array( 'add', 'edit', 'view' ) ) ) {
				$this->set( 'formeci', $this->Option->formeci() );
			}

			if( in_array( $this->action, array( 'add', 'edit'/*, 'view'*/ ) ) ) {
				$this->set( 'qual', $this->Option->qual() );
				$this->set( 'raison_ci', $this->Option->raison_ci() );
				$this->set( 'avisraison_ci', $this->Option->avisraison_ci() );
				$this->set( 'aviseqpluri', $this->Option->aviseqpluri() );
				$this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
				$this->set( 'emp_occupe', $this->Option->emp_occupe() );
				$this->set( 'duree_hebdo_emp', $this->Option->duree_hebdo_emp() );
				$this->set( 'nat_cont_trav', $this->Option->nat_cont_trav() );
				$this->set( 'duree_cdd', $this->Option->duree_cdd() );
				$this->set( 'duree_engag_cg66', $this->Option->duree_engag_cg66() );
				$this->set( 'duree_engag_cg93', $this->Option->duree_engag_cg93() );
				$this->set( 'duree_engag_cg58', $this->Option->duree_engag_cg58() );

				$this->set( 'typevoie', $this->Option->typevoie() );
				$this->set( 'fonction_pers', $this->Option->fonction_pers() );

				$this->set( 'lib_action', $this->Option->lib_action() );
				$this->set( 'typo_aide', $this->Option->typo_aide() );
				$this->set( 'soclmaj', $this->Option->natpfcre( 'soclmaj' ) );
				$this->set( 'rolepers', $this->Option->rolepers() );
				$this->set( 'zoneprivilegie', ClassRegistry::init( 'Zonegeographique' )->find( 'list' ) );
				$this->set( 'actions', $this->Action->grouplist( 'prest' ) );
			}
		}

		/**
		*   Ajax pour les coordonnées du référent APRE
		*/

		public function ajaxref( $referent_id = null ) { // FIXME
			Configure::write( 'debug', 0 );

			if( !empty( $referent_id ) ) {
				$referent_id = suffix( $referent_id );
			}
			else {
				$referent_id = suffix( Set::extract( $this->data, 'Propocontratinsertioncov58.referent_id' ) );
			}

			$referent = array();
			if( is_int( $referent_id ) ) {
				$referent = $this->Propocontratinsertioncov58->Referent->findbyId( $referent_id, null, null, -1
				);
			}

			$this->set( 'referent', $referent );
			$this->render( 'ajaxref', 'ajax' );
		}

		/**
		*   Ajax pour les coordonnées de la structure référente liée
		*/

		public function ajaxstruct( $structurereferente_id = null ) { // FIXME
			Configure::write( 'debug', 0 );
			$this->set( 'typesorients', $this->Typeorient->find( 'list', array( 'fields' => array( 'lib_type_orient' ) ) ) );

			$dataStructurereferente_id = Set::extract( $this->data, 'Propocontratinsertioncov58.structurereferente_id' );
			$structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );

			$struct = $this->Propocontratinsertioncov58->Structurereferente->findbyId( $structurereferente_id, null, null, -1 );
			$this->set( 'struct', $struct );
			$this->render( 'ajaxstruct', 'ajax' );
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null, $avenant_id = null ) {
			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $id ) );
			}

			$valueFormeci = null;

			$contratinsertion_id = null;
			$personne_id = $id;
			$nbrPersonnes = $this->Propocontratinsertioncov58->Dossiercov58->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );
			$valueFormeci = 'S';

			$nbContratsPrecedents = $this->Propocontratinsertioncov58->Dossiercov58->Personne->Contratinsertion->find( 'count', array( 'recursive' => -1, 'conditions' => array( 'Contratinsertion.personne_id' => $personne_id ) ) );
			if( $nbContratsPrecedents >= 1 ) {
				$tc = 'REN';
			}
			else {
				$tc = 'PRE';
			}

			if ( isset( $avenant_id ) && !empty( $avenant_id ) ) {
				$this->set( 'avenant_id', $avenant_id );
			}

			$this->set( 'nbContratsPrecedents',  $nbContratsPrecedents );
			/**
			*   Détails des précédents contrats
			*/
			$lastContrat = $this->Propocontratinsertioncov58->Dossiercov58->Personne->Contratinsertion->find(
				'all',
				array(
					'fields' => array(
						'Contratinsertion.rg_ci',
						'Contratinsertion.dd_ci',
						'Contratinsertion.df_ci',
						'Contratinsertion.structurereferente_id',
						'Structurereferente.lib_struc',
						'Contratinsertion.engag_object',
						'Contratinsertion.observ_ci',
						//thematique du contrat,
						'Contratinsertion.decision_ci',
					),
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne_id
					),
					'order' => 'Contratinsertion.date_saisi_ci DESC',
					'limit' => 5
				)
			);
			$this->set( 'lastContrat',  $lastContrat );


			/// Recherche du type d'orientation
			$orientstruct = $this->Propocontratinsertioncov58->Structurereferente->Orientstruct->find(
				'first',
				array(
					'conditions' => array(
						'Orientstruct.personne_id' => $personne_id,
						'Orientstruct.typeorient_id IS NOT NULL',
						'Orientstruct.statut_orient' => 'Orienté'
					),
					'order' => 'Orientstruct.date_valid DESC',
					'recursive' => -1
				)
			);
			$this->set( 'orientstruct', $orientstruct );

			///Personne liée au parcours
			$personne_referent = $this->Propocontratinsertioncov58->Dossiercov58->Personne->PersonneReferent->find(
				'first',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id' => $personne_id,
						'PersonneReferent.dfdesignation IS NULL'
					),
					'recursive' => -1
				)
			);

			$structures = $this->Propocontratinsertioncov58->Structurereferente->listOptions();
			$referents = $this->Propocontratinsertioncov58->Referent->listOptions();

			$this->set( 'tc', $tc );

			/// Peut-on prendre le jeton ?
			$this->Propocontratinsertioncov58->begin();
			$dossier_id = $this->Propocontratinsertioncov58->Dossiercov58->Personne->dossierId( $personne_id );
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Propocontratinsertioncov58->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );
			$this->set( 'dossier_id', $dossier_id );

			$situationdossierrsa = $this->Propocontratinsertioncov58->Dossiercov58->Personne->Foyer->Dossier->Situationdossierrsa->find(
				'first',
				array(
					'fields' => array(
						'Situationdossierrsa.id',
						'Situationdossierrsa.dtclorsa'
					),
					'conditions' => array(
						'Situationdossierrsa.dossier_id' => $dossier_id
					),
					'recursive' => -1
				)
			);
			$this->assert( !empty( $situationdossierrsa ), 'error500' );
			$this->set( 'situationdossierrsa_id', $situationdossierrsa['Situationdossierrsa']['id'] );

			//On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
			$personne = $this->Propocontratinsertioncov58->Dossiercov58->Personne->newDetailsCi( $personne_id, $this->Session->read( 'Auth.User.id' ) );
			$this->set( 'personne', $personne );

			/// Calcul du numéro du contrat d'insertion
			$nbrCi = $this->Propocontratinsertioncov58->Dossiercov58->Personne->Contratinsertion->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );
			$this->set( 'nbrCi', $nbrCi );

			$this->set( 'valueFormeci', $valueFormeci );

			/// Essai de sauvegarde
			if( !empty( $this->data ) ) {

				$success = true;

				if ( $this->action == 'add' ) {
					$themecov58 = $this->Propocontratinsertioncov58->Dossiercov58->Themecov58->find(
						'first',
						array(
							'conditions' => array(
								'Themecov58.name' => Inflector::tableize($this->Propocontratinsertioncov58->alias)
							),
							'contain' => false
						)
					);
					$dossiercov58['Dossiercov58']['themecov58_id'] = $themecov58['Themecov58']['id'];
					$dossiercov58['Dossiercov58']['personne_id'] = $personne_id;
					$dossiercov58['Dossiercov58']['themecov58'] = 'proposcontratsinsertioncovs58';

					$success = $this->Propocontratinsertioncov58->Dossiercov58->save($dossiercov58) && $success;
					$this->data['Propocontratinsertioncov58']['dossiercov58_id'] = $this->Propocontratinsertioncov58->Dossiercov58->id;
				}

				$this->data['Propocontratinsertioncov58']['rg_ci'] = $nbrCi + 1;

				$this->data['Propocontratinsertioncov58']['forme_ci'] = 'S';

				$this->data['Propocontratinsertioncov58']['datedemande'] = date( 'Y-m-d' );

				$contratinsertionRaisonCi = Set::classicExtract( $this->data, 'Propocontratinsertioncov58.raison_ci' );
				if( $contratinsertionRaisonCi == 'S' ) {
					$this->data['Propocontratinsertioncov58']['avisraison_ci'] = Set::classicExtract( $this->data, 'Propocontratinsertioncov58.avisraison_suspension_ci' );
				}
				else if( $contratinsertionRaisonCi == 'R' ){
					$this->data['Propocontratinsertioncov58']['avisraison_ci'] = Set::classicExtract( $this->data, 'Propocontratinsertioncov58.avisraison_radiation_ci' );
				}
				/// Validation
				$success = $this->Propocontratinsertioncov58->save( $this->data ) && $success;

				if( $success ) {
					$saved = true;
					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Propocontratinsertioncov58->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Propocontratinsertioncov58->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$this->Propocontratinsertioncov58->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				if( $this->action == 'edit' ) {

					$this->data = $this->Propocontratinsertioncov58->find(
						'first',
						array(
							'joins' => array(
								array(
									'table' => 'dossierscovs58',
									'alias' => 'Dossiercov58',
									'type' => 'INNER',
									'conditions' => array(
										'Dossiercov58.id = Propocontratinsertioncov58.dossiercov58_id',
										'Dossiercov58.personne_id' => $personne_id
									)
								)
							),
							'contain' => false,
							'order' => array( 'Propocontratinsertioncov58.df_ci DESC' )
						)
					);
				}
			}

			// Doit-on setter les valeurs par défault ?
			$dataStructurereferente_id = Set::classicExtract( $this->data, "{$this->Propocontratinsertioncov58->alias}.structurereferente_id" );
			$dataReferent_id = Set::classicExtract( $this->data, "{$this->Propocontratinsertioncov58->alias}.referent_id" );

			// Si le formulaire ne possède pas de valeur pour ces champs, on met celles par défaut
			if( empty( $dataStructurereferente_id ) && empty( $dataReferent_id ) ) {
				$structurereferente_id = $referent_id = null;
				// Valeur par défaut préférée: à partir de personnes_referents
				if( !empty( $personne_referent ) ){
					$structurereferente_id = Set::classicExtract( $personne_referent, "{$this->Propocontratinsertioncov58->Dossiercov58->Personne->PersonneReferent->alias}.structurereferente_id" );
					$referent_id = Set::classicExtract( $personne_referent, "{$this->Propocontratinsertioncov58->Dossiercov58->Personne->PersonneReferent->alias}.referent_id" );
				}
				// Valeur par défaut de substitution: à partir de orientsstructs
				else if( !empty( $orientstruct ) ) {
					$structurereferente_id = Set::classicExtract( $orientstruct, "{$this->Propocontratinsertioncov58->Structurereferente->Orientstruct->alias}.structurereferente_id" );
					$referent_id = Set::classicExtract( $orientstruct, "{$this->Propocontratinsertioncov58->Structurereferente->Orientstruct->alias}.referent_id" );
				}


				if( !empty( $structurereferente_id ) ) {
					$this->data = Set::insert( $this->data, "{$this->Propocontratinsertioncov58->alias}.structurereferente_id", $structurereferente_id );
				}
				if( !empty( $structurereferente_id ) && !empty( $referent_id ) ) {
					$this->data = Set::insert( $this->data, "{$this->Propocontratinsertioncov58->alias}.referent_id", preg_replace( '/^_$/', '', "{$structurereferente_id}_{$referent_id}" ) );
				}
			}

			$struct_id = Set::classicExtract( $this->data, 'Propocontratinsertioncov58.structurereferente_id' );
			$this->set( 'struct_id', $struct_id );

			if( !empty( $struct_id ) ) {
				$struct = $this->Propocontratinsertioncov58->Structurereferente->find(
					'first',
					array(
						'fields' => array(
							'Structurereferente.num_voie',
							'Structurereferente.type_voie',
							'Structurereferente.nom_voie',
							'Structurereferente.code_postal',
							'Structurereferente.ville',
						),
						'conditions' => array(
							'Structurereferente.id' => Set::extract( $this->data, 'Propocontratinsertioncov58.structurereferente_id' )
						),
						'recursive' => -1
					)
				);
				$this->set( 'StructureAdresse', $struct['Structurereferente']['num_voie'].' '.$struct['Structurereferente']['type_voie'].' '.$struct['Structurereferente']['nom_voie'].'<br/>'.$struct['Structurereferente']['code_postal'].' '.$struct['Structurereferente']['ville'] );
			}

			$referent_id = Set::classicExtract( $this->data, 'Propocontratinsertioncov58.referent_id' );
			$referent_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $referent_id );
			$this->set( 'referent_id', $referent_id );

			if( !empty( $referent_id ) && !empty( $this->data['Propocontratinsertioncov58']['referent_id'] ) ) {
				$contratinsertionReferentId = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Propocontratinsertioncov58']['referent_id'] );
				$referent = $this->Propocontratinsertioncov58->Referent->find(
					'first',
					array(
						'fields' => array(
							'Referent.email',
							'Referent.fonction',
							'Referent.nom',
							'Referent.prenom',
							'Referent.numero_poste',
						),
						'conditions' => array(
							'Referent.id' => $contratinsertionReferentId
						),
						'recursive' => -1
					)
				);
				$this->set( 'ReferentEmail', $referent['Referent']['email']. '<br/>' .$referent['Referent']['numero_poste'] );
				$this->set( 'ReferentFonction', $referent['Referent']['fonction'] );
				$this->set( 'ReferentNom', $referent['Referent']['nom'].' '.$referent['Referent']['prenom'] );
			}
			$this->Propocontratinsertioncov58->commit();
			$this->_setOptions();
			$this->set( compact( 'structures', 'referents' ) );
			$this->set( 'urlmenu', '/contratsinsertion/index/'.$personne_id );

			if( Configure::read( 'nom_form_ci_cg' ) == 'cg58' ) {
				$this->render( $this->action, null, 'add_edit_specif_cg58' );
			}
			else {
				$this->render( $this->action, null, 'add_edit' );
			}
		}

		/**
		 * Suppression de la proposition de contrat d'insertion en COV lorsque le dossier COV n'est pas
		 * encore attaché à une COV.
		 *
		 * @param integer $propocontratinsertioncov58_id L'id de la proposition de contrat d'insertion
		 */
		public function delete( $propocontratinsertioncov58_id ) {
			$propocontratinsertioncov58 = $this->Propocontratinsertioncov58->find(
				'first',
				array(
					'fields' => array(
						'Propocontratinsertioncov58.id',
						'Propocontratinsertioncov58.dossiercov58_id'
					),
					'contain' => false,
					'conditions' => array(
						'Propocontratinsertioncov58.id' => $propocontratinsertioncov58_id
					)
				)
			);

			$this->Propocontratinsertioncov58->begin();

			$success = $this->Propocontratinsertioncov58->delete( $propocontratinsertioncov58['Propocontratinsertioncov58']['id'] );
			$success = $this->Propocontratinsertioncov58->Dossiercov58->delete( $propocontratinsertioncov58['Propocontratinsertioncov58']['dossiercov58_id'] ) && $success;

			$this->_setFlashResult( 'Delete', $success );

			if( $success ) {
				$this->Propocontratinsertioncov58->commit();
			}
			else {
				$this->Propocontratinsertioncov58->rollback();
			}
			$this->redirect( $this->referer() );
		}
	}
?>