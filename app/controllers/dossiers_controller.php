<?php
	App::import( 'Sanitize' );

	class DossiersController extends AppController
	{
		public $name = 'Dossiers';
		public $uses = array( 'Dossier', 'Option' );
		public $aucunDroit = array( 'menu' );
		public $helpers = array( 'Csv' );

		public $paginate = array(
			// FIXME
			'limit' => 20
		);

		public $commeDroit = array(
			'view' => 'Dossiers:index'
		);

		/**
		*
		*/

		function __construct() {
			$this->components = Set::merge( $this->components, array( 'Prg' => array( 'actions' => array( 'index' ) ) ) );
			parent::__construct();
		}

		/**
		*
		*/

		protected function _setOptions() {
			$this->set( 'natpf', $this->Option->natpf() );
			$this->set( 'decision_ci', $this->Option->decision_ci() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'moticlorsa', $this->Option->moticlorsa() );
			$this->set( 'typeserins', $this->Option->typeserins() );
			$this->set( 'toppersdrodevorsa', $this->Option->toppersdrodevorsa() );
			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'sitfam', $this->Option->sitfam() );
			$this->set( 'couvsoc', $this->Option->couvsoc() ); // INFO: pas dans view
			$this->set( 'categorie', $this->Option->categorie() );
			///FIXME:
			$this->set(
				'trancheAge',
				array(
					'< 25',
					'25 - 30',
					'31 - 55',
					'56 - 65',
					'> 65'
				)
			); // INFO: pas dans view

			// FIXME: à intégrer à la fonction view pour ne pas avoir d'énormes variables
			if( $this->action == 'view' ) {
				$this->set( 'numcontrat', $this->Dossier->Foyer->Personne->Contratinsertion->allEnumLists() );
				$this->set( 'enumcui', $this->Dossier->Foyer->Personne->Cui->allEnumLists() );
			}
			else if( $this->action == 'exportcsv' ) {
				$typesorient = $this->Dossier->Foyer->Personne->Orientstruct->Typeorient->find( 'list', array( 'fields' => array( 'id', 'lib_type_orient' ) ) );
				$this->set( 'typesorient', $typesorient );
			}
			else if( $this->action == 'index' ) {
				$typeservice = $this->Dossier->Foyer->Personne->Orientstruct->Serviceinstructeur->find( 'list', array( 'fields' => array( 'lib_service' ) ) );
				$this->set( 'typeservice', $typeservice );
			}
		}

		/**
		*/
		function beforeFilter() {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '512M');
			$return = parent::beforeFilter();
			return $return;
		}

		/**
		*/
		function index() {
			if( Configure::read( 'CG.cantons' ) ) {
				$this->loadModel( 'Canton' );
				$this->set( 'cantons', $this->Canton->selectList() );
			}

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

			$params = $this->data;
			if( !empty( $params ) ) {
				$this->paginate = $this->Dossier->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), $this->data );
				$dossiers = $this->paginate( 'Dossier' );

				// Les dossiers que l'on a obtenus sont-ils lockés ?
				$lockedList = $this->Jetons->lockedList( Set::extract( $dossiers, '/Dossier/id' ) );
				foreach( $dossiers as $key => $dossier ) {
					$dossiers[$key]['Dossier']['locked'] = in_array( $dossier['Dossier']['id'], $lockedList );
				}

				$this->set( 'dossiers', $dossiers );
			}

			if( Configure::read( 'Zonesegeographiques.CodesInsee' ) ) {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Personne->Cui->Structurereferente->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
			}
			else {
				$this->set( 'mesCodesInsee', $this->Dossier->Foyer->Adressefoyer->Adresse->listeCodesInsee() );
			}

			$this->_setOptions();
		}

		/**
		*
		*/

		function menu() {
			$this->assert( isset( $this->params['requested'] ), 'error404' );
			$conditions = array();

			// Quel paramètre avons-nous pour trouver le bon dossier ?
			if( !empty( $this->params['id'] ) && is_numeric( $this->params['id'] ) ) {
				$conditions['Dossier.id'] = $this->params['id'];
			}
			else if( !empty( $this->params['foyer_id'] ) && is_numeric( $this->params['foyer_id'] ) ) {
				$conditions['Foyer.id'] = $this->params['foyer_id'];
			}
			else if( !empty( $this->params['personne_id'] ) && is_numeric( $this->params['personne_id'] ) ) {
				$conditions['Dossier.id'] = $this->Dossier->Foyer->Personne->dossierId( $this->params['personne_id'] );
			}
			$this->assert( !empty( $conditions ), 'invalidParameter' );

			$dossier = $this->Dossier->find(
				'first',
				array(
					'fields' => array(
						'Dossier.id',
						'Dossier.matricule',
						'Dossier.numdemrsa',
						'Foyer.id',
						'Situationdossierrsa.etatdosrsa'
					),
					'contain' => array(
						'Foyer',
						'Situationdossierrsa'
					),
					'conditions' => $conditions
				)
			);

			$dossier['Dossier']['locked'] = $this->Jetons->locked( $dossier['Dossier']['id'] );

			// FIXME: bizzarre qu'il ne soit plus bindé
			$this->Dossier->Foyer->Personne->bindModel( array( 'hasOne' => array( 'Prestation' ) ) );

			// Les personnes du foyer
			$personnes = $this->Dossier->Foyer->Personne->find(
				'all',
				array(
					'fields' => array(
						'Personne.id',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Prestation.rolepers'
					),
					'conditions' => array(
						'Personne.foyer_id' => Set::classicExtract( $dossier, 'Foyer.id' ),
						'Prestation.natprest' => 'RSA'
					),
					'contain' => array(
						'Prestation'
					)
				)
			);

			// Reformattage pour la vue
			$dossier['Foyer']['Personne'] = Set::classicExtract( $personnes, '{n}.Personne' );
			foreach( Set::classicExtract( $personnes, '{n}.Prestation' ) as $i => $prestation ) {
				$dossier['Foyer']['Personne'] = Set::insert( $dossier['Foyer']['Personne'], "{$i}.Prestation", $prestation );
			}

			return $dossier;
		}

		/**
		*/
		function view( $id = null ) {
			$this->assert( valid_int( $id ), 'invalidParameter' );

			/** Tables necessaire à l'ecran de synthèse

				OK -> Dossier
				OK -> Foyer
				OK -> Situationdossierrsa
				OK -> Adresse
				OK -> Detaildroitrsa
					OK -> Detailcalculdroitrsa
				OK -> Suiviinstruction
				OK -> Infofinanciere
				OK -> Creance
				OK -> Dossiercaf
				OK -> Personne (DEM/CJT)
					OK -> Personne
					OK -> Prestation
					OK -> Orientstruct (premier/dernier)
						//Typeorient
					OK -> Dsp
					OK -> Contratinsertion
					Calculsdroitrsa
			*/

			$details = array();
			$details = $this->Dossier->find(
				'first',
				array(
					'fields' => array(
						'Dossier.id',
						'Dossier.matricule',
						'Dossier.numdemrsa',
						'Dossier.dtdemrsa',
						'Foyer.id',
						'Foyer.sitfam',
						'Situationdossierrsa.id',
						'Situationdossierrsa.dtclorsa',
						'Situationdossierrsa.etatdosrsa',
						'Situationdossierrsa.moticlorsa',
					),
					'contain' => array(
						'Foyer',
						'Situationdossierrsa'
					),
					'conditions' => array(
						'Dossier.id' => $id
					)
				)
			);

			// Dernière créance
			$tCreance = $this->Dossier->Foyer->Creance->find(
				'first',
				array(
					'fields' => array(
						'Creance.motiindu'
					),
					'contain' => false,
					'conditions' => array(
						'Creance.foyer_id' => $details['Foyer']['id']
					),
					'order' => array(
						'Creance.dtdercredcretrans DESC',
					),
				)
			);
			$details = Set::merge( $details, $tCreance );

			$tDetaildroitrsa = $this->Dossier->Detaildroitrsa->find(
				'first',
				array(
					'fields' => array(
						'Detaildroitrsa.id',
						'Detaildroitrsa.dossier_id',
					),
					'contain' => array(
						'Detailcalculdroitrsa' => array(
							'fields' => array(
								'Detailcalculdroitrsa.mtrsavers',
								'Detailcalculdroitrsa.dtderrsavers',
								'Detailcalculdroitrsa.natpf',
							),
							'order' => array(
								'Detailcalculdroitrsa.ddnatdro DESC',
							),
							'limit' => 1
						)
					),
					'conditions' => array(
						'Detaildroitrsa.dossier_id' => $id
					)
				)
			);
			$details = Set::merge( $details, $tDetaildroitrsa );

			// Dernier suivi d'instruction
			$tSuiviinstruction = $this->Dossier->Suiviinstruction->find(
				'first',
				array(
					'fields' => array(
						'Suiviinstruction.typeserins'
					),
					'conditions' => array(
						'Suiviinstruction.dossier_id' => $id
					),
					'contain' => false,
					'order' => array(
						'Suiviinstruction.date_etat_instruction DESC'
					)
				)
			);
			$details = Set::merge( $details, $tSuiviinstruction );

			// Dernière info financière
			$tInfofinanciere = $this->Dossier->Infofinanciere->find(
				'first',
				array(
					'fields' => array(
						'Infofinanciere.mtmoucompta'
					),
					'conditions' => array(
						'Infofinanciere.dossier_id' => $id,
						'Infofinanciere.type_allocation' => 'IndusConstates'
					),
					'contain' => false,
					'order' => array( 'Infofinanciere.moismoucompta DESC' )
				)
			);
			$details = Set::merge( $details, $tInfofinanciere );

			// Dernière adresse foyer
			$adresseFoyer = $this->Dossier->Foyer->Adressefoyer->find(
				'first',
				array(
					'fields' => array(
						'Adressefoyer.id'
					),
					'conditions' => array(
						'Adressefoyer.foyer_id' => $details['Foyer']['id'],
						'Adressefoyer.rgadr'    => '01'
					),
					'order' => array( 'Adressefoyer.dtemm DESC' ),
					'contain' => array(
						'Adresse' => array(
							'fields' => array(
								'Adresse.numvoie',
								'Adresse.typevoie',
								'Adresse.nomvoie',
								'Adresse.locaadr',
							)
						)
					)
				)
			);
			$details = Set::merge( $details, array( 'Adresse' => $adresseFoyer['Adresse'] ) );

			/**
				Personnes
			*/

			$personnesFoyer = $this->Dossier->Foyer->Personne->find(
				'all',
				array(
					'fields' => array(
						'Personne.id',
						'Personne.nom',
						'Personne.prenom',
						'Dsp.id',
						'Dossiercaf.ddratdos',
						'Dossiercaf.dfratdos',
						'Infopoleemploi.identifiantpe',
						'Infopoleemploi.dateinscription',
						'Infopoleemploi.categoriepe',
						'Infopoleemploi.datecessation',
						'Infopoleemploi.motifcessation',
						'Infopoleemploi.dateradiation',
						'Infopoleemploi.motifradiation',
						'Calculdroitrsa.toppersdrodevorsa',
						'Prestation.rolepers',
					),
					'conditions' => array(
						'Personne.foyer_id' => $details['Foyer']['id'],
						'Prestation.natprest' => 'RSA',
						'Prestation.rolepers' => array( 'DEM', 'CJT' )
					),
					'contain' => array(
						'Prestation',
						'Dossiercaf',
						'Dsp',
						'Infopoleemploi',
						'Calculdroitrsa',
					),
					'recursive' => 0
				)
			);

			$roles = Set::extract( '{n}.Prestation.rolepers', $personnesFoyer );
			foreach( $roles as $index => $role ) {
				$tPersReferent = $this->Dossier->Foyer->Personne->PersonneReferent->find(
					'first',
					array(
						'fields' => array(
							'Referent.id',
							'Referent.qual',
							'Referent.nom',
							'Referent.prenom',
						),
						'contain' => array(
							'Referent'
						),
						'conditions' => array( 'PersonneReferent.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
						'order' => array( 'PersonneReferent.dddesignation DESC' )
					)
				);
				$personnesFoyer[$index]['Referent'] = $tPersReferent['Referent'];

				$tContratinsertion = $this->Dossier->Foyer->Personne->Contratinsertion->find(
					'first',
					array(
						'fields' => array(
							'Contratinsertion.dd_ci',
							'Contratinsertion.df_ci',
							'Contratinsertion.num_contrat',
							'Contratinsertion.decision_ci',
							'Contratinsertion.datevalidation_ci'
						),
						'conditions' => array( 'Contratinsertion.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
						'contain' => false,
						'order' => array( 'Contratinsertion.rg_ci DESC' )
					)
				);
				$personnesFoyer[$index]['Contratinsertion'] = $tContratinsertion['Contratinsertion'];

				$tCui = $this->Dossier->Foyer->Personne->Cui->find(
					'first',
					array(
						'fields' => array(
							'Cui.convention',
							'Cui.secteur',
							'Cui.datecontrat',
							'Cui.decisioncui',
							'Cui.datevalidationcui'
						),
						'conditions' => array( 'Cui.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
						'contain' => false,
						'order' => array( 'Cui.datecontrat DESC' )
					)
				);
				$personnesFoyer[$index]['Cui'] = $tCui['Cui'];

				// Dernière orientation
				$tOrientstruct = $this->Dossier->Foyer->Personne->Orientstruct->find(
					'first',
					array(
						'fields' => array(
							'Orientstruct.date_valid',
							'Orientstruct.statut_orient',
							'Typeorient.lib_type_orient',
							'Structurereferente.lib_struc'

						),
						'contain' => array(
							'Typeorient',
							'Structurereferente'
						),
						'conditions' => array(
							'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => "Orientstruct.date_valid DESC",
					)
				);
				$personnesFoyer[$index]['Orientstruct']['derniere'] = $tOrientstruct;

				$details[$role] = $personnesFoyer[$index];
			}

			$this->set( 'details', $details );
			$this->_setOptions();
		}

		/**
		* Export du tableau en CSV
		*/

		public function exportcsv() {
			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

			$querydata = $this->Dossier->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ) );
			unset( $querydata['limit'] );

			$dossiers = $this->Dossier->find( 'all', $querydata );

			$this->layout = ''; // FIXME ?
			$this->set( compact( 'headers', 'dossiers' ) );
			$this->_setOptions();
		}
	}
?>
