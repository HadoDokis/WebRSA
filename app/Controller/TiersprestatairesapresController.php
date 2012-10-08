<?php
	class TiersprestatairesapresController extends AppController
	{

		public $name = 'Tiersprestatairesapres';
		public $uses = array( 'Tiersprestataireapre', 'Option', 'Apre' );
		public $helpers = array( 'Xform' );

		public $commeDroit = array(
			'add' => 'Tiersprestatairesapres:edit'
		);

		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$options = $this->Tiersprestataireapre->allEnumLists();
			$this->set( 'options', $options );
			$this->set( 'aidesApres', $this->Apre->aidesApre );
			$this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
		}

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'apres', 'action' => 'indexparams' ) );
			}

			$tiersprestatairesapres = $this->Tiersprestataireapre->adminList();

			$this->set('tiersprestatairesapres', $tiersprestatairesapres);
		}

		public function add() {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			if( !empty( $this->request->data ) ) {
				if( $this->Tiersprestataireapre->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'tiersprestatairesapres', 'action' => 'index' ) );
				}
			}
			$this->render( 'add_edit' );
		}

		public function edit( $tiersprestataireapre_id = null ) {
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $tiersprestataireapre_id ), 'invalidParameter' );

			// Retour à l'index en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			if( !empty( $this->request->data ) ) {
				if( $this->Tiersprestataireapre->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'tiersprestatairesapres', 'action' => 'index' ) );
				}
			}
			else {
				$tiersprestataireapre = $this->Tiersprestataireapre->find(
					'first',
					array(
						'conditions' => array(
							'Tiersprestataireapre.id' => $tiersprestataireapre_id,
						)
					)
				);
				$this->request->data = $tiersprestataireapre;
			}

			$this->render( 'add_edit' );
		}

		public function delete( $tiersprestataireapre_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $tiersprestataireapre_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$tiersprestataireapre = $this->Tiersprestataireapre->find(
				'first',
				array( 'conditions' => array( 'Tiersprestataireapre.id' => $tiersprestataireapre_id )
				)
			);

			// Mauvais paramètre
			if( empty( $tiersprestataireapre_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Tiersprestataireapre->delete( array( 'Tiersprestataireapre.id' => $tiersprestataireapre_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'tiersprestatairesapres', 'action' => 'index' ) );
			}
		}
	}

?>