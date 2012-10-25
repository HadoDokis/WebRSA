<?php
	/**
	 * Code source de la classe Typessujetscers93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Typessujetscers93Controller permet la gestion des CER du CG 93 (hors workflow).
	 *
	 * @package app.Controller
	 */
	class Typessujetscers93Controller extends AppController
	{
		/**
		 * Nom
		 *
		 * @var string
		 */
		public $name = 'Typessujetscers93';

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Typesujetcer93' );

		
		
		/**
		 * Pagination sur les <élément>s de la table.
		 *
		 * @return void
		 */
		public function index() {
			$typessujetscers93 = $this->Typesujetcer93->find(
				'all',
				array(
					'fields' => array_merge(
						$this->Typesujetcer93->fields(),
						$this->Typesujetcer93->Sujetcer93->fields()
					),
					'contain' => array(
						'Sujetcer93'
					),
					'order' => array( 'Sujetcer93.name ASC' )
				)
			);

			$this->set('typessujetscers93', $typessujetscers93);
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
		public function edit( $typesujetcer93_id = null ) {
			if( $this->action == 'edit') {
				// Vérification du format de la variable
				if( !$this->Typesujetcer93->exists( $typesujetcer93_id ) ) {
					throw new NotFoundException();
				}
			}
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'typessujetscers93', 'action' => 'index' ) );
			}
		
			// Tentative de sauvegarde du formulaire
			if( !empty( $this->request->data ) ) {
				$this->Typesujetcer93->begin();
				if( $this->Typesujetcer93->saveAll( $this->request->data ) ) {
					$this->Typesujetcer93->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Typesujetcer93->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit') {
				$this->request->data = $this->Typesujetcer93->find(
					'first',
					array(
						'conditions' => array(
							'Typesujetcer93.id' => $typesujetcer93_id
						),
						'contain' => array(
							'Sujetcer93'
						)
					)
				);
			}
			
			$options = array(
				'Typesujetcer93' => array(
					'sujetcer93_id' => $this->Typesujetcer93->Sujetcer93->find( 'list', array( 'fields' => array( 'id', 'name' ) ) )
				)
			);
			$this->set( compact( 'options' ) );

			$this->render( 'edit' );
		}
		
		public function delete( $typesujetcer93_id = null ) {
			// Vérification du format de la variable
			if( !$this->Typesujetcer93->exists( $typesujetcer93_id ) ) {
				throw new NotFoundException();
			}

			$typesujetcer93 = $this->Typesujetcer93->find(
				'first',
				array( 'conditions' => array( 'Typesujetcer93.id' => $typesujetcer93_id )
				)
			);

			// Tentative de suppression ... FIXME
			if( $this->Typesujetcer93->deleteAll( array( 'Typesujetcer93.id' => $typesujetcer93_id ), true ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'typessujetscers93', 'action' => 'index' ) );
			}
		}
	}
?>
