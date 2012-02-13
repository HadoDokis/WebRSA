<?php
	class TypesnotifspdosController extends AppController
	{

		public $name = 'Typesnotifspdos';
		public $uses = array( 'Typenotifpdo', 'Propopdo' );
		public $helpers = array( 'Xform' );

		public $commeDroit = array(
			'add' => 'Typesnotifspdos:edit'
		);

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'pdos', 'action' => 'index' ) );
			}

			$typesnotifspdos = $this->Typenotifpdo->find(
				'all',
				array(
					'recursive' => -1
				)
			);
			$this->set('typesnotifspdos', $typesnotifspdos);
		}

		public function add() {
			if( !empty( $this->data ) ) {
				if( $this->Typenotifpdo->saveAll( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'typesnotifspdos', 'action' => 'index' ) );
				}
			}
			$this->render( $this->action, null, 'add_edit' );
		}

		public function edit( $typenotifpdo_id = null ) {
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $typenotifpdo_id ), 'invalidParameter' );

			if( !empty( $this->data ) ) {
				if( $this->Typenotifpdo->saveAll( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'typesnotifspdos', 'action' => 'index' ) );
				}
			}
			else {
				$typenotifpdo = $this->Typenotifpdo->find(
					'first',
					array(
						'conditions' => array(
							'Typenotifpdo.id' => $typenotifpdo_id,
						)
					)
				);
				$this->data = $typenotifpdo;
			}

			$this->render( $this->action, null, 'add_edit' );
		}

		public function deleteparametrage( $typenotifpdo_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $typenotifpdo_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$typenotifpdo = $this->Typenotifpdo->find(
				'first',
				array( 'conditions' => array( 'Typenotifpdo.id' => $typenotifpdo_id )
				)
			);
			// Mauvais paramètre
			if( empty( $typenotifpdo_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Typenotifpdo->delete( array( 'Typenotifpdo.id' => $typenotifpdo_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'typesnotifspdos', 'action' => 'index' ) );
			}
		}
	}

?>