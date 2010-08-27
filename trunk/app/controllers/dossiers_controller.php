<?php
    App::import( 'Sanitize' );

    class DossiersController extends AppController
    {
        var $name = 'Dossiers';
        var $uses = array(
			'Canton',
			'Dossier',
			/*'Foyer',
			'Adresse',
			'Personne',
			'Structurereferente',
			'Orientstruct',*/
			'Typeorient',
			'Contratinsertion',
			/*'Detaildroitrsa',
			'Detailcalculdroitrsa',*/
			'Option',
			/*'Dsp',
			'Infofinanciere',
			'Modecontact',
			'Typocontrat',
			'Creance',
			'Adressefoyer',
			'Dossiercaf',*/
			'Serviceinstructeur',
			/*'Jeton',
			'Indu',*/
			'Referent',
			'Zonegeographique',
			/*'PersonneReferent',*/
			'Cui'
		);
        var $aucunDroit = array( 'menu' );
        var $helpers = array( 'Csv' );

        var $paginate = array(
            // FIXME
            'limit' => 20
        );

		var $commeDroit = array(
			'view' => 'Dossiers:index'
		);

        /**
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
			$this->set( 'couvsoc', $this->Option->couvsoc() );
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
			);

			if( in_array( $this->action, array( 'view', 'exportcsv' ) ) ) {
				// FIXME: à intégrer à la fonction view pour ne pas avoir d'énormes variables
				if( $this->action == 'view' ) {
					$this->set( 'referents', $this->Referent->find( 'list' ) );
					$this->set( 'numcontrat', $this->Contratinsertion->allEnumLists() );
					$this->set( 'enumcui', $this->Cui->allEnumLists() );
				}
				$typesorient = $this->Typeorient->find( 'list', array( 'fields' => array( 'id', 'lib_type_orient' ) ) );
				$this->set( 'typesorient', $typesorient );
			}
			else if( $this->action == 'index' ) {
				$typeservice = $this->Serviceinstructeur->find( 'list', array( 'fields' => array( 'lib_service' ) ) );
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
				$this->set( 'mesCodesInsee', $this->Zonegeographique->listeCodesInseeLocalites( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ) ) );
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
				$foyer_id = $this->Dossier->Foyer->Personne->field( 'foyer_id', array( 'id' => $this->params['personne_id'] ) );
				$this->assert( !empty( $foyer_id ), 'invalidParameter' );
				$conditions['Foyer.id'] = $foyer_id;
			}
			$this->assert( !empty( $conditions ), 'invalidParameter' );

			// On n'en a pas besoin pour le menu
			$this->Dossier->unbindModel(
				array(
					'hasOne' => array( 'Avispcgdroitrsa', 'Detaildroitrsa' )
				)
			);

			$dossier = $this->Dossier->find(
				'first',
				array(
					'conditions' => $conditions,
					'recursive' => 0
				)
			);
			$dossier['Dossier']['locked'] = $this->Jetons->locked( $dossier['Foyer']['dossier_rsa_id'] );

			// On n'en a pas besoin pour le menu
			$this->Dossier->Foyer->Personne->unbindModelAll();
			// A part la prestation RSA
			$this->Dossier->Foyer->Personne->bindModel(
				array(
					'hasOne' => array(
						'Prestation' => array(
							'conditions' => array( 'Prestation.natprest' => 'RSA' )
						)
					)
				)
			);
			$personnes = $this->Dossier->Foyer->Personne->find(
				'all',
				array(
					'conditions' => array(
						'Personne.foyer_id' => Set::classicExtract( $dossier, 'Foyer.id' )
					),
					'recursive' => 0
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
		*
        */

		/*function menu() {
			// Ce n'est pas un appel par une URL
			$this->assert( isset( $this->params['requested'] ), 'error404' );
//             $this->params['id'] = 203121;
//             $this->Dossier->query( 'SELECT 1;' );

			$conditions = array();

			if( !empty( $this->params['id'] ) && is_numeric( $this->params['id'] ) ) {
				$conditions['"Dossier"."id"'] = $this->params['id'];
			}
			else if( !empty( $this->params['foyer_id'] ) && is_numeric( $this->params['foyer_id'] ) ) {
				$conditions['"Foyer"."id"'] = $this->params['foyer_id'];
			}
			else if( !empty( $this->params['personne_id'] ) && is_numeric( $this->params['personne_id'] ) ) {
				$personne = $this->Dossier->Foyer->Personne->find(
					'first', array(
						'conditions' => array(
							'Personne.id' => $this->params['personne_id']
						)
					)
				);

				$this->assert( !empty( $personne ), 'invalidParameter' );

				$conditions['"Foyer"."id"'] = $personne['Personne']['foyer_id'];
			}

			$this->assert( !empty( $conditions ), 'invalidParameter' );

			$this->Dossier->Foyer->bindModel(
				array(
					'hasMany' => array(
						'Adressefoyer' => array(
							'classname'     => 'Adressefoyer',
							'foreignKey'    => 'foyer_id'
						),
						'Personne' => array(
							'classname'     => 'Personne',
							'foreignKey'    => 'foyer_id'
						)
					)
				)
			);
			$dossier = $this->Dossier->find(
				'first',
				array(
					'conditions' => $conditions,
					'recursive'  => 2
				)
			);

			$this->assert( !empty( $dossier ), 'invalidParameter' );

			usort( $dossier['Foyer']['Adressefoyer'], create_function( '$a,$b', 'return strcmp( $a["rgadr"], $b["rgadr"] );' ) );

			foreach( $dossier['Foyer']['Adressefoyer'] as $key => $Adressefoyer ) {
				$adresses = $this->Dossier->Foyer->Adressefoyer->Adresse->find(
					'all',
					array(
						'conditions' => array(
							'Adresse.id' => $Adressefoyer['adresse_id'] )
					)
				);
				$dossier['Foyer']['Adressefoyer'][$key] = array_merge( $dossier['Foyer']['Adressefoyer'][$key], $adresses[0] );
			}

			foreach( $dossier['Foyer']['Personne'] as $iPersonne => $personne ) {
				$this->Dossier->Foyer->Personne->unbindModelAll();
				$this->Dossier->Foyer->Personne->bindModel( array( 'hasOne' => array( 'Prestation' ) ));
				$prestation = $this->Dossier->Foyer->Personne->findById( $personne['id'] );
				$dossier['Foyer']['Personne'][$iPersonne]['Prestation'] = $prestation['Prestation'];
			}

			// Dossier verrouillé
			$this->Dossier->begin();
			$dossier['Dossier']['locked'] = $this->Jetons->locked( $dossier['Foyer']['dossier_rsa_id'] );
			$this->Dossier->commit();

			return $dossier;
		}*/

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

			$tDossier = $this->Dossier->findById( $id, null, null, -1 );
			$details = Set::merge( $details, $tDossier );

			$tFoyer = $this->Dossier->Foyer->findByDossierRsaId( $id, null, null, -1 );
			$details = Set::merge( $details, $tFoyer );

			$tDetaildroitrsa = $this->Dossier->Detaildroitrsa->findByDossierRsaId( $id, null, null, 1 );
			$details = Set::merge( $details, $tDetaildroitrsa );

			$tSituationdossierrsa = $this->Dossier->Situationdossierrsa->findByDossierRsaId( $id, null, null, -1 );
			$details = Set::merge( $details, $tSituationdossierrsa );

			$tSuiviinstruction = $this->Dossier->Suiviinstruction->find(
				'first',
				array(
					'conditions' => array( 'Suiviinstruction.dossier_rsa_id' => $id ),
					'recursive' => -1,
					'order' => array( 'Suiviinstruction.date_etat_instruction DESC' )
				)
			);
			$details = Set::merge( $details, $tSuiviinstruction );

			$tInfofinanciere = $this->Dossier->Infofinanciere->find(
				'first',
				array(
					'conditions' => array(
						'Infofinanciere.dossier_rsa_id' => $id,
						'Infofinanciere.type_allocation' => 'IndusConstates'
					),
					'recursive' => -1,
					'order' => array( 'Infofinanciere.moismoucompta DESC' )
				)
			);
			$details = Set::merge( $details, $tInfofinanciere );

			$adresseFoyer = $this->Dossier->Foyer->Adressefoyer->find(
				'first',
				array(
					'conditions' => array(
						'Adressefoyer.foyer_id' => $details['Foyer']['id'],
						'Adressefoyer.rgadr'    => '01'
					),
					'recursive' => 0
				)
			);
			$details = Set::merge( $details, array( 'Adresse' => $adresseFoyer['Adresse'] ) );

			/**
				Personnes
			*/
			$bindPrestation = $this->Dossier->Foyer->Personne->hasOne['Prestation'];
			$this->Dossier->Foyer->Personne->unbindModelAll();
			$this->Dossier->Foyer->Personne->bindModel( array( 'hasOne' => array( 'Dossiercaf', 'Dsp', 'Infopoleemploi', 'Calculdroitrsa', 'Prestation' => $bindPrestation ) ) );
			$personnesFoyer = $this->Dossier->Foyer->Personne->find(
				'all',
				array(
					'conditions' => array(
						'Personne.foyer_id' => $tFoyer['Foyer']['id'],
						'Prestation.rolepers' => array( 'DEM', 'CJT' )
					),
					'recursive' => 0
				)
			);

			$roles = Set::extract( '{n}.Prestation.rolepers', $personnesFoyer );
			foreach( $roles as $index => $role ) {
				$tPersReferent = $this->Dossier->Foyer->Personne->PersonneReferent->find(
					'first',
					array(
						'conditions' => array( 'PersonneReferent.personne_id' => $personnesFoyer[$index]['Personne']['id'] ),
						'recursive' => -1,
						'order' => array( 'PersonneReferent.dddesignation DESC' )
					)
				);
				$personnesFoyer[$index]['PersonneReferent']['dernier'] = $tPersReferent['PersonneReferent'];

				$tContratinsertion = $this->Contratinsertion->find(
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
						'recursive' => -1,
						'order' => array( 'Contratinsertion.rg_ci DESC' )
					)
				);
				$personnesFoyer[$index]['Contratinsertion'] = $tContratinsertion['Contratinsertion'];


				$tCui = $this->Cui->find(
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
						'recursive' => -1,
						'order' => array( 'Cui.datecontrat DESC' )
					)
				);
				$personnesFoyer[$index]['Cui'] = $tCui['Cui'];

				$tOrientstruct = $this->Dossier->Foyer->Personne->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => 'Orientstruct.date_valid ASC',
						'recursive' => -1
					)
				);
				$personnesFoyer[$index]['Orientstruct']['premiere'] = $tOrientstruct['Orientstruct'];

				$tOrientstruct = $this->Dossier->Foyer->Personne->Orientstruct->find(
					'first',
					array(
						'conditions' => array(
							'Orientstruct.personne_id' => $personnesFoyer[$index]['Personne']['id']
						),
						'order' => 'Orientstruct.date_valid DESC',
						'recursive' => -1
					)
				);
				$personnesFoyer[$index]['Orientstruct']['derniere'] = $tOrientstruct['Orientstruct'];

				$details[$role] = $personnesFoyer[$index];
			}



			$structuresreferentes = ClassRegistry::init( 'Structurereferente' )->find( 'list', array( 'fields' => array( 'id', 'lib_struc' ) ) );
			$typesorient = $this->Typeorient->find( 'list', array( 'fields' => array( 'id', 'lib_type_orient' ) ) );
			$typoscontrat = ClassRegistry::init( 'Typocontrat' )->find( 'list', array( 'fields' => array( 'id', 'lib_typo' ) ) );

			$this->set( 'structuresreferentes', $structuresreferentes );
			$this->set( 'typesorient', $typesorient );
			$this->set( 'typoscontrat', $typoscontrat );

			$this->set( 'details', $details );
			$this->_setOptions();
        }

		/**
        * Export du tableau en CSV
		*/

        function exportcsv() {
            $mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
            $mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? array_values( $mesZonesGeographiques ) : array() );

            $querydata = $this->Dossier->search( $mesCodesInsee, $this->Session->read( 'Auth.User.filtre_zone_geo' ), array_multisize( $this->params['named'] ) );
            unset( $querydata['limit'] );

            $dossiers = $this->Dossier->find( 'all', $querydata );

            $this->layout = ''; // FIXME ?
            $this->set( compact( 'headers', 'dossiers' ) );
			$this->_setOptions();
//             debug($dossiers);
//             die();
        }
    }
?>
