<?php
	/**
	 * Code source de la classe AccompagnementsbeneficiairesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppController', 'Controller' );
	/**
	 * La classe AccompagnementsbeneficiairesController ...
	 *
	 * @package app.Controller
	 */
	class AccompagnementsbeneficiairesController extends AppController
	{

		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Accompagnementsbeneficiaires';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'DossiersMenus',
			'Jetons2'
		);

		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
			'Search.SearchForm'
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array(
			'Personne',
			'WebrsaAccompagnementbeneficiaire'
		);

		/**
		 * Liste des actions pour lesquelles les droits ACL sont les mêmes que
		 * ceux d'autres actions.
		 *
		 * @var array
		 */
		public $commeDroit = array(
			'fichiersmodules' => 'Accompagnementsbeneficiaires:index',
			'impressions' => 'Accompagnementsbeneficiaires:index'
		);

		/**
		 * ...
		 *
		 * @see /accompagnementsbeneficiaires/index/372005
		 * @todo séparer en différentes méthodes (avec $commeDroit) pour les appels Ajax
		 */
		public function index( $personne_id ) {
			$dossierMenu = $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) );
			$this->set( compact( 'dossierMenu' ) );

			//
			// TODO: si pas ajax
			$query = $this->WebrsaAccompagnementbeneficiaire->qdDetails( array( 'Personne.id' => $personne_id ) );
			$this->Personne->forceVirtualFields = true;
			$details = $this->Personne->find( 'first', $query );

			// TODO: query actions
			$actions = $this->WebrsaAccompagnementbeneficiaire->actions( $personne_id );

			$options = $this->WebrsaAccompagnementbeneficiaire->options();

			$this->set( compact( 'actions', 'options', 'details' ) );

			// FIXME: à supprimer, on triche tant que l'Ajax n'est pas mis en place
			$this->fichiersmodules( $personne_id );
			$this->impressions( $personne_id );
		}

		/**
		 * @todo Fichiers liés
		 *
		 * @param integer $personne_id
		 */
		public function fichiersmodules( $personne_id ) {
			//isset( $this->request->params['isAjax'] )
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $personne_id ) );

			$fichiersmodules = $this->WebrsaAccompagnementbeneficiaire->fichiersmodules( $personne_id );
			$this->set( compact( 'fichiersmodules' ) );
		}

		/**
		 * @todo Impressions
		 *
		 * @param integer $personne_id
		 */
		public function impressions( $personne_id ) {
			//isset( $this->request->params['isAjax'] )
			$this->DossiersMenus->checkDossierMenu( array( 'personne_id' => $personne_id ) );

			$pdfs = $this->WebrsaAccompagnementbeneficiaire->impressions( $personne_id );
			$this->set( compact( 'pdfs' ) );
		}
	}
?>
