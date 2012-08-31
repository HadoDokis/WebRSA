<?php
	class CriteresentretiensController extends AppController
	{
		public $name = 'Criteresentretiens';

		public $uses = array( 'Critereentretien', 'Entretien' );
		public $helpers = array( 'Csv', 'Ajax', 'Default2', 'Search' );
		public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );

		/**
		 *
		 */
		public function _setOptions() {
			$this->set( 'options',  $this->Entretien->allEnumLists() );
			$this->set( 'structs', $this->Entretien->Structurereferente->listOptions() );
			$this->set( 'referents', $this->Entretien->Referent->listOptions() );
		}

		/**
		 *
		 */
		public function index() {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->set( 'cantons', ClassRegistry::init( 'Canton' )->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			if( !empty( $this->data ) ) {

				if( !empty( $this->data['Entretien']['referent_id'] )) {
					$referentId = suffix( $this->data['Entretien']['referent_id'] );
					$this->data['Entretien']['referent_id'] = $referentId;
				}

				$paginate = $this->Critereentretien->search(
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->data,
					false
				);

				$paginate = $this->_qdAddFilters( $paginate );
				$paginate['limit'] = 10;

				$this->paginate = $paginate;
				$entretiens = $this->paginate( 'Entretien' );

				$this->set( 'entretiens', $entretiens );
			}

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', ClassRegistry::init( 'Zonegeographique' )->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', ClassRegistry::init( 'Adresse' )->listeCodesInsee() );
			}
			$this->_setOptions();
		}

		/**
		 * Export du tableau en CSV
		 */
		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Critereentretien->search(
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Xset::bump( $this->params['named'], '__' ),
				false
			);

			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$entretiens = $this->Entretien->find( 'all', $querydata );

			$this->layout = '';
			$this->set( compact( 'entretiens' ) );
			$this->_setOptions();
		}
	}
?>