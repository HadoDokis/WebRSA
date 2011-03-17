<?php
	App::import('Sanitize');

	class CriteresrdvController extends AppController
	{
		public $name = 'Criteresrdv';
		public $uses = array( 'Canton', 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Rendezvous', 'Critererdv', 'Structurereferente', 'Typeorient', 'Option', 'Typerdv', 'Referent', 'Permanence', 'Statutrdv', 'Zonegeographique' );
		public $aucunDroit = array( 'constReq', 'ajaxreferent', 'ajaxperm' );

		public $helpers = array( 'Csv', 'Ajax', 'Paginator' );

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

		public function __construct() {
			$this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
			parent::__construct();
		}

		/**
		*
		*/

		protected function _setOptions() {
			$this->set( 'statutrdv', $this->Statutrdv->find( 'list' ) );
			$this->set( 'struct', $this->Structurereferente->listOptions() );
//             $this->set( 'sr', $this->Structurereferente->find( 'list', array( 'recursive' => 1 ) ) );
			$typerdv = $this->Typerdv->find( 'list', array( 'fields' => array( 'id', 'libelle' ) ) );
			$this->set( 'typerdv', $typerdv );
			$this->set( 'permanences', $this->Permanence->find( 'list' ) );
			$this->set( 'referents', $this->Referent->find( 'list' ) );

			$this->set( 'natpf', $this->Option->natpf() );
		}

		/**
		*
		*/
/*
		public function beforeFilter() {
			$return = parent::beforeFilter();
			$this->set( 'statutrdv', $this->Statutrdv->find( 'list' ) );
			$this->set( 'struct', $this->Structurereferente->listOptions() );
			$this->set( 'sr', $this->Structurereferente->find( 'list', array( 'recursive' => 1 ) ) );
			$typerdv = $this->Typerdv->find( 'list', array( 'fields' => array( 'id', 'libelle' ) ) );
			$this->set( 'typerdv', $typerdv );
			$this->set( 'permanences', $this->Permanence->find( 'list' ) );
			$this->set( 'natpf', $this->Option->natpf() );

		}*/

		/**
		* Ajax pour lien référent - structure référente
		*/
/*
		protected function _selectReferents( $structurereferente_id ) {
			$conditions = array();

			if( !empty( $structurereferente_id ) ) {
				$conditions['Referent.structurereferente_id'] = $structurereferente_id;
			}

			$referents = $this->Referent->find(
				'all',
				array(
					'conditions' => $conditions,
					'recursive' => -1
				)
			);
			return $referents;

		}*/

		/**
		*
		*/
/*
		public function ajaxreferent() { // FIXME
			Configure::write( 'debug', 0 );
			$referents = $this->_selectReferents( Set::classicExtract( $this->data, 'Critererdv.structurereferente_id' ) );
			$options = array( '<option value=""></option>' );
			foreach( $referents as $referent ) {
				$options[] = '<option value="'.$referent['Referent']['id'].'">'.$referent['Referent']['nom'].' '.$referent['Referent']['prenom'].'</option>';
			} ///FIXME: à mettre dans la vue
			echo implode( '', $options );
			$this->render( null, 'ajax' );
		}*/

		/**
		* Ajax pour la permanence liée à la structure référente
		*/
//         protected function _selectPermanences( $structurereferente_id ) {
//             $permanences = $this->Rendezvous->Structurereferente->Permanence->find(
//                 'all',
//                 array(
//                     'conditions' => array(
//                         'Permanence.structurereferente_id' => $structurereferente_id
//                     ),
//                     'recursive' => -1
//                 )
//             );
//
//             return $permanences;
//
//         }
//
//         public function ajaxperm() { // FIXME
//             Configure::write( 'debug', 0 );
//             $permanences = $this->_selectPermanences( Set::classicExtract( $this->data, 'Critererdv.structurereferente_id' ) );
//
//             $options = array( '<option value=""></option>' );
//             foreach( $permanences as $permanence ) {
//                 $options[] = '<option value="'.$permanence['Permanence']['id'].'">'.$permanence['Permanence']['libpermanence'].'</option>';
//             }
//             echo implode( '', $options );
//             $this->render( null, 'ajax' );
//         }

		/**
		*
		*/

		public function index() {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', $this->Canton->selectList() );
			}
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

			if( !empty( $this->data ) ) {
				$this->Dossier->begin(); // Pour les jetons

				$querydata = $this->Critererdv->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );
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

			// Population du select référents liés aux structures
//             $structurereferente_id = Set::classicExtract( $this->data, 'Critererdv.structurereferente_id' );
//             $referents = $this->Referent->referentsListe( $structurereferente_id );
			$this->_setOptions();
//             $this->set( 'referents', $referents );
		}

		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

			$querydata = $this->Critererdv->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
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
