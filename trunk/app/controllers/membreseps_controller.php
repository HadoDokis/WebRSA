<?php
	class MembresepsController extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public function beforeFilter() {
		}

		
		protected function _setOptions() {
			$options = $this->Membreep->enums();
			if( $this->action != 'index' ) {
				$options['Membreep']['fonctionmembreep_id'] = $this->Membreep->Fonctionmembreep->find( 'list' );
				$options['Membreep']['ep_id'] = $this->Membreep->Ep->find( 'list' );
			}
			$this->set( compact( 'options' ) );
		}


		public function index() {
			$this->paginate = array(
				'fields' => array(
					'Membreep.id',
					'Fonctionmembreep.name',
					'Ep.name',
					'Membreep.qual',
					'Membreep.nom',
					'Membreep.prenom'
				),
				'contain' => array(
					'Fonctionmembreep',
					'Ep'
				),
				'limit' => 10
			);

			$this->_setOptions();
			$this->set( 'membreeps', $this->paginate( $this->Membreep ) );
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		*
		*/

		protected function _add_edit( $id = null ) {
			if( !empty( $this->data ) ) {
				$this->Membreep->create( $this->data );
				$success = $this->Membreep->save();

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			elseif( $this->action == 'edit' ) {
				$this->data = $this->Membreep->find(
					'all',
					array(
						'contain' => false,
						'conditions' => array( 'Membreep.id' => $id )
					)
				);
				$this->assert( !empty( $this->data ), 'error404' );
			}
			
			if ($this->action == 'edit') {
				$listeMembres = $this->Membreep->find(
					'all',
					array(
						'conditions'=>array(
							'Membreep.id <>' => $id
						),
						'contain'=>false
					)
				);
			}
			else {
				$listeMembres = $this->Membreep->find(
					'all',
					array(
						'contain'=>false
					)
				);
			}
			$listeMembresEps = array();
			foreach($listeMembres as $membreEp) {
				$listeMembresEps[$membreEp['Membreep']['id']] = $membreEp['Membreep']['qual'].' '.$membreEp['Membreep']['nom'].' '.$membreEp['Membreep']['prenom'];
			}
			$this->set(compact('listeMembresEps'));
			
			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$success = $this->Membreep->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}

		public function ajaxfindsuppleant( $ep_id = null, $defaultvalue = '' ) {
            Configure::write( 'debug', 0 );
            $suppleants = $this->Membreep->find(
            	'all',
            	array(
            		'conditions'=>array(
            			'Membreep.ep_id'=>$ep_id
            		),
            		'contain'=>false
            	)
            );
			$listeSuppleant = array();
			foreach($suppleants as $suppleant) {
				$listeSuppleant[$suppleant['Membreep']['id']] = $suppleant['Membreep']['qual'].' '.$suppleant['Membreep']['nom'].' '.$suppleant['Membreep']['prenom'];
			}
            $this->set( compact( 'listeSuppleant' ) );
            $this->set( compact( 'defaultvalue' ) );
            $this->render( $this->action, 'ajax', '/membreseps/ajaxfindsuppleant' );
		}


	}
?>
