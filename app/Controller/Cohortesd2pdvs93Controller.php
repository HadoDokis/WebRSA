<?php
	/**
	 * Code source de la classe Cohortesd2pdvs93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Cohortesd2pdvs93Controller ...
	 *
	 * @package app.Controller
	 */
	class Cohortesd2pdvs93Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Cohortesd2pdvs93';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Cohortes' => array( 'index' ),
			'Gestionzonesgeos',
			'InsertionsAllocataires',
			'Search.Filtresdefaut' => array( 'index' ),
			'Search.SearchPrg' => array(
				'actions' => array(
					'index' => array( 'filter' => 'Search' ),
				)
			),
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Cake1xLegacy.Ajax',
			'Default3' => array(
				'className' => 'Default.DefaultDefault'
			),
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Cohorted2pdv93', 'Dossier', 'Tableausuivipdv93', 'Option', 'Personne' );

		/**
		 * Moteur de recherche des questionnaires D2
		 */
		public function index() {
			if( !empty( $this->request->data ) ) {
				// Traitement du formulaire de recherche
				$querydata = $this->Cohorted2pdv93->search( $this->request->data['Search'] );

				$querydata = $this->Gestionzonesgeos->qdConditions( $querydata );
				$querydata = $this->Cohortes->qdConditions( $querydata );

				$this->paginate = array( 'Personne' => $querydata );
				$results = $this->paginate(
					$this->Personne,
					array(),
					array(),
					!Hash::get( $this->request->data, 'Search.Pagination.nombre_total' )
				);

				$this->Cohortes->get( Hash::extract( $results, '{n}.Dossier.id' ) );

				$this->set( compact( 'results' ) );
			}

			// Options à envoyer à la vue
			$years = array_reverse( array_range( 2009, date( 'Y' ) ) );
			$options = array(
				'Calculdroitrsa' => array(
					'toppersdrodevorsa' => $this->Option->toppersdrodevorsa()
				),
				'Questionnaired1' => array(
					'annee' => array_combine( $years, $years )
				),
				'Rendezvous' => array(
					'structurereferente_id' => $this->Tableausuivipdv93->listePdvs()
				),
				'cantons' => $this->Gestionzonesgeos->listeCantons(),
				'mesCodesInsee' => $this->Gestionzonesgeos->listeCodesInsee(),
				'etatdosrsa' => $this->Option->etatdosrsa(),
			);
			$this->set( compact( 'options' ) );

			$this->set( 'structuresreferentesparcours', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) ) );
			$this->set( 'referentsparcours', $this->InsertionsAllocataires->referents( array( 'prefix' => true ) ) );

			$this->set( 'isAjax', $this->request->is( 'ajax' ) );

			if( $this->request->is( 'ajax' ) ) {
				$this->layout = null;
			}
		}
	}
?>