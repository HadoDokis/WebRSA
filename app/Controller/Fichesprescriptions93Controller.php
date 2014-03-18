<?php
	/**
	 * Code source de la classe Fichesprescriptions93Controller.
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppController', 'Controller' );

	/**
	 * La classe Fichesprescriptions93Controller ...
	 *
	 * @todo exportcsv
	 *
	 * @package app.Controller
	 */
	class Fichesprescriptions93Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Fichesprescriptions93';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires',
			'DossiersMenus',
			'InsertionsAllocataires',
			'Jetons2', // FIXME: à cause de DossiersMenus
			'Search.Filtresdefaut' => array( 'search' ),
			'Search.SearchPrg' => array(
				'actions' => array(
					'search' => array( 'filter' => 'Search' ),
				)
			),
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Ajax2' => array(
				'className' => 'Prototype.PrototypeAjax',
				'useBuffer' => false
			),
			'Allocataires',
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
			'Search.SearchForm',
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Ficheprescription93', 'Personne' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'ajax_ficheprescription93_numconvention' => 'read',
			'ajax_prescripteur' => 'read',
			'add' => 'create',
			'edit' => 'update',
			'index' => 'read',
			'impression' => 'read',
			'exportcsv' => 'read',
			'search' => 'read',
			'cancel' => 'update',
		);

		public $commeDroit = array(
			'ajax_ficheprescription93_numconvention' => 'Fichesprescriptions93:index',
			'ajax_prescripteur' => 'Fichesprescriptions93:index',
		);

		/**
		 * Ajax permettant le pré-remplissage de la fiche de prescription à
		 * partir du numéro de convention.
		 */
		public function ajax_ficheprescription93_numconvention() {
			$json = $this->Ficheprescription93->Actionfp93->jsonNumconvention( $this->request->data );

			$this->set( compact( 'json' ) );
			$this->layout = 'ajax';
			$this->render( '/Elements/json' );
		}

		/**
		 * Ajax permettant de récupérer les coordonnées du prescripteur ou de sa
		 * structure.
		 */
		public function ajax_prescripteur() {
			$structurereferente_id = Hash::get( $this->request->data, 'Ficheprescription93.structurereferente_id' );
			$referent_id = suffix( Hash::get( $this->request->data, 'Ficheprescription93.referent_id' ) );

			$result = array();
			if( !empty( $structurereferente_id ) ) {
				$query = array(
					'fields' => array(
						'Structurereferente.num_voie',
						'Structurereferente.type_voie',
						'Structurereferente.nom_voie',
						'Structurereferente.code_postal',
						'Structurereferente.ville',
						'Structurereferente.numtel',
						'Structurereferente.numfax',
					),
					'contain' => false,
					'joins' => array(),
					'conditions' => array(
						'Structurereferente.id' => $structurereferente_id,
					)
				);

				if( !empty( $referent_id ) ) {
					$query['fields'][] = 'Referent.email';
					$query['fields'][] = 'Referent.fonction';
					$query['joins'][] = $this->Ficheprescription93->Referent->Structurereferente->join( 'Referent', array( 'type' => 'INNER' ) );
					$query['conditions']['Referent.id'] = $referent_id;
				}

				$result = $this->Ficheprescription93->Referent->Structurereferente->find( 'first', $query );
			}

			$options = array(
				'Structurereferente' => array(
					'type_voie' => ClassRegistry::init( 'Option' )->typevoie()
				)
			);

			$this->set( compact( 'result', 'options' ) );
			$this->layout = 'ajax';
		}

		/**
		 * Moteur de recherche des fiches de prescription.
		 */
		public function search() {
			if( Hash::check( $this->request->data, 'Search' ) ) {
				$query = $this->Ficheprescription93->search( $this->request->data['Search'] );

				$query['fields'] = array(
					'Personne.id',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Personne.nom_complet',
					'Prestation.rolepers',
					'Adresse.locaadr',
					'Ficheprescription93.id',
					'Ficheprescription93.statut',
					'Actionfp93.name',
				);

				$query = $this->Allocataires->completeSearchQuery( $query );

				// Optimisation méthode 1: on attaque fichesprescriptions93 en premier lieu
				// TODO: ne pas oublier de faire de même dans l'export CSV
				// TODO: permettre la valeur vide/null dans les options
				if( Hash::get( $this->request->data, 'Search.Ficheprescription93.exists' ) ) {
					// TODO: à faire dans la vue, de manière implicite dans searchConditions() ou search() appellerait searchOptimisations() ?
					foreach( $query['joins'] as $i => $join ) {
						if( $join['alias'] == 'Ficheprescription93' ) {
							unset( $query['joins'][$i] );
							array_unshift( $query['joins'], $this->Ficheprescription93->join( 'Personne', array( 'type' => 'INNER' ) ) );
						}
					}
					$this->Ficheprescription93->forceVirtualFields = true;
					$results = $this->Allocataires->paginate( $query, 'Ficheprescription93' );
				}
				// Optimisation méthode 2: on fait un INNER JOIN (ça ne change rien)
				/*if( Hash::get( $this->request->data, 'Search.Ficheprescription93.exists' ) ) {
					foreach( $query['joins'] as $i => $join ) {
						if( $join['alias'] == 'Ficheprescription93' ) {
							$query['joins'][$i]['type'] = 'INNER';
//							unset( $query['joins'][$i] );
//							array_unshift( $query['joins'], $this->Ficheprescription93->join( 'Personne', array( 'type' => 'INNER' ) ) );
						}
					}
					$results = $this->Allocataires->paginate( $query );
				}*/
				else {
					$results = $this->Allocataires->paginate( $query );
				}

				$this->set( compact( 'results' ) );
			}

			$options = Hash::merge(
				$this->Allocataires->options(),
				$this->Ficheprescription93->options( array( 'allocataire' => false, 'find' => true, 'autre' => false ) )
			);
			$this->set( compact( 'options' ) );
		}

		/**
		 * Liste des fiches de presciptions d'un allocataire.
		 *
		 * @param integer $personne_id
		 */
		public function index( $personne_id ) {
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			$this->_setEntriesAncienDossier( $personne_id, 'Ficheprescription93' );

			$messages = $this->Ficheprescription93->messages( $personne_id );
			$addEnabled = $this->Ficheprescription93->addEnabled( $messages );

			$query = array(
				'fields' => array(
					'Ficheprescription93.id',
					'Ficheprescription93.created',
					'Thematiquefp93.type',
					'Thematiquefp93.name',
					'Categoriefp93.name',
					'Ficheprescription93.dd_action',
					'Ficheprescription93.df_action',
					'Ficheprescription93.statut',
				),
				'conditions' => array(
					'Ficheprescription93.personne_id' => $personne_id
				),
				'contain' => false,
				'joins' => array(
					$this->Ficheprescription93->join( 'Actionfp93' ),
					$this->Ficheprescription93->Actionfp93->join( 'Filierefp93' ),
					$this->Ficheprescription93->Actionfp93->Filierefp93->join( 'Categoriefp93' ),
					$this->Ficheprescription93->Actionfp93->Filierefp93->Categoriefp93->join( 'Thematiquefp93' )
				),
				'order' => array(
					'Ficheprescription93.created DESC'
				)
			);

			$results = $this->Ficheprescription93->find( 'all', $query );

			// TODO: début dans le modèle (?), ajout des permissions sur les différentes actions
			// champs virtuels "disabled" pour les actions...
			if( !empty( $results ) ) {
				foreach( $results as $i => $result ) {
					$impression = ( (int)substr( $result['Ficheprescription93']['statut'], 0, 2 ) >= 2 );
					$results[$i]['/Fichesprescriptions93/impression'] = $impression;

					$edit = ( (int)substr( $result['Ficheprescription93']['statut'], 0, 2 ) != 99 );
					$results[$i]['/Fichesprescriptions93/edit'] = $edit;

					$cancel = ( (int)substr( $result['Ficheprescription93']['statut'], 0, 2 ) != 99 );
					$results[$i]['/Fichesprescriptions93/cancel'] = $cancel;
				}
			}
			// Fin dans le modèle (?), ajout des permissions sur les différentes actions

			$options = $this->Ficheprescription93->options();

			$this->set( compact( 'results', 'options', 'personne_id', 'messages', 'addEnabled' ) );
		}

		/**
		 * Formulaire d'ajout de fiche de prescription.
		 *
		 * @param integer $personne_id L'id de la Personne à laquelle on veut ajouter une fiche
		 */
		public function add( $personne_id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Formulaire de modification de fiche de prescription.
		 *
		 * @param integer $id L'id de la fiche que l'on veut modifier
		 */
		public function edit( $id ) {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * Méthode générique d'ajout et de modification de fiche de prescription.
		 *
		 * @param integer $id L'id de la personne (add) ou de la fiche (edit)
		 */
		protected function _add_edit( $id = null ) {
			if( $this->action == 'add' ) {
				$personne_id = $id;
				$id = null;
			}
			else {
				$personne_id = $this->Ficheprescription93->personneId( $id );
			}

			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->Jetons2->get( $dossierMenu['Dossier']['id'] );

			// Retour à l'index en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Ficheprescription93->begin();
				if( $this->Ficheprescription93->saveAddEdit( $this->request->data ) ) {
					$this->Ficheprescription93->commit();
					$this->Jetons2->release( $dossierMenu['Dossier']['id'] );
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Ficheprescription93->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				$this->request->data = $this->Ficheprescription93->prepareFormDataAddEdit( $personne_id, $id );
			}

			$options = $this->Ficheprescription93->options( array( 'allocataire' => true, 'find' => true, 'autre' => true ) );

			$options['Ficheprescription93']['structurereferente_id'] = $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) );
			$options['Ficheprescription93']['referent_id'] = $this->InsertionsAllocataires->referents( array( 'prefix' => true ) );

			$urlmenu = "/fichesprescriptions93/index/{$personne_id}";

			$this->set( compact( 'options', 'personne_id', 'dossierMenu', 'urlmenu' ) );
			$this->render( 'add_edit' );
		}

		/**
		 * Export CSV des résultats de la recherche.
		 */
		public function exportcsv() {
			$search = (array)Hash::get( (array)Hash::expand( $this->request->params['named'], '__' ), 'Search' );

			$query = $this->Ficheprescription93->search( $search );

			$query['fields'] = array(
				'Personne.id',
				'Dossier.numdemrsa',
				'Dossier.dtdemrsa',
				'Dossier.matricule',
				'Personne.nom_complet',
				'Prestation.rolepers',
				'Ficheprescription93.id',
				'Ficheprescription93.statut',
				'Referent.nom_complet',
				'Adresse.numvoie',
				'Adresse.typevoie',
				'Adresse.nomvoie',
				'Adresse.complideadr',
				'Adresse.compladr',
				'Adresse.lieudist',
				'Adresse.numcomrat',
				'Adresse.numcomptt',
				'Adresse.codepos',
				'Adresse.locaadr',
				'Ficheprescription93.rdvprestataire_date',
				'Actionfp93.numconvention',
				'Thematiquefp93.type',
				'Thematiquefp93.name',
				'Categoriefp93.name',
				'Filierefp93.name',
				'Prestatairefp93.name',
				'Actionfp93.name',
				'Ficheprescription93.dd_action',
				'Ficheprescription93.df_action',
				'Ficheprescription93.date_signature',
				'Ficheprescription93.date_transmission',
				'Ficheprescription93.date_retour',
				'Ficheprescription93.personne_recue',
				'Ficheprescription93.motifnonreceptionfp93_id',
				'Ficheprescription93.personne_nonrecue_autre',
				'Ficheprescription93.personne_retenue',
				'Ficheprescription93.motifnonretenuefp93_id',
				'Ficheprescription93.personne_nonretenue_autre',
				'Ficheprescription93.personne_souhaite_integrer',
				'Ficheprescription93.motifnonsouhaitfp93_id',
				'Ficheprescription93.personne_nonsouhaite_autre',
				'Ficheprescription93.personne_a_integre',
				'Ficheprescription93.personne_date_integration',
				'Ficheprescription93.motifnonintegrationfp93_id',
				'Ficheprescription93.personne_nonintegre_autre',
				'Ficheprescription93.date_bilan_mi_parcours',
				'Ficheprescription93.date_bilan_final',
			);

			$query = $this->Allocataires->completeSearchQuery( $query, false );

			$query = $this->Components->load( 'Search.SearchPaginator' )->setPaginationOrder( $query );

			$this->Ficheprescription93->Personne->forceVirtualFields = true;
			$results = $this->Ficheprescription93->Personne->find( 'all', $query );

			$options = $this->Ficheprescription93->options( array( 'allocataire' => true, 'find' => true ) );

			$this->set( compact( 'results', 'options' ) );
			$this->layout = null;
		}

		/**
		 * Imprime une fiche de prescription.
		 *
		 * @param integer $ficheprescription93_id
		 * @return void
		 */
		public function impression( $ficheprescription93_id = null ) {
			$personne_id = $this->Ficheprescription93->personneId( $ficheprescription93_id );
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $personne_id ) );

			$pdf = $this->Ficheprescription93->getDefaultPdf( $ficheprescription93_id, $this->Session->read( 'Auth.User.id' ) );

			if( !empty( $pdf ) ) {
				$this->Gedooo->sendPdfContentToClient( $pdf, "contratinsertion_{$ficheprescription93_id}_nouveau.pdf" );
			}
			else {
				$this->Session->setFlash( 'Impossible de générer la fiche de prescription.', 'default', array( 'class' => 'error' ) );
				$this->redirect( $this->referer() );
			}
		}

		/**
		 * Formulaire d'annulation d'un fiche de prescription.
		 *
		 * @param integer $id
		 */
		public function cancel( $id = null ) {
			$query = array(
				'conditions' => array(
					'Ficheprescription93.id' => $id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$ficheprescription93 = $this->Ficheprescription93->find( 'first', $query );

			$personne_id = Hash::get( $ficheprescription93, 'Ficheprescription93.personne_id' );
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// On transforme les champs date_annulation et motif_annulation en champs obligatoires
			$notEmpty = array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'allowEmpty' => false,
					'required' => true
				)
			);
			foreach( array( 'date_annulation', 'motif_annulation' ) as $field ) {
				$this->Ficheprescription93->validate[$field] = Hash::merge(
					$notEmpty,
					$this->Ficheprescription93->validate[$field]
				);
			}

			if( !empty( $this->request->data ) ) {
				$this->Ficheprescription93->begin();

				$ficheprescription93['Ficheprescription93']['statut'] = '99annulee';
				$ficheprescription93['Ficheprescription93']['date_annulation'] = Hash::get( $this->request->data, 'Ficheprescription93.date_annulation' );
				$ficheprescription93['Ficheprescription93']['motif_annulation'] = Hash::get( $this->request->data, 'Ficheprescription93.motif_annulation' );
				$this->Ficheprescription93->create( $ficheprescription93 );

				if( $this->Ficheprescription93->save() ) {
					$this->Ficheprescription93->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Ficheprescription93->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else {
				$this->request->data = $ficheprescription93;
			}
			$this->set( 'urlmenu', '/fichesprescriptions93/index/'.$personne_id );
		}
	}
?>
