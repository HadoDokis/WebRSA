<?php
	/**
	 * Code source de la classe SuivisaidesaprestypesaidesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe SuivisaidesaprestypesaidesController ...
	 *
	 * @package app.Controller
	 */
	class SuivisaidesaprestypesaidesController extends AppController
	{

		public $name = 'Suivisaidesaprestypesaides';
		public $uses = array( 'Suiviaideapretypeaide', 'Suiviaideapre', 'Option', 'Apre' );
		public $helpers = array( 'Xform' );

		public $commeDroit = array(
			'add' => 'Suivisaidesaprestypesaides:edit'
		);

		public function beforeFilter() {
			$return = parent::beforeFilter();
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'personnessuivis', $this->Suiviaideapre->find( 'list' ) );
			$this->set( 'aidesApres', $this->Apre->aidesApre );
			$this->set( 'natureAidesApres', $this->Option->natureAidesApres() );

			return $return;
		}

		public function index() {
			// Retour à la liste en cas d'annulation
			if( isset( $this->request->data['Cancel'] ) ) {
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
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->redirect( array( 'action' => 'index' ) );
			}

			if( !empty( $this->request->data ) ) {
				$this->request->data = Set::extract( $this->request->data, '/Suiviaideapretypeaide' );
				if( $this->Suiviaideapretypeaide->saveAll( $this->request->data ) ) {
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'suivisaidesaprestypesaides', 'action' => 'index' ) );
				}
			}
			else {
				$suiviaideapretypeaide = $this->Suiviaideapretypeaide->find( 'all' );
				$this->request->data = array( 'Suiviaideapretypeaide' => Set::classicExtract( $suiviaideapretypeaide, '{n}.Suiviaideapretypeaide' ) );
			}

			$this->render( 'add_edit' );
		}
	}

?>