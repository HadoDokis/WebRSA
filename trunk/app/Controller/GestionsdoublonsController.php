<?php
	/**
	 * Code source de la classe GestionsdoublonsController.
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe GestionsdoublonsController ...
	 *
	 * @package app.Controller
	 */
	class GestionsdoublonsController extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Gestionsdoublons';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'DossiersMenus',
			'Gestionzonesgeos',
			'InsertionsAllocataires',
			'Jetons2',
			'Search.Filtresdefaut' => array( 'index' ),
			'Search.SearchPrg' => array(
				'actions' => array(
					'index' => array( 'filter' => 'Search' ),
				)
			),
			'Search.SearchSavedRequests',
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Foyer',
			'Gestiondoublon',
			'Option',
		);

		/**
		 * Moteur de recherche des doublons complexes.
		 */
		public function index() {
			if( !empty( $this->request->data ) ) {
				$query = $this->Gestiondoublon->searchComplexes(
					(array)Hash::get( $this->request->data, 'Search' )
				);

				$query['fields'][] = $this->Jetons2->sqLocked( 'Dossier', 'locked' );
				$query['fields'][] = str_replace( 'Dossier__locked', 'Dossier2__locked', $this->Jetons2->sqLocked( 'Dossier', 'locked' ) );

				$this->paginate = Hash::merge(
					$query,
					array( 'limit' => 10 )
				);

				$progressivePaginate = !Hash::get( $this->request->data, 'Search.Pagination.nombre_total' );

				$results = $this->paginate( 'Foyer', array(), array(), $progressivePaginate );

				$this->SearchSavedRequests->write(
					Inflector::underscore( $this->name ),
					$this->action,
					$this->request->params
			);

				$this->set( compact( 'results' ) );
			}

			$options = array(
				'Calculdroitrsa' => array(
					'toppersdrodevorsa' => $this->Option->toppersdrodevorsa(true)
				),
				'Situationdossierrsa' => array(
					'etatdosrsa' => $this->Option->etatdosrsa()
				),
				'Situationdossierrsa2' => array(
					'etatdosrsa' => $this->Option->etatdosrsa()
				),
				'mesCodesInsee' => $this->Gestionzonesgeos->listeCodesInsee(),
				'cantons' => $this->Gestionzonesgeos->listeCantons(),
			);
			$this->set( compact( 'options' ) );

			$this->set( 'structuresreferentesparcours', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) ) );
			$this->set( 'referentsparcours', $this->InsertionsAllocataires->referents( array( 'prefix' => true ) ) );
		}

		/**
		 * Formulaire de fusion des données de 2 foyers.
		 *
		 * @param integer $foyer1_id
		 * @param integer $foyer2_id
		 */
		public function fusion( $foyer1_id, $foyer2_id ) {
			$dossier1Menu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'foyer_id' => $foyer1_id ) );
			$dossier2Menu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'foyer_id' => $foyer2_id ) );

			$dossier1_id = Hash::get( $dossier1Menu, 'Dossier.id' );
			$dossier2_id = Hash::get( $dossier2Menu, 'Dossier.id' );

			// Acquisition des locks ?
			$this->Jetons2->get( $dossier1_id );
			$this->Jetons2->get( $dossier2_id );

			Configure::write( 'Cache.disable', false );
			$this->Foyer->Behaviors->attach( 'Postgres.PostgresTable' );
			$this->Foyer->getAllPostgresForeignKeys();
			$foreignkeys = $this->Foyer->getPostgresForeignKeysTo();

			// Modèles liés
			$contain = array( 'Dossier' );
			foreach( $foreignkeys as $foreignkey ) {
				$contain[] = Inflector::classify( $foreignkey['From']['table'] );
			}
			$contain = array_unique( $contain );

			$modelNames = $contain;
			$key = array_search( 'Dossier', $modelNames );
			if( $key !== false ) {
				unset( $modelNames[$key] );
			}
			$this->set( compact( 'modelNames' ) );

			// Recherche des enregistrements
			$results = $this->Foyer->find(
				'all',
				array(
					'conditions' => array(
						'Foyer.id' => array( $foyer1_id, $foyer2_id )
					),
					'contain' => $contain,
					'order' => array( 'Dossier.id' )
				)
			);

			// Fichiers liés
			$resultsFichiersLies = array(
				'Foyer' => array(
					array( 'id' => $foyer1_id ),
					array( 'id' => $foyer2_id ),
				)
			);
			foreach( $modelNames as $modelName ) {
				$resultsFichiersLies[$modelName] = Hash::extract( $results, "{n}.{$modelName}.{n}" );
			}
			$fichiersModuleLies = $this->Gestiondoublon->fichiersModuleLies( $resultsFichiersLies, '/%s/id' );
			$this->set( compact( 'fichiersModuleLies' ) );

			if( !empty( $this->request->data ) ) {
				if( isset( $this->request->data['Cancel'] ) ) {
					$this->Jetons2->release( $dossier1_id );
					$this->Jetons2->release( $dossier2_id );

					$this->SearchSavedRequests->redirect(
						Inflector::underscore( $this->name ),
						'index',
						array( 'controller' => Inflector::underscore( $this->name ), 'action' => 'index' )
					);
				}
				else {
					// On vérifie que l'on garde bien un foyer et au moins un allocataire
					$errors = array();

					$foyer = Hash::get( $this->request->data, 'Foyer.id' );
					$personnes = Hash::get( $this->request->data, 'Personne.id' );

					if( empty( $foyer ) ) {
						$errors[] = 'Veuillez sélectionner un foyer dans lequel fusionner les données';
					}
					if( empty( $personnes ) ) {
						$errors[] = 'Veuillez sélectionner au moins une personne';
					}

					$this->set( compact( 'errors' ) );

					// Tentative d'enregistrement s'il y a lieu
					if( empty( $errors ) ) {
						$success = $this->Gestiondoublon->fusionComplexe( $foyer1_id, $foyer2_id, $results, $this->request->data );
					}
					else {
						$success = false;
					}

					if( $success ) {
						$this->Foyer->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->Jetons2->release( $dossier1_id );
						$this->Jetons2->release( $dossier2_id );

						$this->redirect( array( 'controller' => 'gestionsanomaliesbdds', 'action' => 'foyer', Hash::get( $this->request->data, 'Foyer.id' ) ) );
					}
					else {
						$this->Foyer->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/error' );
					}
				}
			}

			$this->set( compact( 'results', 'foreignkeys' ) );
		}
	}
?>