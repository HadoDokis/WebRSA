<?php
	App::import( 'Helper', 'Locale' );

	class RendezvousController extends AppController
	{

		public $name = 'Rendezvous';
		public $uses = array( 'Rendezvous', 'Option' );

		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform', 'Default2', 'Fileuploader' );
		public $components = array( 'Gedooo', 'Fileuploader' );

		public $commeDroit = array(
			'view' => 'Rendezvous:index',
			'add' => 'Rendezvous:edit'
		);

		public $aucunDroit = array( 'ajaxreferent', 'ajaxreffonct', 'ajaxperm', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );

		/**
		*
		*/

		protected function _setOptions() {
			$this->set( 'struct', $this->Rendezvous->Structurereferente->listOptions() );
			$this->set( 'permanences', $this->Rendezvous->Permanence->listOptions() );
			$this->set( 'statutrdv', $this->Rendezvous->Statutrdv->find( 'list' ) );
			$options = $this->Rendezvous->allEnumLists();
			$this->set( 'options', $options );
		}


		/**
		*   Ajax pour les coordonnées du référent APRE
		*/

		public function ajaxreffonct( $referent_id = null ) { // FIXME
			Configure::write( 'debug', 0 );

			if( !empty( $referent_id ) ) {
				$referent_id = suffix( $referent_id );
			}
			else {
				$referent_id = suffix( Set::extract( $this->data, 'Rendezvous.referent_id' ) );
			}

			$referent = array();
			if( !empty( $referent_id ) ) {
				$referent = $this->Rendezvous->Referent->findbyId( $referent_id, null, null, -1 );
			}

			$this->set( 'referent', $referent );
			$this->render( 'ajaxreffonct', 'ajax' );
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
			$rendezvous = $this->Rendezvous->find(
				'first',
				array(
					'conditions' => array(
						'Rendezvous.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);

			$personne_id = $rendezvous['Rendezvous']['personne_id'];
			$dossier_id = $this->Rendezvous->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Rendezvous->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Rendezvous->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {

				$saved = $this->Rendezvous->updateAll(
					array( 'Rendezvous.haspiecejointe' => '\''.$this->data['Rendezvous']['haspiecejointe'].'\'' ),
					array(
						'"Rendezvous"."personne_id"' => $personne_id,
						'"Rendezvous"."id"' => $id
					)
				);

				if( $saved ){
					// Sauvegarde des fichiers liés à une PDO
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->data, "Rendezvous.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->Rendezvous->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
// 					$this->redirect( array(  'controller' => 'rendezvous','action' => 'index', $personne_id ) );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->Rendezvous->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/rendezvous/index/'.$personne_id );
			$this->set( compact( 'dossier_id', 'personne_id', 'fichiers', 'rendezvous' ) );
		}

		/**
		*
		*/

		public function index( $personne_id = null ) {
			$this->Rendezvous->Personne->unbindModelAll();
			$nbrPersonnes = $this->Rendezvous->Personne->find(
				'count',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'contain' => false
				)
			);
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			$this->Rendezvous->forceVirtualFields = true;
			$rdvs = $this->Rendezvous->find(
				'all',
				array(
					'fields' => array(
						'Rendezvous.id',
						'Rendezvous.personne_id',
						'Personne.nom_complet',
						'Structurereferente.lib_struc',
						'Referent.nom_complet',
						'Permanence.libpermanence',
						'Typerdv.libelle',
						'Statutrdv.libelle',
						'Rendezvous.daterdv',
						'Rendezvous.heurerdv',
						'Rendezvous.objetrdv',
						'Rendezvous.commentairerdv',
						'StatutrdvTyperdv.motifpassageep',
						'( SELECT COUNT(fichiersmodules.id) FROM fichiersmodules WHERE fichiersmodules.modele = \'Rendezvous\' AND fichiersmodules.fk_value = "Rendezvous"."id" ) AS "Fichiermodule__nbFichiersLies"'
					),
					'joins' => array(
						$this->Rendezvous->join( 'Personne' ),
						$this->Rendezvous->join( 'Structurereferente' ),
						$this->Rendezvous->join( 'Referent' ),
						$this->Rendezvous->join( 'Statutrdv' ),
						$this->Rendezvous->join( 'Permanence' ),
						$this->Rendezvous->join( 'Typerdv' ),
						$this->Rendezvous->Typerdv->join( 'StatutrdvTyperdv' )
					),
					'contain' => false,
					'conditions' => array(
						'Rendezvous.personne_id' => $personne_id
					),
					'order' => array(
						'Rendezvous.daterdv DESC',
						'Rendezvous.heurerdv DESC'
					)
				)
			);
// debug($rdvs);


			if ( isset( $rdvs['0']['Rendezvous']['id'] ) && !empty( $rdvs['0']['Rendezvous']['id'] ) ) {
				$lastrdv_id = $rdvs['0']['Rendezvous']['id'];
			}
			else {
				$lastrdv_id = 0;
			}
			$this->set( 'lastrdv_id', $lastrdv_id );
			$this->Rendezvous->forceVirtualFields = false;

			if ( Configure::read( 'Cg.departement' ) == 58 ) {
				$dossierep = $this->Rendezvous->Personne->Dossierep->find(
					'first',
					array(
						'fields' => array(
							'StatutrdvTyperdv.motifpassageep',
						),
						'joins' => array(
							$this->Rendezvous->Personne->Dossierep->join( 'Sanctionrendezvousep58' ),
							$this->Rendezvous->Personne->Dossierep->Sanctionrendezvousep58->join( 'Rendezvous' ),
							$this->Rendezvous->Personne->Dossierep->Sanctionrendezvousep58->Rendezvous->join( 'Typerdv' ),
							$this->Rendezvous->Personne->Dossierep->Sanctionrendezvousep58->Rendezvous->Typerdv->join( 'StatutrdvTyperdv' )
						),
// 						'contain' => array(
// 							'Sanctionrendezvousep58' => array(
// 								'Rendezvous' => array(
// 									'Typerdv' => array(
// 										'StatutrdvTyperdv'
// 									)
// 								)
// 							)
// 						),
						'conditions' => array(
							'Dossierep.themeep' => 'sanctionsrendezvouseps58',
							'Dossierep.personne_id' => $personne_id,
							'Dossierep.id NOT IN ( '.
								$this->Rendezvous->Personne->Dossierep->Passagecommissionep->sq(
									array(
										'fields' => array(
											'passagescommissionseps.dossierep_id'
										),
										'alias' => 'passagescommissionseps',
										'conditions' => array(
											'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
										)
									)
								)
							.' )'
						),
						'order' => array( 'Dossierep.created ASC' )
					)
				);
				$this->set( compact( 'dossierep' ) );

				$dossierepLie = $this->Rendezvous->Personne->Dossierep->find(
					'count',
					array(
						'conditions' => array(
							'Dossierep.id IN ( '.
								$this->Rendezvous->Personne->Dossierep->Passagecommissionep->sq(
									array(
										'fields' => array(
											'passagescommissionseps.dossierep_id'
										),
										'alias' => 'passagescommissionseps',
										'conditions' => array(
											'passagescommissionseps.etatdossierep' => array( 'associe', 'decisionep', 'decisioncg', 'traite', 'annule', 'reporte' )
										)
									)
								)
							.' )'
						),
						'joins' => array(
							array(
								'table' => 'sanctionsrendezvouseps58',
								'alias' => 'Sanctionrendezvousep58',
								'type' => 'INNER',
								'conditions' => array(
									'Sanctionrendezvousep58.dossierep_id = Dossierep.id',
									'Sanctionrendezvousep58.rendezvous_id' => $lastrdv_id
								)
							)
						),
						'order' => array( 'Dossierep.created ASC' )
					)
				);
				$this->set( compact( 'dossierepLie' ) );
			}

			$this->set( compact( 'rdvs' ) );
			$this->set( 'personne_id', $personne_id );
		}

		/**
		*
		*/

		public function view( $rendezvous_id = null ) {
			$this->Rendezvous->forceVirtualFields = true;
			$rendezvous = $this->Rendezvous->find(
				'first',
				array(
					'fields' => array(
						'Rendezvous.personne_id',
						'Personne.nom_complet',
						'Structurereferente.lib_struc',
						'Referent.nom_complet',
						'Referent.fonction',
						'Permanence.libpermanence',
						'Typerdv.libelle',
						'Statutrdv.libelle',
						'Rendezvous.daterdv',
						'Rendezvous.heurerdv',
						'Rendezvous.objetrdv',
						'Rendezvous.commentairerdv'
					),
					'conditions' => array(
						'Rendezvous.id' => $rendezvous_id
					),
					'recursive' => 0
				)
			);

			$this->assert( !empty( $rendezvous ), 'invalidParameter' );

			$this->set( 'rendezvous', $rendezvous );
			$this->set( 'personne_id', $rendezvous['Rendezvous']['personne_id'] );
			$this->set( 'urlmenu', '/rendezvous/index/'.$rendezvous['Rendezvous']['personne_id'] );
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
			$this->assert( valid_int( $id ), 'invalidParameter' );

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$personne_id = $id;
				$dossier_id = $this->Rendezvous->Personne->dossierId( $personne_id );
			}
			else if( $this->action == 'edit' ) {
				$rdv_id = $id;
				$rdv = $this->Rendezvous->findById( $rdv_id, null, null, -1 );
				$this->assert( !empty( $rdv ), 'invalidParameter' );

				$personne_id = $rdv['Rendezvous']['personne_id'];
				$dossier_id = $this->Rendezvous->dossierId( $rdv_id );
			}

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			$this->Rendezvous->begin();

			$dossier_id = $this->Rendezvous->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Rendezvous->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			$referents = $this->Rendezvous->Referent->listOptions();
			$this->set( 'referents', $referents );


			if( !empty( $this->data ) ){
				if( $this->Rendezvous->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					if( $this->Rendezvous->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
						if ( !empty( $this->data['Rendezvous']['statutrdv_id'] ) && $this->Rendezvous->Statutrdv->provoquePassageEp( $this->data['Rendezvous']['statutrdv_id'] ) && Configure::read( 'Cg.departement' ) == 58 ) {
							if ( $this->Rendezvous->passageEp( $personne_id, $this->data['Rendezvous']['typerdv_id'], $this->data['Rendezvous']['statutrdv_id'] ) ) {
								$dossierep = array(
									'Dossierep' => array(
										'personne_id' => $personne_id,
										'themeep' => 'sanctionsrendezvouseps58'
									)
								);
								$this->Rendezvous->Personne->Dossierep->save( $dossierep );

								$sanctionrendezvousep58 = array(
									'Sanctionrendezvousep58' => array(
										'dossierep_id' => $this->Rendezvous->Personne->Dossierep->id,
										'rendezvous_id' => $this->Rendezvous->id
									)
								);
								$this->Rendezvous->Personne->Dossierep->Sanctionrendezvousep58->save( $sanctionrendezvousep58 );
							}
						}
						$this->Jetons->release( $dossier_id );
						$this->Rendezvous->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array(  'controller' => 'rendezvous','action' => 'index', $personne_id ) );
					}
					else {
						$this->Rendezvous->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			else{
				if( $this->action == 'edit' ) {
					$this->data = $rdv;

				}
				else{
					//Récupération de la structure référente liée à l'orientation
					$orientstruct = $this->Rendezvous->Structurereferente->Orientstruct->find(
						'first',
						array(
							'fields' => array(
								'Orientstruct.id',
								'Orientstruct.personne_id',
								'Orientstruct.structurereferente_id'
							),
							'conditions' => array(
								'Orientstruct.personne_id' => $personne_id,
								'Orientstruct.date_valid IS NOT NULL'
							),
							'contain' => array(
								'Structurereferente',
								'Referent'
							),
							'order' => array( 'Orientstruct.date_valid DESC' )
						)
					);

					if( !empty( $orientstruct ) ){
						$this->data['Rendezvous']['structurereferente_id'] = $orientstruct['Orientstruct']['structurereferente_id'];
					}
				}
			}
			$this->Rendezvous->commit();

			$struct_id = Set::classicExtract( $this->data, "{$this->modelClass}.structurereferente_id" );
			$this->set( 'struct_id', $struct_id );

			$referent_id = Set::classicExtract( $this->data, "{$this->modelClass}.referent_id" );
			$referent_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $referent_id );
			$this->set( 'referent_id', $referent_id );

			$permanence_id = Set::classicExtract( $this->data, "{$this->modelClass}.permanence_id" );
			$permanence_id = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $permanence_id );
			$this->set( 'permanence_id', $permanence_id );

			$typerdv = $this->Rendezvous->Typerdv->find( 'list', array( 'fields' => array( 'id', 'libelle' ) ) );
			$this->set( 'typerdv', $typerdv );

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/rendezvous/index/'.$personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		* Suppression du rendez-vous et du dossier d'EP lié si celui-ci n'est pas
		* associé à un passage en commission d'EP
		*/

		public function delete( $id ) {
			$rendezvous = $this->Rendezvous->find(
				'first',
				array(
					'fields' => array(
						'Rendezvous.personne_id'
					),
					'conditions' => array(
						'Rendezvous.id' => $id
					),
					'contain' => false
				)
			);
			$success = true;

			if ( Configure::read( 'Cg.departement' ) == 58 ) {
				$dossierep = $this->Rendezvous->Sanctionrendezvousep58->find(
					'first',
					array(
						'fields' => array(
							'Sanctionrendezvousep58.id',
							'Sanctionrendezvousep58.dossierep_id'
						),
						'conditions' => array(
							'Sanctionrendezvousep58.rendezvous_id' => $id
						),
						'contain' => false
					)
				);

				if ( !empty( $dossierep ) ) {
					$success = $this->Rendezvous->Sanctionrendezvousep58->delete( $dossierep['Sanctionrendezvousep58']['id'] ) && $success;
				}
			}
			$success = $this->Rendezvous->delete( $id ) && $success;

			$this->_setFlashResult( 'Save', $success );
			$this->redirect( array(  'controller' => 'rendezvous','action' => 'index', $rendezvous['Rendezvous']['personne_id'] ) );
		}

		function gedooo( $rdv_id = null ) {
			// TODO: error404/error500 si on ne trouve pas les données
			$qual = $this->Option->qual();
			$typevoie = $this->Option->typevoie();

			$rdv = $this->Rendezvous->find(
				'first',
				array(
					'conditions' => array(
						'Rendezvous.id' => $rdv_id
					)
				)
			);

			///Pour le choix entre les différentes notifications possibles
			$modele = $rdv['Typerdv']['modelenotifrdv'];

			$this->Rendezvous->Personne->Foyer->Adressefoyer->bindModel(
				array(
					'belongsTo' => array(
						'Adresse' => array(
							'className'     => 'Adresse',
							'foreignKey'    => 'adresse_id'
						)
					)
				)
			);

			$adresse = $this->Rendezvous->Personne->Foyer->Adressefoyer->find(
				'first',
				array(
					'conditions' => array(
						'Adressefoyer.foyer_id' => $rdv['Personne']['foyer_id'],
						'Adressefoyer.rgadr' => '01',
					)
				)
			);
			$rdv['Adresse'] = $adresse['Adresse'];

			// Récupération de l'utilisateur
			$user = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $this->Session->read( 'Auth.User.id' )
					)
				)
			);
			$rdv['User'] = $user['User'];
			$rdv['Serviceinstructeur'] = $user['Serviceinstructeur'];

			$dossier = $this->Rendezvous->Personne->Foyer->Dossier->find(
				'first',
				array(
					'conditions' => array(
						'Foyer.id' => $rdv['Personne']['foyer_id']
					),
					'contain' => array(
						'Foyer'
					)
				)
			);

			$rdv['Dossier_RSA'] = $dossier['Dossier'];

			///Pour la qualité de la personne
			$rdv['Personne']['qual'] = Set::extract( $qual, Set::extract( $rdv, 'Personne.qual' ) );
			///Pour l'adresse de la structure référente
			$rdv['Structurereferente']['type_voie'] = Set::extract( $typevoie, Set::classicExtract( $rdv, 'Structurereferente.type_voie' ) );
			///Pour la date du rendez-vous
			$LocaleHelper = new LocaleHelper();
			$rdv['Rendezvous']['daterdv'] =  $LocaleHelper->date( '%d/%m/%Y', Set::classicExtract( $rdv, 'Rendezvous.daterdv' ) );
			$rdv['Rendezvous']['heurerdv'] = $LocaleHelper->date( 'Time::short', Set::classicExtract( $rdv, 'Rendezvous.heurerdv' ) );
			///Pour l'adresse de la personne
			$rdv['Adresse']['typevoie'] = Set::extract( $typevoie, Set::extract( $rdv, 'Adresse.typevoie' ) );

			///Pour le référent lié au RDV
			$structurereferente_id = Set::classicExtract( $rdv, 'Structurereferente.id' );
			$referents = $this->Rendezvous->Personne->Referent->referentsListe( $structurereferente_id );
			$this->set( 'referents', $referents );
			if( !empty( $referents ) ) {
				$rdv['Rendezvous']['referent_id'] = Set::extract( $referents, Set::classicExtract( $rdv, 'Rendezvous.referent_id' ) );
			}

			///Pour les permanences liées aux structures référentes
			$perm = $this->Rendezvous->Personne->Referent->Structurereferente->Permanence->find(
				'first',
				array(
					'conditions' => array(
						'Permanence.id' => Set::classicExtract( $rdv, 'Rendezvous.permanence_id' )
					)
				)
			);
			$rdv['Permanence'] = $perm['Permanence'];
			if( !empty( $perm ) ){
				$rdv['Permanence']['typevoie'] = Set::extract( $typevoie, Set::classicExtract( $rdv, 'Permanence.typevoie' ) );
			}

			$pdf = $this->Rendezvous->ged( $rdv, 'RDV/'.$modele.'.odt' );
			$this->Gedooo->sendPdfContentToClient( $pdf, sprintf( 'rendezvous-%s.pdf', date( 'Y-m-d' ) ) );
		}
	}
?>