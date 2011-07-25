<?php
	class OrientsstructsController extends AppController
	{

		public $name = 'Orientsstructs';
		public $uses = array( 'Orientstruct',  'Option' , 'Dossier', 'Foyer', 'Adresse', 'Adressefoyer', 'Personne', 'Typeorient', 'Structurereferente', 'Pdf', 'Referent' );
		public $helpers = array( 'Default', 'Default2', 'Fileuploader' );
		public $components = array( 'Gedooo', 'Fileuploader' );

		public $commeDroit = array(
			'add' => 'Orientsstructs:edit'
		);

		public $aucunDroit = array( 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		protected function _setOptions() {
			$this->set( 'pays', $this->Option->pays() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );
			$this->set( 'referents', $this->Referent->listOptions() );
			$this->set( 'typesorients', $this->Typeorient->listOptions() );
			$this->set( 'structs', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );

			$options = array();
			$options = $this->Orientstruct->allEnumLists();
			$this->set( compact( 'options' ) );

			//Ajout des structures et référents orientants
			$this->set( 'refsorientants', $this->Referent->listOptions() );
			$this->set( 'structsorientantes', $this->Structurereferente->listOptions( array( 'orientation' => 'O' ) ) );

		}


		/**
		*
		*/

		public function beforeFilter() {
			$return = parent::beforeFilter();
			$options = array();
			foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
			}

			$this->set( compact( 'options' ) );

			return $return;
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
			$orientstruct = $this->Orientstruct->find(
				'first',
				array(
					'conditions' => array(
						'Orientstruct.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);

			$personne_id = $orientstruct['Orientstruct']['personne_id'];
			$dossier_id = $this->Orientstruct->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Orientstruct->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Orientstruct->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {

				$saved = $this->Orientstruct->updateAll(
					array( 'Orientstruct.haspiecejointe' => '\''.$this->data['Orientstruct']['haspiecejointe'].'\'' ),
					array(
						'"Orientstruct"."personne_id"' => $personne_id,
						'"Orientstruct"."id"' => $id
					)
				);

				if( $saved ){
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->data, "Orientstruct.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->Orientstruct->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array(  'controller' => 'orientsstructs','action' => 'index', $personne_id ) );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Orientstruct->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

//             $this->Orientstruct->commit();
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'orientstruct' ) );

			$this->_setOptions();
		}


		/**
		*
		*
		*
		*/

		public function index( $personne_id = null ){
			$this->assert( valid_int( $personne_id ), 'invalidParameter' );

			$orientstructs = $this->Orientstruct->find(
				'all',
				array(
					'conditions' => array(
						'Orientstruct.personne_id' => $personne_id
					),
// 					'recursive' => 0,
					'contain' => array(
						'Personne' => array(
							'Calculdroitrsa'
						),
						'Typeorient',
						'Structurereferente',
						'Referent'
					),
					'order' => array(
						'COALESCE( Orientstruct.rgorient, \'0\') DESC',
						'Orientstruct.date_valid DESC'
					)
				)
			);

			foreach( $orientstructs as $key => $orientstruct ) {
				$orientstruct[$this->Orientstruct->alias]['imprime'] = $this->Pdf->find(
					'count',
					array(
						'conditions' => array(
							'Pdf.fk_value' => $orientstruct[$this->Orientstruct->alias]['id'],
							'Pdf.modele = \'Orientstruct\''
						)
					)
				);
				$orientstructs[$key] = $orientstruct;
			}

			$dossier_id = Set::extract( $orientstructs, '0.Personne.Foyer.dossier_id' );

			$en_procedure_relance = false;
			if( Configure::read( 'Cg.departement' ) == 93 ) {
				// TODO: à déplacer dans un modèle à terme
				$en_procedure_relance = (
					$this->Orientstruct->Nonrespectsanctionep93->find(
						'count',
						array(
							'contain' => array(
								'Dossierep',
								'Orientstruct',
								'Contratinsertion',
								'Propopdo',
							),
							'conditions' => array(
								'OR' => array(
									array(
										'Dossierep.personne_id' => $personne_id,
										'Dossierep.id NOT IN ( '.$this->Personne->Dossierep->Passagecommissionep->sq(
											array(
												'alias' => 'passagescommissionseps',
												'fields' => array(
													'passagescommissionseps.dossierep_id'
												),
												'conditions' => array(
													'passagescommissionseps.etatdossierep' => 'traite'
												)
											)
										).' )'
									),
									array(
										'Nonrespectsanctionep93.active' => 1,
										'OR' => array(
											array(
												'Orientstruct.personne_id' => $personne_id,
												'Nonrespectsanctionep93.origine' => 'orientstruct'
											),
											array(
												'Contratinsertion.personne_id' => $personne_id,
												'Nonrespectsanctionep93.origine' => 'contratinsertion'
											),
											array(
												'Propopdo.personne_id' => $personne_id,
												'Nonrespectsanctionep93.origine' => 'pdo'
											)
										)
									),
								)
							)
						)
					) > 0
				);

				$reorientationep93 = $this->Orientstruct->Reorientationep93->find(
					'first',
					$this->Orientstruct->Reorientationep93->qdReorientationEnCours( $personne_id )
				);
				$this->set( 'reorientationep93', $reorientationep93 );
				$this->set( 'optionsdossierseps', $this->Orientstruct->Reorientationep93->Dossierep->Passagecommissionep->enums() );
			}
			elseif ( Configure::read( 'Cg.departement' ) == 58 ) {
				$propoorientationcov58 = $this->Orientstruct->Personne->Dossiercov58->Propoorientationcov58->find(
					'first',
					array(
						'fields' => array(
							'Propoorientationcov58.id',
							'Propoorientationcov58.dossiercov58_id',
							'Propoorientationcov58.datedemande',
							'Propoorientationcov58.rgorient',
							'Propoorientationcov58.typeorient_id',
							'Typeorient.lib_type_orient',
							'Propoorientationcov58.structurereferente_id',
							'Structurereferente.lib_struc',
							'Dossiercov58.personne_id',
							'Dossiercov58.etapecov',
							'Personne.id',
							'Personne.nom',
							'Personne.prenom'
						),
						'conditions' => array(
							'Dossiercov58.personne_id' => $personne_id,
							'Themecov58.name' => 'proposorientationscovs58',
							'Dossiercov58.etapecov <>' => 'finalise'
						),
						'joins' => array(
							array(
								'table' => 'dossierscovs58',
								'alias' => 'Dossiercov58',
								'type' => 'INNER',
								'conditions' => array(
									'Dossiercov58.id = Propoorientationcov58.dossiercov58_id'
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
							),
							array(
								'table' => 'typesorients',
								'alias' => 'Typeorient',
								'type' => 'INNER',
								'conditions' => array(
									'Propoorientationcov58.typeorient_id = Typeorient.id'
								)
							),
							array(
								'table' => 'structuresreferentes',
								'alias' => 'Structurereferente',
								'type' => 'INNER',
								'conditions' => array(
									'Propoorientationcov58.structurereferente_id = Structurereferente.id'
								)
							)
						),
						'contain' => false,
						'order' => array( 'Propoorientationcov58.rgorient DESC' )
					)
				);
				$this->set( 'propoorientationcov58', $propoorientationcov58 );
				$this->set( 'optionsdossierscovs58', $this->Orientstruct->Personne->Dossiercov58->enums() );

				$regressionorientaionep58 = $this->Orientstruct->Personne->Dossierep->Regressionorientationep58->find(
					'first',
					$this->Orientstruct->Personne->Dossierep->Regressionorientationep58->qdReorientationEnCours( $personne_id )
				);
				$this->set( compact( 'regressionorientaionep58' ) );
				$this->set( 'optionsdossierseps', $this->Orientstruct->Personne->Dossierep->Passagecommissionep->enums() );
			}

			$this->set( 'droitsouverts', $this->Dossier->Situationdossierrsa->droitsOuverts( $dossier_id ) );
			$this->set( 'orientstructs', $orientstructs );
			$this->set( 'en_procedure_relance', $en_procedure_relance );
			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );

			$force_edit = false;
			$rgorient_max = $this->Orientstruct->rgorientMax( $personne_id );

			if ( Configure::read( 'Cg.departement' ) == 58 && $rgorient_max <=1 ) {
				$ajout_possible = $this->Orientstruct->Personne->Dossiercov58->ajoutPossible( $personne_id ) && $this->Orientstruct->ajoutPossible( $personne_id );
				$nbdossiersnonfinalisescovs = $this->Orientstruct->Personne->Dossiercov58->find(
					'count',
					array(
						'conditions' => array(
							'Dossiercov58.personne_id' => $personne_id,
							'Dossiercov58.etapecov <>' => 'finalise'
						),
						'joins' => array(
							array(
								'table' => 'proposorientationscovs58',
								'alias' => 'Propoorientationcov58',
								'type' => 'INNER',
								'conditions' => array(
									'Propoorientationcov58.dossiercov58_id = Dossiercov58.id'
								)
							)
						)
					)
				);
				$this->set( 'ajout_possible', $ajout_possible );
				$this->set( 'nbdossiersnonfinalisescovs', $nbdossiersnonfinalisescovs );
			}
			else {
				$this->set( 'ajout_possible', $this->Orientstruct->ajoutPossible( $personne_id ) );
			}

			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$force_edit = ( $rgorient_max == 0 );
			}

			$this->set( 'force_edit', $force_edit );
			$this->set( 'rgorient_max', $rgorient_max );
			$this->set( 'last_orientstruct_id', @$orientstructs[0]['Orientstruct']['id'] );
		}

		/**
		*
		*
		*
		*/

		public function add( $personne_id = null ) {
			$this->assert( valid_int( $personne_id ), 'invalidParameter' );

			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// Retour à l'index s'il n'est pas possible d'ajouter une orientation
			if( !$this->Orientstruct->ajoutPossible( $personne_id ) ) {
				$this->Session->setFlash( 'Impossible d\'ajouter une orientation pour cette personne.', 'flash/error' );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// Pour le CG 93, les orientations de rang > 1 doivent passer en EP, donc il faut utiliser Reorientationseps93Controller::add
			// FIXME
			/*if( Configure::read( 'Cg.departement' ) == 93 && $this->Orientstruct->rgorientMax( $personne_id ) > 1 ) {
				$this->Session->setFlash( 'L\'orientation de cette personne doit se faire via un passage en EP', 'flash/error' );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}*/

			$dossier_id = $this->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Orientstruct->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Orientstruct->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

//             $this->set( 'options', $this->Typeorient->listOptions() );
//             $this->set( 'options2', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
//             $this->set( 'referents', $this->Referent->listOptions() );


			if( !empty( $this->data ) ) {
				$this->data['Orientstruct']['user_id'] = $this->Session->read( 'Auth.User.id' );

				$this->Orientstruct->set( $this->data );
//                 $this->Typeorient->set( $this->data );
//                 $this->Structurereferente->set( $this->data );

				$validates = $this->Orientstruct->validates();
//				$validates = $this->Typeorient->validates() && $validates;
//				$validates = $this->Structurereferente->validates() && $validates;

				if( $validates ) {
					$saved = true;

					/// FIXME: ne fonctionne que pour le cg58, à faire évoluer une fois la thématique mise en place
					if ( $this->Orientstruct->isRegression( $personne_id, $this->data['Orientstruct']['typeorient_id'] ) && /*( */Configure::read( 'Cg.departement' ) == 58 /*|| Configure::read( 'Cg.departement' ) == 93 )*/ ) {
						$theme = 'Regressionorientationep'.Configure::read( 'Cg.departement' );

						$dossierep = array(
							'Dossierep' => array(
								'personne_id' => $personne_id,
								'themeep' => Inflector::tableize( $theme )
							)
						);
						
						$saved = $this->Orientstruct->Personne->Dossierep->save( $dossierep ) && $saved;

						$regressionorientationep[$theme] = $this->data['Orientstruct'];
						$regressionorientationep[$theme]['personne_id'] = $personne_id;
						$regressionorientationep[$theme]['dossierep_id'] = $this->Orientstruct->Personne->Dossierep->id;

						if ( isset( $regressionorientationep[$theme]['referent_id'] ) && !empty( $regressionorientationep[$theme]['referent_id'] ) ) {
							list( $structurereferente_id, $referent_id ) = explode( '_', $regressionorientationep[$theme]['referent_id'] );
							$regressionorientationep[$theme]['structurereferente_id'] = $structurereferente_id;
							$regressionorientationep[$theme]['referent_id'] = $referent_id;
						}

						$regressionorientationep[$theme]['datedemande'] = $regressionorientationep[$theme]['date_propo'];

						$saved = $this->Orientstruct->Personne->Dossierep->{$theme}->save( $regressionorientationep ) && $saved;

					}
					else {
						// Correction: si la personne n'a pas encore d'entrée dans calculdroitsrsa
						$this->data['Calculdroitrsa']['personne_id'] = $personne_id;
						$this->data['Orientstruct']['personne_id'] = $personne_id;
						$this->data['Orientstruct']['valid_cg'] = true;
						if( Configure::read( 'Cg.departement' ) != 66 ) {
							$this->data['Orientstruct']['date_propo'] = date( 'Y-m-d' );
							$this->data['Orientstruct']['date_valid'] = date( 'Y-m-d' );
						}
						$this->data['Orientstruct']['statut_orient'] = 'Orienté';

						$saved = $this->Orientstruct->Personne->Calculdroitrsa->save( $this->data );
						$saved = $this->Orientstruct->save( $this->data['Orientstruct'] ) && $saved;
					}

					if ( Configure::read( 'Cg.departement' ) == 66 && $saved && !empty( $this->data['Orientstruct']['referent_id'] ) ) {
						$saved = $this->Orientstruct->Referent->PersonneReferent->referentParOrientstruct( $this->data ) && $saved;
					}

					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Orientstruct->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Orientstruct->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else {
				$personne = $this->Personne->findByid( $personne_id, null, null, 0 );
				$this->data['Calculdroitrsa'] = $personne['Calculdroitrsa'];
			}

			//$this->Orientstruct->commit();

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		public function edit( $orientstruct_id = null ) {
			$this->assert( valid_int( $orientstruct_id ), 'invalidParameter' );

			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$orientstruct_id = $this->Orientstruct->field( 'personne_id', array( 'id' => $orientstruct_id ) );
				$this->redirect( array( 'action' => 'index', $orientstruct_id ) );
			}

			$orientstruct = $this->Orientstruct->find(
				'first',
				array(
					'conditions' => array(
						'Orientstruct.id' => $orientstruct_id
					),
					'contain' => array(
						'Personne' => array(
							'Calculdroitrsa'
						)
					)
				)
			);
			$this->assert( !empty( $orientstruct ), 'invalidParameter' );

			// Retour à l'index si on essaie de modifier une autre orientation que la dernière
			if( !empty( $orientstruct['Orientstruct']['date_valid'] ) && $orientstruct['Orientstruct']['statut_orient'] == 'Orienté' && $orientstruct['Orientstruct']['rgorient'] != $this->Orientstruct->rgorientMax( $orientstruct['Orientstruct']['personne_id'] ) ) {
				$this->Session->setFlash( 'Impossible de modifier une autre orientation que la plus récente.', 'flash/error' );
				$this->redirect( array( 'action' => 'index', $orientstruct['Orientstruct']['personne_id'] ) );
			}

			$dossier_id = $this->Orientstruct->dossierId( $orientstruct_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Orientstruct->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Orientstruct->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

//             $this->set( 'options', $this->Typeorient->listOptions() );
//             $this->set( 'options2', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
//             $this->set( 'referents', $this->Referent->listOptions() );

			// Essai de sauvegarde
			if( !empty( $this->data ) ) {
				$this->data['Orientstruct']['user_id'] = $this->Session->read( 'Auth.User.id' );

				// Correction: si la personne n'a pas encore d'entrée dans calculdroitsrsa
				$this->data['Calculdroitrsa']['personne_id'] = $orientstruct['Orientstruct']['personne_id'];
				$this->data['Orientstruct']['personne_id'] = $orientstruct['Orientstruct']['personne_id'];

				$this->Orientstruct->set( $this->data );
				$this->Orientstruct->Personne->Calculdroitrsa->set( $this->data );
				$valid = $this->Orientstruct->Personne->Calculdroitrsa->validates();
				$valid = $this->Orientstruct->validates() && $valid;

				if( $valid ) {
					$saved = true;
					$saved = $this->Orientstruct->Personne->Calculdroitrsa->save( $this->data ) && $saved;
					$saved = $this->Orientstruct->save( $this->data ) && $saved;
	
					if ( Configure::read( 'Cg.departement' ) == 66 && $saved && !empty( $this->data['Orientstruct']['referent_id'] ) ) {
						$saved = $this->Orientstruct->Referent->PersonneReferent->referentParOrientstruct( $this->data ) && $saved;
					}

					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Orientstruct->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $orientstruct['Orientstruct']['personne_id'] ) );

					}
					else {
						$this->Orientstruct->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			// Afficage des données
			else {
				// Assignation au formulaire*
				$this->data = Set::merge( array( 'Orientstruct' => $orientstruct['Orientstruct'] ), array( 'Calculdroitrsa' => $orientstruct['Personne']['Calculdroitrsa'] ) );

			}

			$this->Orientstruct->commit();
			$this->_setOptions();
			$this->set( 'personne_id', $orientstruct['Orientstruct']['personne_id'] );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		* Impression d'une orientation simple
		*
		* - dans cohortes/orientees -> cohortes/impression_individuelle
		* - dans orientsstructs/index -> gedooos/orientstruct
		*/

		public function impression( $orientstruct_id = null ) {
			$this->assert( !empty( $orientstruct_id ), 'error404' );

			$pdf = $this->Orientstruct->getStoredPdf( $orientstruct_id, 'date_impression' );

			$this->assert( !empty( $pdf ), 'error404' );
			$this->assert( !empty( $pdf['Pdf']['document'] ), 'error500' ); // FIXME: ou en faire l'impression ?

			$this->Gedooo->sendPdfContentToClient( $pdf['Pdf']['document'], "{$orientstruct_id}.pdf" );
		}
	}
?>