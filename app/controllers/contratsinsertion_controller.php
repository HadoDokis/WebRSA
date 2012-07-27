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
		public $uses = array( 'Contratinsertion', 'Option', 'Action', 'Referent', 'Personne', 'Dossier', 'Structurereferente', 'Dsp', 'Typeorient', 'Orientstruct', 'Serviceinstructeur', 'Action', 'Adressefoyer', 'Actioninsertion', 'Prestform', 'Refpresta', 'PersonneReferent', 'Pdf' );
		public $helpers = array( 'Default2', 'Ajax', 'Fileuploader' );
		public $components = array( 'RequestHandler', 'Gedooo.Gedooo', 'Fileuploader' );

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

			if( in_array( $this->action, array( 'index', 'add', 'edit', 'view', 'valider', 'validersimple', 'validerparticulier' ) ) ) {
				$options = array_merge(
					$this->Contratinsertion->Propodecisioncer66->enums(),
					$options
				);
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

			if( in_array( $this->action, array( 'add', 'edit', 'view', 'valider', 'validersimple', 'validerparticulier' ) ) ) {
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
		*   Fonction permettant d'accéder à la page pour lier les fichiers au CER
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
// 					$this->redirect( array(  'controller' => 'contratsinsertion','action' => 'index', $personne_id ) );
					$this->redirect( $this->referer() );
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

			// Recherche de la dernière orientation en cours pour l'allocataire
			// Si aucun alors message d'erreur signalant la présence d'une orientation en emploi (cg66)
			$orientstruct = $this->Orientstruct->find(
				'count',
				array(
					'conditions' => array(
						'Orientstruct.personne_id' => $personne_id,
						'Orientstruct.statut_orient' => 'Orienté',
					),
					'recursive' => -1
				)
			);
			$this->set( compact( 'orientstruct' ) );

			$soumisADroitEtDevoir = $this->Personne->Calculdroitrsa->isSoumisAdroitEtDevoir($personne_id);
			$this->set( compact( 'soumisADroitEtDevoir' ) );

			if( Configure::read( 'Cg.departement' ) != 93 ) {
				$conditionsTypeorient = array();
				if( Configure::read( 'Cg.departement' ) == 66 ) {
					$typeOrientPrincipaleEmploiId = Configure::read( 'Orientstruct.typeorientprincipale.Emploi' );
					if( is_array( $typeOrientPrincipaleEmploiId ) && isset( $typeOrientPrincipaleEmploiId[0] ) ){
						$typeOrientPrincipaleEmploiId = $typeOrientPrincipaleEmploiId[0];
					}
					else{
						trigger_error( __( 'Le type orientation principale Emploi n\'est pas bien défini.', true ), E_USER_WARNING );
					}
					
					$conditionsTypeorient = array( 'Typeorient.parentid' => $typeOrientPrincipaleEmploiId );
				}
				else {
					$typeOrientPrincipaleEmploiId = Configure::read( 'Typeorient.emploi_id' );
					if( empty( $typeOrientPrincipaleEmploiId ) ){
						trigger_error( __( 'Le type orientation principale Emploi n\'est pas bien défini.', true ), E_USER_WARNING );
					}
					
					$conditionsTypeorient = array( 'Typeorient.id' => $typeOrientPrincipaleEmploiId );
				}

				
				$orientstructEmploi = $this->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'Orientstruct.personne_id' => $personne_id,
							'Orientstruct.statut_orient' => 'Orienté',
							$conditionsTypeorient,
							'Orientstruct.id IN ( '.$this->Orientstruct->sqDerniere( 'Orientstruct.personne_id' ).' )'
						),
						'order' => 'Orientstruct.date_valid DESC',
						'contain' => array(
							'Typeorient'
						)
					)
				);
				$this->set( compact( 'orientstructEmploi' ) );
			}

			$persreferent = $this->PersonneReferent->find(
				'count',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id' => $personne_id,
						'PersonneReferent.dfdesignation IS NULL'
					),
					'recursive' => -1
				)
			);
			$this->set( compact( 'persreferent' ) );

			// DEBUT à mettre dans le modèle ?
			$querydata = array(
				'fields' => array(
					'Contratinsertion.id',
					'Contratinsertion.forme_ci',
					'Contratinsertion.decision_ci',
					'Contratinsertion.datedecision',
					'Contratinsertion.structurereferente_id',
					'Contratinsertion.referent_id',
					'Contratinsertion.num_contrat',
					'Contratinsertion.dd_ci',
					'Contratinsertion.duree_engag',
					'Contratinsertion.positioncer',
					'Contratinsertion.df_ci',
					'Contratinsertion.date_saisi_ci',
					'Contratinsertion.observ_ci',
					'Contratinsertion.created',
					'Contratinsertion.datevalidation_ci',
					'Contratinsertion.datenotification',
					'Contratinsertion.avenant_id',
					'( SELECT COUNT(fichiersmodules.id) FROM fichiersmodules WHERE fichiersmodules.modele = \'Contratinsertion\' AND fichiersmodules.fk_value = "Contratinsertion"."id" ) AS "Fichiermodule__nbFichiersLies"'
				),
				'conditions' => array(
					'Contratinsertion.personne_id' => $personne_id
				),
				'order' => array(
					'Contratinsertion.df_ci DESC',
					'Contratinsertion.id DESC'
				),
				'contain' => false
			);

			// On veut connaître ...
			if ( Configure::read('Cg.departement') == 58 ) {
				$sqDernierPassageCov58 = $this->Contratinsertion->Propocontratinsertioncov58nv->Dossiercov58->Passagecov58->sqDernier();

				$querydata = Set::merge(
					$querydata,
					array(
						'fields' => array(
							'Sitecov58.name',
							'Cov58.observation',
							'Cov58.datecommission',
							'Decisionpropocontratinsertioncov58.commentaire'
						),
						'joins' => array(
							$this->Contratinsertion->join( 'Propocontratinsertioncov58nv', array( 'type' => 'LEFT OUTER' ) ),
							$this->Contratinsertion->Propocontratinsertioncov58nv->join( 'Dossiercov58', array( 'type' => 'LEFT OUTER' ) ),
							$this->Contratinsertion->Propocontratinsertioncov58nv->Dossiercov58->join( 'Passagecov58', array( 'type' => 'LEFT OUTER' ) ),
							$this->Contratinsertion->Propocontratinsertioncov58nv->Dossiercov58->Passagecov58->join( 'Cov58', array( 'type' => 'LEFT OUTER' ) ),
							$this->Contratinsertion->Propocontratinsertioncov58nv->Dossiercov58->Passagecov58->Cov58->join( 'Sitecov58', array( 'type' => 'LEFT OUTER' ) ),
							$this->Contratinsertion->Propocontratinsertioncov58nv->Dossiercov58->Passagecov58->join( 'Decisionpropocontratinsertioncov58', array( 'type' => 'LEFT OUTER' ) ),
						),
						'conditions' => array(
							'OR' => array(
								"Passagecov58.id IS NULL",
								"Passagecov58.id IN ( {$sqDernierPassageCov58} )"
							)
						)
					)
				);
			}
			else if ( Configure::read('Cg.departement') == 66 ) {
				$querydata['joins'][] = $this->Contratinsertion->join( 'Personne', array( 'type' => 'INNER' ) );
				$querydata['fields'][] = '( ( EXTRACT ( YEAR FROM AGE( "Personne"."dtnai" ) ) ) > 55 ) AS "Personne__plus55ans"';

				$querydata = Set::merge(
					$querydata,
					array(
						'fields' => $this->Contratinsertion->Propodecisioncer66->fields(),
						'contain' => array( 'Propodecisioncer66' )
					)
				);

			}
			// FIN à mettre dans le modèle ?

			$contratsinsertion = $this->Contratinsertion->find( 'all', $querydata );

