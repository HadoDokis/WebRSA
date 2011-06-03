<?php
	class CompositionsregroupementsepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		protected function _setOptions() {
			$options = $this->Compositionregroupementep->enums();
			$this->set( compact( 'options' ) );
		}


		public function index() {
			$this->paginate = array(
				'limit' => 10
			);

			$this->_setOptions();
			$this->set( 'regroupementseps', $this->paginate( $this->Compositionregroupementep->Regroupementep ) );
			$compteurs = array(
				'Regroupementep' => $this->Compositionregroupementep->Regroupementep->find( 'count' ),
				'Fonctionmembreep' => $this->Compositionregroupementep->Fonctionmembreep->find( 'count' )
			);
			$this->set( compact( 'compteurs' ) );
		}

		/**
		*
		*/

		public function edit( $id = null ) {
			if( !empty( $this->data ) ) {
				$success = true;
				$this->Compositionregroupementep->begin();
				foreach( $this->data['Compositionregroupementep'] as $functionmembreep_id => $fields ) {
// 					debug($fields);
					$compositionregroupementep['Compositionregroupementep'] = $fields;
					$compositionregroupementep['Compositionregroupementep']['regroupementep_id'] = $id;
					$compositionregroupementep['Compositionregroupementep']['fonctionmembreep_id'] = $functionmembreep_id;
					$this->Compositionregroupementep->create( $compositionregroupementep );
					$success = $this->Compositionregroupementep->save() && $success;
				}

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->Compositionregroupementep->commit();
					$this->redirect( array( 'action' => 'index' ) );
				}
				else{
					$this->Compositionregroupementep->rollback();
				}
			}
			else {
				$regroupementep = $this->Compositionregroupementep->Regroupementep->find(
					'first',
					array(
						'conditions' => array( 'Regroupementep.id' => $id ),
						'contain' => array(
							'Compositionregroupementep'
						)
					)
				);
				$this->assert( !empty( $regroupementep ), 'error404' );
				$this->data['Regroupementep'] = $regroupementep['Regroupementep'];
				foreach( $regroupementep['Compositionregroupementep'] as $compo ) {
					$this->data['Compositionregroupementep'][$compo['fonctionmembreep_id']] = $compo;
				}
			}
			$fonctionsmembreseps = $this->Compositionregroupementep->Fonctionmembreep->find( 'list' );
			$this->set( compact( 'fonctionsmembreseps' ) );
			$this->_setOptions();
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Fonctionmembreep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>