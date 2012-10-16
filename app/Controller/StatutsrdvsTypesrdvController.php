<?php

	class StatutsrdvsTypesrdvController extends AppController
	{

		public $name = 'StatutsrdvsTypesrdv';
		public $uses = array( 'StatutrdvTyperdv', 'Option' );
		public $helpers = array( 'Default2' );

		public $commeDroit = array(
			'add' => 'StatutsrdvsTypesrdv:edit'
		);

		public function _setOptions() {
			$options = $this->StatutrdvTyperdv->enums();
			$statutsrdvs = $this->StatutrdvTyperdv->Statutrdv->find( 'list', array( 'fields' => 'Statutrdv.libelle' ) );
			$typesrdv = $this->StatutrdvTyperdv->Typerdv->find( 'list', array( 'fields' => 'Typerdv.libelle' ) );
			$this->set( compact( 'options', 'typesrdv', 'statutsrdvs' ) );
		}

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'statutsrdvs_typesrdv', 'action' => 'index' ) );
			}
			$fields = array(
				'StatutrdvTyperdv.id',
				'StatutrdvTyperdv.nbabsenceavantpassagecommission',
				'StatutrdvTyperdv.typecommission',
				'StatutrdvTyperdv.motifpassageep',
				'Typerdv.libelle',
				'Statutrdv.libelle'
			);

			$this->paginate = array(
				'fields' => $fields,
				'contain' => array(
					'Statutrdv',
					'Typerdv'
				),
				'limit' => 10
			);

			$this->_setOptions();
			$this->set( 'statutsrdvs_typesrdv', $this->paginate( $this->StatutrdvTyperdv ) );

			$this->_setOptions();
		}

		public function add() {
			if( !empty( $this->request->data ) ) {
				$this->StatutrdvTyperdv->begin();
				if( $this->StatutrdvTyperdv->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
					$this->StatutrdvTyperdv->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'statutsrdvs_typesrdv', 'action' => 'index' ) );
				}
				else {
					$this->StatutrdvTyperdv->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		public function edit( $statutrdv_typerdv_id = null ){
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $statutrdv_typerdv_id ), 'invalidParameter' );

			$statutrdv_typerdv = $this->StatutrdvTyperdv->find(
				'first',
				array(
					'conditions' => array(
						'StatutrdvTyperdv.id' => $statutrdv_typerdv_id
					),
					'recursive' => -1
				)
			);

			// Si action n'existe pas -> 404
			if( empty( $statutrdv_typerdv ) ) {
				$this->cakeError( 'error404' );
			}

			if( !empty( $this->request->data ) ) {
				if( $this->StatutrdvTyperdv->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'statutsrdvs_typesrdv', 'action' => 'index', $statutrdv_typerdv['StatutrdvTyperdv']['id']) );
				}
			}
			else {
				$this->request->data = $statutrdv_typerdv;
			}
			$this->_setOptions();
			$this->render( 'add_edit' );
		}

		public function delete( $statutrdv_typerdv_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $statutrdv_typerdv_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$statutrdv_typerdv = $this->StatutrdvTyperdv->find(
				'first',
				array( 'conditions' => array( 'StatutrdvTyperdv.id' => $statutrdv_typerdv_id )
				)
			);

			// Mauvais paramètre
			if( empty( $statutrdv_typerdv ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->StatutrdvTyperdv->deleteAll( array( 'StatutrdvTyperdv.id' => $statutrdv_typerdv_id ), true ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'statutsrdvs_typesrdv', 'action' => 'index' ) );
			}
		}
	}
?>
