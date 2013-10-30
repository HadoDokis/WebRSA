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
			'Cohortes',
			'Search.Filtresdefaut' => array( 'index' ),
			'Search.Prg' => array(
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
		public $uses = array( 'Cohorted2pdv93', 'Dossier', 'Tableausuivipdv93' );

		/**
		 * Moteur de recherche des questionnaires D2
		 */
		public function index() {
			if( !empty( $this->request->data ) ) {
				// Traitement des données renvoyées -> TODO

				// Traitement du formulaire de recherche
				$querydata = array(
					'Dossier' => $this->Cohorted2pdv93->search(
						(array)$this->Session->read( 'Auth.Zonegeographique' ),
						$this->Session->read( 'Auth.User.filtre_zone_geo' ),
						$this->request->data['Search'],
						null
					)
				);

				$this->paginate = $querydata;
				$results = $this->paginate(
					$this->Dossier,
					array(),
					array(),
					!Hash::get( $this->request->data, 'Search.Pagination.nombre_total' )
				);

				$this->set( compact( 'results' ) );
			}

			// Options à envoyer à la vue
			$years = array_reverse( array_range( 2009, date( 'Y' ) ) );
			$options = array(
				'Questionnaired1' => array(
					'annee' => array_combine( $years, $years )
				),
				'Rendezvous' => array(
					'structurereferente_id' => $this->Tableausuivipdv93->listePdvs()
				),
			);
			$this->set( compact( 'options' ) );
		}
	}
?>
