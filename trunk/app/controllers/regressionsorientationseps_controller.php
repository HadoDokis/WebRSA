<?php
	App::import('Sanitize');

	class RegressionsorientationsepsController extends AppController {

		public $helpers = array( 'Default2', 'Xpaginator' );

		public $uses = array( 'Regressionorientationep58'/*, 'Regressionorientationep66', 'Regressionorientationep93'*/ );

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

		public function index() {
			$cohorte = array();
			if ( !empty( $this->data ) ) {
				if ( isset( $this->data['Regressionsorientationseps'] ) ) {
					$this->{$this->modelClass}->begin();
					$success = $this->{$this->modelClass}->saveCohorte( $this->data );
					$this->_setFlashResult( 'Save', $success );
					if ( $success ) {
						$this->{$this->modelClass}->commit();
						$this->redirect( Set::merge( array( 'action' => 'index' ), Set::flatten( array( 'Filtre' => $this->data['Filtre'] ), '__' ) ) );
					}
					else {
						$this->{$this->modelClass}->rollback();
					}
				}

				$this->paginate = $this->{$this->modelClass}->searchNonReoriente($this->data);
				$this->paginate['limit'] = 10;
				$cohorte = $this->paginate( $this->{$this->modelClass}->Orientstruct );
			}
			$this->set( 'nbmoisnonreorientation', array( 0 => 'Aujourd\'hui', 6 => '6 mois', 12 => '12 mois', 24 => '24 mois' ) );
			$this->set( compact( 'cohorte' ) );
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