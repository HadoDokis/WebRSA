<?php
	class CriteresentretiensController extends AppController
	{
		public $name = 'Criteresentretiens';

		public $uses = array(
			'Critereentretien',
			'Entretien'
		);

		public $helpers = array( 'Csv', 'Ajax', 'Default2' );

		public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );

//         public $paginate = array( 'limit' => 20 );


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
				$this->Critereentretien->begin(); // Pour les jetons

				if( !empty( $this->data['Entretien']['referent_id'] )) {
					$referentId = suffix( $this->data['Entretien']['referent_id'] );
					$this->data['Entretien']['referent_id'] = $referentId;
				}

				$this->paginate = $this->Critereentretien->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data, $this->Jetons->ids() );

				$this->paginate = $this->_qdAddFilters( $this->paginate );

				$this->paginate['limit'] = 10;
				$entretiens = $this->paginate( 'Entretien' );

				$this->Critereentretien->commit();

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

			$querydata = $this->Critereentretien->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ), $this->Jetons->ids() );
			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$entretiens = $this->Entretien->find( 'all', $querydata );


			$this->layout = ''; // FIXME ?
			$this->set( compact( 'entretiens' ) );
			$this->_setOptions();
		}
	}
?>