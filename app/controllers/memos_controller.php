<?php
	class MemosController extends AppController
	{

		public $name = 'Memos';
		public $uses = array( 'Memo', 'Option', 'Personne' );
		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform' );
		
		public $commeDroit = array(
			'add' => 'Memos:edit'
		);

		/** ********************************************************************
		*
		*** *******************************************************************/

		public function index( $personne_id = null ){
			$nbrPersonnes = $this->Memo->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'contain' => false ) );
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			$memos = $this->Memo->find(
				'all',
				array(
					'conditions' => array(
						'Memo.personne_id' => $personne_id
					),
					'recursive' => -1
				)
			);

			$this->set( 'memos', $memos );
			$this->set( 'personne_id', $personne_id );
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
			$this->assert( valid_int( $id ), 'invalidParameter' );

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$personne_id = $id;
				$dossier_id = $this->Personne->dossierId( $personne_id );
			}
			else if( $this->action == 'edit' ) {
				$memo_id = $id;
				$memo = $this->Memo->findById( $memo_id, null, null, -1 );
				$this->assert( !empty( $memo ), 'invalidParameter' );

				$personne_id = $memo['Memo']['personne_id'];
			}

			$this->Memo->begin();

			$dossier_id = $this->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Memo->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ){
				if( $this->Memo->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					if( $this->Memo->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
						$this->Jetons->release( $dossier_id );
						$this->Memo->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array(  'controller' => 'memos','action' => 'index', $personne_id ) );
					}
					else {
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			elseif( $this->action == 'edit' ) {
				$this->data = $memo;
			}
			$this->Memo->commit();

			$this->set( 'personne_id', $personne_id );
			$this->set( 'urlmenu', '/memos/index/'.$personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}

		/**
		*
		*/

		public function delete( $id ) {
			$this->Default->delete( $id );
		}
	}

?>