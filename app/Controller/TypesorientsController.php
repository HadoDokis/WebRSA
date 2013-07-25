<?php
	/**
	 * Code source de la classe TypesorientsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe TypesorientsController ...
	 *
	 * @package app.Controller
	 */
	class TypesorientsController extends AppController
	{

		public $name = 'Typesorients';
		public $uses = array( 'Typeorient', 'Structurereferente');
		public $helpers = array( 'Xform' );

		public $commeDroit = array(
			'add' => 'Typesorients:edit'
		);


		public function _setOptions() {
			$options = $this->Typeorient->allEnumLists();
			$this->set( compact( 'options' ) );
		}


		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}

			$typesorients = $this->Typeorient->find(
				'all',
				array(
					'recursive' => -1
				)
			);

			App::import( 'Behaviors', 'Occurences' );
			$this->Typeorient->Behaviors->attach( 'Occurences' );
			$this->set( 'occurences', $this->Typeorient->occurencesExists() );
			$this->_setOptions();

			$this->set( 'typesorients', $typesorients );
		}

		public function add() {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			$this->set( 'options', $this->Typeorient->listOptions() );

			$typesorients = $this->Typeorient->find(
				'all',
				array(
					'recursive' => -1
				)

			);
			$this->set('typesorients', $typesorients);

			$parentid = $this->Typeorient->find(
				'list',
				array(
					'fields' => array(
						'Typeorient.id',
						'Typeorient.lib_type_orient',
					),
					'conditions' => array( 'Typeorient.parentid' => null ),
					'recursive' => -1
				)
			);
			$this->set( 'parentid', $parentid );


			if( !empty( $this->request->data ) ) {
				if( $this->Typeorient->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'typesorients', 'action' => 'index' ) );
				}
			}
			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		public function edit( $typeorient_id = null ) {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $typeorient_id ), 'error404' );

			$typesorients = $this->Typeorient->find(
				'all',
				array(
					'recursive' => -1
				)

			);
			$this->set('typesorients', $typesorients);

			$parentid = $this->Typeorient->find(
				'list',
				array(
					'fields' => array(
						'Typeorient.id',
						'Typeorient.lib_type_orient',
					),
					'conditions' => array(
						'Typeorient.parentid' => null,
						'Typeorient.id <>' => $typeorient_id,
					),
					'recursive' => -1
				)
			);
			$this->set( 'parentid', $parentid );

			$notif = $this->Typeorient->find(
				'list',
				array(
					'fields' => array(
						'Typeorient.modele_notif'
					),
					'recursive' => -1
				)
			);
			$this->set( 'notif', $notif );

			if( !empty( $this->request->data ) ) {
				if( $this->Typeorient->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'typesorients', 'action' => 'index' ) );
				}
			}
			else {
				$typeorient = $this->Typeorient->find(
					'first',
					array(
						'conditions' => array(
							'Typeorient.id' => $typeorient_id,
						),
						'recursive' => -1
					)
				);
				$this->request->data = $typeorient;
			}
			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		public function delete( $typeorient_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $typeorient_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$typeorient = $this->Typeorient->find(
				'first',
				array( 'conditions' => array( 'Typeorient.id' => $typeorient_id )
				)
			);

			// Mauvais paramètre
			if( empty( $typeorient_id ) ) {
				$this->cakeError( 'error404' );
			}

			App::import( 'Behaviors', 'Occurences' );
			$this->Typeorient->Behaviors->attach( 'Occurences' );

			$occurences = $this->Typeorient->occurences();
			$nbOccurences = Set::enum( $typeorient['Typeorient']['id'], $occurences );
			$nbOccurences = ( is_numeric( $nbOccurences ) ? $nbOccurences : 0 );

			// Tentative de suppression ... FIXME
			if( $nbOccurences != 0 ) {
				$this->Session->setFlash( 'Impossible de supprimer un type d\'orientation utilisé dans l\'application', 'flash/error' );
				$this->redirect( array( 'controller' => 'typesorients', 'action' => 'index' ) );
			}

			// Tentative de suppression ... FIXME
			if( $this->Typeorient->delete( array( 'Typeorient.id' => $typeorient_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'typesorients', 'action' => 'index' ) );
			}
		}
	}

?>
