<?php
	class ZonesgeographiquesController extends AppController
	{

		public $name = 'Zonesgeographiques';
		public $uses = array( 'Zonegeographique', 'User', 'Adresse', 'Structurereferente');
		public $helpers = array( 'Xform' );
		
		public $commeDroit = array(
			'add' => 'Zonesgeographiques:edit'
		);

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}

			$zones = $this->Zonegeographique->find(
				'all',
				array(
					'recursive' => -1
				)

			);

			$this->set('zones', $zones);
		}

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, 'add_edit' ), $args );
		}

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, 'add_edit' ), $args );
		}

		public function add_edit( $zone_id = null ) {
			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			// TODO : vérif param
			// Vérification du format de la variable
			if ( $this->action == 'edit' ) {
				$this->assert( valid_int( $zone_id ), 'invalidParameter' );
			}

			if( !empty( $this->data ) ) {
				if( $this->Zonegeographique->saveAll( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'zonesgeographiques', 'action' => 'index' ) );
				}
			}
			else {
				$zone = $this->Zonegeographique->find(
					'first',
					array(
						'conditions' => array(
							'Zonegeographique.id' => $zone_id,
						)
					)
				);
				$this->data = $zone;
			}

			$this->render( $this->action, null, 'add_edit' );
		}

		public function delete( $zone_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $zone_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$zone = $this->Zonegeographique->find(
				'first',
				array( 'conditions' => array( 'Zonegeographique.id' => $zone_id )
				)
			);

			// Mauvais paramètre
			if( empty( $zone_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Zonegeographique->delete( array( 'Zonegeographique.id' => $zone_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'zonesgeographiques', 'action' => 'index' ) );
			}
		}
	}

?>
