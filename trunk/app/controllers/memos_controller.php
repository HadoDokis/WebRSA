<?php
	/**
	 * Code source de la classe MemosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe MemosController permet de gérer les mémos attachés à un allocataire.
	 *
	 * @package app.controllers
	 */
	class MemosController extends AppController
	{
		public $name = 'Memos';

		public $uses = array( 'Memo', 'Option', 'Personne' );

		public $helpers = array( 'Locale', 'Csv', 'Ajax', 'Xform' );

		public $components = array( 'Jetons2' );

		public $commeDroit = array(
			'add' => 'Memos:edit'
		);

		/**
		 *
		 * @param integer $personne_id
		 */
		public function index( $personne_id = null ) {
			$nbrPersonnes = $this->Memo->Personne->find( 'count', array( 'conditions' => array( 'Personne.id' => $personne_id ), 'contain' => false ) );
			$this->assert( ( $nbrPersonnes == 1 ), 'invalidParameter' );

			$memos = $this->Memo->find(
					'all', array(
				'conditions' => array(
					'Memo.personne_id' => $personne_id
				),
				'recursive' => -1
					)
			);

			$this->set( 'memos', $memos );
			$this->set( 'personne_id', $personne_id );
		}

		/**		 * *******************************************************************
		 *
		 * ** ****************************************************************** */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 *
		 * @param integer $id
		 */
		protected function _add_edit( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			// Récupération des id afférents
			if( $this->action == 'add' ) {
				$personne_id = $id;
				$dossier_id = $this->Personne->dossierId( $personne_id );
			}
			else if( $this->action == 'edit' ) {
				$memo_id = $id;
				$qd_memo = array(
					'conditions' => array(
						'Memo.id' => $memo_id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$memo = $this->Memo->find( 'first', $qd_memo);
				$this->assert( !empty( $memo ), 'invalidParameter' );

				$personne_id = $memo['Memo']['personne_id'];
			}

			$dossier_id = $this->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			if( !empty( $this->data ) ) {
				$this->Memo->begin();
				if( $this->Memo->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					if( $this->Memo->saveAll( $this->data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
						$this->Memo->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'memos', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}
				}
			}
			elseif( $this->action == 'edit' ) {
				$this->data = $memo;
			}

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