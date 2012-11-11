<?php	
	/**
	 * Code source de la classe CantonsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe CantonsController ...
	 *
	 * @package app.Controller
	 */
	class CantonsController extends AppController
	{

		public $name = 'Cantons';
		public $uses = array( 'Canton', 'Option' );
		public $helpers = array( 'Xform' );
		public $paginate = array(
			'limit' => 20,
			'recursive' => -1,
			'order' => array( 'canton ASC' )
		);
		public $commeDroit = array(
			'add' => 'Cantons:edit'
		);

		/**
		 * 	FIXME: docs
		 */
		public function beforeFilter() {
			$return = parent::beforeFilter();
			$this->set( 'typevoie', $this->Option->typevoie() );
			return $return;
		}

		/**
		 * 	FIXME: docs
		 */
		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'parametrages', 'action' => 'index' ) );
			}

			$this->paginate = array(
				'fields' => array(
					'Canton.id',
					'Canton.typevoie',
					'Canton.nomvoie',
					'Canton.locaadr',
					'Canton.codepos',
					'Canton.numcomptt',
					'Canton.canton',
					'Canton.zonegeographique_id',
					'Zonegeographique.libelle',
				)
			);
			$cantons = $this->paginate( $this->modelClass );
			$this->set( compact( 'cantons' ) );
		}

		/**
		 * 	FIXME: docs
		 */
		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * 	FIXME: docs
		 */
		public function edit() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		 * 	FIXME: docs
		 */
		protected function _add_edit( $id = null ) {
			// Retour à l'index en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			if( $this->action == 'edit' ) {
				$qd_canton = array(
					'conditions' => array(
						'Canton.id' => $id
					),
					'fields' => null,
					'order' => null,
					'recursive' => -1
				);
				$canton = $this->Canton->find( 'first', $qd_canton );
				$this->assert( !empty( $canton ), 'invalidParameter' );
			}

			if( !empty( $this->request->data ) ) {
				$this->Canton->create( $this->request->data );
				if( $this->Canton->save() ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'action' => 'index' ) );
				}
			}
			else if( $this->action == 'edit' ) {
				$this->request->data = $canton;
			}

			$this->set( 'zonesgeographiques', $this->Canton->Zonegeographique->find( 'list' ) );
			$this->set( 'typesvoies', $this->Option->typevoie() );
			$this->render( 'add_edit' );
		}

		/**
		 * 	FIXME: docs
		 */
		public function delete( $id = null ) {
			$qd_canton = array(
				'conditions' => array(
					'Canton.id' => $id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$canton = $this->Canton->find( 'first', $qd_canton );
			$this->assert( !empty( $canton ), 'invalidParameter' );

			if( $this->Canton->delete( Set::classicExtract( $canton, 'Canton.id' ) ) ) {
				$this->Session->setFlash( 'Suppression effectuée', 'flash/success' );
				$this->redirect( array( 'action' => 'index' ) );
			}
		}

	}
?>