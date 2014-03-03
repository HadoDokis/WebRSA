<?php
	/**
	 * Code source de la classe PiecespdosController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe PiecespdosController ...
	 *
	 * @package app.Controller
	 */
	class PiecespdosController extends AppController
	{

		public $name = 'Piecespdos';
		public $uses = array( 'Piecepdo', 'Propopdo' );

		public $commeDroit = array(
			'view' => 'Piecespdos:index',
			'add' => 'Piecespdos:edit'
		);

		public function index(  ) {

		}

		public function add( $pdo_id = null ) {
			$this->assert( valid_int( $pdo_id ), 'invalidParameter' );

			$pdo = $this->Propopdo->find( 'first', array( 'conditions' => array( 'Propopdo.id' => $pdo_id ) ) );
			$this->set( 'pdo', $pdo );

			$dossier_id = Set::extract( $pdo, 'Propopdo.dossier_id' );

			if( !empty( $this->request->data ) ) {

				if( $this->Piecepdo->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'dossierspdo', 'action' => 'index', $dossier_id ) );
				}
			}
			$this->render( 'add_edit' );

		}

		public function edit( $piecepdo_id = null ) {
			// TODO : vérif param
			// Vérification du format de la variable
			$this->assert( valid_int( $piecepdo_id ), 'invalidParameter' );

			if( !empty( $this->request->data ) ) {
				if( $this->Piecepdo->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'piecespdos', 'action' => 'index' ) );
				}
			}
			else {
				$piecepdo = $this->Piecepdo->find(
					'first',
					array(
						'conditions' => array(
							'Piecepdo.id' => $piecepdo_id,
						)
					)
				);
				$this->request->data = $piecepdo;
			}

			$this->render( 'add_edit' );
		}

		public function delete( $piecepdo_id = null ) {
			// Vérification du format de la variable
			if( !valid_int( $piecepdo_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Recherche de la personne
			$piecepdo = $this->Piecepdo->find(
				'first',
				array( 'conditions' => array( 'Piecepdo.id' => $piecepdo_id )
				)
			);

			// Mauvais paramètre
			if( empty( $piecepdo_id ) ) {
				$this->cakeError( 'error404' );
			}

			// Tentative de suppression ... FIXME
			if( $this->Piecepdo->delete( array( 'Piecepdo.id' => $piecepdo_id ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'controller' => 'piecespdos', 'action' => 'index' ) );
			}
		}
	}

?>