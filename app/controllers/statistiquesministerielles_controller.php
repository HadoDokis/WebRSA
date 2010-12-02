<?php
	class StatistiquesministeriellesController extends AppController
	{
		var $name = 'Statistiquesministerielles';
		var $uses = array( 'Serviceinstructeur', 'Statistiquesministerielle');


		public function beforeFilter() {
			parent::beforeFilter();
			$typeservice = $this->Serviceinstructeur->find( 'list', array( 'fields' => array( 'lib_service' ) ) );
			$this->set( 'typeservice', $typeservice );
		}


		/**
		* @return unknown_type
		*/
		function _filtre()
		{
			$inputs = array(
				'localisation'	=> null,
				'service'		=> null,
				'annee'			=> null
			);
			if( !empty($this->data['Statistiquesministerielle']['localisation'])) {
				$inputs['localisation'] = $this->data['Statistiquesministerielle']['localisation'];
			}
			if( !empty($this->data['Statistiquesministerielle']['service'])) {
				$inputs['service'] = $this->data['Statistiquesministerielle']['service'];
			}
			if( !empty($this->data['Statistiquesministerielle']['date']['year'])) {
				$inputs['annee'] = $this->data['Statistiquesministerielle']['date']['year'];
			}
			return $inputs;
		}


		function indicateursOrientations()
		{
			if( !empty( $this->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursOrientations($args);
				$this->set( compact( 'results' ) );
			}
		}

		/**
		* Localité /  Service instructeur / Année
		*/
		function indicateursOrganismes()
		{
			if( !empty( $this->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursOrganismes($args);
				$this->set( compact( 'results' ) );
			}
		}

		function indicateursDelais()
		{
			if( !empty( $this->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursDelais($args);
				$this->set( compact( 'results' ) );
			}
		}

		function indicateursReorientations()
		{
			if( !empty( $this->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursReorientations($args);
				$this->set( compact( 'results' ) );
			}
		}

		function indicateursMotifsReorientation()
		{
			if( !empty( $this->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursMotifsReorientations($args);
				$this->set( compact( 'results' ) );
			}
		}

		function indicateursCaracteristiquesContrats()
		{
			if( !empty( $this->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursCaracteristiquesContrats($args);
				$this->set( compact( 'results' ) );
			}
		}

		function indicateursNatureContrats()
		{
				if( !empty( $this->data ) ) {
				$args = $this->_filtre();
				$results = $this->Statistiquesministerielle->indicateursNatureContrats($args);
				$this->set( compact( 'results' ) );
			}
		}
	}
?>