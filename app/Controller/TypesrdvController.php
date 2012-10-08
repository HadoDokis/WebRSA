<?php
	class TypesrdvController extends AppController
	{

		public $name = 'Typesrdv';
		public $uses = array( 'Rendezvous', 'Option', 'Typerdv' );
		public $helpers = array( 'Xform' );

		public $commeDroit = array(
			'add' => 'Typesrdv:edit'
		);

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'gestionsrdvs', 'action' => 'index' ) );
			}

			$typesrdv = $this->Typerdv->find(
				'all',
				array(
					'recursive' => -1,
					'order' => 'Typerdv.libelle ASC'
				)
			);

			$this->set( 'typesrdv', $typesrdv );
		}

		public function add() {
			if( !empty( $this->request->data ) ) {
				$this->Typerdv->begin();
				if( $this->Typerdv->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
					$this->Typerdv->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'typesrdv', 'action' => 'index' ) );
				}
				else {
					$this->Typerdv->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->render( 'add_edit' );
		}

		public function edit( $typerdv_id = null ){
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $typerdv_id ), 'invalidParameter' );

			$typerdv = $this->Typerdv->find(
				'first',
				array(
					'conditions' => array(
						'Typerdv.id' => $typerdv_id
					),
					'recursive' => -1
				)
			);
			// Si action n'existe pas -> 404
			if( empty( $typerdv ) ) {
				$this->cakeError( 'error404' );
			}

			if( !empty( $this->request->data ) ) {
				if( $this->Typerdv->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'typesrdv', 'action' => 'index', $typerdv['Typerdv']['id']) );
				}
			}
			else {
				$this->request->data = $typerdv;
			}
			$this->render( 'add_edit' );
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function delete( $typerdv_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $typerdv_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$typerdv = $this->Typerdv->find(
				'first',
				array( 'conditions' => array( 'Typerdv.id' => $typerdv_id )
				)
			);

			// Mauvais paramètre
			if( empty( $typerdv ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Typerdv->deleteAll( array( 'Typerdv.id' => $typerdv_id ), true ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'typesrdv', 'action' => 'index' ) );
			}
		}
	}

?>