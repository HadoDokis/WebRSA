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

	class Reorientationseps93Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );

		public $commeDroit = array(
			'add' => 'Reorientationseps93:edit'
		);

		/**
		* FIXME: evite les droits
		*/

		public function beforeFilter() {
		}

		/**
		*
		*/

		protected function _setOptions() {
			$options = $this->Reorientationep93->enums();
			$options['Reorientationep93']['typeorient_id'] = $this->Reorientationep93->Typeorient->listOptions();
			$options['Reorientationep93']['structurereferente_id'] = $this->Reorientationep93->Structurereferente->list1Options( array( 'orientation' => 'O' ) );
			$options['Reorientationep93']['motifreorientep93_id'] = $this->Reorientationep93->Motifreorientep93->find( 'list' );
			$options['Reorientationep93']['referent_id'] = $this->Reorientationep93->Referent->listOptions();
			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		public function index() {
			$searchData = Set::classicExtract( $this->data, 'Search' );
			$searchMode = Set::classicExtract( $searchData, 'Reorientationep93.mode' );

			if( !empty( $searchData ) ) {
				$conditions = array( 'Dossierep.themeep' => 'reorientationseps93' );

				if( $searchMode == 'traite' ) {
					$conditions[]['passagescommissionseps.etatdossierep'] = array( 'traite', 'annule', 'reporte' );

					$searchDossierepSeanceepId = Set::classicExtract( $searchData, 'Dossierep.commissionep_id' );
					if( !empty( $searchDossierepSeanceepId ) ) {
						$conditions[]['passagescommissionseps.commissionep_id'] = $searchDossierepSeanceepId;
					}
				}
				else {
					$conditions[]['NOT']['passagescommissionseps.etatdossierep'] = array( 'traite', 'annule', 'reporte' );
				}

				$conditions = array(
					'Dossierep.id IN ( '.$this->Reorientationep93->Dossierep->Passagecommissionep->sq(
						array(
							'alias' => 'passagescommissionseps',
							'fields' => array(
								'passagescommissionseps.dossierep_id'
							),
							'conditions' => array(
								$conditions
							)
						)
					).' )'
				);

				$this->paginate = array(
					'contain' => array(
						'Orientstruct' => array(
							'Typeorient',
							'Structurereferente',
						),
						'Typeorient',
						'Motifreorientep93',
						'Structurereferente',
						'Dossierep' => array(
							'Passagecommissionep' => array(
								'Commissionep',
								'Decisionreorientationep93' => array(
									'Typeorient',
									'Structurereferente',
								),
							),
							'Personne',
						),
					),
					'conditions' => $conditions,
					'order' => array( 'Reorientationep93.created DESC' ),
					'limit' => 10
				);

				$this->set( 'reorientationseps93', $this->paginate( $this->Reorientationep93 ) );
			}

			// INFO: containable ne fonctionne pas avec les find('list')
			$commissionseps = array();
			$tmpSeanceseps = $this->Reorientationep93->Dossierep->Passagecommissionep->Commissionep->find(
				'all',
				array(
					'fields' => array(
						'Commissionep.id',
						'Commissionep.dateseance',
						'Ep.name'
					),
					'contain' => array(
						'Ep'
					),
					'order' => array( 'Ep.name ASC', 'Commissionep.dateseance DESC' )
				)
			);

			if( !empty( $tmpSeanceseps ) ) {
				foreach( $tmpSeanceseps as $key => $commissionep ) {
					$commissionseps[$commissionep['Ep']['name']][$commissionep['Commissionep']['id']] = $commissionep['Commissionep']['dateseance'];
				}
			}

			$options = Set::merge(
				$this->Reorientationep93->Dossierep->enums(),
				$this->Reorientationep93->Dossierep->Passagecommissionep->Decisionreorientationep93->enums(),
				array( 'Dossierep' => array( 'commissionep_id' => $commissionseps ) )
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
		* FIXME: on passe orientstruct_id (add) ou Reorientationep93.id (edit)
		*/

		protected function _add_edit( $id = null ) {
			// Retour à l'index en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				if( $this->action == 'add' ){
					$persId = $this->Reorientationep93->Orientstruct->field( 'personne_id', array( 'Orientstruct.id' => $id ) );
				}
				else if( $this->action == 'edit' ){
					$persId = $this->Reorientationep93->Orientstruct->field( 'personne_id', array( 'Orientstruct.id' => $this->data['Reorientationep93']['orientstruct_id'] ) );
				}
				$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $persId ) );
			}

			if( !empty( $this->data ) ) {
				// FIXME: dans les contrôleurs des autres thèmes aussi
				$success = true;
				$this->Reorientationep93->begin();
				$this->data['Dossierep']['themeep'] = Inflector::tableize( $this->modelClass );
				if( $this->action == 'add' ) {
					$this->Reorientationep93->Orientstruct->id = $this->data['Reorientationep93']['orientstruct_id'];
					$this->data['Dossierep']['personne_id'] = $this->Reorientationep93->Orientstruct->field( 'personne_id' );
					$dossierep['Dossierep'] = $this->data['Dossierep'];
					$this->Reorientationep93->Dossierep->create( $dossierep );
					$success = $this->Reorientationep93->Dossierep->save();
					$this->data['Reorientationep93']['dossierep_id'] = $this->Reorientationep93->Dossierep->id;
				}

				$reorientationep93['Reorientationep93'] = $this->data['Reorientationep93'];
				$reorientationep93['Reorientationep93']['user_id'] = $this->Session->read( 'Auth.User.id' );
				$this->Reorientationep93->create( $reorientationep93 );
				$success = $this->Reorientationep93->save() && $success;

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Reorientationep93->commit();
					$personne_id = $this->Reorientationep93->Orientstruct->field( 'personne_id', array( 'Orientstruct.id' => $this->data['Reorientationep93']['orientstruct_id'] ) );
					$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Reorientationep93->rollback();
				}
			}
			else if( $this->action == 'edit' ) {
				$this->data = $this->Reorientationep93->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Reorientationep93.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );

				// Formattage des id pour les listes liées
				$this->data['Reorientationep93']['referent_id'] = implode(
					'_',
					array(
						$this->data['Reorientationep93']['structurereferente_id'],
						$this->data['Reorientationep93']['referent_id']
					)
				);

				$this->data['Reorientationep93']['structurereferente_id'] = implode(
					'_',
					array(
						$this->data['Reorientationep93']['typeorient_id'],
						$this->data['Reorientationep93']['structurereferente_id']
					)
				);
			}
			else if( $this->action == 'add' ) {
				$this->data = array(
					'Reorientationep93' => array(
						'orientstruct_id' => $id
					)
				);
			}

			// Lecture de valeurs
			if( $this->action == 'add' ) {
				$personne_id = $this->Reorientationep93->Orientstruct->field( 'personne_id', array( 'Orientstruct.id' => $id ) );
				$this->assert( !empty( $personne_id ), 'invalidParameter' );

				// Retour à l'index d'orientsstrucs s'il n'est pas possible d'ajouter une réorientation
				if( !$this->Reorientationep93->ajoutPossible( $personne_id ) ) {
					$this->Session->setFlash( 'Impossible d\'ajouter une orientation pour cette personne.', 'flash/error' );
					$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
				}

				$this->set( 'nb_orientations', $this->Reorientationep93->Orientstruct->rgorientMax( $personne_id ) );
				$this->set( 'toppersdrodevorsa', $this->Reorientationep93->Orientstruct->Personne->Calculdroitrsa->field( 'toppersdrodevorsa', array( 'Calculdroitrsa.personne_id' => $personne_id ) ) );
				$this->set( 'personne_id', $personne_id );
			}
			else {
				$reorientationep93 = $this->Reorientationep93->find(
					'first',
					array(
						'contain' => array(
							'Orientstruct',
							'Dossierep',
						),
						'conditions' => array( 'Reorientationep93.id' => $id )
					)
				);

				if( !( empty( $reorientationep93['Dossierep']['etatdossierep'] ) || $reorientationep93['Dossierep']['etatdossierep'] == 'cree' ) ) {
					$this->Session->setFlash( 'Cette demande de réorientation ne peut pas être modifiée', 'flash/error' );
					$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $reorientationep93['Orientstruct']['personne_id'] ) ); // FIXME
				}

				$this->set( 'nb_orientations', $reorientationep93['Orientstruct']['rgorient'] );
				$this->set( 'toppersdrodevorsa', $this->Reorientationep93->Orientstruct->Personne->Calculdroitrsa->field( 'toppersdrodevorsa', array( 'Calculdroitrsa.personne_id' => $reorientationep93['Orientstruct']['personne_id'] ) ) );
				$this->set( 'personne_id', $reorientationep93['Orientstruct']['personne_id'] );
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/orientsstructs/index/'.$personne_id );
			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$this->Reorientationep93->begin();
			$success = true;
			$reorientationep93 = $this->Reorientationep93->find( 'first', array( 'condition' => array( 'Reorientationep93.id' => $id ), 'contain' => false ) );
			$success = $this->Reorientationep93->delete( $id ) && $success;
			if( !empty( $reorientationep93['Reorientationep93']['dossierep_id'] ) ) {
				$success = $this->Reorientationep93->Dossierep->delete( $reorientationep93['Reorientationep93']['dossierep_id'] ) && $success;
			}
			$this->_setFlashResult( 'Delete', $success );
			if ( $success ) {
				$this->Reorientationep93->commit();
			}
			else {
				$this->Reorientationep93->rollback();
			}
			$this->redirect( Router::url( $this->referer(), true ) );
		}
	}
?>