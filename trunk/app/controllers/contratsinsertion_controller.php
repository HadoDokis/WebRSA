<?php
	/**
		FIXME / TODO
		* Passer en dur:
			* Suivi d'insertion / CI en alternance

		* Prend une mauvaise valeur:
			* Suivi d'insertion -> CI en alternance
	**/
	App::import( 'Helper', 'Locale' );

	class ContratsinsertionController extends AppController
	{
		public $name = 'Contratsinsertion';
		public $uses = array( 'Contratinsertion', 'Option', 'Action', 'Referent', 'Personne', 'Dossier', 'Structurereferente', 'Dsp', 'Typeorient', 'Orientstruct', 'Serviceinstructeur', 'Action', 'Adressefoyer', 'Actioninsertion', 'Prestform', 'Refpresta', 'PersonneReferent' );
		public $helpers = array( 'Default2', 'Ajax', 'Fileuploader' );
		public $components = array( 'RequestHandler', 'Gedooo', 'Fileuploader' );

		public $commeDroit = array(
			'view' => 'Contratsinsertion:index',
			'add' => 'Contratsinsertion:edit'
		);

		public $aucunDroit = array( 'ajax', 'ajaxref', 'ajaxstruct', 'ajaxraisonci', 'notificationsop', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		/**
		*
		*/

		protected function _setOptions() {
			$options = $this->Contratinsertion->allEnumLists();

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

			if( in_array( $this->action, array( 'add', 'edit', 'view', 'valider' ) ) ) {
				$this->set( 'formeci', $this->Option->formeci() );
			}

			if( in_array( $this->action, array( 'add', 'edit', 'view' ) ) ) {
				$this->set( 'qual', $this->Option->qual() );
				$this->set( 'raison_ci', $this->Option->raison_ci() );
				if( Configure::read( 'Cg.departement' ) == 66 ){
					$this->set( 'avisraison_ci', $this->Option->avisraison_ci() );
				}
				else if( Configure::read( 'Cg.departement' ) == 93 ){
					$this->set( 'avisraison_ci', array( 'D' => 'Defaut de conclusion', 'N' => 'Non respect du contrat' ) );
				}
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
				$this->set( 'nivetus', $this->Contratinsertion->Personne->Dsp->enumList( 'nivetu' ) );
				$this->set( 'nivdipmaxobt', $this->Contratinsertion->Personne->Dsp->enumList( 'nivdipmaxobt' ) );
				$this->set( 'typeserins', $this->Option->typeserins() );

				$this->set( 'lib_action', $this->Option->lib_action() );
				$this->set( 'typo_aide', $this->Option->typo_aide() );
				$this->set( 'soclmaj', $this->Option->natpfcre( 'soclmaj' ) );
				$this->set( 'rolepers', $this->Option->rolepers() );
				$this->set( 'sitfam', $this->Option->sitfam() );
				$this->set( 'typeocclog', $this->Option->typeocclog() );
				$this->set( 'emp_trouv', array( 'N' => 'Non', 'O' => 'Oui' ) );
				$this->set( 'zoneprivilegie', ClassRegistry::init( 'Zonegeographique' )->find( 'list' ) );
				$this->set( 'actions', $this->Action->grouplist( 'prest' ) );
				$optionsautreavissuspension = $this->Contratinsertion->Autreavissuspension->allEnumLists();
				$optionsautreavisradiation = $this->Contratinsertion->Autreavisradiation->allEnumLists();
				$this->set( 'fiches', $this->Contratinsertion->Personne->ActioncandidatPersonne->Actioncandidat->allEnumLists() );

				$options = array_merge( $options, $optionsautreavissuspension );
				$options = array_merge( $options, $optionsautreavisradiation );

			}
			$options = array_merge(
				$this->Contratinsertion->enums(),
				$options
			);
// 			$options = array_merge(
// 				$this->Contratinsertion->Objetcontratprecedent->enums(),
// 				$options
// 			);
			$this->set( 'options', $options );
		}

		/**
		*
		*/

		protected function _libelleTypeorientNiv0( $typeorient_id ) {
			$typeorient_niv1_id = $this->Contratinsertion->Personne->Orientstruct->Typeorient->getIdLevel0( $typeorient_id );
			$typeOrientation = $this->Contratinsertion->Personne->Orientstruct->Typeorient->find(
				'first',
				array(
					'fields' => array( 'Typeorient.lib_type_orient' ),
					'recursive' => -1,
					'conditions' => array(
						'Typeorient.id' => $typeorient_niv1_id
					)
				)
			);
			return Set::classicExtract( $typeOrientation, 'Typeorient.lib_type_orient' );
		}

		/**
		*
		*/

		protected function _referentStruct( $structurereferente_id ) {
			$referents = $this->Contratinsertion->Structurereferente->Referent->find(
				'all',
				array(
					'recursive' => -1,
					'fields' => array( 'Referent.id', 'Referent.qual', 'Referent.nom', 'Referent.prenom', 'Referent.fonction' ),
					'conditions' => array( 'structurereferente_id' => $structurereferente_id )
				)
			);
			if( !empty( $referents ) ) {
				$ids = Set::extract( $referents, '/Referent/id' );
				$values = Set::format( $referents, '{0} {1}', array( '{n}.Referent.nom', '{n}.Referent.prenom' ) );
				$referents = array_combine( $ids, $values );
			}
			return $referents;
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
				$referent_id = suffix( Set::extract( $this->data, 'Contratinsertion.referent_id' ) );
			}

			$referent = array();
			if( is_int( $referent_id ) ) {
				$referent = $this->Contratinsertion->Structurereferente->Referent->findbyId( $referent_id, null, null, -1
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

			$dataStructurereferente_id = Set::extract( $this->data, 'Contratinsertion.structurereferente_id' );
			$structurereferente_id = ( empty( $structurereferente_id ) && !empty( $dataStructurereferente_id ) ? $dataStructurereferente_id : $structurereferente_id );

			$struct = $this->Contratinsertion->Structurereferente->findbyId( $structurereferente_id, null, null, -1 );
			$this->set( 'struct', $struct );
			$this->render( 'ajaxstruct', 'ajax' );
		}

		/**
		* http://valums.com/ajax-upload/
		* http://doc.ubuntu-fr.org/modules_php
		* increase post_max_size and upload_max_filesize to 10M
		* debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		*/

		public function ajaxfileupload() {
			$this->Fileuploader->ajaxfileupload();
		}

		/**
		* http://valums.com/ajax-upload/
		* http://doc.ubuntu-fr.org/modules_php
		* increase post_max_size and upload_max_filesize to 10M
		* debug( array( ini_get( 'post_max_size' ), ini_get( 'upload_max_filesize' ) ) ); -> 10M
		* FIXME: traiter les valeurs de retour
		*/

		public function ajaxfiledelete() {
			$this->Fileuploader->ajaxfiledelete();
		}

		/**
		*   Fonction permettant de visualiser les fichiers chargés dans la vue avant leur envoi sur le serveur
		*/

		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		*   Téléchargement des fichiers préalablement associés à un traitement donné
		*/

		public function download( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );
			$this->Fileuploader->download( $fichiermodule_id );
		}

		/**
		*   Fonction permettant d'accéder à la page pour lier les fichiers à l'Orientation
		*/

		public function filelink( $id ){
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$fichiers = array();
			$contratinsertion = $this->Contratinsertion->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);

			$personne_id = $contratinsertion['Contratinsertion']['personne_id'];
			$dossier_id = $this->Contratinsertion->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Contratinsertion->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Contratinsertion->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {
				$saved = $this->Contratinsertion->updateAll(
					array( 'Contratinsertion.haspiecejointe' => '\''.$this->data['Contratinsertion']['haspiecejointe'].'\'' ),
					array(
						'"Contratinsertion"."personne_id"' => $personne_id,
						'"Contratinsertion"."id"' => $id
					)
				);

				if( $saved ){
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->data, "Contratinsertion.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->Contratinsertion->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array(  'controller' => 'contratsinsertion','action' => 'index', $personne_id ) );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Contratinsertion->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'contratinsertion' ) );
			$this->set( 'urlmenu', '/contratsinsertion/index/'.$personne_id );
		}

		/**
		*
		*/

		protected function _qdThematiqueEp( $modele, $personne_id ) {
			return array(
				'fields' => array(
					'Dossierep.id',
					'Dossierep.personne_id',
					'Dossierep.themeep',
					'Dossierep.created',
					'Dossierep.modified',
					'Passagecommissionep.etatdossierep',
					'Contratinsertion.dd_ci',
					'Contratinsertion.df_ci',
				),
				'conditions' => array(
					'Dossierep.personne_id' => $personne_id,
					'Dossierep.themeep' => Inflector::tableize( $modele ),
					'Dossierep.id NOT IN ( '.$this->Contratinsertion->{$modele}->Dossierep->Passagecommissionep->sq(
						array(
							'alias' => 'passagescommissionseps',
							'fields' => array(
								'passagescommissionseps.dossierep_id'
							),
							'conditions' => array(
								'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
							)
						)
					).' )'
				),
				'joins' => array(
					array(
						'table'      => Inflector::tableize( $modele ),
						'alias'      => $modele,
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Dossierep.id = {$modele}.dossierep_id" )
					),
					array(
						'table'      => 'contratsinsertion',
						'alias'      => 'Contratinsertion',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( "Contratinsertion.id = {$modele}.contratinsertion_id" )
					),
					array(
						'table'      => 'passagescommissionseps',
						'alias'      => 'Passagecommissionep',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Dossierep.id = Passagecommissionep.dossierep_id' )
					),
				),
			);
		}

		/**
		*
		*/

		public function index( $personne_id = null ){
			// On s'assure que la personne existe
			$nbrPersonnes = $this->Contratinsertion->Personne->find(
				'count',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id ),
						'recursive' => -1
				)
			);
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			// Recherche du nombre de référent lié au parcours de la personne
			// Si aucun alors message d'erreur signalant l'absence de référent (cg66)
			$orientstruct = $this->Orientstruct->find(
				'count',
				array(
					'conditions' => array(
						'Orientstruct.personne_id' => $personne_id,
						'Orientstruct.statut_orient' => 'Orienté'
					),
					'recursive' => -1
				)
			);
			$this->set( compact( 'orientstruct' ) );

			$persreferent = $this->PersonneReferent->find(
				'count',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id' => $personne_id
					),
					'recursive' => -1
				)
			);
			$this->set( compact( 'persreferent' ) );

			$contratsinsertion = $this->Contratinsertion->find(
				'all',
				array(
					'fields' => array(
						'Contratinsertion.id',
						'Contratinsertion.forme_ci',
						'Contratinsertion.decision_ci',
						'Contratinsertion.num_contrat',
						'Contratinsertion.dd_ci',
						'Contratinsertion.positioncer',
						'Contratinsertion.df_ci',
						'Contratinsertion.date_saisi_ci',
						'Contratinsertion.datevalidation_ci',
						'Contratinsertion.avenant_id'
					),
					'conditions' => array(
						'Contratinsertion.personne_id' => $personne_id
					),
					'order' => array(
						'Contratinsertion.df_ci DESC',
						'Contratinsertion.id DESC'
					),
					'recursive' => -1
				)
			);

			$this->_setOptions();
			$this->set( compact( 'contratsinsertion' ) );
			$this->set( 'personne_id', $personne_id );

			if ( Configure::read('Cg.departement') == 58 ) {
				$propocontratinsertioncov58 = $this->Contratinsertion->Personne->Dossiercov58->Propocontratinsertioncov58->find(
					'first',
					array(
						'fields' => array(
							'Propocontratinsertioncov58.id',
							'Propocontratinsertioncov58.dossiercov58_id',
							'Propocontratinsertioncov58.forme_ci',
							'Propocontratinsertioncov58.num_contrat',
							'Propocontratinsertioncov58.dd_ci',
							'Propocontratinsertioncov58.df_ci',
							'Propocontratinsertioncov58.avenant_id',
							'Dossiercov58.personne_id',
							'Passagecov58.etatdossiercov',
							'Personne.id',
							'Personne.nom',
							'Personne.prenom',
							'Decisionpropocontratinsertioncov58.decisioncov'
						),
						'conditions' => array(
							'Dossiercov58.personne_id' => $personne_id,
							'Themecov58.name' => 'proposcontratsinsertioncovs58',
							'OR' => array(
								'Passagecov58.etatdossiercov NOT' => array( 'traite', 'annule' ),
								'Passagecov58.etatdossiercov IS NULL'
							)
						),
						'joins' => array(
							array(
								'table' => 'dossierscovs58',
								'alias' => 'Dossiercov58',
								'type' => 'INNER',
								'conditions' => array(
									'Dossiercov58.id = Propocontratinsertioncov58.dossiercov58_id'
								)
							),
							array(
								'table' => 'passagescovs58',
								'alias' => 'Passagecov58',
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Passagecov58.dossiercov58_id = Dossiercov58.id'
								)
							),
							array(
								'table' => 'decisionsproposcontratsinsertioncovs58',
								'alias' => 'Decisionpropocontratinsertioncov58',
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Decisionpropocontratinsertioncov58.passagecov58_id = Passagecov58.id'
								)
							),
							array(
								'table' => 'themescovs58',
								'alias' => 'Themecov58',
								'type' => 'INNER',
								'conditions' => array(
									'Dossiercov58.themecov58_id = Themecov58.id'
								)
							),
							array(
								'table' => 'personnes',
								'alias' => 'Personne',
								'type' => 'INNER',
								'conditions' => array(
									'Dossiercov58.personne_id = Personne.id'
								)
							)
						),
						'contain' => false,
						'order' => array( 'Propocontratinsertioncov58.df_ci DESC' )
					)
				);
