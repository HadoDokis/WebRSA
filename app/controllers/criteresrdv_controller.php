<?php
	App::import('Sanitize');

	class CriteresrdvController extends AppController
	{
		public $name = 'Criteresrdv';
		public $uses = array( 'Canton', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Rendezvous', 'Critererdv', 'Structurereferente', 'Typeorient', 'Option', 'Typerdv', 'Referent', 'Permanence', 'Statutrdv', 'Zonegeographique' );
		public $aucunDroit = array( 'constReq', 'ajaxreferent', 'ajaxperm' );

		public $helpers = array( 'Csv', 'Ajax', 'Paginator' );

		public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );


		/**
		*
		*/


		public function beforeFilter() {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '128M');
			parent::beforeFilter();
		}

		/**
		*
		*/

//		public function __construct() {
//			$this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
//			parent::__construct();
//		}

		/**
		*
		*/

		protected function _setOptions() {
			$this->set( 'statutrdv', $this->Statutrdv->find( 'list' ) );
			$this->set( 'struct', $this->Structurereferente->listOptions() );
			$typerdv = $this->Typerdv->find( 'list', array( 'fields' => array( 'id', 'libelle' ) ) );
			$this->set( 'typerdv', $typerdv );
			$this->set( 'permanences', $this->Permanence->find( 'list' ) );
			$this->set( 'referents', $this->Referent->find( 'list' ) );

			$this->set( 'natpf', $this->Option->natpf() );
			$this->set( 'rolepers', $this->Option->rolepers() );
		}

		/**
		*
		*/

		public function index() {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->data ) ) {
				$this->Dossier->begin(); // Pour les jetons

				$querydata = $this->Critererdv->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
				$querydata['limit'] = 10;
				$querydata = $this->_qdAddFilters( $querydata );
				$this->paginate['Rendezvous'] = $querydata;

				$rdvs = $this->paginate( 'Rendezvous' );

				$this->Dossier->commit();
				$this->set( 'rdvs', $rdvs );
			}

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}

			$this->_setOptions();
		}

		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Critererdv->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), Xset::bump( $this->params['named'], '__' ), $this->Jetons->ids() );
			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$rdvs = $this->Rendezvous->find( 'all', $querydata );

			// Population du select référents liés aux structures
			$structurereferente_id = Set::classicExtract( $this->data, 'Critererdv.structurereferente_id' );
			$referents = $this->Referent->referentsListe( $structurereferente_id );
			$this->set( 'referents', $referents );

			$this->layout = ''; // FIXME ?
			$this->_setOptions();
			$this->set( compact( 'rdvs' ) );
		}
	}
?>