<?php
	/**
	 * Code source de la classe Nonorientationsproscovs58Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses('AppController', 'Controller');

	/**
	 * La classe Nonorientationsproscovs58Controller ...
	 *
	 * @package app.Controller
	 */
	class Nonorientationsproscovs58Controller extends AppController
	{
		/**
		 * Nom du contrôleur.
		 *
		 * @var string
		 */
		public $name = 'Nonorientationsproscovs58';

		/**
		 * Components utilisés.
		 *
		 * @var array
		 */
		public $components = array(
			'Allocataires',
			'Cohortes',
			'Search.Filtresdefaut' => array( 'cohorte' ),
			'Search.SearchPrg' => array(
				'actions' => array(
					'cohorte' => array( 'filter' => 'Search' ),
				)
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
				'className' => 'Default.DefaultDefault'
			),
			'Search.SearchForm',
		);

		/**
		 * Modèles utilisés.
		 *
		 * @var array
		 */
		public $uses = array( 'Nonorientationprocov58' );

		/**
		 * Les droits sont les mêmes que ceux de l'ancienne cohorte.
		 *
		 * @var array
		 */
		public $commeDroit = array(
			'cohorte' => 'Nonorientationsproseps:index',
			'exportcsv' => 'Nonorientationsproseps:exportcsv'
		);

		/**
		 * Recherche des bénéficiaires pour lesquels il est possible de créer un
		 * dossier de COV pour la thématique.
		 */
		public function cohorte() {
			if( !empty( $this->request->data ) ) {
				$cohorte = (array)Hash::get( $this->request->data, 'Cohorte' );

				// 1. Traitement du formulaire de sélection si nécessaire
				if( !empty( $cohorte ) ) {
					$dossiers_ids = array_filter( Hash::extract( $cohorte, 'Dossier.{n}.id' ) );

					// a. Acquisition des jetons
					$this->Cohortes->get( $dossiers_ids );

					// b. Traitement du cases cochées
					$orientsstructs = array_filter( (array)Hash::extract( $cohorte, 'Orientstruct' ) );
					if( !empty( $orientsstructs ) ) {
						$this->Nonorientationprocov58->begin();
						if( $this->Nonorientationprocov58->saveCohorte( $orientsstructs, $this->Session->read( 'Auth.User.id' ) ) ) {
							$this->Nonorientationprocov58->commit();
							$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
							$this->request->data = Hash::insert( $this->request->data, 'Cohorte', array() );
						}
						else {
							$this->Nonorientationprocov58->rollback();
							$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
						}
					}

					// c. On relache les jetons
					$this->Cohortes->release( $dossiers_ids );
				}

				// 2. Traitement du formulaire de recherche
				$search = (array)Hash::get( $this->request->data, 'Search' );

				$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
				$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

				$query = $this->Nonorientationprocov58->cohorte( $search );
				$query = $this->Cohortes->qdConditions( $query );

				$query['limit'] = 10;

				$query = $this->Allocataires->completeSearchQuery( $query );
				$query = ClassRegistry::init( 'Allocataire' )->searchConditions( $query, $search );
				$this->Nonorientationprocov58->Orientstruct->forceVirtualFields = true;

				$this->paginate = $query;
				$results = $this->paginate(
					$this->Nonorientationprocov58->Orientstruct,
					array(),
					array(),
					!Hash::get( $search, 'Pagination.nombre_total' )
				);

				// Acquisition des jetons du jeu de résultat
				$dossiers_ids = array_filter( Hash::extract( $results, '{n}.Dossier.id' ) );
				$this->Cohortes->get( $dossiers_ids );

				$this->set( compact( 'results' ) );
			}

			// Options
			$options = $this->Allocataires->options();
			$this->set( compact( 'options' ) );
		}

		/**
		 * Export du tableau de résultats de la cohorte en CSV.
		 */
		public function exportcsv() {
			$search = (array)Hash::get( Hash::expand( $this->request->params['named'], '__' ), 'Search' );

			$query = $this->Nonorientationprocov58->cohorte( $search );
			$query = $this->Cohortes->qdConditions( $query );
			$query = $this->Allocataires->completeSearchQuery( $query );
			unset( $query['limit'] );

			$this->Nonorientationprocov58->Orientstruct->forceVirtualFields = true;
			$results = $this->Nonorientationprocov58->Orientstruct->find( 'all', $query );

			$this->layout = null;
			$this->set( compact( 'results' ) );
		}
	}
?>