// debug( $propocontratinsertioncov58 );
				$this->set( 'propocontratinsertioncov58', $propocontratinsertioncov58 );
				$this->set( 'optionsdossierscovs58', array_merge( $this->Orientstruct->Personne->Dossiercov58->Passagecov58->enums(), $this->Orientstruct->Personne->Dossiercov58->Propocontratinsertioncov58->enums() ) );

				$nbdossiersnonfinalisescovs = $this->Contratinsertion->Personne->Dossiercov58->find(
					'count',
					array(
						'conditions' => array(
							'Dossiercov58.id NOT IN ( '.$this->Contratinsertion->Personne->Dossiercov58->Passagecov58->sq(
								array(
									'fields' => array(
										'passagescovs58.dossiercov58_id'
									),
									'alias' => 'passagescovs58',
									'conditions' => array(
										'dossierscovs58.themecov58' => 'proposcontratsinsertioncovs58',
										'dossierscovs58.personne_id' => $personne_id,
										'passagescovs58.etatdossiercov' => array( 'traite', 'annule' )
									),
									'joins' => array(
										array(
											'table' => 'dossierscovs58',
											'alias' => 'dossierscovs58',
											'type' => 'INNER',
											'conditions' => array(
												'passagescovs58.dossiercov58_id = dossierscovs58.id'
											)
										),
										array(
											'table' => 'covs58',
											'alias' => 'covs58',
											'type' => 'LEFT OUTER',
											'conditions' => array(
												'passagescovs58.cov58_id = covs58.id'
											)
										)
									)
								)
							).' )',
							'Dossiercov58.personne_id' => $personne_id,
							'Dossiercov58.themecov58' => 'proposcontratsinsertioncovs58'
						),
						'contain' => false,
						'joins' => array(
							array(
								'table' => 'passagescovs58',
								'alias' => 'Passagecov58',
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Dossiercov58.id = Passagecov58.dossiercov58_id'
								)
							),
							array(
								'table' => 'decisionsproposcontratsinsertioncovs58',
								'alias' => 'Decisionpropocontratinsertioncov58',
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Decisionpropocontratinsertioncov58.passagecov58_id = Passagecov58.id'
								)
							)
						)
					)
				);
				$this->set( 'nbdossiersnonfinalisescovs', $nbdossiersnonfinalisescovs );
