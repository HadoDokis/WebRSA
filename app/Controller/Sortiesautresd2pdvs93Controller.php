<?php
	/**
	 * Code source de la classe Sortiesautresd2pdvs93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Sortiesautresd2pdvs93Controller permet de paramétrer les motifs
	 * de sortie "Autres" de l'accompagnement PDV (formulaire D2).
	 *
	 * @package app.Controller
	 */
	class Sortiesautresd2pdvs93Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Sortiesautresd2pdvs93';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array();

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Sortieautred2pdv93' );

		/**
		 * Pagination sur les motifs de sortie.
		 */
		public function index() {
			$this->paginate = array(
				'Sortieautred2pdv93' => array(
					'limit' => 10,
					'order' => array( 'Sortieautred2pdv93.name ASC' )
				)
			);

			$this->set( 'sortiesautresd2pdvs93', $this->paginate() );
		}

		/**
		 * Formulaire d'ajout d'un motifs de sortie.
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, 'edit' ), $args );
		}

		/**
		 * Formulaire de modification d'un motif de sortie.
		 *
		 * @throws NotFoundException
		 */
		public function edit( $id = null ) {
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->Sortieautred2pdv93->begin();
				$this->Sortieautred2pdv93->create( $this->request->data );

				if( $this->Sortieautred2pdv93->save() ) {
					$this->Sortieautred2pdv93->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Sortieautred2pdv93->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $this->Sortieautred2pdv93->find(
					'first',
					array(
						'conditions' => array(
							"Sortieautred2pdv93.id" => $id
						),
						'contain' => false
					)
				);

				if( empty( $this->request->data  ) ) {
					throw new NotFoundException();
				}
			}

			$this->render( 'edit' );
		}

		/**
		 * Suppression d'un motif de sortie et redirection vers l'index.
		 *
		 * @param integer $id
		 */
		public function delete( $id ) {
			$this->Sortieautred2pdv93->begin();

			if( $this->Sortieautred2pdv93->delete( $id ) ) {
				$this->Sortieautred2pdv93->commit();
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
			}
			else {
				$this->Sortieautred2pdv93->rollback();
				$this->Session->setFlash( 'Erreur lors de la suppression', 'flash/error' );
			}

			$this->redirect( array( 'action' => 'index' ) );
		}
	}
?>
