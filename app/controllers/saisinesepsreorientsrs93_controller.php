<?php
	/**
	* Gestion des saisines d'EP pour les réorientations proposées par les structures
	* référentes pour le conseil général du département 93.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class Saisinesepsreorientsrs93Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );

		/**
		* FIXME: evite les droits
		*/

		public function beforeFilter() {
		}

		/**
		*
		*/

		protected function _setOptions() {
			$options = $this->Saisineepreorientsr93->enums();
			$options['Saisineepreorientsr93']['typeorient_id'] = $this->Saisineepreorientsr93->Typeorient->listOptions();
			$options['Saisineepreorientsr93']['structurereferente_id'] = $this->Saisineepreorientsr93->Structurereferente->list1Options( array( 'orientation' => 'O' ) );
			$options['Saisineepreorientsr93']['motifreorient_id'] = $this->Saisineepreorientsr93->Motifreorient->find( 'list' );
            $options['Saisineepreorientsr93']['referent_id'] = $this->Saisineepreorientsr93->Referent->listOptions();
			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		public function index() {
			$searchData = Set::classicExtract( $this->data, 'Search' );
			$searchMode = Set::classicExtract( $searchData, 'Saisineepreorientsr93.mode' );

			if( !empty( $searchData ) ) {
				$conditions = array( 'Dossierep.themeep' => 'saisinesepsreorientsrs93' );

				if( $searchMode == 'traite' ) {
					$conditions[]['Dossierep.etapedossierep'] = 'traite';

					$searchDossierepSeanceepId = Set::classicExtract( $searchData, 'Dossierep.seanceep_id' );
					if( !empty( $searchDossierepSeanceepId ) ) {
						$conditions[]['Dossierep.seanceep_id'] = $searchDossierepSeanceepId;
					}
				}
				else {
					$conditions[]['Dossierep.etapedossierep <>'] = 'traite';
				}

				$this->paginate = array(
					// FIXME: du mal à utiliser Containable pour prendre juste les champs que l'on veut,
					// dès lors que l'on cherche à accéder à la Personne.
					/*'fields' => array(
						'Saisineepreorientsr93.id',
						'Saisineepreorientsr93.created',
						'Saisineepreorientsr93.orientstruct_id',
						'Typeorient.lib_type_orient',
						'Structurereferente.lib_struc',
						'Motifreorient.name',
						'Saisineepreorientsr93.commentaire',
						'Saisineepreorientsr93.accordaccueil',
						'Saisineepreorientsr93.desaccordaccueil',
						'Saisineepreorientsr93.accordallocataire',
						'Saisineepreorientsr93.urgent',
						'Dossierep.id',
					),*/
					'contain' => array(
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
						),
						'Typeorient',
						'Motifreorient',
						'Structurereferente',
						'Dossierep' => array(
							'Seanceep',
							'Personne',
						),
						'Nvsrepreorientsr93' => array(
							'Typeorient',
							'Structurereferente',
						),
					),
					'conditions' => $conditions,
					'order' => array( 'Saisineepreorientsr93.created DESC' ),
					'limit' => 10
				);

				$this->set( 'saisinesepsreorientsrs93', $this->paginate( $this->Saisineepreorientsr93 ) );
			}

			// INFO: containable ne fonctionne pas avec les find('list')
			$seanceseps = array();
			$tmpSeanceseps = $this->Saisineepreorientsr93->Dossierep->Seanceep->find(
				'all',
				array(
					'fields' => array(
						'Seanceep.id',
						'Seanceep.dateseance',
						'Ep.name'
					),
					'contain' => array(
						'Ep'
					),
					'order' => array( 'Ep.name ASC', 'Seanceep.dateseance DESC' )
				)
			);

			if( !empty( $tmpSeanceseps ) ) {
				foreach( $tmpSeanceseps as $key => $seanceep ) {
					$seanceseps[$seanceep['Ep']['name']][$seanceep['Seanceep']['id']] = $seanceep['Seanceep']['dateseance'];
				}
			}

			$options = Set::merge(
				$this->Saisineepreorientsr93->Dossierep->enums(),
				$this->Saisineepreorientsr93->Nvsrepreorientsr93->enums(),
				array( 'Dossierep' => array( 'seanceep_id' => $seanceseps ) )
			);
			$this->set( compact( 'options' ) );

			$view = implode( '_', Set::filter( array( 'index', $searchMode ) ) );
			$this->render( null, null, $view );
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
		* FIXME: on passe orientstruct_id (add) ou Saisineepreorientsr93.id (edit)
		*/

		protected function _add_edit( $id = null ) {
			if( !empty( $this->data ) ) {
				// FIXME: dans les contrôleurs des autres thèmes aussi
				$this->Saisineepreorientsr93->begin();
				$this->data['Dossierep']['themeep'] = Inflector::tableize( $this->modelClass );
				if( $this->action == 'add' ) {
					$this->Saisineepreorientsr93->Orientstruct->id = $this->data['Saisineepreorientsr93']['orientstruct_id'];
					$this->data['Dossierep']['personne_id'] = $this->Saisineepreorientsr93->Orientstruct->field( 'personne_id' );
					$success = $this->Saisineepreorientsr93->saveAll( $this->data, array( 'atomic' => false ) );
				}
				else {
					$this->Saisineepreorientsr93->create( $this->data );
					$success = $this->Saisineepreorientsr93->save();
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Saisineepreorientsr93->commit();
					$personne_id = $this->Saisineepreorientsr93->Orientstruct->field( 'personne_id', array( 'Orientstruct.id' => $this->data['Saisineepreorientsr93']['orientstruct_id'] ) );
					$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Saisineepreorientsr93->rollback();
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Saisineepreorientsr93->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Saisineepreorientsr93.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );

				// Formattage des id pour les listes liées
				$this->data['Saisineepreorientsr93']['referent_id'] = implode(
					'_',
					array(
						$this->data['Saisineepreorientsr93']['structurereferente_id'],
						$this->data['Saisineepreorientsr93']['referent_id']
					)
				);

				$this->data['Saisineepreorientsr93']['structurereferente_id'] = implode(
					'_',
					array(
						$this->data['Saisineepreorientsr93']['typeorient_id'],
						$this->data['Saisineepreorientsr93']['structurereferente_id']
					)
				);
			}
			else if( $this->action == 'add' ) {
				$this->data = array(
					'Saisineepreorientsr93' => array(
						'orientstruct_id' => $id
					)
				);
			}

			// Lecture de valeurs
			if( $this->action == 'add' ) {
				$personne_id = $this->Saisineepreorientsr93->Orientstruct->field( 'personne_id', array( 'Orientstruct.id' => $id ) );
				$this->assert( !empty( $personne_id ), 'invalidParameter' );

				// Retour à l'index d'orientsstrucs s'il n'est pas possible d'ajouter une réorientation
				if( !$this->Saisineepreorientsr93->ajoutPossible( $personne_id ) ) {
					$this->Session->setFlash( 'Impossible d\'ajouter une orientation pour cette personne.', 'flash/error' );
					$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
				}

				$this->set( 'nb_orientations', $this->Saisineepreorientsr93->Orientstruct->rgorientMax( $personne_id ) );
				$this->set( 'toppersdrodevorsa', $this->Saisineepreorientsr93->Orientstruct->Personne->Calculdroitrsa->field( 'toppersdrodevorsa', array( 'Calculdroitrsa.personne_id' => $personne_id ) ) );
				$this->set( 'personne_id', $personne_id );
			}
			else {
				$saisineepreorientsr93 = $this->Saisineepreorientsr93->find(
					'first',
					array(
						'contain' => array(
							'Orientstruct',
							'Dossierep',
						),
						'conditions' => array( 'Saisineepreorientsr93.id' => $id )
					)
				);

				if( !( empty( $saisineepreorientsr93['Dossierep']['etapedossierep'] ) || $saisineepreorientsr93['Dossierep']['etapedossierep'] == 'cree' ) ) {
					$this->Session->setFlash( 'Cette demande de réorientation ne peut pas être modifiée', 'flash/error' );
					$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $saisineepreorientsr93['Orientstruct']['personne_id'] ) ); // FIXME
				}

				$this->set( 'nb_orientations', $saisineepreorientsr93['Orientstruct']['rgorient'] );
				$this->set( 'toppersdrodevorsa', $this->Saisineepreorientsr93->Orientstruct->Personne->Calculdroitrsa->field( 'toppersdrodevorsa', array( 'Calculdroitrsa.personne_id' => $saisineepreorientsr93['Orientstruct']['personne_id'] ) ) );
				$this->set( 'personne_id', $saisineepreorientsr93['Orientstruct']['personne_id'] );
			}

			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$this->Saisineepreorientsr93->begin();
			$saisineepreorientsr93 = $this->Saisineepreorientsr93->find( 'first', array( 'condition' => array( 'Saisineepreorientsr93.id' => $id ), 'contain' => false ) );
			if( !empty( $saisineepreorientsr93['Saisineepreorientsr93']['dossierep_id'] ) ) {
				$success = $this->Saisineepreorientsr93->Dossierep->delete( $saisineepreorientsr93['Saisineepreorientsr93']['dossierep_id'] );
			}
			else {
				$success = $this->Saisineepreorientsr93->delete( $id );
			}
			$this->_setFlashResult( 'Delete', $success );
			$this->Saisineepreorientsr93->commit();
			$this->redirect( Router::url( $this->referer(), true ) );
		}
	}
?>