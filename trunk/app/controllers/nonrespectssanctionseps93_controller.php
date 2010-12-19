<?php
	/**
	* FIXME
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class Nonrespectssanctionseps93Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2', 'Csv' );

		public $components = array( 'Prg' => array( 'actions' => array( 'index' ) ) );

		/**
		* FIXME: evite les droits
		*/

		public function beforeFilter() {
			$this->Auth->allow( '*' );
		}

		/**
		*
		*/

		protected function _setOptions() {
			$options = Set::merge(
				$this->Nonrespectsanctionep93->enums(),
				$this->Nonrespectsanctionep93->Dossierep->enums()
			);
			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		protected function _queryData( $searchData ) {
			$searchMode = Set::classicExtract( $searchData, 'Nonrespectsanctionep93.mode' );

			$conditions = array( 'Dossierep.themeep' => 'nonrespectssanctionseps93' );

			if( $searchMode == 'traite' ) {
				$conditions[]['Dossierep.etapedossierep'] = 'traite';

				$searchDossierepSeanceepId = Set::classicExtract( $searchData, 'Dossierep.seanceep_id' );
				if( !empty( $searchDossierepSeanceepId ) ) {
					$conditions[]['Dossierep.seanceep_id'] = $searchDossierepSeanceepId;
				}
			}
			else {
				$conditions[]['Dossierep.etapedossierep <>'] = 'traite';
			}

			return array(
				'contain' => array(
					'Dossierep' => array(
						'Personne' => array(
							'Foyer' => array(
								'Dossier',
								'Adressefoyer' => array(
									'conditions' => array( 'Adressefoyer.rgadr' => '01' ),
									'Adresse'
								)
							)
						),
						'Seanceep'
					),
					'Orientstruct',
					'Contratinsertion',
				),
				'conditions' => $conditions,
				'order' => array( 'Nonrespectsanctionep93.created DESC' )
			);
		}

		/**
		*
		*/

		public function index() {
			$searchData = Set::classicExtract( $this->data, 'Search' );
			$searchMode = Set::classicExtract( $searchData, 'Nonrespectsanctionep93.mode' );

			if( !empty( $searchData ) ) {
				$queryData = $this->_queryData( $searchData );
				$queryData['limit'] = 10;

				$this->paginate = $queryData;

				$this->set( 'nonrespectssanctionseps93', $this->paginate( $this->Nonrespectsanctionep93 ) );
			}

			// INFO: containable ne fonctionne pas avec les find('list')
			$seanceseps = array();
			$tmpSeanceseps = $this->Nonrespectsanctionep93->Dossierep->Seanceep->find(
				'all',
				array(
					'fields' => array(
						'Seanceep.id',
						'Seanceep.dateseance',
						'Ep.name'
					),
					'contain' => array(
						'Ep'
					),
					'order' => array( 'Ep.name ASC', 'Seanceep.dateseance DESC' )
				)
			);

			if( !empty( $tmpSeanceseps ) ) {
				foreach( $tmpSeanceseps as $key => $seanceep ) {
					$seanceseps[$seanceep['Ep']['name']][$seanceep['Seanceep']['id']] = $seanceep['Seanceep']['dateseance'];
				}
			}

			$this->_setOptions();
			$options = Set::merge(
				array( 'Dossierep' => array( 'seanceep_id' => $seanceseps ) ),
				$this->viewVars['options']
			);
			$this->set( compact( 'options' ) );

			$view = implode( '_', Set::filter( array( 'index', $searchMode ) ) );
			$this->render( null, null, $view );
		}

		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$searchData = Set::classicExtract( Xset::bump( $this->params['named'], '__' ), 'Search' );
			$searchMode = Set::classicExtract( $searchData, 'Nonrespectsanctionep93.mode' );

			$dossiers = $this->Nonrespectsanctionep93->find( 'all', $this->_queryData( $searchData ) );

			$this->layout = ''; // FIXME ?
			$this->set( compact( 'headers', 'dossiers' ) );
			$this->_setOptions();
		}
	}
?>