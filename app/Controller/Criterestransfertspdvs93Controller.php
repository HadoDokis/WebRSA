<?php
	/**
	 * Code source de la classe Criterestransfertspdvs93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * Classe Criterestransfertspdvs93Controller.
	 *
	 * @package app.Controller
	 */
	class Criterestransfertspdvs93Controller extends AppController
	{
		/**
		 * Nom
		 *
		 * @var string
		 */
		public $name = 'Criterestransfertspdvs93';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Search.Filtresdefaut' => array(
				'index',
			),
			'Gedooo.Gedooo',
			'Gestionzonesgeos',
			'InsertionsAllocataires',
			'Search.SearchPrg' => array(
				'actions' => array(
					'index' => array(
						'filter' => 'Search'
					),
				)
			),
			'Workflowscers93'
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array();

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Criteretransfertpdv93', 'Dossier', 'Option' );

		/**
		 * Permet de limiter les résultats de la recherche à ceux dont l'adresse
		 * de rang 02 uniquement est sur une des zones géographiques couverte par
		 * la structure référente de l'utilisateur connecté lorsque celui-ci est
		 * un externe (CG 93).
		 *
		 * @param array $query
		 * @return array
		 */
		protected function _completeSearchQuery( $query ) {
			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$type = $this->Session->read( 'Auth.User.type' );

				if( stristr( $type, 'externe_' ) !== false ) {
					$query['conditions']['Adressefoyer.rgadr'] = '02';
				}
			}

			return $query;
		}

		/**
		 * Pagination sur les <élément>s de la table.
		 *
		 * @return void
		 */
		public function index() {
			if( !empty( $this->request->data ) ) {
				$querydata = array(
					'Dossier' => $this->Criteretransfertpdv93->search(
						(array)$this->Session->read( 'Auth.Zonegeographique' ),
						$this->Session->read( 'Auth.User.filtre_zone_geo' ),
						$this->request->data['Search'],
						null
					)
				);

				$querydata['Dossier'] = $this->_completeSearchQuery( $querydata['Dossier'] );

				$this->paginate = $querydata;
				$results = $this->paginate(
					$this->Dossier,
					array(),
					array(),
					!Set::classicExtract( $this->request->data, 'Search.Pagination.nombre_total' )
				);

				$this->set( compact( 'results' ) );
			}

			$options = array(
				'cantons' => $this->Gestionzonesgeos->listeCantons(),
				'etatdosrsa' => $this->Option->etatdosrsa(),
				'mesCodesInsee' => $this->Gestionzonesgeos->listeCodesInsee(),
				'toppersdrodevorsa' => $this->Option->toppersdrodevorsa( true ),
				'rolepers' => $this->Option->rolepers(),
				'qual' => $this->Option->qual(),
				'structuresreferentes' => $this->Dossier->Foyer->Personne->Orientstruct->Structurereferente->listOptions(),
				'typesorients' => $this->Dossier->Foyer->Personne->Orientstruct->Typeorient->listOptions(),
			);
			$this->set( compact( 'options' ) );

			$this->set( 'structuresreferentesparcours', $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) ) );
			$this->set( 'referentsparcours', $this->InsertionsAllocataires->referents( array( 'prefix' => true ) ) );
		}
	}
?>