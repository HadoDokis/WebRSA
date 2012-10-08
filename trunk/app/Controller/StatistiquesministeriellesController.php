<?php
	class StatistiquesministeriellesController extends AppController
	{
		public $name = 'Statistiquesministerielles';
		public $uses = array( 'Serviceinstructeur', 'Statistiquesministerielle');

		public function beforeFilter() {
			parent::beforeFilter();
			$typeservice = $this->Serviceinstructeur->find( 'list', array( 'fields' => array( 'lib_service' ) ) );
			$this->set( 'typeservice', $typeservice );
		}

		/**
		* @return unknown_type
		*/
		protected function _filtre() {
			$inputs = array(
				'localisation'	=> null,
				'service'		=> null,
				'annee'			=> null
			);
			if( !empty($this->request->data['Statistiquesministerielle']['localisation'])) {
				$inputs['localisation'] = $this->request->data['Statistiquesministerielle']['localisation'];
			}
			if( !empty($this->request->data['Statistiquesministerielle']['service'])) {
				$inputs['service'] = $this->request->data['Statistiquesministerielle']['service'];
			}
			if( !empty($this->request->data['Statistiquesministerielle']['date']['year'])) {
				$inputs['annee'] = $this->request->data['Statistiquesministerielle']['date']['year'];
			}
			return $inputs;
		}


		public function indicateursOrientations() {
			if( !empty( $this->request->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursOrientations($args);
				$this->set( compact( 'results' ) );
			}
		}

		/**
		* Localité /  Service instructeur / Année
		*/
		public function indicateursOrganismes() {
			if( !empty( $this->request->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursOrganismes($args);
				$this->set( compact( 'results' ) );
			}
		}

		public function indicateursDelais() {
			if( !empty( $this->request->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursDelais($args);
				$this->set( compact( 'results' ) );
			}
		}

		public function indicateursReorientations() {
			if( !empty( $this->request->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursReorientations($args);
				$this->set( compact( 'results' ) );
			}
		}

		public function indicateursMotifsReorientation() {
			if( !empty( $this->request->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursMotifsReorientations($args);
				$this->set( compact( 'results' ) );
			}
		}

		public function indicateursCaracteristiquesContrats() {
			if( !empty( $this->request->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursCaracteristiquesContrats($args);
				$this->set( compact( 'results' ) );
			}
		}

		public function indicateursNatureContrats() {
				if( !empty( $this->request->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursNatureContrats($args);
				$this->set( compact( 'results' ) );
			}
		}
	}
?>