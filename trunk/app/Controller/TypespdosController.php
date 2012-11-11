<?php
	/**
	 * Code source de la classe TypespdosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe TypespdosController ...
	 *
	 * @package app.Controller
	 */
	class TypespdosController extends AppController
	{

		public $name = 'Typespdos';
		public $uses = array( 'Typepdo', 'Propopdo' );
		public $helpers = array( 'Xform', 'Default2' );

		public $commeDroit = array(
			'add' => 'Typespdos:edit'
		);

		protected function _setOptions() {
			$options = $this->Typepdo->enums();
			$this->set( compact ( 'options' ) );
		}

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'pdos', 'action' => 'index' ) );
			}

			$typespdos = $this->Typepdo->find(
				'all',
				array(
					'recursive' => -1
				)
			);
			$this->set('typespdos', $typespdos);
			$this->_setOptions();
		}

		public function add() {
			if( !empty( $this->request->data ) ) {
				if( $this->Typepdo->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'typespdos', 'action' => 'index' ) );
				}
			}
			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		public function edit( $typepdo_id = null ) {
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $typepdo_id ), 'invalidParameter' );

			if( !empty( $this->request->data ) ) {
				if( $this->Typepdo->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'typespdos', 'action' => 'index' ) );
				}
			}
			else {
				$typepdo = $this->Typepdo->find(
					'first',
					array(
						'conditions' => array(
							'Typepdo.id' => $typepdo_id,
						)
					)
				);
				$this->request->data = $typepdo;
			}
			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		public function delete( $typepdo_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $typepdo_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$typepdo = $this->Typepdo->find(
				'first',
				array( 'conditions' => array( 'Typepdo.id' => $typepdo_id )
				)
			);

			// Mauvais paramètre
			if( empty( $typepdo_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Typepdo->delete( array( 'Typepdo.id' => $typepdo_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'typespdos', 'action' => 'index' ) );
			}
		}
	}

?>