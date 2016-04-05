<?php
	/**
	 * Code source de la classe Transfertspdvs93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Transfertspdvs93Controller ...
	 *
	 * @package app.Controller
	 */
	class Transfertspdvs93Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Transfertspdvs93';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
//			'Allocataires',
//			'DossiersMenus',
//			'Gedooo.Gedooo',
			'Cohortes',
			'InsertionsBeneficiaires',
			'Jetons2',
			'Search.Filtresdefaut' => array( 'search' ),
			'Search.SearchPrg' => array(
				'actions' => array( 'search', 'cohorte_atransferer' )
			),
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Allocataires',
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
			'Search.SearchForm',
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Dossier' );

		/**
		 *
		 * @var array
		 */
		public $commeDroit = array(
			'search' => 'Criterestransfertspdvs93:index',
			'exportcsv' => 'Criterestransfertspdvs93:exportcsv',
			'cohorte_atransferer' => 'Cohortestransfertspdvs93:atransferer',
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
			'cohorte_atransferer' => 'create',
		);


		/**
		 * Moteur de recherche par rendez-vous (nouveau).
		 */
		public function search() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesTransfertspdvs93New' );
			$Recherches->search(
				array(
					'modelName' => 'Dossier',
					'modelRechercheName' => 'WebrsaRechercheTransfertpdv93',
				)
			);
		}

		/**
		 * Export CSV des résultats de la recherche par rendez-vous (nouveau).
		 */
		public function exportcsv() {
			$Recherches = $this->Components->load( 'WebrsaRecherchesTransfertspdvs93New' );
			$Recherches->exportcsv(
				array(
					'modelName' => 'Dossier',
					'modelRechercheName' => 'WebrsaRechercheTransfertpdv93',
				)
			);
		}

		/**
		 * Cohorte de transferts PDV, allocataires à transférer
		 */
		public function cohorte_atransferer() {
			$Recherches = $this->Components->load( 'WebrsaCohortesTransfertspdvs93Atransferer' );
			$Recherches->cohorte(
				array(
					'modelName' => 'Dossier',
					'modelRechercheName' => 'WebrsaCohorteTransfertpdv93Atransferer',
				)
			);
		}
	}
?>
