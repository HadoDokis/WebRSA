<?php
	/**
	 * Code source de la classe InfoscomplementairesController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe InfoscomplementairesController ...
	 *
	 * @package app.Controller
	 */
	class InfoscomplementairesController extends AppController
	{
		public $name = 'Infoscomplementaires';

		public $uses = array( 'Personne', 'Creancealimentaire', 'Titresejour', 'Activite', 'Allocationsoutienfamilial', 'Option', 'Dossier' );

		public $helpers = array( 'Theme' );

		public $components = array( 'Jetons2', 'DossiersMenus' );

		/**
		 * Correspondances entre les méthodes publiques correspondant à des
		 * actions accessibles par URL et le type d'action CRUD.
		 *
		 * @var array
		 */
		public $crudMap = array(
			'view' => 'read',
		);

		/**
		 *
		 */
		public function beforeFilter() {
			$return = parent::beforeFilter();

			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'sitfam', $this->Option->sitfam() );
			$this->set( 'act', $this->Option->act() );
			$this->set( 'reg', $this->Option->reg() );
			$this->set( 'paysact', $this->Option->paysact() );
			$this->set( 'orioblalim', $this->Option->orioblalim() );
			$this->set( 'etatcrealim', $this->Option->etatcrealim() );
			$this->set( 'verspa', $this->Option->verspa() );
			$this->set( 'topjugpa', $this->Option->topjugpa() );
			$this->set( 'motidiscrealim', $this->Option->motidiscrealim() );
			$this->set( 'engproccrealim', $this->Option->engproccrealim() );
			$this->set( 'topdemdisproccrealim', $this->Option->topdemdisproccrealim() );
			$this->set( 'sitasf', $this->Option->sitasf() );
			$this->set( 'parassoasf', $this->Option->parassoasf() );

			return $return;
		}

		/**
		 *
		 * @param integer $id
		 */
		public function view( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );
			$this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'id' => $id ) ) );

			/** Tables necessaire à l'ecran de synthèse

			  OK -> Dossier
			  OK -> Foyer

			  OK -> Creance
			  OK -> Dossiercaf
			  OK -> Personne (DEM/CJT)
			  OK -> allocationssoutienfamilial
			  OK -> activites
			  OK -> dossierscaf (premier/dernier)
			  OK -> titressejours
			  OK ->  creancesalimentaires 
			 */
			$details = array( );

			$qd_tDossier = array(
				'conditions' => array(
					'Dossier.id' => $id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$tDossier = $this->Dossier->find( 'first', $qd_tDossier );
			$details = Set::merge( $details, $tDossier );

			$qd_tFoyer = array(
				'conditions' => array(
					'Foyer.dossier_id' => $id
				),
				'fields' => null,
				'order' => null,
				'recursive' => -1
			);
			$tFoyer = $this->Dossier->Foyer->find( 'first', $qd_tFoyer );
			$details = Set::merge( $details, $tFoyer );

			/**
			  Personnes
			 */
			$personnesFoyer = $this->Personne->find(
				'all',
				array(
					'conditions' => array(
						'Personne.foyer_id' => $tFoyer['Foyer']['id'],
						'Prestation.rolepers' => array( 'DEM', 'CJT' )
					),
					'contain' => array(
						'Prestation',
						'Dossiercaf'
					)
				)
			);

			$roles = Set::extract( '{n}.Prestation.rolepers', $personnesFoyer );

			foreach( $roles as $index => $role ) {
				///Créances alimentaires
				$tCreancealimentaire = $this->Creancealimentaire->find(
					'first',
					array(
						'conditions' => array( 'Creancealimentaire.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
						'recursive' => -1,
						'order' => 'Creancealimentaire.ddcrealim DESC',
					)
				);
				$personnesFoyer[$index]['Creancealimentaire'] = $tCreancealimentaire['Creancealimentaire'];

				///Titres séjour
				$tTitresejour = $this->Titresejour->find(
					'first',
					array(
						'conditions' => array(
							'Titresejour.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => 'Titresejour.ddtitsej DESC',
						'recursive' => -1
					)
				);
				$personnesFoyer[$index]['Titresejour'] = $tTitresejour['Titresejour'];

				///Activités
				$tActivite = $this->Activite->find(
					'first',
					array(
						'conditions' => array(
							'Activite.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => 'Activite.ddact DESC',
						'recursive' => -1
					)
				);
				$personnesFoyer[$index]['Activite'] = $tActivite['Activite'];

				///Allocation au soutien familial
				$tAllocationsoutienfamilial = $this->Allocationsoutienfamilial->find(
					'first',
					array(
						'conditions' => array(
							'Allocationsoutienfamilial.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => 'Allocationsoutienfamilial.ddasf DESC',
						'recursive' => -1
					)
				);
				$personnesFoyer[$index]['Allocationsoutienfamilial'] = $tAllocationsoutienfamilial['Allocationsoutienfamilial'];

				$details[$role] = $personnesFoyer[$index];
			}
			$this->set( 'details', $details );
		}

	}
?>