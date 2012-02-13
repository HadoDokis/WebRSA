<?php

	class StatutsrdvsController extends Appcontroller
	{

		public $name = 'Statutsrdvs';
		public $uses = array( 'Rendezvous', 'Option', 'Statutrdv' );
		public $helpers = array( 'Xform' );
		
		public $commeDroit = array(
			'add' => 'Statutsrdvs:edit'
		);

		protected function _setOptions() {
			$provoquepassageep = array( '0' => 'Non', '1' => 'Oui' );
			$permetpassageepl = array( '0' => 'Non', '1' => 'Oui' );
			$this->set( compact( 'provoquepassageep', 'permetpassageepl' ) );
		}

		public function index() {
			// Retour à la liste en cas d'annulation
            if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'gestionsrdvs', 'action' => 'index' ) );
			}

			$statutsrdvs = $this->Statutrdv->find(
				'all',
				array(
					'recursive' => 1,
					'order' => 'Statutrdv.libelle ASC'
				)
			);
			$this->_setOptions();
			$this->set( 'statutsrdvs', $statutsrdvs );
		}

		public function add() {
			if( !empty( $this->data ) ) {
				$this->Statutrdv->begin();
				if( $this->Statutrdv->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
					$this->Statutrdv->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'statutsrdvs', 'action' => 'index' ) );
				}
				else {
					$this->Statutrdv->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			$this->_setOptions();
			$this->render( $this->action, null, 'add_edit' );
		}

		public function edit( $statutrdv_id = null ){
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $statutrdv_id ), 'invalidParameter' );

			$statutrdv = $this->Statutrdv->find(
				'first',
				array(
					'conditions' => array(
						'Statutrdv.id' => $statutrdv_id
					),
					'recursive' => 1
				)
			);

			// Si action n'existe pas -> 404
			if( empty( $statutrdv ) ) {
				$this->cakeError( 'error404' );
			}

			if( !empty( $this->data ) ) {
				if( $this->Statutrdv->saveAll( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'statutsrdvs', 'action' => 'index', $statutrdv['Statutrdv']['id']) );
				}
			}
			else {
				$this->data = $statutrdv;
			}
			$this->_setOptions();
			$this->render( $this->action, null, 'add_edit' );
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function delete( $statutrdv_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $statutrdv_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$statutrdv = $this->Statutrdv->find(
				'first',
				array( 'conditions' => array( 'Statutrdv.id' => $statutrdv_id )
				)
			);

			// Mauvais paramètre
			if( empty( $statutrdv ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Statutrdv->deleteAll( array( 'Statutrdv.id' => $statutrdv_id ), true ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'statutsrdvs', 'action' => 'index' ) );
			}
		}
	}
?>