// debug( $contratsinsertion );
			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );

			if ( Configure::read('Cg.departement') == 58 ) {

				/*foreach( $contratsinsertion as $i => $contrat ) {
					$qdCovPassee = array(
						'fields' => array_merge(
							$this->Contratinsertion->Personne->Dossiercov58->Passagecov58->fields(),
							$this->Contratinsertion->Personne->Dossiercov58->Passagecov58->Cov58->fields(),
							$this->Contratinsertion->Personne->Dossiercov58->Passagecov58->Cov58->Sitecov58->fields()
						),
						'joins' => array(
							$this->Contratinsertion->Personne->Dossiercov58->Passagecov58->join( 'Dossiercov58' ),
							$this->Contratinsertion->Personne->Dossiercov58->Passagecov58->join( 'Cov58' ),
							$this->Contratinsertion->Personne->Dossiercov58->Passagecov58->Cov58->join( 'Sitecov58' ),
							$this->Contratinsertion->Personne->Dossiercov58->Passagecov58->join( 'Decisionpropocontratinsertioncov58' ),
							$this->Contratinsertion->Personne->Dossiercov58->Passagecov58->Dossiercov58->join( 'Themecov58' ),
							$this->Contratinsertion->Personne->Dossiercov58->join( 'Propocontratinsertioncov58' )
						),
						'conditions' => array(
							'Dossiercov58.personne_id' => $personne_id,
							'Themecov58.name' => 'proposcontratsinsertioncovs58',
							'Passagecov58.etatdossiercov' => 'traite',
							// Conditions pour retrouver l'orientation -> FIXME: ne sert plus à rien, on a l'id maintenant
							'Propocontratinsertioncov58.structurereferente_id' => $contrat['Contratinsertion']['structurereferente_id'],
// 								'Propocontratinsertioncov58.referent_id' => $contrat['Contratinsertion']['referent_id'],
							'Propocontratinsertioncov58.forme_ci' => $contrat['Contratinsertion']['forme_ci'],
// 								'Propocontratinsertioncov58.datedemande' => $contrat['Contratinsertion']['dd_ci'],
							'Decisionpropocontratinsertioncov58.dd_ci' => $contrat['Contratinsertion']['dd_ci'],
// 								'Decisionpropocontratinsertioncov58.duree_engag' => $contrat['Contratinsertion']['duree_engag'],
							'Decisionpropocontratinsertioncov58.df_ci' => $contrat['Contratinsertion']['df_ci'],
//							'Decisionpropocontratinsertioncov58.datevalidation' => ( !empty( $contrat['Contratinsertion']['datevalidation_ci'] ) ? $contrat['Contratinsertion']['datevalidation_ci'] : null ),
						),
						'contain' => false
					);

					if( !empty( $contrat['Contratinsertion']['datevalidation_ci'] ) ) {
						$qdCovPassee['conditions']['Decisionpropocontratinsertioncov58.datevalidation'] = $contrat['Contratinsertion']['datevalidation_ci'];
					}
					else {
						$qdCovPassee['conditions'][] = "Decisionpropocontratinsertioncov58.datevalidation IS NULL";
					}

 					$covpassee58 = $this->Contratinsertion->Personne->Dossiercov58->Passagecov58->find( 'first', $qdCovPassee );
					$contratsinsertion[$i] = Set::merge( $contrat, $covpassee58 );
				}*/

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

			$this->set( compact( 'contratsinsertion' ) );
			///FIXME: pas propre, mais pr le moment ça marche afin d'eviter de tout renommer
			$this->render( $this->action, null, '/contratsinsertion/index_'.Configure::read( 'nom_form_ci_cg' ) );
		}

		/**
		*
		*/

		public function view( $contratinsertion_id = null ) {
			$this->Contratinsertion->forceVirtualFields = true;
			$contratinsertion = $this->Contratinsertion->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Contratinsertion->fields(),
						$this->Contratinsertion->Action->fields(),
						$this->Contratinsertion->Actioninsertion->fields(),
						$this->Contratinsertion->Propodecisioncer66->fields(),
						array(
							'Referent.nom_complet',
							'Structurereferente.lib_struc',
							'Typeorient.lib_type_orient',
							'Personne.nom_complet',
							$this->Contratinsertion->Propodecisioncer66->Motifcernonvalid66Propodecisioncer66->Motifcernonvalid66->vfListeMotifs( 'Propodecisioncer66.id', '', ', ' ).' AS "Propodecisioncer66__listeMotifs66"'
						)
					),
					'joins' => array(
						$this->Contratinsertion->join( 'Action', array( 'type' => 'LEFT OUTER' ) ),
						$this->Contratinsertion->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
						$this->Contratinsertion->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->Contratinsertion->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
						$this->Contratinsertion->Structurereferente->join( 'Typeorient', array( 'type' => 'INNER' ) ),
						$this->Contratinsertion->join( 'Actioninsertion', array( 'type' => 'LEFT OUTER' ) ),
						$this->Contratinsertion->join( 'Propodecisioncer66', array( 'type' => 'LEFT OUTER' ) ),/*
						$this->Contratinsertion->Propodecisioncer66->join( 'Motifcernonvalid66Propodecisioncer66', array( 'type' => 'LEFT OUTER' ) ),
						$this->Contratinsertion->Propodecisioncer66->Motifcernonvalid66Propodecisioncer66->join( 'Motifcernonvalid66', array( 'type' => 'LEFT OUTER' ) )*/
					),
					'conditions' => array(
						'Contratinsertion.id' => $contratinsertion_id
					),
					'recursive' => -1,
					'contain' => false
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
// 							'Objetcontratprecedent'
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


			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$typeOrientPrincipaleEmploiId = Configure::read( 'Orientstruct.typeorientprincipale.Emploi' );
				if( is_array( $typeOrientPrincipaleEmploiId ) && isset( $typeOrientPrincipaleEmploiId[0] ) ){
					$typeOrientPrincipaleEmploiId = $typeOrientPrincipaleEmploiId[0];
				}
				else{
					trigger_error( __( 'Le type orientation principale Emploi n\'est pas bien défini.', true ), E_USER_WARNING );
				}

				$structures = $this->Structurereferente->find(
					'list',
					array(
						'fields' => array(
							'Structurereferente.id',
							'Structurereferente.lib_struc',
							'Typeorient.lib_type_orient'
						),
						'recursive' => 0,
						'order' => array(
							'Typeorient.lib_type_orient ASC',
							'Structurereferente.lib_struc'
						),
						'conditions' => array(
							'Structurereferente.actif' => 'O',
							'Typeorient.parentid <>' => $typeOrientPrincipaleEmploiId
						)
					)
				);

			}
			else {
				$structures = $this->Structurereferente->listOptions();
			}
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

			$situationdossierrsa_id = ( empty( $situationdossierrsa ) ? null : Set::classicExtract( $situationdossierrsa, 'Situationdossierrsa.id' ) );
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


				//FIXME: bloc à commenter une fois confirmé le fait de ne plus valider automatiquemlent les CERs à l'enregistrement
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
				if( isset( $situationdossierrsa ) && !empty($situationdossierrsa['Situationdossierrsa']['dtclorsa']) ){
					$this->data['Contratinsertion']['dateradiationparticulier'] = $situationdossierrsa['Situationdossierrsa']['dtclorsa'];
				}
				if( isset( $suspension ) && !empty( $suspension[0]['Suspensiondroit']['ddsusdrorsa'] ) ){
					$this->data['Contratinsertion']['datesuspensionparticulier'] = $suspension[0]['Suspensiondroit']['ddsusdrorsa'];
				}

				// Si Contratinsertion.objetcerprecautre est disabled, on enregistre null
				$this->data = Set::merge( array( 'Contratinsertion' => array( 'objetcerprecautre' => null ) ), $this->data );

				$this->Contratinsertion->create( $this->data );
				$success = $this->Contratinsertion->save();

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

				// SAuvegarde des numéros ed téléphone si ceux-ci ne sont pas présents en amont
				if( isset( $this->data['Personne'] ) ) {
					$isDataPersonne = Set::filter( $this->data['Personne'] );
					if( !empty( $isDataPersonne ) ){
						$success = $this->Contratinsertion->Personne->save( array( 'Personne' => $this->data['Personne'] ) ) && $success;
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

			if( Configure::read( 'Cg.departement' ) == 66 ) {
				$fields = array(
					'Contratinsertion.id',
					'Contratinsertion.personne_id',
					'Contratinsertion.structurereferente_id',
					'Contratinsertion.forme_ci',
					'Contratinsertion.observ_ci',
					'Contratinsertion.datevalidation_ci',
					'Contratinsertion.datedecision',
					'Contratinsertion.decision_ci',
					'Contratinsertion.positioncer',
					'Contratinsertion.dd_ci',
					'Contratinsertion.df_ci',
					'Contratinsertion.duree_engag',
					'Propodecisioncer66.isvalidcer',
					'Propodecisioncer66.datevalidcer',
					'Referent.nom_complet'
				);
				$contain = array(
					'Propodecisioncer66',
					'Referent'
				);
			}
			else {
				$fields = array(
					'Contratinsertion.id',
					'Contratinsertion.personne_id',
					'Contratinsertion.structurereferente_id',
					'Contratinsertion.forme_ci',
					'Contratinsertion.observ_ci',
					'Contratinsertion.datevalidation_ci',
					'Contratinsertion.decision_ci',
					'Contratinsertion.positioncer',
					'Contratinsertion.dd_ci',
					'Contratinsertion.df_ci'
				);
				$recursive = -1;
				$contain = false;
			}

			$contratinsertion = $this->Contratinsertion->find(
				'first',
				array(
					'fields' => $fields,
					'conditions' => array(
						'Contratinsertion.id' => $contratinsertion_id
					),
					'contain' => $contain
				)
			);
// debug($contratinsertion);
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
			$this->render( $this->action, null, 'valider' );
		}

                /**
			**Fonction de validation pour les CERs Simples du CG66
			* @param type $contratinsertion_id
			*
			*/
		public function validersimple( $contratinsertion_id = null ){
			$this->Contratinsertion->id = $contratinsertion_id;
			$forme_ci = $this->Contratinsertion->field( 'forme_ci' );
			$this->assert( ( $forme_ci == 'S' ), 'error500' );

			$this->valider( $contratinsertion_id );
		}


		/**
			**Fonction de validation pour les CERs Particuliers du CG66
			* @param type $contratinsertion_id
			*
			*/
		public function validerparticulier( $contratinsertion_id = null ){
			$this->Contratinsertion->id = $contratinsertion_id;
			$forme_ci = $this->Contratinsertion->field( 'forme_ci' );
			$this->assert( ( $forme_ci == 'C' ), 'error500' );

			$this->valider( $contratinsertion_id );
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
			$personne_id = Set::classicExtract( $contrat, 'Contratinsertion.personne_id' );
			$this->set( 'personne_id', $personne_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {
				if( $this->Contratinsertion->save( $this->data ) ) {

					$this->{$this->modelClass}->updateAll(
						array( 'Contratinsertion.positioncer' => '\'annule\'' ),
						array(
							'"Contratinsertion"."personne_id"' => $contrat['Contratinsertion']['personne_id'],
							'"Contratinsertion"."id"' => $contrat['Contratinsertion']['id']
						)
					);
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
			}
			else {
				$this->data = $contrat;
			}
			$this->set( 'urlmenu', '/contratsinsertion/index/'.$personne_id );
		}

		/**
		 * Retourn le PDF de notification d'un CER pour l'OP.
		 *
		 * @param integer $id L'id du CER pour lequel générer la notification.
		 * @return void
		 */
		public function notificationsop( $contratinsertion_id = null ) {
			$pdf = $this->Contratinsertion->getNotificationopPdf( $contratinsertion_id );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( "contratinsertion_%d_notificationop_%s.pdf", $contratinsertion_id, date( 'Y-m-d' ) ) );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la notification du CER pour l\'OP.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Impression de la fiche de liaison d'un CER.
		 *
		 * @param integer $contratinsertion_id
		 * @return void
		 */
		public function ficheliaisoncer( $contratinsertion_id ) {
			$pdf = $this->Contratinsertion->getPdfFicheliaisoncer( $contratinsertion_id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, "contratinsertion_{$contratinsertion_id}_FicheLiaison.pdf" );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la fiche de liaison', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Impression d'une notification pour le bénéficiaire concernant une proposition de décision d'un CER.
		 *
		 * @param integer $id
		 * @return void
		 */
		public function notifbenef( $contratinsertion_id ) {
			$pdf = $this->Contratinsertion->getPdfNotifbenef( $contratinsertion_id , $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, "contratinsertion_{$contratinsertion_id}_NotificationBeneficiaire_.pdf" );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la notification du bénéficiaire', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Imprime un CER.
		 * INFO: http://localhost/webrsa/trunk/contratsinsertion/impression/44327
		 * FIXME: ajouter une colonne de date de première impression ?
		 *
		 * @param integer $contratinsertion_id
		 * @return void
		 */
		public function impression( $contratinsertion_id = null ) {
			$pdf = $this->Contratinsertion->getDefaultPdf( $contratinsertion_id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, "contratinsertion_{$contratinsertion_id}_nouveau.pdf" );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer le courrier de contrat d\'insertion.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		*   Fonction permettant d'enregistrer la date de la notification  au bénéficiaire
		*/
		public function notification( $id ) {
			$this->assert( !empty( $id ), 'error404' );

			$this->Contratinsertion->begin();
			$contratinsertion = $this->Contratinsertion->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.id' => $id
					),
					'contain' => false
				)
			);

			$this->assert( !empty( $contratinsertion ), 'invalidParameter' );
			$this->set( 'contratinsertion', $contratinsertion );

			$personne_id = $contratinsertion['Contratinsertion']['personne_id'];
			$this->set( 'personne_id', $personne_id );

			$dossier_id = $this->Contratinsertion->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			if ( !$this->Jetons->check( $dossier_id ) ) {
				$this->Contratinsertion->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {
				$datenotification = $this->data['Contratinsertion']['datenotification'];
				$saved = $this->Contratinsertion->updateAll(
					array( 'Contratinsertion.datenotification' => "'{$datenotification['year']}-{$datenotification['month']}-{$datenotification['day']}'" ),
					array(
						'"Contratinsertion"."personne_id"' => $personne_id,
						'"Contratinsertion"."id"' => $id
					)
				);
				if( $saved ){
					$this->data['Contratinsertion']['decision_ci'] = $contratinsertion['Contratinsertion']['decision_ci'];
					$this->data['Contratinsertion']['positioncer'] = $this->Contratinsertion->calculPosition( $this->data );

					$saved = $this->Contratinsertion->updateAll(
						array( 'Contratinsertion.positioncer' => "'".$this->data['Contratinsertion']['positioncer']."'" ),
						array(
							'"Contratinsertion"."personne_id"' => $personne_id,
							'"Contratinsertion"."id"' => $id
						)
					);
				}
// debug($this->data);
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
			else{
				$this->data = $contratinsertion;
			}

			$this->set( 'urlmenu', '/contratsinsertion/index/'.$contratinsertion['Contratinsertion']['personne_id'] );
			$this->render( $this->action, null, 'notification' );
		}

		
		/**
		 * Impression d'une notification pour les bénéficiaires de + 55ans
		 *
		 * @param integer $id
		 * @return void
		 */
		public function reconductionCERPlus55Ans( $contratinsertion_id ) {
			$pdf = $this->Contratinsertion->getPdfReconductionCERPlus55Ans( $contratinsertion_id , $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ){
				$this->Gedooo->sendPdfContentToClient( $pdf, "taciteReconductionPlus55ans.pdf" );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la notification du bénéficiaire', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}
	}
?>