<?php
	App::import('Sanitize');

	class RegressionsorientationsepsController extends AppController {

		public $helpers = array( 'Default2', 'Xpaginator' );

		public $uses = array( 'Regressionorientationep58' );

		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			$this->modelClass = 'Regressionorientationep'.Configure::read( 'Cg.departement' );
			parent::beforeFilter();
		}

		/**
		*
		*/

		public function __construct() {
			$this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
			parent::__construct();
		}

		/**
		 *
		 */
		public function delete( $regressionorientationep_id ) {
			$this->{$this->modelClass}->begin();
			$success = true;
			$regressionorientationep = $this->{$this->modelClass}->find( 'first', array( 'condition' => array( $this->modelClass.'.id' => $regressionorientationep_id ), 'contain' => false ) );
			$success = $this->{$this->modelClass}->delete( $regressionorientationep_id ) && $success;
			if( !empty( $regressionorientationep[$this->modelClass]['dossierep_id'] ) ) {
				$success = $this->{$this->modelClass}->Dossierep->delete( $regressionorientationep[$this->modelClass]['dossierep_id'] ) && $success;
			}
			$this->_setFlashResult( 'Delete', $success );
			if ( $success ) {
				$this->{$this->modelClass}->commit();
			}
			else {
				$this->{$this->modelClass}->rollback();
			}
			$this->redirect( Router::url( $this->referer(), true ) );
		}
	}

?>