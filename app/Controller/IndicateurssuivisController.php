<?php
	/**
	 * Code source de la classe IndicateurssuivisController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe IndicateurssuivisController ...
	 *
	 * @package app.Controller
	 */
	class IndicateurssuivisController extends AppController
	{
		public $name = 'Indicateurssuivis';

		public $helpers = array(
			'Xform',
			'Xhtml',
			'Default2',
			'Search',
			'Csv',
			'Allocataires',
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
		);

		public $uses = array(
			'Allocataire',
			'Dossier',
			'Option',
			'Structurereferente',
			'Referent',
			'Indicateursuivi',
			'Dossierep',
			'Foyer',
			'Personne',
			'Informationpe'
		);

		public $components = array(
			'Allocataires',
			'Gestionzonesgeos',
			'Search.Filtresdefaut' => array(
				'index',
				'search'
			),
			'Search.SearchPrg' => array(
				'actions' => array(
					'index',
					'search' => array( 'filter' => 'Search' ),
				)
			),
		);

		public $commeDroit = array(
			'search' => 'Indicateurssuivis:index',
			'exportcsv_search' => 'Indicateurssuivis:exportcsv',
		);

		protected function _setOptions() {
			$natpfsSocle = Configure::read( 'Detailcalculdroitrsa.natpf.socle' );
			$this->set( 'natpf', $this->Option->natpf( $natpfsSocle ) );
			$this->Gestionzonesgeos->setCantonsIfConfigured();
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
			$this->set( 'structs', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
			$this->set( 'referents', $this->Referent->referentsListe() );
			$this->set( 'options', (array)Hash::get( $this->Dossierep->enums(), 'Dossierep' ) );
			$this->set( 'etatpe', (array)Hash::get( $this->Informationpe->Historiqueetatpe->enums(), 'Historiqueetatpe' ) );
		}


		/**
		 * @deprecated
		 */
		public function index() {
			$this->_setOptions();
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			//debug($this->request->data);exit;
			if( !empty( $this->request->data ) ) {
				$this->paginate = $this->Indicateursuivi->search(
					$mesCodesInsee,
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data
				);
				$indicateurs = $this->paginate( 'Dossier' );

				foreach($indicateurs as $key => $value ) {
					//Conjoint :
					$bindPrestation = $this->Personne->hasOne['Prestation'];
					$this->Personne->unbindModelAll();
					$this->Personne->bindModel( array( 'hasOne' => array('Prestation' => $bindPrestation ) ) );
					$conjoint = $this->Personne->find('first', array(
						'fields' => array('Personne.qual','Personne.nom', 'Personne.prenom'),
						'conditions' => array(
							'Personne.foyer_id' => $value['Foyer']['id'],
							'Prestation.rolepers' => 'CJT'
						)
					));
					$indicateurs[$key]['Personne']['qualcjt'] = !empty($conjoint['Personne']['qual']) ? $conjoint['Personne']['qual'] : '';
					$indicateurs[$key]['Personne']['prenomcjt'] = !empty($conjoint['Personne']['prenom']) ? $conjoint['Personne']['prenom'] : '';
					$indicateurs[$key]['Personne']['nomcjt'] = !empty($conjoint['Personne']['nom']) ? $conjoint['Personne']['nom'] : '';
				}
				$this->set('indicateurs', $indicateurs);
			}

		}

		/**
		 * Moteur de recherche par suivi de l'allocataire
		 */
		public function search() {
			if( Hash::check( $this->request->data, 'Search' ) ) {
				$query = $this->Indicateursuivi->search58( $this->request->data['Search'] );

				$query = $this->Allocataires->completeSearchQuery( $query );

				$this->Personne->forceVirtualFields = true;
				$results = $this->Allocataires->paginate( $query );

				$this->set( compact( 'results' ) );
			}

			$options = $this->Allocataires->options();
			$options = Hash::merge( $options, $this->Indicateursuivi->options( array( 'allocataire' => false ) ) );
			$this->set( compact( 'options' ) );
		}

		/**
		 * Export CSV des résultats du moteur de recherche par suivi de l'allocataire
		 */
		public function exportcsv_search() {
			$search = (array)Hash::get( (array)Hash::expand( $this->request->params['named'], '__' ), 'Search' );

			$query = $this->Indicateursuivi->search58( $search );

			$query = $this->Allocataires->completeSearchQuery( $query, array( 'limit' => false ) );
			$query = $this->Components->load( 'Search.SearchPaginator' )->setPaginationOrder( $query );

			$this->Personne->forceVirtualFields = true;
			$results = $this->Personne->find( 'all', $query );

			$options = $this->Allocataires->options();
			$options = Hash::merge( $options, $this->Indicateursuivi->options( array( 'allocataire' => false ) ) );
			$this->set( compact( 'options' ) );

			$this->set( compact( 'results', 'options' ) );
			$this->layout = null;
		}


		public function exportcsv() {
			$this->_setOptions();
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Indicateursuivi->search(
				$mesCodesInsee,
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				Hash::expand( $this->request->params['named'], '__' )
			);
			unset( $querydata['limit'] );
			$querydata = $this->_qdAddFilters( $querydata );

			$indicateurs = $this->Dossier->find( 'all', $querydata );
			foreach($indicateurs as $key => $value ) {
				//Conjoint :
				$conjoint = $this->Personne->find('first', array(
					'fields' => array('Personne.qual', 'Personne.nom', 'Personne.prenom'),
					'conditions' => array(
						'Personne.foyer_id' => $value['Foyer']['id'],
						'Prestation.rolepers' => 'CJT'
					),
					'contain' => array(
						'Prestation'
					)
				));

				$indicateurs[$key]['Personne']['qualcjt'] = !empty($conjoint['Personne']['qual']) ? $conjoint['Personne']['qual'] : '';
				$indicateurs[$key]['Personne']['prenomcjt'] = !empty($conjoint['Personne']['prenom']) ? $conjoint['Personne']['prenom'] : '';
				$indicateurs[$key]['Personne']['nomcjt'] = !empty($conjoint['Personne']['nom']) ? $conjoint['Personne']['nom'] : '';
			}

			$this->layout = ''; // FIXME ?
			$this->set( compact( 'headers', 'indicateurs' ) );
		}
	}
?>