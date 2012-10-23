<?php
	/**
	 * Code source de la classe NaturescontratsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe NaturescontratsController permet la gestion des CER du CG 93 (hors workflow).
	 *
	 * @package app.Controller
	 */
	class NaturescontratsController extends AppController
	{
		/**
		 * Nom
		 *
		 * @var string
		 */
		public $name = 'Naturescontrats';

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Naturecontrat' );

		
		
		/**
		 * Pagination sur les <élément>s de la table.
		 *
		 * @return void
		 */
		public function index() {

			$naturescontrats = $this->Naturecontrat->find( 'all', array( 'recursive' => -1 ) );

			$this->set('naturescontrats', $naturescontrats);
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
		public function edit( $naturecontrat_id = null ) {
			if( $this->action == 'edit') {
				// Vérification du format de la variable
				if( !$this->Naturecontrat->exists( $naturecontrat_id ) ) {
					throw new NotFoundException();
				}
			}
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'naturescontrats', 'action' => 'index' ) );
			}
		
			// Tentative de sauvegarde du formulaire
			if( !empty( $this->request->data ) ) {
				$this->Naturecontrat->begin();
				if( $this->Naturecontrat->saveAll( $this->request->data ) ) {
					$this->Naturecontrat->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Naturecontrat->rollback();
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			else if( $this->action == 'edit') {
				$this->request->data = $this->Naturecontrat->find(
					'first',
					array(
						'conditions' => array(
							'Naturecontrat.id' => $naturecontrat_id
						),
						'contain' => false
					)
				);
			}
			
			$this->set( 'options', $this->Naturecontrat->enums() );
			$this->render( 'edit' );
		}
		
		public function delete( $naturecontrat_id = null ) {
			// Vérification du format de la variable
			if( !$this->Naturecontrat->exists( $naturecontrat_id ) ) {
				throw new NotFoundException();
			}

			$metierexerce = $this->Naturecontrat->find(
				'first',
				array( 'conditions' => array( 'Naturecontrat.id' => $naturecontrat_id )
				)
			);

			// Tentative de suppression ...
			if( $this->Naturecontrat->deleteAll( array( 'Naturecontrat.id' => $naturecontrat_id ), true ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'naturescontrats', 'action' => 'index' ) );
			}
		}
	}
?>
