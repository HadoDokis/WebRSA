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
		public $helpers = array( 'Default', 'Default2' );

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
			/*$options = $this->Saisineepreorientsr93->enums();
			$options['Saisineepreorientsr93']['typeorient_id'] = $this->Saisineepreorientsr93->Typeorient->listOptions();
			$options['Saisineepreorientsr93']['structurereferente_id'] = $this->Saisineepreorientsr93->Structurereferente->list1Options( array( 'orientation' => 'O' ) );
			$options['Saisineepreorientsr93']['motifreorient_id'] = $this->Saisineepreorientsr93->Motifreorient->find( 'list' );
			$this->set( compact( 'options' ) );*/
		}

		/**
		*
		*/

		public function index() {
			$searchData = $this->data;

			if( true || !empty( $searchData ) ) { // FIXME: moteur de recherche
				$conditions = array( 'Nonrespectsanctionep93.dossierep_id IS NOT NULL' );

				$this->paginate = array(
					'contain' => array(
						'Dossierep' => array(
							'Personne',
							'Seanceep'
						),
						'Orientstruct'
					),
					'conditions' => $conditions,
					'order' => array( 'Nonrespectsanctionep93.created DESC' ),
					'limit' => 10
				);

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

// 			$options = Set::merge(
// 				$this->Saisineepreorientsr93->Dossierep->enums(),
// 				$this->Saisineepreorientsr93->Nvsrepreorientsr93->enums(),
// 				array( 'Dossierep' => array( 'seanceep_id' => $seanceseps ) )
// 			);
// 			$this->set( compact( 'options' ) );

// 			$view = implode( '_', Set::filter( array( 'index', $searchMode ) ) );
// 			$this->render( null, null, $view );
		}
	}
?>