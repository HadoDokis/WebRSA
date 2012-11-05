<?php
	/**
	 * Code source de la classe Valeursparsoussujetscers93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Valeursparsoussujetscers93Controller permet la gestion des CER du CG 93 (hors workflow).
	 *
	 * @package app.Controller
	 */
	class Valeursparsoussujetscers93Controller extends AppController
	{
		/**
		 * Nom
		 *
		 * @var string
		 */
		public $name = 'Valeursparsoussujetscers93';

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Valeurparsoussujetcer93' );

		
		
		/**
		 * Pagination sur les <élément>s de la table.
		 *
		 * @return void
		 */
		public function index() {
			$valeursparsoussujetscers93 = $this->Valeurparsoussujetcer93->find(
				'all',
				array(
					'fields' => array_merge(
						$this->Valeurparsoussujetcer93->fields(),
						$this->Valeurparsoussujetcer93->Soussujetcer93->fields()
					),
					'contain' => array(
						'Soussujetcer93'
					),
					'order' => array( 'Soussujetcer93.name ASC' )
				)
			);

			$this->set('valeursparsoussujetscers93', $valeursparsoussujetscers93);
		}

		/**
		 * Formulaire d'ajout d'un élémént.
		 *
		 * @return void
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}

		/**
		 * Formulaire de modification d'un <élément>.
		 *
		 * @return void
		 * @throws NotFoundException
		 */
		public function edit( $valeurparsoussujetcer93_id = null ) {
			if( $this->action == 'edit') {
				// Vérification du format de la variable
				if( !$this->Valeurparsoussujetcer93->exists( $valeurparsoussujetcer93_id ) ) {
					throw new NotFoundException();
				}
			}
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'valeursparsoussujetscers93', 'action' => 'index' ) );
			}
		
			// Tentative de sauvegarde du formulaire
			if( !empty( $this->request->data ) ) {
				$this->Valeurparsoussujetcer93->begin();
				if( $this->Valeurparsoussujetcer93->saveAll( $this->request->data ) ) {
					$this->Valeurparsoussujetcer93->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Valeurparsoussujetcer93->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit') {
				$this->request->data = $this->Valeurparsoussujetcer93->find(
					'first',
					array(
						'conditions' => array(
							'Valeurparsoussujetcer93.id' => $valeurparsoussujetcer93_id
						),
						'contain' => array(
							'Soussujetcer93'
						)
					)
				);
			}
			
			$options = array(
				'Valeurparsoussujetcer93' => array(
					'soussujetcer93_id' => $this->Valeurparsoussujetcer93->Soussujetcer93->find( 'list', array( 'fields' => array( 'id', 'name' ) ) )
				)
			);
			$this->set( compact( 'options' ) );

			$this->render( 'edit' );
		}
		
		public function delete( $valeurparsoussujetcer93_id = null ) {
			// Vérification du format de la variable
			if( !$this->Valeurparsoussujetcer93->exists( $valeurparsoussujetcer93_id ) ) {
				throw new NotFoundException();
			}

			$valeurparsoussujetcer93 = $this->Valeurparsoussujetcer93->find(
				'first',
				array( 'conditions' => array( 'Valeurparsoussujetcer93.id' => $valeurparsoussujetcer93_id )
				)
			);

			// Tentative de suppression ... FIXME
			if( $this->Valeurparsoussujetcer93->deleteAll( array( 'Valeurparsoussujetcer93.id' => $valeurparsoussujetcer93_id ), true ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'valeursparsoussujetscers93', 'action' => 'index' ) );
			}
		}
	}
?>
