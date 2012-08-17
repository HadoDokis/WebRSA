<?php

	App::import('Sanitize');

	class CriterescuisController extends AppController
	{
		public $name = 'Criterescuis';
		public $uses = array( 'Canton', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Structurereferente', 'Contratinsertion', 'Option', 'Serviceinstructeur', 'Criterecui', 'Cui', 'Referent', 'Zonegeographique' );
		public $aucunDroit = array( 'exportcsv' );

		public $helpers = array( 'Csv', 'Ajax' );

		public $components = array(  'Prg' => array( 'actions' => array( 'index' ) ) );

		/**
		*
		*/

//		public function __construct() {
//			$this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
//			parent::__construct();
//		}

		protected function _setOptions(){
			$options = array();
			$struct = $this->Structurereferente->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
			$this->set( 'struct', $struct );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$qual = $this->Option->qual();
			$this->set( 'qual', $qual );
			$options = $this->Cui->allEnumLists();
			$this->set( 'options', $options );
		}

		public function index() {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$params = $this->data;
			if( !empty( $params ) ) {

				$this->Dossier->begin(); // Pour les jetons

				$paginate = $this->Criterecui->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
				$paginate['limit'] = 10;
				$paginate = $this->_qdAddFilters( $paginate );

				$this->paginate = $paginate;
				$criterescuis = $this->paginate( 'Cui' );

				$this->Dossier->commit();

				$this->set( 'criterescuis', $criterescuis );
			}
			$this->_setOptions();
			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}
		}

		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Criterecui->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), Xset::bump( $this->params['named'], '__' ), $this->Jetons->ids() );
			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$cuis = $this->Cui->find( 'all', $querydata );

			$this->_setOptions();
			$this->layout = ''; // FIXME ?
			$this->set( compact( 'cuis' ) );
		}
	}
?>