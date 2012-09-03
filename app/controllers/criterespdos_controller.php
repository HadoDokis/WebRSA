<?php
	App::import('Sanitize');

	class CriterespdosController extends AppController
	{
		public $name = 'Criterespdos';
		public $uses = array( 'Canton', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Typenotifpdo', 'Typepdo', 'Option', 'Situationpdo', 'Criterepdo', 'Propopdo', 'Referent', 'Decisionpdo', 'Originepdo', 'Statutpdo', 'Statutdecisionpdo', 'Cohortepdo', 'Situationdossierrsa', 'Zonegeographique' );
		public $aucunDroit = array( 'exportcsv' );

		public $helpers = array( 'Csv', 'Ajax','Search', 'Default2' );

		public $components = array( 'Gestionzonesgeos', 'Prg' => array( 'actions' => array( 'index', 'nouvelles' ) ) );

		/**
		*
		*/

//		public function __construct() {
//			$this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index', 'nouvelles' ) ) ) );
//			parent::__construct();
//		}

		/**
		*
		*/

		protected function _setOptions() {

			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'pieecpres', $this->Option->pieecpres() );
			$this->set( 'commission', $this->Option->commission() );
			$this->set( 'motidempdo', $this->Option->motidempdo() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'motifpdo', $this->Option->motifpdo() );
			$this->set( 'typenotifpdo', $this->Typenotifpdo->find( 'list' ) );
			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'typepdo', $this->Typepdo->find( 'list' ) );
			$this->set( 'decisionpdo', $this->Decisionpdo->find( 'list' ) );
			$this->set( 'originepdo', $this->Originepdo->find( 'list' ) );

			$this->set( 'statutlist', $this->Statutpdo->find( 'list' ) );
			$this->set( 'situationlist', $this->Situationpdo->find( 'list' ) );
			$this->set( 'statutdecisionlist', $this->Statutdecisionpdo->find( 'list' ) );

			$this->set( 'rolepers', $this->Option->rolepers() );

			$this->set( 'gestionnaire', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						)
					)
				)
			);

			$options = $this->Propopdo->allEnumLists();
			$options = Set::insert( $options, 'Suiviinstruction.typeserins', $this->Option->typeserins() );
			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		public function index( ) {
			$this->Gestionzonesgeos->setCantonsIfConfigured();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->data ) ) {
				$this->Dossier->begin(); // Pour les jetons

				$paginate = $this->Criterepdo->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );
				$paginate['limit'] = 10;
				$paginate = $this->_qdAddFilters( $paginate );

				$this->paginate = $paginate;
				$criterespdos = $this->paginate( 'Propopdo' );
				$this->Dossier->commit();

				$this->set( 'criterespdos', $criterespdos );
			}

			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
			$this->_setOptions();
		}

		/**
		*
		*/

		public function nouvelles() {

			$this->Gestionzonesgeos->setCantonsIfConfigured();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$params = $this->data;
			if( !empty( $params ) ) {

				$this->Dossier->begin(); // Pour les jetons
					$querydata = $this->Criterepdo->listeDossierPDO( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );

					$querydata['limit'] = 10;
					$querydata = $this->_qdAddFilters( $querydata );
					$this->paginate = array( 'Personne' => $querydata );

					$criterespdos = $this->paginate( 'Personne' );

					$this->Dossier->commit();
					$this->set( 'criterespdos', $criterespdos );
			}
			

			$this->_setOptions();
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
			// Précise les options des états de dossiers :
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa( $this->Situationdossierrsa->etatAttente()) );

			$this->render( $this->action, null, 'liste' );
		}

		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Criterepdo->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), Xset::bump( $this->params['named'], '__' ), $this->Jetons->ids() );
			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$pdos = $this->Propopdo->find( 'all', $querydata );

			$this->_setOptions();
			$this->layout = ''; // FIXME ?
			$this->set( compact( 'pdos' ) );
		}
	}
?>