<?php
	/**
	 * Code source de la classe ActionscandidatsPersonnesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'CakeEmail', 'Network/Email' );
	App::uses( 'WebrsaEmailConfig', 'Utility' );

	/**
	 * La classe ActionscandidatsPersonnesController permet la gestion des fiches
	 * de liaison (CG 66 et 93).
	 *
	 * @package app.Controller
	 */
	class ActionscandidatsPersonnesController extends AppController
	{
		public $name = 'ActionscandidatsPersonnes';
		public $uses = array( 'ActioncandidatPersonne','Option' );
		public $helpers = array( 'Default', 'Locale', 'Cake1xLegacy.Ajax', 'Xform', 'Default2', 'Fileuploader' );
		public $components = array( 'Default', 'Gedooo.Gedooo', 'Fileuploader', 'Jetons2', 'DossiersMenus', 'InsertionsAllocataires' );
		public $aucunDroit = array( 'ajaxpart', 'ajaxstruct', 'ajaxreferent', 'ajaxreffonct', 'ajaxfileupload', 'ajaxfiledelete', 'fileview', 'download' );
		public $commeDroit = array(
// 			'view' => 'ActionscandidatsPersonnes:index',
			'add' => 'ActionscandidatsPersonnes:edit'
		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'add' => 'create',
			'ajaxfiledelete' => 'delete',
			'ajaxfileupload' => 'update',
			'ajaxpart' => 'read',
			'ajaxreferent' => 'read',
			'ajaxreffonct' => 'read',
			'ajaxstruct' => 'read',
			'cancel' => 'update',
			'delete' => 'delete',
			'download' => 'read',
			'edit' => 'update',
			'filelink' => 'read',
			'fileview' => 'read',
			'index' => 'read',
			'indexparams' => 'read',
			'maillink' => 'read',
			'printFiche' => 'read',
			'view' => 'read',
		);

		/**
		 *
		 */
		protected function _setOptions() {
			$options = array( );
			foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
				$options = Hash::insert( $options, "{$this->modelClass}.{$field}", $values );
			}

			$options = Hash::insert( $options, 'Adresse.typevoie', $this->Option->typevoie() );
			$options = Hash::insert( $options, 'Personne.qual', $this->Option->qual() );
			$options = Hash::insert( $options, 'Contratinsertion.decision_ci', $this->Option->decision_ci() );
			$options = Hash::insert( $options, 'Dsp', $this->ActioncandidatPersonne->Personne->Dsp->allEnumLists() );

			foreach( array( 'Referent' ) as $linkedModel ) {
				$field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
				$options = Hash::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list', array( 'recursive' => -1 ) ) );
			}
			$field = Inflector::singularize( Inflector::tableize( 'Actioncandidat' ) ).'_id';
			$options = Hash::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{'Actioncandidat'}->find( 'list', array( 'recursive' => -1, 'order' => 'name' ) ) );

			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
			$this->set( 'sitfam', $this->Option->sitfam() );
			$this->set( 'sect_acti_emp', $this->Option->sect_acti_emp() );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'typeservice', $this->ActioncandidatPersonne->Personne->Orientstruct->Serviceinstructeur->find( 'first' ) );
			$this->set( compact( 'options', 'typevoie' ) );
		}

		/**
		 * Liste des paramétrages pour le module.
		 */
		public function indexparams() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}
			$compteurs = array(
				'Partenaire' => ClassRegistry::init( 'Partenaire' )->find( 'count' ),
				'Contactpartenaire' => ClassRegistry::init( 'Contactpartenaire' )->find( 'count' )
			);
			$this->set( compact( 'compteurs' ) );
			$this->render( 'indexparams_'.Configure::read( 'ActioncandidatPersonne.suffixe' ) );
		}

		/**
		 *
		 */
		public function ajaxfileupload() {
			$this->Fileuploader->ajaxfileupload();
		}

		/**
		 *
		 */
		public function ajaxfiledelete() {
			$this->Fileuploader->ajaxfiledelete();
		}

		/**
		 * Visualisation d'un fichier lié avant son envoi sur le serveur.
		 *
		 * @param integer $id
		 */
		public function fileview( $id ) {
			$this->Fileuploader->fileview( $id );
		}

		/**
		 * Téléchargement d'un fichier lié au module.
		 *
		 * @param integer $fichiermodule_id
		 */
		public function download( $fichiermodule_id ) {
			$this->assert( !empty( $fichiermodule_id ), 'error404' );
			$this->Fileuploader->download( $fichiermodule_id );
		}

		/**
		 * Liste des fichiers liés à une fiche de liaison.
		 *
		 * @param integer $id
		 */
		public function filelink( $id ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$fichiers = array( );
			$actioncandidat_personne = $this->ActioncandidatPersonne->find(
				'first',
				array(
					'conditions' => array(
						'ActioncandidatPersonne.id' => $id
					),
					'contain' => array(
						'Fichiermodule' => array(
							'fields' => array( 'name', 'id', 'created', 'modified' )
						)
					)
				)
			);


			$dossier_id = $this->ActioncandidatPersonne->Personne->dossierId( $actioncandidat_personne['ActioncandidatPersonne']['personne_id'] );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			$personne_id = Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.personne_id' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

            $this->Jetons2->get( $dossier_id );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
                $this->Jetons2->release( $dossier_id );
                $this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
                $this->ActioncandidatPersonne->begin();

				$saved = $this->ActioncandidatPersonne->updateAllUnBound(
					array( 'ActioncandidatPersonne.haspiecejointe' => '\''.$this->request->data['ActioncandidatPersonne']['haspiecejointe'].'\'' ),
					array( '"ActioncandidatPersonne"."id"' => $id )
				);

				if( $saved ) {
					// Sauvegarde des fichiers liés.
					$dir = $this->Fileuploader->dirFichiersModule( $this->action, $this->request->params['pass'][0] );
					$saved = $this->Fileuploader->saveFichiers( $dir, !Set::classicExtract( $this->request->data, "ActioncandidatPersonne.haspiecejointe" ), $id ) && $saved;
				}

				if( $saved ) {
					$this->ActioncandidatPersonne->commit();
					$this->Jetons2->release( $dossier_id );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( $this->referer() );
				}
				else {
					$fichiers = $this->Fileuploader->fichiers( $id );
					$this->ActioncandidatPersonne->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->_setOptions();
			$this->set( 'actioncandidat_personne', $actioncandidat_personne );
			$this->set( compact( 'dossier_id', 'id', 'fichiers' ) );
			$this->set( 'personne_id', $personne_id );
		}

		/**
		 * Liste des fiches de liaison d'un allocataire.
		 *
		 * @param integer $personne_id
		 */
		public function index( $personne_id ) {
			// Préparation du menu du dossier
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$dossier_id = $this->ActioncandidatPersonne->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			$this->set( 'dossier_id', $dossier_id );

			//Vérification de la présence d'une orientation ou d'un référent pour cet allocataire
			$referentLie = $this->ActioncandidatPersonne->Personne->PersonneReferent->find(
				'count',
				array(
					'conditions' => array(
						'PersonneReferent.personne_id' => $personne_id,
						'PersonneReferent.dfdesignation IS NULL'
					),
					'contain' => false
				)
			);
			$this->set( compact( 'referentLie' ) );

			$orientationLiee = $this->ActioncandidatPersonne->Personne->Orientstruct->find(
				'count',
                array(
                    'conditions' => array(
                        'Orientstruct.personne_id' => $personne_id
                	),
                    'contain' => false
                )
			);
			$this->set( compact( 'orientationLiee' ) );

			$queryData = array(
				'fields' => array_merge(
					$this->ActioncandidatPersonne->Actioncandidat->fields(),
					$this->ActioncandidatPersonne->Motifsortie->fields(),
					$this->ActioncandidatPersonne->Actioncandidat->Contactpartenaire->fields(),
					$this->ActioncandidatPersonne->Actioncandidat->Contactpartenaire->Partenaire->fields(),
					$this->ActioncandidatPersonne->fields(),
					array(
						$this->ActioncandidatPersonne->Referent->sqVirtualField( 'nom_complet' ),
						$this->ActioncandidatPersonne->Fichiermodule->sqNbFichiersLies( $this->ActioncandidatPersonne, 'nb_fichiers_lies', 'ActioncandidatPersonne' )
					)
				),
				'conditions' => array(
					'ActioncandidatPersonne.personne_id' => $personne_id
				),
				'joins' => array(
					$this->ActioncandidatPersonne->join( 'Actioncandidat', array( 'type' => 'INNER' ) ),
					$this->ActioncandidatPersonne->join( 'Referent', array( 'type' => 'INNER' ) ),
					$this->ActioncandidatPersonne->join( 'Motifsortie', array( 'type' => 'LEFT OUTER' ) ),
					$this->ActioncandidatPersonne->Actioncandidat->join( 'Contactpartenaire', array( 'type' => 'LEFT OUTER' ) ),
					$this->ActioncandidatPersonne->Actioncandidat->Contactpartenaire->join( 'Partenaire', array( 'type' => 'LEFT OUTER' ) )
				),
				'contain' => false,
				'order' => array( 'ActioncandidatPersonne.datesignature DESC' )
			);
			$actionscandidats_personnes = $this->ActioncandidatPersonne->find( 'all', $queryData );
			$this->set( 'actionscandidats_personnes', $actionscandidats_personnes );

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
		}

		/**
		 * Ajax pour les partenaires fournissant les actions
		 *
		 * @param integer $actioncandidat_id
		 */
		public function ajaxpart( $actioncandidat_id = null ) {
			Configure::write( 'debug', 0 );

			$dataActioncandidat_id = Set::extract( $this->request->data, 'ActioncandidatPersonne.actioncandidat_id' );
			$actioncandidat_id = ( empty( $actioncandidat_id ) && !empty( $dataActioncandidat_id ) ? $dataActioncandidat_id : $actioncandidat_id );

			if( !empty( $actioncandidat_id ) ) {

				$actioncandidat = $this->ActioncandidatPersonne->Actioncandidat->find(
					'first',
					array(
						'conditions' => array(
							'Actioncandidat.id' => $actioncandidat_id
						),
						'contain' => array(
							'Contactpartenaire' => array(
								'Partenaire'
							),
							'Fichiermodule' => array(
								'fields' => array( 'Fichiermodule.id', 'Fichiermodule.name', 'Fichiermodule.created' )
							)
						)
					)
				);

				if( ($actioncandidat['Actioncandidat']['correspondantaction'] == 1) && !empty( $actioncandidat['Actioncandidat']['referent_id'] ) ) {
					$this->ActioncandidatPersonne->Personne->Referent->recursive = -1;
					$referent = $this->ActioncandidatPersonne->Personne->Referent->read( null, $actioncandidat['Actioncandidat']['referent_id'] );
				}
				$this->set( compact( 'actioncandidat', 'referent' ) );
			}
			$this->render( 'ajaxpart', 'ajax' );
		}

		/**
		 *
		 * @param integer $referent_id
		 */
		public function ajaxreferent( $referent_id = null ) {
			Configure::write( 'debug', 0 );
			$dataReferent_id = Set::extract( $this->request->data, 'ActioncandidatPersonne.referent_id' );
			$referent_id = ( empty( $referent_id ) && !empty( $dataReferent_id ) ? $dataReferent_id : $referent_id );

			$this->set( 'typevoie', $this->Option->typevoie() );
			$prescripteur = $this->ActioncandidatPersonne->Personne->Referent->find(
				'first',
				array(
					'fields' => array(
						'Referent.numero_poste',
						'Referent.email',
						'Structurereferente.lib_struc',
						'Structurereferente.num_voie',
                        'Structurereferente.type_voie',
						'Structurereferente.nom_voie',
						'Structurereferente.code_postal',
						'Structurereferente.ville'
					),
					'conditions' => array(
						'Referent.id' => $referent_id
					),
					'contain' => array(
						'Structurereferente'
					)
				)
			);
			$this->set( compact( 'prescripteur' ) );
			$this->render( 'ajaxreferent', 'ajax' );
		}

		/**
		 * Ajax pour les partenaires fournissant les actions
		 *
		 * @param integer $referent_id
		 */
		public function ajaxstruct( $referent_id = null ) {
			Configure::write( 'debug', 0 );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$dataReferent_id = Set::extract( $this->request->data, 'ActioncandidatPersonne.referent_id' );
			$referent_id = ( empty( $referent_id ) && !empty( $dataReferent_id ) ? $dataReferent_id : $referent_id );

			if( !empty( $referent_id ) ) {
				$referent = $this->ActioncandidatPersonne->Referent->find(
					'first',
					array(
						'conditions' => array(
							'Referent.id' => $referent_id
						),
						'contain' => false,
						'recursive' => -1
					)
				);

				if( !empty( $referent ) ) {
					$structs = $this->ActioncandidatPersonne->Personne->Orientstruct->Structurereferente->find(
						'first',
						array(
							'conditions' => array(
								'Structurereferente.id' => Set::classicExtract( $referent, 'Referent.structurereferente_id' )
							),
							'recursive' => -1
						)
					);
				}

				$this->set( compact( 'referent', 'structs' ) );
			}
			$this->render( 'ajaxstruct', 'ajax' );
		}

		/**
		 * Ajax pour les partenaires fournissant les actions
		 *
		 * @param integer $referent_id
		 */
		public function ajaxreffonct( $referent_id = null ) {
			Configure::write( 'debug', 0 );

			if( !empty( $referent_id ) ) {
				$referent_id = suffix( $referent_id );
			}
			else {
				$referent_id = suffix( Set::extract( $this->request->data, 'Rendezvous.referent_id' ) );
			}

			$this->set( 'typevoie', $this->Option->typevoie() );

			$dataReferent_id = Set::extract( $this->request->data, 'Rendezvous.referent_id' );
			$referent_id = ( empty( $referent_id ) && !empty( $dataReferent_id ) ? $dataReferent_id : $referent_id );

			if( !empty( $referent_id ) ) {
				$qd_referent = array(
					'conditions' => array(
						'Referent.id' => $referent_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$referent = $this->ActioncandidatPersonne->Personne->Referent->find('first', $qd_referent);


				$structs = $this->ActioncandidatPersonne->Personne->Orientstruct->Structurereferente->find(
					'first',
					array(
						'conditions' => array(
							'Structurereferente.id' => Set::classicExtract( $referent, 'Referent.structurereferente_id' )
						),
						'recursive' => -1
					)
				);
				$this->set( compact( 'referent', 'structs' ) );
			}

			$this->render( 'ajaxreffonct', 'ajax' );
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
         * @param integer $id Lors d'un add, c'est l'id de la Personne, sinon l'id
         *  de l'ActionCandidat
         */
		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			if( $this->action == 'add' ) {
                $personne_id = $id;
                $dossier_id = $this->ActioncandidatPersonne->Personne->dossierId( $personne_id );
            }
            else {
				$personne_id = $this->ActioncandidatPersonne->personneId( $id );
                $dossier_id = $this->ActioncandidatPersonne->dossierId( $id );
            }
            $this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

            $this->Jetons2->get( $dossier_id );

			$useDsps = ( Configure::read( 'ActioncandidatPersonne.suffixe' ) == 'cg93' );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
                $personne_id = $this->request->data['ActioncandidatPersonne']['personne_id'];
                $this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				///Pour récupérer le référent lié à la personne s'il existe déjà
				$personne_referent = $this->ActioncandidatPersonne->Personne->PersonneReferent->find( 'first', array( 'conditions' => array( 'PersonneReferent.personne_id' => $personne_id, 'PersonneReferent.dfdesignation IS NULL' ), 'contain' => false ) );

				$referentId = null;
				if( !empty( $personne_referent ) ) {
					$referentId = Set::classicExtract( $personne_referent, 'PersonneReferent.referent_id' );

					$qd_referent = array(
						'conditions' => array(
							'Referent.id' => $referentId
						),
						'fields' => null,
						'order' => null,
						'recursive' => -1
					);
					$referents = $this->ActioncandidatPersonne->Personne->Referent->find( 'first', $qd_referent );
					$this->set( compact( 'referents' ) );
				}
				$this->set( compact( 'referentId' ) );

				///Données propre au partenaire
				$part = $this->ActioncandidatPersonne->Actioncandidat->Partenaire->find( 'list', array( 'contain' => false ) );
				$this->set( compact( 'part' ) );
			}
			else if( $this->action == 'edit' ) {
				$actioncandidat_personne_id = $id;

				$qd_actioncandidat_personne = array(
					'conditions' => array(
						'ActioncandidatPersonne.id' => $actioncandidat_personne_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$actioncandidat_personne = $this->ActioncandidatPersonne->find( 'first', $qd_actioncandidat_personne );
				$this->assert( !empty( $actioncandidat_personne ), 'invalidParameter' );

				$personne_id = $actioncandidat_personne['ActioncandidatPersonne']['personne_id'];

				$referentId = null;
				$this->set( compact('referentId' ) );
			}
            $this->set( 'personne_id', $personne_id );

			$personne = $this->{$this->modelClass}->Personne->newDetailsCi( $personne_id, $this->Session->read( 'Auth.User.id' ) );

            if( Configure::read( 'ActioncandidatPersonne.suffixe' ) == 'cg93' ) {
                $detaildroitrsa = $this->{$this->modelClass}->Personne->Foyer->Dossier->Detaildroitrsa->find(
                    'first',
                    array(
                        'fields' => array_merge(
                            $this->{$this->modelClass}->Personne->Foyer->Dossier->Detaildroitrsa->fields(),
                            $this->{$this->modelClass}->Personne->Foyer->Dossier->Detaildroitrsa->Detailcalculdroitrsa->vfsSummary()
                        ),
                        'conditions' => array(
                            'Detaildroitrsa.dossier_id' => Hash::get( $personne, 'Dossier.id' )
                        ),
                        'contain' => false
                    )
                );
                $personne = Hash::merge( $personne, $detaildroitrsa );

			
				$contratinsertion = $this->{$this->modelClass}->Personne->Contratinsertion->find(
					'first',
					array(
						'fields' => array_merge(
							$this->{$this->modelClass}->Personne->Contratinsertion->fields(),
							$this->{$this->modelClass}->Personne->Contratinsertion->Cer93->fields()
						),
						'conditions' => array(
							'Contratinsertion.personne_id' => Hash::get( $personne, 'Personne.id' )
						),
						'order' => array(
							'Contratinsertion.dd_ci DESC'
						),
						'contain' => array( 'Cer93' )
					)
				);
				$personne = Hash::merge( $personne, $contratinsertion );
			}

			$this->set( 'personne', $personne );

			///Nombre d'enfants par foyer
			$nbEnfants = $this->ActioncandidatPersonne->Personne->Foyer->nbEnfants( Hash::get( $personne, 'Personne.foyer_id' ) );
			$this->set( 'nbEnfants', $nbEnfants );

			///Récupération de la liste des structures référentes liés uniquement à l'APRE
			$structs = $this->ActioncandidatPersonne->Personne->Orientstruct->Structurereferente->listOptions();
			$this->set( 'structs', $structs );

			///Récupération de la liste des référents liés à l'APRE
			$referents = $this->ActioncandidatPersonne->Personne->Referent->listOptions();
			$this->set( 'referents', $referents );

			///Données Dsp
			$qd_dsp = array(
				'conditions' => array(
					'Dsp.personne_id' => $personne_id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$dsp = $this->ActioncandidatPersonne->Personne->Dsp->find( 'first', $qd_dsp );
			$this->set( compact( 'dsp' ) );

			///Récupération de la liste des actions avec une fiche de candidature
			$qd_user = array(
				'conditions' => array(
					'User.id' => $this->Session->read( 'Auth.User.id' )
				),
				'fields' => null,
				'order' => null,
				'contain' => array(
					'Serviceinstructeur'
				)
			);
			$user = $this->User->find( 'first', $qd_user );

			$codeinseeUser = Set::classicExtract( $user, 'Serviceinstructeur.code_insee' );

            //On affiche les actions inactives en édition mais pas en ajout,
            // afin de pouvoir gérer les actions n'étant plus prises en compte mais toujours en cours
            $isactive = 'O';
            if( $this->action == 'edit' ){
                $isactive = array( 'O', 'N' );
            }
			$actionsfiche = $this->{$this->modelClass}->Actioncandidat->listePourFicheCandidature( $codeinseeUser, $isactive, '1' );
			$this->set( 'actionsfiche', $actionsfiche );

			if( !empty( $this->request->data ) ) {
                $this->ActioncandidatPersonne->begin();

                
                // Mise à jour de la case à cocher Poursuite suivi CG si l'action n'est pas de type région (CG66)
                if( Configure::read( 'Cg.departement' ) == 66 ) {
                    $actionsTypeRegionIds = implode( "', '", Configure::read( 'ActioncandidatPersonne.Actioncandidat.typeregionId' ) );
                    if( !in_array( $this->request->data['ActioncandidatPersonne']['actioncandidat_id'], array( $actionsTypeRegionIds ) ) ){
                        $this->request->data['ActioncandidatPersonne']['poursuitesuivicg'] = '0';
                    }
                    
                    // Si aucune case n'est cochée pour les RDVs, on n'enregistre aucune info
                    if( empty( $this->request->data['ActioncandidatPersonne']['rendezvouspartenaire'] ) ) {
                        unset( $this->request->data['ActioncandidatPersonne']['rendezvouspartenaire'] );
                        unset( $this->request->data['ActioncandidatPersonne']['horairerdvpartenaire'] );
                        unset( $this->request->data['ActioncandidatPersonne']['lieurdvpartenaire'] );
                    }
                    
                    // Si aucune case n'est cochée pour la mobilité, on n'enregistre aucune info
                    if( empty( $this->request->data['ActioncandidatPersonne']['mobile'] ) ) {
                        unset( $this->request->data['ActioncandidatPersonne']['mobile'] );
                        unset( $this->request->data['ActioncandidatPersonne']['naturemobile'] );
                        unset( $this->request->data['ActioncandidatPersonne']['typemobile'] );
                    }
                }    

                
				if( $this->ActioncandidatPersonne->saveAll( $this->request->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {

					if( $useDsps ) { ///Récupération des Dsps et sauvegarde
						$success = $this->ActioncandidatPersonne->Personne->Dsp->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) );
					}
                    else {
                        $success = true;
                    }

					// SAuvegarde des numéros ed téléphone si ceux-ci ne sont pas présents en amont
					if( isset( $this->request->data['Personne'] ) ) {
						$isDataPersonne = Hash::filter( (array)$this->request->data['Personne'] );
						if( !empty( $isDataPersonne ) ) {
                            $this->{$this->modelClass}->Personne->create( array( 'Personne' => $this->request->data['Personne'] ) );
							$success = $this->{$this->modelClass}->Personne->save() && $success;
						}
					}

					if( $success && $this->ActioncandidatPersonne->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
						$this->ActioncandidatPersonne->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'actionscandidats_personnes', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
						$this->ActioncandidatPersonne->rollback();
					}
				}
			}
			else {
				if( $this->action == 'edit' ) {           
                    $this->request->data = $actioncandidat_personne;

					/// Récupération des données socio pro (notamment Niveau etude) lié au contrat
					$qd_dsp = array(
						'conditions' => array(
							'Dsp.personne_id' => $personne_id
						),
						'fields' => null,
						'order' => null,
						'recursive' => -1
					);
					$dsp = $this->ActioncandidatPersonne->Personne->Dsp->find( 'first', $qd_dsp );

					if( empty( $dsp ) ) {
						$dsp = array( 'Dsp' => array( 'personne_id' => $personne_id ) );
						$this->ActioncandidatPersonne->Personne->Dsp->set( $dsp );
                        $this->ActioncandidatPersonne->Personne->Dsp->begin();
						if( $this->ActioncandidatPersonne->Personne->Dsp->save( $dsp ) ) {
                            $this->ActioncandidatPersonne->Personne->Dsp->commit();
							$qd_dsp = array(
								'conditions' => array(
									'Dsp.personne_id' => $personne_id
								),
								'fields' => null,
								'order' => null,
								'recursive' => -1
							);
							$dsp = $this->ActioncandidatPersonne->Personne->Dsp->find( 'first', $qd_dsp );
						}
						else {
                            $this->ActioncandidatPersonne->Personne->Dsp->rollback();
							$this->cakeError( 'error500' );
						}
						$this->assert( !empty( $dsp ), 'error500' );
					}
					$this->request->data['Dsp'] = array( 'id' => $dsp['Dsp']['id'], 'personne_id' => $dsp['Dsp']['personne_id'] );
					$this->request->data['Dsp']['nivetu'] = ( ( isset( $dsp['Dsp']['nivetu'] ) ) ? $dsp['Dsp']['nivetu'] : null );
					///Fin des Dsps

					// Liste des motifs de sortie pour le CG66
					$sqMotifsortie = $this->{$this->modelClass}->Actioncandidat->ActioncandidatMotifsortie->sq(
						array(
							'alias' => 'actionscandidats_motifssortie',
							'fields' => array( 'actionscandidats_motifssortie.motifsortie_id' ),
							'conditions' => array(
								'actionscandidats_motifssortie.actioncandidat_id' => $actioncandidat_personne['ActioncandidatPersonne']['actioncandidat_id']
							),
							'contain' => false
						)
					);
					$options[$this->modelClass]['motifsortie_id'] =
						$this->{$this->modelClass}->Motifsortie->find(
							'list',
							array(
								'fields' => array( 'Motifsortie.id', 'Motifsortie.name'),
								'conditions' => array(
									"Motifsortie.id IN ( {$sqMotifsortie} )"
								),
								'contain' => false,
								'order' => array( 'Motifsortie.name ASC')
							)
					);
                         
				}
			}

			// $options cacheables pour tout le monde
			$cacheKey = "{$this->name}_{$this->action}_options";
			$options = Cache::read( $cacheKey );

			if( $options === false ) {
				$options = array();

				$this->loadModel( 'Option' );
				$options['Adresse'] = array( 'typevoie' => $this->Option->typevoie() );
                $options['Personne'] = array( 'qual' => $this->Option->qual() );
                $options['Contratinsertion'] = array( 'decision_ci' => $this->Option->decision_ci() );
                $options['Prestation'] = array( 'rolepers' => $this->Option->rolepers() );
                $options['Suiviinstruction'] = array( 'typeserins' => $this->Option->typeserins() );
				$options = Hash::merge( $options, $this->{$this->modelClass}->Personne->Contratinsertion->Cer93->enums() );

				Cache::write( $cacheKey, $options );
			}

            // Cache géré dans les modèles
            $options[$this->modelClass] = $this->{$this->modelClass}->allEnumLists();
            $options[$this->modelClass]['referent_id'] = $this->InsertionsAllocataires->referents();
            $options[$this->modelClass]['motifsortie_id'] = $this->{$this->modelClass}->Motifsortie->listOptions();
            $options[$this->modelClass]['actioncandidat_id'] = $this->{$this->modelClass}->Actioncandidat->listOptions();
            $options['Dsp']['nivetu'] = $this->ActioncandidatPersonne->Personne->Dsp->enumList( 'nivetu' );

			$this->set( compact( 'options' ) );

			$this->render( 'add_edit_'.Configure::read( 'ActioncandidatPersonne.suffixe' ) );
		}

		/**
		 * Impression d'une fiche de liaison.
		 *
		 * @param integer $actioncandidat_personne_id
		 */
		public function printFiche( $actioncandidat_personne_id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->ActioncandidatPersonne->personneId( $actioncandidat_personne_id ) ) );

			$pdf = $this->ActioncandidatPersonne->getPdfFiche( $actioncandidat_personne_id );

			if( $pdf ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, 'FicheCandidature.pdf' );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la fiche de candidature', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Suppression d'une fiche de liaison.
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $this->ActioncandidatPersonne->personneId( $id ) ) );

			$this->Default->delete( $id );
		}

		/**
		 * Fonction pour annuler la fiche de candidature (CG 66).
		 *
		 * @param integer $id
		 */
		public function cancel( $id = null ) {
			$qd_actioncandidat = array(
				'conditions' => array(
					$this->modelClass.'.id' => $id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$actioncandidat = $this->{$this->modelClass}->find( 'first', $qd_actioncandidat );

			$personne_id = Set::classicExtract( $actioncandidat, 'ActioncandidatPersonne.personne_id' );
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$this->set( 'personne_id', $personne_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				if( $this->ActioncandidatPersonne->save( $this->request->data ) ) {
					$this->{$this->modelClass}->updateAllUnBound(
                        array( 'ActioncandidatPersonne.positionfiche' => '\'annule\'' ),
                        array(
                            '"ActioncandidatPersonne"."id"' => $id
                        )
					);

					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
			}
			else {
				$this->request->data = $actioncandidat;
			}
			$this->set( 'urlmenu', '/actionscandidats_personnes/index/'.$personne_id );
		}

		/**
		 * Visualisation d'une fiche de liaison.
		 *
		 * @param integer $id
		 */
		public function view( $id ) {
			if( Configure::read( 'ActioncandidatPersonne.suffixe' ) == 'cg93' ) {
				$actioncandidat_personne = $this->ActioncandidatPersonne->getFichecandidatureData( $id );

				if( empty( $actioncandidat_personne ) ) {
					throw new Error404Exception();
				}

				$personne_id = $actioncandidat_personne['Personne']['id'];
				$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

				$this->set( 'actionscandidatspersonne', $actioncandidat_personne );
				$this->set( 'options', $this->ActioncandidatPersonne->getFichecandidatureOptions() );

				$this->render( 'view' );
			}

			// Pour le CG 66
			$personne_id = $this->ActioncandidatPersonne->field( 'personne_id', array( 'id' => $id ) );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$dossier_id = $this->ActioncandidatPersonne->Personne->dossierId( $personne_id );

			$this->assert( !empty( $dossier_id ), 'invalidParameter' );
			$this->set( 'personne_id', $personne_id );


			$actionscandidatspersonne = $this->ActioncandidatPersonne->find(
				'first',
				array(
					'fields' => array_merge(
						$this->ActioncandidatPersonne->Actioncandidat->fields(),
						$this->ActioncandidatPersonne->Motifsortie->fields(),
						$this->ActioncandidatPersonne->Personne->fields(),
						$this->ActioncandidatPersonne->Referent->fields(),
						$this->ActioncandidatPersonne->fields(),
						array(
							$this->ActioncandidatPersonne->Fichiermodule->sqNbFichiersLies( $this->ActioncandidatPersonne, 'nb_fichiers_lies', 'ActioncandidatPersonne' )
						)
					),
					'conditions' => array(
						'ActioncandidatPersonne.id' => $id
					),
					'joins' => array(
						$this->ActioncandidatPersonne->join( 'Actioncandidat', array( 'type' => 'INNER' ) ),
						$this->ActioncandidatPersonne->join( 'Referent', array( 'type' => 'INNER' ) ),
						$this->ActioncandidatPersonne->join( 'Motifsortie', array( 'type' => 'LEFT OUTER' ) ),
						$this->ActioncandidatPersonne->join( 'Personne', array( 'type' => 'INNER' ) )
					),
					'contain' => false
				)
			);

			if( ($actionscandidatspersonne['Actioncandidat']['correspondantaction'] == 1) && !empty( $actionscandidatspersonne['Actioncandidat']['referent_id'] ) ) {
				$this->ActioncandidatPersonne->Personne->Referent->recursive = -1;
				$referent = $this->ActioncandidatPersonne->Personne->Referent->read( null, $actionscandidatspersonne['Actioncandidat']['referent_id'] );
			}
			if( !empty( $referent ) ) {
				$actionscandidatspersonne['Actioncandidat']['referent_id'] = $referent['Referent']['nom_complet'];
			}
			else {
				$actionscandidatspersonne['Actioncandidat']['referent_id'] = '';
			}
			$this->set( compact( 'actionscandidatspersonne' ) );
			$this->_setOptions();
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}
		}


		/**
		 * Permet d'envoyer un mail au référent en lien avec la fiche de candidature
		 *
		 * @param integer $id
		 */
		public function maillink( $id = null ) {
			$personne_id = $this->ActioncandidatPersonne->personneId( $id );
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $personne_id ) );

			$actioncandidat_personne = $this->ActioncandidatPersonne->find(
				'first', array(
					'conditions' => array(
						'ActioncandidatPersonne.id' => $id
					),
					'contain' => array(
						'Personne',
						'Referent',
						'Actioncandidat' => array(
							'Contactpartenaire',
							'Partenaire'
						)
					)
				)
			);

			$this->assert( !empty( $actioncandidat_personne ), 'error404' );

			if( !isset( $actioncandidat_personne['Actioncandidat']['Contactpartenaire']['email'] ) || empty( $actioncandidat_personne['Actioncandidat']['Contactpartenaire']['email'] ) ) {
				$this->Session->setFlash( "Mail non envoyé: adresse mail du référent ({$actioncandidat_personne['Actioncandidat']['Contactpartenaire']['nom']} {$actioncandidat_personne['Actioncandidat']['Contactpartenaire']['prenom']}) non renseignée.", 'flash/error' );
				$this->redirect( $this->referer() );
			}

			// Envoi du mail
			$success = true;
			try {
				$configName = WebrsaEmailConfig::getName( 'fiche_candidature' );
				$Email = new CakeEmail( $configName );

				// Choix du destinataire suivant le niveau de debug
				if( Configure::read( 'debug' ) == 0 ) {
					$Email->to( $actioncandidat_personne['Referent']['email'] );
				}
				else {
					$Email->to( WebrsaEmailConfig::getValue( 'fiche_candidature', 'to', $Email->from() ) );
				}

				$Email->subject( WebrsaEmailConfig::getValue( 'fiche_candidature', 'subject', 'Fiche de candidature' ) );
				$mailBody = "Bonjour,\n\nla fiche de candidature de {$actioncandidat_personne['Personne']['qual']} {$actioncandidat_personne['Personne']['nom']} {$actioncandidat_personne['Personne']['prenom']} a été saisie dans WEBRSA.";

				$result = $Email->send( $mailBody );
				$success = !empty( $result ) && $success;
			} catch( Exception $e ) {
				$this->log( $e->getMessage(), LOG_ERROR );
				$success = false;
			}

			if( $success ) {
				$this->Session->setFlash( 'Mail envoyé', 'flash/success' );
			}
			else {
				$this->Session->setFlash( 'Mail non envoyé', 'flash/error' );
			}

			$this->redirect( $this->referer() );
		}
	}
?>