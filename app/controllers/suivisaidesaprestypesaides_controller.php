<?php
	class SuivisaidesaprestypesaidesController extends AppController 
	{

		public $name = 'Suivisaidesaprestypesaides';
		public $uses = array( 'Suiviaideapretypeaide', 'Suiviaideapre', 'Option', 'Apre' );
		public $helpers = array( 'Xform' );

		public $commeDroit = array(
			'add' => 'Suivisaidesaprestypesaides:edit'
		);

		public function beforeFilter() {
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'personnessuivis', $this->Suiviaideapre->find( 'list' ) );
			$this->set( 'aidesApres', $this->Apre->aidesApre );
			$this->set( 'natureAidesApres', $this->Option->natureAidesApres() );
		}

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'apres', 'action' => 'indexparams' ) );
			}

			$suivisaidesaprestypesaides = $this->Suiviaideapretypeaide->find( 'all', array( 'recursive' => -1 ) );
			$this->set('suivisaidesaprestypesaides', $suivisaidesaprestypesaides );
		}

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/** ********************************************************************
		*
		*** *******************************************************************/
		protected function _add_edit( $id = null ) {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			if( !empty( $this->data ) ) {
				$this->data = Set::extract( $this->data, '/Suiviaideapretypeaide' );
				if( $this->Suiviaideapretypeaide->saveAll( $this->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'suivisaidesaprestypesaides', 'action' => 'index' ) );
				}
			}
			else {
				$suiviaideapretypeaide = $this->Suiviaideapretypeaide->find( 'all' );
				$this->data = array( 'Suiviaideapretypeaide' => Set::classicExtract( $suiviaideapretypeaide, '{n}.Suiviaideapretypeaide' ) );
			}

			$this->render( $this->action, null, 'add_edit' );
		}
	}

?>