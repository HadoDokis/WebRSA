<?php
	class ParametragesController extends AppController
	{

		public $name = 'Parametrages';
		public $uses = array( 'Dossier', 'Structurereferente', 'Zonegeographique' );
		
		public $commeDroit = array(
			'view' => 'Parametrages:index'
		);

		public function index() {

		}

		public function view( $param = null ) {
			$zone = $this->Zonegeographique->find(
				'first',
				array(
					'conditions' => array(
					)
				)
			);
			$this->set('zone', $zone);
		}
	}

?>