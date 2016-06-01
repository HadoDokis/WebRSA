<?php
	/**
	 * Code source de la classe ChangementsadressesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'CakeEmail', 'Network/Email' );
	App::uses( 'WebrsaEmailConfig', 'Utility' );

	/**
	 * La classe ChangementsadressesController
	 *
	 * @package app.Controller
	 * @see Changementsadresses66Controller (refonte)
	 */
	class ChangementsadressesController extends AppController
	{
		public $name = 'Changementsadresses';

		public $uses = array( 'Adressefoyer' );

		public $helpers = array( 
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
		);

		public $components = array(
			'Default',
			'Jetons2',
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
			'search' => 'read',
			'exportcsv' => 'read',
		);
		
		/**
		 * Moteur de recherche
		 */
		public function search() {
			$search = $this->Components->load('WebrsaRecherchesChangementsadresses');
			$search->search(array('modelName' => 'Dossier', 'modelRechercheName' => 'WebrsaRechercheChangementadresse'));
		}
		
		/**
		 * Export CSV du Moteur de recherche
		 */
		public function exportcsv() {
			$search = $this->Components->load('WebrsaRecherchesChangementsadresses');
			$search->exportcsv(array('modelName' => 'Dossier', 'modelRechercheName' => 'WebrsaRechercheChangementadresse'));
		}
	}
?>