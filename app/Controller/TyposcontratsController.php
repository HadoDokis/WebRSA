<?php
	class TyposcontratsController extends AppController
	{

		public $name = 'Typoscontrats';
		public $uses = array( 'Typocontrat', 'Contratinsertion');
		public $helpers = array( 'Xform' );

		public $commeDroit = array(
			'add' => 'Typoscontrats:edit'
		);

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}
			$typoscontrats = $this->Typocontrat->find(
				'all',
				array(
					'recursive' => -1
				)
			);
			$this->set('typoscontrats', $typoscontrats);
		}

		public function add() {
			if( !empty( $this->request->data ) ) {
				if( $this->Typocontrat->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'typoscontrats', 'action' => 'index' ) );
				}
			}
			$this->render( 'add_edit' );
		}

		public function edit( $typocontrat_id = null ) {
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $typocontrat_id ), 'error404' );

			if( !empty( $this->request->data ) ) {
				if( $this->Typocontrat->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'typoscontrats', 'action' => 'index' ) );
				}
			}
			else {
				$typocontrat = $this->Typocontrat->find(
					'first',
					array(
						'conditions' => array(
							'Typocontrat.id' => $typocontrat_id,
						),
						'recursive' => -1
					)
				);
				$this->request->data = $typocontrat;
			}

			$this->render( 'add_edit' );
		}

		public function delete( $typocontrat_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $typocontrat_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$typocontrat = $this->Typocontrat->find(
				'first',
				array( 'conditions' => array( 'Typocontrat.id' => $typocontrat_id )
				)
			);
			// Mauvais paramètre
			if( empty( $typocontrat_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Typocontrat->delete( array( 'Typocontrat.id' => $typocontrat_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'typoscontrats', 'action' => 'index' ) );
			}
		}
	}
?>