<?php

	class TypesactionsController extends Appcontroller
	{

		public $name = 'Typesactions';
		public $uses = array( 'Actioninsertion', 'Aidedirecte', 'Prestform', 'Option', 'Refpresta', 'Action', 'Typeaction' );
		public $helpers = array( 'Xform' );
		
		public $commeDroit = array(
			'add' => 'Typesactions:edit'
		);

		function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}

			$typesactions = $this->Typeaction->find(
				'all',
				array(
					'recursive' => 1,
					'order' => 'Typeaction.libelle ASC'
				)
			);
			$this->set( 'typesactions', $typesactions );
		}

		function add() {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}
			if( !empty( $this->data ) ) {
				$this->Typeaction->begin();
				if( $this->Typeaction->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
					$this->Typeaction->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'typesactions', 'action' => 'index' ) );
				}
				else {
					$this->Typeaction->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}

			$this->render( $this->action, null, 'add_edit' );
		}

		function edit( $typeaction_id = null ){
			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $typeaction_id ), 'invalidParameter' );

			$typeaction = $this->Typeaction->find(
				'first',
				array(
					'conditions' => array(
						'Typeaction.id' => $typeaction_id
					),
					'recursive' => 1
				)
			);

			// Si action n'existe pas -> 404
			if( empty( $typeaction ) ) {
				$this->cakeError( 'error404' );
			}

			if( !empty( $this->data ) ) {
				if( $this->Typeaction->saveAll( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'typesactions', 'action' => 'index', $typeaction['Typeaction']['id']) );
				}
			}
			else {
				$this->data = $typeaction;
			}
			$this->render( $this->action, null, 'add_edit' );
		}


		/** ********************************************************************
		*
		*** *******************************************************************/

		function delete( $typeaction_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $typeaction_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$typeaction = $this->Typeaction->find(
				'first',
				array( 'conditions' => array( 'Typeaction.id' => $typeaction_id )
				)
			);

			// Mauvais paramètre
			if( empty( $typeaction ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Typeaction->deleteAll( array( 'Typeaction.id' => $typeaction_id ), true ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'typesactions', 'action' => 'index' ) );
			}
		}
	}
?>
