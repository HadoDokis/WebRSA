<?php
	App::import('Sanitize');

	class NonorientationsprosController extends AppController {
	
		public $helpers = array( 'Default2', 'Xpaginator' );
		
		public $uses = array( 'Nonorientationpro58',/* 'Nonorientationpro66',*/ 'Nonorientationpro93' );
		
		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			$this->modelClass = 'Nonorientationpro'.Configure::read( 'Cg.departement' );
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
				if ( isset( $this->data['Nonorientationpro'] ) ) {
					$this->{$this->modelClass}->begin();
					$success = $this->{$this->modelClass}->saveCohorte($this->data);
					$this->_setFlashResult( 'Save', $success );
					if ( $success ) {
						$this->{$this->modelClass}->commit();
						$this->redirect( array( 'action' => 'index' ) );
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
	}
	
?>