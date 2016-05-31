<?php
	/**
	 * Code source de la classe ReferentsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe ReferentsController ...
	 *
	 * @package app.Controller
	 */
	App::uses('Folder', 'Utility');
	App::uses('File', 'Utility');

	class ReferentsController extends AppController
	{

		public $name = 'Referents';
		public $uses = array( 'Referent', 'Structurereferente', 'Option' );
		public $helpers = array( 'Xform', 'Default2', 'Default' );

		public $components = array( 'Default', 'Search.SearchPrg' => array( 'actions' => array( 'index' ) ), 'Workflowscers93' );

		public $commeDroit = array(
			'add' => 'Referents:edit'
		);

		protected function _setOptions() {

			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'fonction_pers', $this->Option->fonction_pers() );
			$this->set( 'referent', $referent = $this->Referent->find( 'list' ) );

			$options = array();
			$options = $this->Referent->enums();
			$options['Referent']['id'] = $referent;
			$options['Dernierreferent']['prevreferent_id'] = $this->Referent->find('list', 
				array(
					'joins' => array($this->Referent->join('Dernierreferent')),
					'conditions' => array('Dernierreferent.referent_id = Dernierreferent.dernierreferent_id'),
					'order' => array('Referent.nom', 'Referent.prenom')
				)
			);
			
			foreach( array( 'Structurereferente' ) as $linkedModel ) {
				$field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
				$options = Hash::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
			}

			$this->set( compact( 'options' ) );
		}


		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}

			if( !empty( $this->request->data ) ) {
				$queryData = $this->Referent->search( $this->request->data );
				$queryData['limit'] = 20;
				$this->paginate = $queryData;
				$referents = $this->paginate( 'Referent' );

				$this->set( 'referents', $referents );

			}
			$this->_setOptions();

			App::import( 'Behaviors', 'Occurences' );
			$this->Referent->Behaviors->attach( 'Occurences' );
			$this->set( 'occurences', $this->Referent->occurencesExists() );
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

		public function _add_edit() {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}
			$sr = $this->Structurereferente->find(
				'list',
				array(
					'fields' => array(
						'Structurereferente.lib_struc'
					),
				)
			);
			$this->set( 'sr', $sr );

			$this->_setOptions();
			$args = func_get_args();
			
			call_user_func_array( array( $this->Default, $this->action ), $args );
		}

		public function delete( $referent_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $referent_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$referent = $this->Referent->find(
				'first',
				array( 'conditions' => array( 'Referent.id' => $referent_id )
				)
			);

			// Mauvais paramètre
			if( empty( $referent_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Referent->delete( array( 'Referent.id' => $referent_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'referents', 'action' => 'index' ) );
			}
		}

		/**
		*	Clôture en masse des référents
		*/

	/**
		 * Formulaire de clôture d'un référent du parcours.
		 *
		 * @param integer $id L'id technique de l'enregistrement dans la table personnes_referents
		 * @return void
		 */
		public function cloturer( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			$referent = $this->Referent->find(
				'first',
				array(
					'conditions' => array(
						'Referent.id' => $id
					)
				)
			);
			$this->assert( !empty( $referent ), 'invalidParameter' );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			// Tentative d'enregistrement du formulaire
			if( !empty( $this->request->data ) ) {
				$this->Referent->begin();

				$datedfdesignation = ( is_array( $this->request->data['Referent']['datecloture'] ) ? date_cakephp_to_sql( $this->request->data['Referent']['datecloture'] ) : $this->request->data['Referent']['datecloture'] );

				$count = $this->Referent->PersonneReferent->find(
					'count',
					array(
						'conditions' => array(
							'PersonneReferent.referent_id' => $id,
							'PersonneReferent.dfdesignation IS NULL'
						)
					)
				);

				$success = true;
				if( $count > 0 ) {
					$success = $this->Referent->PersonneReferent->updateAllUnBound(
						array( 'PersonneReferent.dfdesignation' => '\''.$datedfdesignation.'\'' ),
						array(
							'"PersonneReferent"."referent_id"' => $id,
							'PersonneReferent.dfdesignation IS NULL'
						)
					);
				}

				if( $success ) {
					$success = $this->Referent->updateAllUnBound(
						array( 'Referent.datecloture' => '\''.$datedfdesignation.'\'' ),
						array(
							'"Referent"."id"' => $id
						)
					) && $success;

					$this->Referent->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'referents', 'action' => 'index' ) );
				}
				else {
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					$this->Referent->rollback();
				}
			}
			else {
				$this->request->data = $referent;
			}
		}


        /**
         * Fonction de clôture en masse des référents, cloisonnée selon le type de structure
         * Uniquement pour les CPDV
         */
		public function clotureenmasse() {
			$structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId();

			if( !empty( $this->request->data ) ) {
				$queryData = $this->Referent->search( $this->request->data );
				$queryData['limit'] = 20;
                $queryData['conditions'][] = array( 'Referent.structurereferente_id' => $structurereferente_id );
				$this->paginate = $queryData;

				$progressivePaginate = !Hash::get( $this->request->data, 'Search.Pagination.nombre_total' );
				$referents = $this->paginate( 'Referent', array(), array(), $progressivePaginate );

				$this->set( 'referents', $referents );

			}
			$this->_setOptions();
            $this->render( 'index' );
		}

		/**
		 * Lecture de la table Dernierreferent pour ajax sur add_edit
		 */
		public function ajax_getreferent() {
			$id = Hash::get($this->request->data, 'id');
			$json = false;
			
			if (!empty($id) && preg_match('/^[\d]+$/', (string)$id)) {
				$json = $this->Referent->find('first', array(
					'fields' => array_merge(
						$this->Referent->fields(),
						$this->Referent->Dernierreferent->fields()
					),
					'joins' => array(
						$this->Referent->join('Dernierreferent')
					),
					'contain' => false,
					'conditions' => array('Referent.id' => $id),
				));
			}
			
			$this->set('json', Hash::flatten($json, '__'));
			$this->layout = 'ajax';
			$this->view = '/Elements/json';
		}
	}
?>