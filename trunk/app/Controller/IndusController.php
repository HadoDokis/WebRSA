<?php
	/**
	 * Code source de la classe IndusController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe IndusController ...
	 *
	 * @package app.Controller
	 */
	class IndusController  extends AppController
	{
		public $name = 'Indus';

		public $uses = array( 'Infofinanciere', 'Indu', 'Option', 'Dossier', 'Personne', 'Foyer', 'Cohorteindu' );

		public $commeDroit = array( 'view' => 'Indus:index' );

		public $helpers = array(
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
		);
		
		public $components = array( 
			'Jetons2', 
			'DossiersMenus',
			'Search.SearchPrg' => array(
				'actions' => array( 'search' )
			),
		);

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'index' => 'read',
			'read' => 'read',
		);

		/**
		 *
		 */
		public function beforeFilter() {
			parent::beforeFilter();
			$this->set( 'type_allocation', $this->Option->type_allocation() );
			$this->set( 'natpfcre', $this->Option->natpfcre() );
			$this->set( 'typeopecompta', $this->Option->typeopecompta() );
			$this->set( 'sensopecompta', $this->Option->sensopecompta() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
		}

		/**
		 *
		 * @param integer $dossier_id
		 */
		public function index( $dossier_id = null) {
			$this->assert( valid_int( $dossier_id ), 'invalidParameter' );
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $dossier_id ) ) );

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$params = $this->Indu->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array( 'Dossier.id' => $dossier_id ) );
			$infofinanciere = $this->Infofinanciere->find( 'first',  $params );

			$this->set('infofinanciere', $infofinanciere );
			$this->set( 'dossier_id', $dossier_id );
		}

		/**
		*
		*/

		public function view( $dossier_id = null ) {
			// Vérification du format de la variable
			$this->assert( valid_int( $dossier_id ), 'error404' );

			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $dossier_id ) ) );

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$params = $this->Indu->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array( 'Dossier.id' => $dossier_id ) );
			$infofinanciere = $this->Infofinanciere->find( 'first',  $params );
			$this->assert( !empty( $infofinanciere ), 'error404' );

			// Assignations à la vue
			$this->set( 'dossier_id', $dossier_id );
			$this->set( 'infofinanciere', $infofinanciere );
			$this->set( 'urlmenu', '/indus/index/'.$dossier_id );
		}
		
		/**
		 * Moteur de recherche
		 */
		public function search() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesInfofinancieres' );
			$Recherches->search( array( 'modelName' => 'Dossier' ) );
			$this->Infofinanciere->validate = array();
		}

		/**
		 * Export du tableau de résultats de la recherche
		 */
		public function exportcsv() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesInfofinancieres' );
			$Recherches->exportcsv( array( 'modelName' => 'Dossier' ) );
		}
	}
?>