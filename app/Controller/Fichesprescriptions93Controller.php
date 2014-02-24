<?php
	/**
	 * Code source de la classe Fichesprescriptions93Controller.
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppController', 'Controller' );

	/**
	 * @todo Nettoyer ces filtres par défaut (pour les tests)
	 */
	Configure::write(
		'Filtresdefaut.Fichesprescriptions93_search',
		array(
			'Search' => array(
				'Calculdroitrsa' => array(
					'toppersdrodevorsa' => '1'
				),
				'Dossier' => array(
					'dernier' => '1',
				),
				'Ficheprescription93' => array(
					'exists' => '1'
				),
				'Pagination' => array(
					'nombre_total' => true
				),
				'Situationdossierrsa' => array(
					'etatdosrsa_choice' => '1',
					'etatdosrsa' => array( '2', '3', '4' )
				)
			)
		)
	);

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
			'Allocataires',
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
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
			'add' => 'create',
			'edit' => 'update',
			'index' => 'read',
			'impression' => 'read',
			'exportcsv' => 'read',
			'search' => 'read',
		);

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

				$results = $this->Allocataires->paginate( $query );

				$this->set( compact( 'results' ) );
			}

			$options = Hash::merge(
				$this->Allocataires->options(),
				$this->Ficheprescription93->options( false, true )
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

					$edit = !isset( $messages['Personne.nati_inconnue'] );
					$results[$i]['/Fichesprescriptions93/edit'] = $edit;
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

			$options = $this->Ficheprescription93->options( false, true );

			$options['Ficheprescription93']['structurereferente_id'] = $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) );
			$options['Ficheprescription93']['referent_id'] = $this->InsertionsAllocataires->referents( array( 'prefix' => true ) );

			$this->set( compact( 'options', 'personne_id', 'dossierMenu' ) );
			$this->render( 'add_edit' );
		}

		/**
		 * @todo
		 */
		public function exportcsv() {
		}

		/**
		 * @todo
		 */
		public function impression() {
		}
	}
?>