// debug($nbdossiersnonfinalisescovs);

				$queryData = $this->_qdThematiqueEp( 'Sanctionep58', $personne_id );
				$queryData['fields'] = Set::merge(
					$queryData['fields'],
					array(
						'Sanctionep58.id',
						'Sanctionep58.contratinsertion_id',
						'Sanctionep58.created',
						'Sanctionep58.modified',
					)
				);

				$sanctionseps58 = $this->Contratinsertion->Signalementep93->Dossierep->find( 'all', $queryData );

				$contratsenep = Set::extract( $sanctionseps58, '/Sanctionep58/contratinsertion_id' );

				$this->set( compact( 'sanctionseps58', 'contratsenep' ) );

				$this->set( 'erreursCandidatePassage', $this->Contratinsertion->Sanctionep58->Dossierep->erreursCandidatePassage( $personne_id ) );
				$this->set( 'optionsdossierseps', $this->Contratinsertion->Sanctionep58->Dossierep->Passagecommissionep->enums() );
			}
			else if ( Configure::read( 'Cg.departement' ) == 93 ) {
				// Des dossiers pour la thématique des signalements ?
				$queryData = $this->_qdThematiqueEp( 'Signalementep93', $personne_id );
				$queryData['fields'] = Set::merge(
					$queryData['fields'],
					array(
						'Signalementep93.contratinsertion_id',
						'Signalementep93.id',
						'Signalementep93.motif',
						'Signalementep93.date',
						'Signalementep93.rang',
						'Signalementep93.created',
						'Signalementep93.modified',
					)
				);

				$signalementseps93 = $this->Contratinsertion->Signalementep93->Dossierep->find( 'all', $queryData );

				// Des dossiers pour la thématique des signalements ?
				$queryData = $this->_qdThematiqueEp( 'Contratcomplexeep93', $personne_id );
				$queryData['fields'] = Set::merge(
					$queryData['fields'],
					array(
						'Contratcomplexeep93.contratinsertion_id',
						'Contratcomplexeep93.id',
						'Contratcomplexeep93.created',
						'Contratcomplexeep93.modified',
					)
				);
				$contratscomplexeseps93 = $this->Contratinsertion->Contratcomplexeep93->Dossierep->find( 'all', $queryData );

				$contratsenep = Set::merge(
					Set::extract( $signalementseps93, '/Signalementep93/contratinsertion_id' ),
					Set::extract( $contratscomplexeseps93, '/Contratcomplexeep93/contratinsertion_id' )
				);

				$this->set( compact( 'signalementseps93', 'contratscomplexeseps93', 'contratsenep' ) );

				$this->set( 'erreursCandidatePassage', $this->Contratinsertion->Signalementep93->Dossierep->erreursCandidatePassage( $personne_id ) );
				$this->set( 'optionsdossierseps', $this->Contratinsertion->Signalementep93->Dossierep->Passagecommissionep->enums() );
			}

			///FIXME: pas propre, mais pr le moment ça marche afin d'eviter de tout renommer
			$this->render( $this->action, null, '/contratsinsertion/index_'.Configure::read( 'nom_form_ci_cg' ) );
		}

		/**
		*
		*/

		public function view( $contratinsertion_id = null ) {
			// On a seulement besoin des modèles liés Structurereferente, Referent et Typeorient
			$binds = array(
				'belongsTo' => array(
					'Structurereferente' => $this->Contratinsertion->belongsTo['Structurereferente'],
					'Referent' => $this->Contratinsertion->belongsTo['Referent'],
					'Personne' => $this->Contratinsertion->belongsTo['Personne'],
					'Typeorient' => array(
						'table'      => $this->Typeorient->getDataSource()->fullTableName( $this->Typeorient, false ),
						'alias'      => 'Typeorient',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Structurereferente.typeorient_id = Typeorient.id' )
					)
				)
			);
			$this->Contratinsertion->unbindModelAll();
			$this->Contratinsertion->bindModel( $binds );
			$this->Contratinsertion->forceVirtualFields = true;
			$contratinsertion = $this->Contratinsertion->find(
				'first',
				array(
					'fields' => array(
						'Contratinsertion.id',
						'Contratinsertion.personne_id',
						'Contratinsertion.forme_ci',
						'Contratinsertion.num_contrat',
						'Contratinsertion.dd_ci',
						'Contratinsertion.df_ci',
						'Contratinsertion.diplomes',
						'Contratinsertion.expr_prof',
						'Contratinsertion.form_compl',
						'Contratinsertion.rg_ci',
						'Contratinsertion.actions_prev',
						'Contratinsertion.obsta_renc',
						'Contratinsertion.service_soutien',
						'Contratinsertion.pers_charg_suivi',
						'Contratinsertion.objectifs_fixes',
						'Contratinsertion.sect_acti_emp',
						'Contratinsertion.emp_occupe',
						'Contratinsertion.duree_hebdo_emp',
						'Contratinsertion.sitfam_ci',
						'Contratinsertion.sitpro_ci',
						'Contratinsertion.observ_benef',
						'Contratinsertion.nature_projet',
						'Contratinsertion.engag_object',
						'Contratinsertion.current_action',
						'Contratinsertion.nat_cont_trav',
						'Contratinsertion.duree_cdd',
						'Contratinsertion.duree_engag',
						'Contratinsertion.nature_projet',
						'Contratinsertion.avenant_id',
						'Contratinsertion.engag_object',
						'Contratinsertion.date_saisi_ci',
						'Contratinsertion.lieu_saisi_ci',
						'Action.libelle',
						'Actioninsertion.dd_action',
						'Structurereferente.lib_struc',
						'Structurereferente.typeorient_id',
						'Referent.nom_complet',
						'Personne.nom_complet',
						'Typeorient.lib_type_orient',
					),
					'conditions' => array(
						'Contratinsertion.id' => $contratinsertion_id
					),
					'joins' => array(
						array(
							'table'      => 'actions', // FIXME
							'alias'      => 'Action',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Contratinsertion.engag_object = Action.code' )
						),
						array(
							'table'      => 'actionsinsertion', // FIXME
							'alias'      => 'Actioninsertion',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array( 'Actioninsertion.contratinsertion_id = Contratinsertion.id' )
						)
					),
					'recursive' => 0
				)
			);
			$this->Contratinsertion->forceVirtualFields = false;
			$this->assert( !empty( $contratinsertion ), 'invalidParameter' );

			$this->set( 'contratinsertion', $contratinsertion );

			/**
			*   Utilisé pour les détections de fiche de candidature
			*   pour savoir si des actions sont en cours ou non
			*/
			$fichescandidature = $this->Contratinsertion->Personne->ActioncandidatPersonne->find(
				'all',
				array(
					'conditions' => array(
						'ActioncandidatPersonne.personne_id' => $contratinsertion['Contratinsertion']['personne_id'],
						'ActioncandidatPersonne.positionfiche = \'encours\'',
					),
					'contain' => array(
						'Actioncandidat' => array(
							'Contactpartenaire' => array(
								'Partenaire'
							)
						),
						'Referent'
					)
				)
			);
			$this->set( compact( 'fichescandidature' ) );

			$this->_setOptions();
			$this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );
			$this->set( 'urlmenu', '/contratsinsertion/index/'.$contratinsertion['Contratinsertion']['personne_id'] );

			// Retour à la liste en cas d'annulation
			if(  isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id'] ) );
			}
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

		protected function _getDsp( $personne_id ) {
			/// Récupération des données socio pro (notamment Niveau etude) lié au contrat
			$this->Contratinsertion->Personne->Dsp->unbindModelAll();
			$dsp = $this->Contratinsertion->Personne->Dsp->find(
				'first',
				array(
					'fields' => array(
						'Dsp.id',
						'Dsp.personne_id',
						'Dsp.nivetu',
						'Dsp.nivdipmaxobt',
						'Dsp.annobtnivdipmax',
					),
					'conditions' => array(
						'Dsp.personne_id' => $personne_id
					),
					'recursive' => -1
				)
			);

			if( empty( $dsp ) ){
				$dsp = array( 'Dsp' => array( 'personne_id' => $personne_id ) );

				$this->Contratinsertion->Personne->Dsp->set( $dsp );
				if( $this->Contratinsertion->Personne->Dsp->save( $dsp ) ) {
					$dsp = $this->Contratinsertion->Personne->Dsp->findByPersonneId( $personne_id, null, null, 1 );

				}
				else {
					$this->cakeError( 'error500' );
				}
				$this->assert( !empty( $dsp ), 'error500' );
			}

			$return = array();
			$return['Dsp'] = array(
				'id' => $dsp['Dsp']['id'],
				'personne_id' => $dsp['Dsp']['personne_id']
			);
			$return['Dsp']['nivetu'] = ( ( isset( $dsp['Dsp']['nivetu'] ) ) ? $dsp['Dsp']['nivetu'] : null );
			$return['Dsp']['nivdipmaxobt'] = ( ( isset( $dsp['Dsp']['nivdipmaxobt'] ) ) ? $dsp['Dsp']['nivdipmaxobt'] : null );
			$return['Dsp']['annobtnivdipmax'] = ( ( isset( $dsp['Dsp']['annobtnivdipmax'] ) ) ? $dsp['Dsp']['annobtnivdipmax'] : null );

			return $return;
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				if( $this->action == 'edit' ) {
					$id = $this->Contratinsertion->field( 'personne_id', array( 'id' => $id ) );
				}
				$this->redirect( array( 'action' => 'index', $id ) );
			}

			$valueFormeci = null;
			if( $this->action == 'add' ) {
				$contratinsertion_id = null;
				$personne_id = $id;
				$nbrPersonnes = $this->Contratinsertion->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'recursive' => -1 ) );
				$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );
				$valueFormeci = 'S';

				$nbContratsPrecedents = $this->Contratinsertion->find( 'count', array( 'recursive' => -1, 'conditions' => array( 'Contratinsertion.personne_id' => $personne_id ) ) );
				if( $nbContratsPrecedents >= 1 ) {
					$tc = 'REN';
				}
				else {
					$tc = 'PRE';
				}
			}
			else if( $this->action == 'edit' ) {
				$contratinsertion_id = $id;
				$contratinsertion = $this->Contratinsertion->find(
					'first',
					array(
						'conditions' => array(
							'Contratinsertion.id' => $contratinsertion_id
						),
						'contain' => array(
							'Autreavissuspension',
							'Autreavisradiation',
							'Objetcontratprecedent'
						)
					)
				);
				$this->assert( !empty( $contratinsertion ), 'invalidParameter' );

				$personne_id = Set::classicExtract( $contratinsertion, 'Contratinsertion.personne_id' );
				$valueFormeci = Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' );

				$nbContratsPrecedents = $this->Contratinsertion->find( 'count', array( 'recursive' => -1, 'conditions' => array( 'Contratinsertion.personne_id' => $personne_id ) ) );

				$tc = Set::classicExtract( $contratinsertion, 'Contratinsertion.num_contrat' );
			}
			$this->set( 'nbContratsPrecedents',  $nbContratsPrecedents );

			/// Détails des précédents contrats
			$lastContrat = $this->Contratinsertion->find(
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
			$orientstruct = $this->Contratinsertion->Structurereferente->Orientstruct->find(
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
			$personne_referent = $this->Contratinsertion->Personne->PersonneReferent->find(
				'first',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id' => $personne_id,
						'PersonneReferent.dfdesignation IS NULL'
					),
					'recursive' => -1
				)
			);

			$structures = $this->Structurereferente->listOptions();
			$referents = $this->Referent->listOptions();

			$this->set( 'tc', $tc );

			/// Peut-on prendre le jeton ?
			$this->Contratinsertion->begin();
			$dossier_id = $this->Contratinsertion->Personne->dossierId( $personne_id );
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Contratinsertion->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );
			$this->set( 'dossier_id', $dossier_id );

			/**
			*   Utilisé pour les dates de suspension et de radiation
			*   Si les dates ne sont pas présentes en base, elles ne seront pas affichées
			*   Situation dossier rsa : dtclorsa -> date de radiation
			*   Suspension droit : ddsusdrorsa -> date de suspension
			*/
			$situationdossierrsa = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->find(
				'first',
				array(
					'conditions' => array(
						'Situationdossierrsa.dossier_id' => $dossier_id
					),
					'recursive' => -1
				)
			);
			$situationdossierrsa_id = Set::classicExtract( $situationdossierrsa, 'Situationdossierrsa.id' );
			$suspension = $this->Contratinsertion->Personne->Foyer->Dossier->Situationdossierrsa->Suspensiondroit->find(
				'all',
				array(
					'fields' => array(
						'Suspensiondroit.ddsusdrorsa'
					),
					'conditions' => array(
						'Suspensiondroit.situationdossierrsa_id' => $situationdossierrsa_id
					),
					'recursive' => -1,
					'order' => 'Suspensiondroit.ddsusdrorsa DESC'
				)
			);
			$this->set( compact( 'situationdossierrsa', 'suspension' ) );

			//On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
			$personne = $this->Contratinsertion->Personne->newDetailsCi( $personne_id, $this->Session->read( 'Auth.User.id' ) );
			$this->set( 'personne', $personne );

			/// Calcul du numéro du contrat d'insertion
			$nbrCi = $this->Contratinsertion->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ) ) );

			$numouverturedroit = $this->Contratinsertion->checkNumDemRsa( $personne_id );
			$this->set( 'numouverturedroit', $numouverturedroit );

			$this->set( 'valueFormeci', $valueFormeci );

			/**
			*   Utilisé pour les détections de fiche de candidature
			*   pour savoir si des actions sont en cours ou non
			*/
			$fichescandidature = $this->Contratinsertion->Personne->ActioncandidatPersonne->find(
				'all',
				array(
					'conditions' => array(
						'ActioncandidatPersonne.personne_id' => $personne_id,
						'ActioncandidatPersonne.positionfiche = \'encours\'',
					),
					'contain' => array(
						'Actioncandidat' => array(
							'Contactpartenaire' => array(
								'Partenaire'
							)
						),
						'Referent'
					)
				)
			);
			$this->set( compact( 'fichescandidature' ) );

			/// Essai de sauvegarde
			if( !empty( $this->data ) ) {
				if( $this->action == 'add' ) {
					$this->data['Contratinsertion']['rg_ci'] = $nbrCi + 1;
				}

				if( Configure::read( 'nom_form_ci_cg' ) == 'cg58' ) {
					$this->data['Contratinsertion']['forme_ci'] = 'S';
					$this->data['Contratinsertion']['date_validation_ci'] = Set::classicExtract( $this->data, 'Contratinsertion.dd_ci' );
				}

				$contratinsertionRaisonCi = Set::classicExtract( $this->data, 'Contratinsertion.raison_ci' );
				if( $contratinsertionRaisonCi == 'S' ) {
					$this->data['Contratinsertion']['avisraison_ci'] = Set::classicExtract( $this->data, 'Contratinsertion.avisraison_suspension_ci' );
				}
				else if( $contratinsertionRaisonCi == 'R' ){
					$this->data['Contratinsertion']['avisraison_ci'] = Set::classicExtract( $this->data, 'Contratinsertion.avisraison_radiation_ci' );
				}

// 				if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) {
// 					$contratinsertionDecisionCi = Set::classicExtract( $this->data, 'Contratinsertion.forme_ci' );
// 					if( $contratinsertionDecisionCi == 'S' ) {
// 						///Validation si le contrat est simple (CG66)
// 						$this->data['Contratinsertion']['decision_ci'] = 'V';
// 						$this->data['Contratinsertion']['datevalidation_ci'] = $this->data['Contratinsertion']['date_saisi_ci'];
// 					}
// 				}

				/**
				*   Utilisé pour les dates de suspension et de radiation
				*   Si les dates ne sont pas présentes en base, elles ne seront pas affichées
				*   Situation dossier rsa : dtclorsa -> date de radiation
				*   Suspension droit : ddsusdrorsa -> date de suspension
				*/
				if( isset( $situationdossierrsa ) ){
					$this->data['Contratinsertion']['dateradiationparticulier'] = $situationdossierrsa['Situationdossierrsa']['dtclorsa'];
				}
				if( isset( $suspension ) && !empty( $suspension ) ){
					$this->data['Contratinsertion']['datesuspensionparticulier'] = $suspension[0]['Suspensiondroit']['ddsusdrorsa'];
				}

				// Si Contratinsertion.objetcerprecautre est disabled, on enregistre null
				$this->data = Set::merge( array( 'Contratinsertion' => array( 'objetcerprecautre' => null ) ), $this->data );

				$success = $this->Contratinsertion->save( $this->data );

				if( $success ) {
					$contratinsertion_id = $this->Contratinsertion->id;

					// Si on avat des entrées Objetcontratprecedent pour le CER, on commence par les supprimer
					$recordFound = $this->Contratinsertion->Objetcontratprecedent->find(
						'first',
						array(
							'conditions' => array( 'Objetcontratprecedent.contratinsertion_id' => $contratinsertion_id ),
							'contain' => false
						)
					);
					if( !empty( $recordFound ) ) {
						$success = $this->Contratinsertion->Objetcontratprecedent->deleteAll(
							array( 'Objetcontratprecedent.contratinsertion_id' => $contratinsertion_id )
						) && $success;
					}

					if ( isset( $this->data['Objetcontratprecedent']['Objetcontratprecedent'] ) && !empty( $this->data['Objetcontratprecedent']['Objetcontratprecedent'] ) ) {
						foreach( $this->data['Objetcontratprecedent']['Objetcontratprecedent'] as $objet ) {
							$objetcontratprecedant['Objetcontratprecedent'] = array(
								'contratinsertion_id' => $contratinsertion_id,
								'objetcerprec' => $objet
							);
							$this->Contratinsertion->Objetcontratprecedent->create( $objetcontratprecedant );
							$success = $this->Contratinsertion->Objetcontratprecedent->save() && $success;
						}
					}
				}

				if( Configure::read( 'nom_form_ci_cg' ) != 'cg66' ) {
					$dspStockees = $this->_getDsp( $personne_id );
					$this->data['Dsp'] = Set::merge(
						isset( $dspStockees['Dsp'] ) ? Set::filter( $dspStockees['Dsp'] ) : array(),
						isset( $this->data['Dsp'] ) ? Set::filter( $this->data['Dsp'] ) : array()
					);

					$isDsp = Set::filter( $this->data['Dsp'] );
					if( !empty( $isDsp ) ){
						$success = $this->Contratinsertion->Personne->Dsp->save( array( 'Dsp' => $this->data['Dsp'] ) ) && $success;
					}
				}

				$models = array( 'Autreavissuspension', 'Autreavisradiation' );
				foreach( $models as $model ) {
					if( $this->action == 'add' ) {
						$this->{$this->modelClass}->{$model}->set( 'contratinsertion_id', $this->{$this->modelClass}->id );
					}
					else if ( $this->action == 'edit' ){
						$this->Contratinsertion->{$model}->deleteAll( array( "{$model}.contratinsertion_id" => $this->Contratinsertion->id ) );
					}

					if( isset( $this->data[$model] ) ){
						$is{$model} = Set::filter( $this->data[$model] );
						if( !empty( $is{$model} ) ){
							$Autresavis = Set::extract( $is{$model}, "/{$model}" );
							$data = array( $model => array() );

							foreach( $Autresavis as $i => $Autreavis ){
								$data[$model][] = array(
									'contratinsertion_id' => $this->Contratinsertion->id,
									strtolower($model) => $Autreavis
								);
							}
							$success = $this->Contratinsertion->{$model}->saveAll( $data[$model], array( 'atomic' => false ) ) && $success;
						}
					}
				}

				if( isset( $this->data['Actioninsertion'] ) ) {
					$isActioninsertion = Set::filter( $this->data['Actioninsertion'] );
					$this->{$this->modelClass}->Actioninsertion->set( 'contratinsertion_id', $this->{$this->modelClass}->id );

					if( !empty( $isActioninsertion ) ){
						$success = $this->Contratinsertion->Actioninsertion->save( array( 'Actioninsertion' => $this->data['Actioninsertion'] ) ) && $success;
					}
				}

				if ( Configure::read( 'Cg.departement' ) == 93 && $this->data['Contratinsertion']['forme_ci'] == 'C' ) {
					$dossierep = array(
						'Dossierep' => array(
							'themeep' => 'contratscomplexeseps93',
							'personne_id' => $personne_id
						)
					);

					$this->Contratinsertion->Personne->Dossierep->create( $dossierep );
					$tmpSuccess = $this->Contratinsertion->Personne->Dossierep->save();

					// Sauvegarde des données de la thématique
					if( $tmpSuccess ) {
						$contratcomplexeep93 = array(
							'Contratcomplexeep93' => array(
								'dossierep_id' => $this->Contratinsertion->Personne->Dossierep->id,
								'contratinsertion_id' => $this->Contratinsertion->id
							)
						);

						$this->Contratinsertion->Personne->Dossierep->Contratcomplexeep93->create( $contratcomplexeep93 );
						$success = $this->Contratinsertion->Personne->Dossierep->Contratcomplexeep93->save() && $success;
					}
					$success = $success && $tmpSuccess;
				}

				if( $success ) {
					$saved = true;
					if ( Configure::read( 'Cg.departement' ) == 93 ) {
						$dossierep = $this->Contratinsertion->Nonrespectsanctionep93->Dossierep->find(
							'first',
							array(
								'fields' => array(
									'Dossierep.id',
									'Passagecommissionep.id',
									'Nonrespectsanctionep93.id'
								),
								'conditions' => array(
									'Dossierep.id NOT IN ( '.$this->Contratinsertion->Nonrespectsanctionep93->Dossierep->Passagecommissionep->sq(
										array(
											'fields' => array(
												'passagescommissionseps.dossierep_id'
											),
											'alias' => 'passagescommissionseps',
											'conditions' => array(
												'dossierseps.themeep' => 'nonrespectssanctionseps93',
												'dossierseps.personne_id' => $personne_id,
												'commissionseps.etatcommissionep' => array( 'valide', 'presence', 'decisionep', 'traiteep', 'decisioncg', 'traite', 'annule', 'reporte' )
											),
											'joins' => array(
												array(
													'table' => 'dossierseps',
													'alias' => 'dossierseps',
													'type' => 'INNER',
													'conditions' => array(
														'passagescommissionseps.dossierep_id = dossierseps.id'
													)
												),
												array(
													'table' => 'commissionseps',
													'alias' => 'commissionseps',
													'type' => 'INNER',
													'conditions' => array(
														'passagescommissionseps.commissionep_id = commissionseps.id'
													)
												)
											)
										)
									).' )',
									'Dossierep.personne_id' => $personne_id,
									'Nonrespectsanctionep93.origine' => array( 'orientstruct', 'contratinsertion' )
								),
								'joins' => array(
									array(
										'table' => 'nonrespectssanctionseps93',
										'alias' => 'Nonrespectsanctionep93',
										'type' => 'INNER',
										'conditions' => array(
											'Nonrespectsanctionep93.dossierep_id = Dossierep.id'
										)
									),
									array(
										'table' => 'passagescommissionseps',
										'alias' => 'Passagecommissionep',
										'type' => 'LEFT OUTER',
										'conditions' => array(
											'Passagecommissionep.dossierep_id = Dossierep.id'
										)
									)
								)
							)
						);
						if ( !empty( $dossierep ) ) {
							if ( !empty( $dossierep['Passagecommissionep']['id'] ) ) {
								$this->Contratinsertion->Nonrespectsanctionep93->Dossierep->Passagecommissionep->delete( $dossierep['Passagecommissionep']['id'] );
							}
							$this->Contratinsertion->Nonrespectsanctionep93->Dossierep->delete( $dossierep['Dossierep']['id'] );
							$this->Contratinsertion->Nonrespectsanctionep93->delete( $dossierep['Dossierep']['id'] );
						}
					}

					$lastrdvorient = $this->Contratinsertion->Referent->Rendezvous->find(
						'first',
						array(
							'fields'=>array(
								'Rendezvous.id'
							),
							'conditions'=>array(
								'Rendezvous.typerdv_id' => 1,
								'Rendezvous.personne_id' => $this->data['Contratinsertion']['personne_id'],
								'Rendezvous.statutrdv_id' => 17
							),
							'contain'=>false
						)
					);
					if( !empty( $lastrdvorient ) ){
						$lastrdvorient['Rendezvous']['statutrdv_id'] = 1;
						$saved = $this->Contratinsertion->Referent->Rendezvous->save($lastrdvorient) && $saved;
					}

					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Contratinsertion->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Contratinsertion->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
				else {
					$this->Contratinsertion->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				if( $this->action == 'edit' ) {
					$this->data = $contratinsertion;

					/// FIXME
					$actioninsertion = $this->Contratinsertion->Actioninsertion->find(
						'first',
						array(
							'conditions' => array(
								'Actioninsertion.contratinsertion_id' => $contratinsertion['Contratinsertion']['id'],
								'Actioninsertion.dd_action IS NOT NULL'
							),
							'recursive' => -1,
							'order' => array( 'Actioninsertion.dd_action DESC' )
						)
					);
					$this->data['Actioninsertion'] = $actioninsertion['Actioninsertion'];

					///Suspension / Radiation
					if( $this->data['Contratinsertion']['raison_ci'] == 'S' ) {
						$this->data['Contratinsertion']['avisraison_suspension_ci'] = $this->data['Contratinsertion']['avisraison_ci'];
					}
					else if( $this->data['Contratinsertion']['raison_ci'] == 'R' ){
						$this->data['Contratinsertion']['avisraison_radiation_ci'] =  $this->data['Contratinsertion']['avisraison_ci'];
					}

					/// Si on est en présence d'un deuxième contrat -> Alors renouvellement
					$nbrCi = $contratinsertion['Contratinsertion']['rg_ci'];
				}
				else {
					$foyer = $this->Contratinsertion->Personne->Foyer->find(
						'first',
						array(
							'fields' => array(
								'Foyer.sitfam',
								'Foyer.typeocclog'
							),
							'conditions' => array(
								'Personne.id' => $personne_id
							),
							'joins' => array(
								array(
									'table' => 'personnes',
									'alias' => 'Personne',
									'type' => 'INNER',
									'conditions' => array(
										'Personne.foyer_id = Foyer.id'
									)
								)
							),
							'contain' => false
						)
					);
					$this->data['Contratinsertion']['sitfam'] = $foyer['Foyer']['sitfam'];
					$this->data['Contratinsertion']['typeocclog'] = $foyer['Foyer']['typeocclog'];
				}

				$this->data = Set::merge( $this->data, $this->_getDsp( $personne_id ) );
			}

			$this->set( 'nbrCi', $nbrCi );
			// Doit-on setter les valeurs par défault ?
			$dataStructurereferente_id = Set::classicExtract( $this->data, "{$this->Contratinsertion->alias}.structurereferente_id" );
			$dataReferent_id = Set::classicExtract( $this->data, "{$this->Contratinsertion->alias}.referent_id" );

			// Si le formulaire ne possède pas de valeur pour ces champs, on met celles par défaut
			if( empty( $dataStructurereferente_id ) && empty( $dataReferent_id ) ) {
				$structurereferente_id = $referent_id = null;
				// Valeur par défaut préférée: à partir de personnes_referents
				if( !empty( $personne_referent ) ){
					$structurereferente_id = Set::classicExtract( $personne_referent, "{$this->PersonneReferent->alias}.structurereferente_id" );
					$referent_id = Set::classicExtract( $personne_referent, "{$this->PersonneReferent->alias}.referent_id" );
				}
				// Valeur par défaut de substitution: à partir de orientsstructs
				else if( !empty( $orientstruct ) ) {
					$structurereferente_id = Set::classicExtract( $orientstruct, "{$this->Orientstruct->alias}.structurereferente_id" );
					$referent_id = Set::classicExtract( $orientstruct, "{$this->Orientstruct->alias}.referent_id" );
				}

				if( !empty( $structurereferente_id ) ) {
					$this->data = Set::insert( $this->data, "{$this->Contratinsertion->alias}.structurereferente_id", $structurereferente_id );
				}
				if( !empty( $structurereferente_id ) && !empty( $referent_id ) ) {
					$this->data = Set::insert( $this->data, "{$this->Contratinsertion->alias}.referent_id", preg_replace( '/^_$/', '', "{$structurereferente_id}_{$referent_id}" ) );
				}
			}

			$struct_id = Set::classicExtract( $this->data, 'Contratinsertion.structurereferente_id' );
			$this->set( 'struct_id', $struct_id );

			if( !empty( $struct_id ) ) {
				$struct = $this->Contratinsertion->Structurereferente->find(
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
							'Structurereferente.id' => Set::extract( $this->data, 'Contratinsertion.structurereferente_id' )
						),
						'recursive' => -1
					)
				);
				$this->set( 'StructureAdresse', $struct['Structurereferente']['num_voie'].' '.$struct['Structurereferente']['type_voie'].' '.$struct['Structurereferente']['nom_voie'].'<br/>'.$struct['Structurereferente']['code_postal'].' '.$struct['Structurereferente']['ville'] );
			}

			$referent_id = Set::classicExtract( $this->data, 'Contratinsertion.referent_id' );
			$referent_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $referent_id );
			$this->set( 'referent_id', $referent_id );

			if( !empty( $referent_id ) && !empty( $this->data['Contratinsertion']['referent_id'] ) ) {
				$contratinsertionReferentId = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Contratinsertion']['referent_id'] );
				$referent = $this->Contratinsertion->Structurereferente->Referent->find(
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

			$this->Contratinsertion->commit();
			$this->_setOptions();
			$this->set( compact( 'structures', 'referents' ) );
			$this->set( 'urlmenu', '/contratsinsertion/index/'.$personne_id );

			$this->render( $this->action, null, 'add_edit_specif_cg'.Configure::read( 'Cg.departement' ) );
		}

		/**
		*
		*/

		public function valider( $contratinsertion_id = null ) {
			$contratinsertion = $this->Contratinsertion->find(
				'first',
				array(
					'fields' => array(
						'Contratinsertion.id',
						'Contratinsertion.personne_id',
						'Contratinsertion.structurereferente_id',
						'Contratinsertion.forme_ci',
						'Contratinsertion.observ_ci',
						'Contratinsertion.datevalidation_ci',
						'Contratinsertion.decision_ci',
						'Contratinsertion.dd_ci',
						'Contratinsertion.df_ci',
					),
					'conditions' => array(
						'Contratinsertion.id' => $contratinsertion_id
					),
					'recursive' => -1
				)
			);
			$this->assert( !empty( $contratinsertion ), 'invalidParameter' );
			$this->set( 'contratinsertion', $contratinsertion );

			$this->set( 'personne_id', $contratinsertion['Contratinsertion']['personne_id'] );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id'] ) );
			}

			if( !empty( $this->data ) ) {
				if( $this->Contratinsertion->valider( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $contratinsertion['Contratinsertion']['personne_id']) );
				}
			}
			else {
				$this->data = $contratinsertion;
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/contratsinsertion/index/'.$contratinsertion['Contratinsertion']['personne_id'] );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$this->{$this->modelClass}->begin();
			$success = $this->{$this->modelClass}->Actioninsertion->deleteAll( array( 'Actioninsertion.contratinsertion_id' => $id ) );
			$success = $this->{$this->modelClass}->delete( $id ) && $success;
			$this->_setFlashResult( 'Delete', $success );

			if( $success ) {
				$this->{$this->modelClass}->commit();
			}
			else {
				$this->{$this->modelClass}->rollback();
			}

			$this->redirect( Router::url( $this->referer(), true ) );
		}

		/**
		*   Fonction pour annuler le CER pour le CG66
		*/

		public function cancel( $id ) {
			$contrat = $this->{$this->modelClass}->findById( $id, null, null, -1 );

			$this->{$this->modelClass}->updateAll(
				array( 'Contratinsertion.positioncer' => '\'annule\'' ),
				array(
					'"Contratinsertion"."personne_id"' => $contrat['Contratinsertion']['personne_id'],
					'"Contratinsertion"."id"' => $contrat['Contratinsertion']['id']
				)
			);
			$this->redirect( array( 'action' => 'index', $contrat['Contratinsertion']['personne_id'] ) );
		}

		/**
		*
		*/

		public function notificationsop( $id = null ) {
			$qual = $this->Option->qual();
			$typevoie = $this->Option->typevoie();
			$duree_engag_cg66 = $this->Option->duree_engag_cg66();
			$duree_engag_cg93 = $this->Option->duree_engag_cg93();

			$contratinsertion = $this->{$this->modelClass}->find(
				'first',
				array(
					'conditions' => array(
						"{$this->modelClass}.id" => $id
					),
					'recursive' => 0
				)
			);

			$this->Adressefoyer->bindModel(
				array(
					'belongsTo' => array(
						'Adresse' => array(
							'className'     => 'Adresse',
							'foreignKey'    => 'adresse_id'
						)
					)
				)
			);

			$adresse = $this->Adressefoyer->find(
				'first',
				array(
					'conditions' => array(
						'Adressefoyer.foyer_id' => Set::classicExtract( $contratinsertion, 'Personne.foyer_id' ),
						'Adressefoyer.rgadr' => '01',
					)
				)
			);
			$contratinsertion['Adresse'] = $adresse['Adresse'];

			/// Récupération du dossier lié à la personne
			$foyer = $this->Adressefoyer->Foyer->find(
				'first',
				array(
					'conditions' => array(
						'Foyer.id' => $contratinsertion['Personne']['foyer_id']
					)
				)
			);
			$contratinsertion['Foyer'] = $foyer['Foyer'];
			$dossier = $this->Dossier->find(
				'first',
				array(
					'conditions' => array(
						'Dossier.id' => $contratinsertion['Foyer']['dossier_id']
					)
				)
			);
			$contratinsertion['Dossier'] = $dossier['Dossier'];

			$contratinsertion_id = Set::classicExtract( $contratinsertion, 'Actioncandidat.id' );

			$LocaleHelper = new LocaleHelper();
			///Traduction pour les données de la Personne/Contact/Partenaire/Référent
			$contratinsertion['Personne']['qual'] = Set::enum( Set::classicExtract( $contratinsertion, 'Personne.qual' ), $qual );
			$contratinsertion['Personne']['dtnai'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $contratinsertion, 'Personne.dtnai' ) );
			$contratinsertion['Referent']['qual'] = Set::enum( Set::classicExtract( $contratinsertion, 'Referent.qual' ), $qual );
			$contratinsertion['Adresse']['typevoie'] = Set::enum( Set::classicExtract( $contratinsertion, 'Adresse.typevoie' ), $typevoie );
			$contratinsertion['Contratinsertion']['datevalidation_ci'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $contratinsertion, 'Contratinsertion.datevalidation_ci' ) );
			$contratinsertion['Contratinsertion']['dd_ci'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $contratinsertion, 'Contratinsertion.dd_ci' ) );
			$contratinsertion['Contratinsertion']['df_ci'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $contratinsertion, 'Contratinsertion.df_ci' ) );
			$contratinsertion['Dossier']['dtdemrsa'] = $LocaleHelper->date( 'Date::short', Set::classicExtract( $contratinsertion, 'Dossier.dtdemrsa' ) );

			if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ) {
				$contratinsertion['Contratinsertion']['duree_engag'] = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.duree_engag' ), $duree_engag_cg66 );
			}
			else if( Configure::read( 'nom_form_ci_cg' ) == 'cg93' ) {
				$contratinsertion['Contratinsertion']['duree_engag'] = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.duree_engag' ), $duree_engag_cg93 );
			}

			///Utilisé pour savoir si le contrat est en accord de validation dans le modèle odt
			if( $contratinsertion['Contratinsertion']['decision_ci'] == 'V' ){
				$contratinsertion['Contratinsertion']['accord'] = 'X';
			}

			///Utilisé pour savoir si le contrat est en premier contrat ou renouvellement
			if( $contratinsertion['Contratinsertion']['num_contrat'] == 'PRE' ){
				$contratinsertion['Contratinsertion']['premier'] = 'X';
			}
			else if( $contratinsertion['Contratinsertion']['num_contrat'] == 'REN' ){
				$contratinsertion['Contratinsertion']['renouvel'] = 'X';
			}

			$this->_setOptions();

			$pdf = $this->Contratinsertion->ged( $contratinsertion, 'Contratinsertion/notificationop.odt' );
			$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'notificationop-%s.pdf', date( 'Y-m-d' ) ) );
		}
	}
?>