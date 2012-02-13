<?php
	/**
	* Classe de vérification de l'installation et du paramétrage de WebRSA.
	*/

	class ChecksController extends AppController
	{
		public $name = 'Checks';

		public $uses = array( 'Structurereferente', 'User', 'Serviceinstructeur' );

		public function index() {
			$this->set( 'webrsaIncExist', $this->_checkWebrsaInc( array( 'Cohorte.dossierTmpPdfs' ) ) );
			$this->set( 'pdftkInstalled', $this->_checkMissingBinaries( array( 'pdftk' ) ) );
			$this->_checkDecisionsStructures();
			$this->_checkDonneesUtilisateursEtServices();
			$this->set( 'donneesApreExist', $this->_checkDonneesApre() );
			$this->set( 'checkWritePdfDirectory', $this->_checkTmpPdfDirectory( Configure::read( 'Cohorte.dossierTmpPdfs' ) ) );
			$this->set( 'compressedAssets', $this->_compressedAssets() );
			$this->set( 'checkWebrsaIncEps', $this->_checkWebrsaIncEps() );
			$this->set( 'checkSqrecherche', $this->_checkSqrecherche() );
			$this->set( 'checkCmis', $this->_checkCmis() );
			$this->set( 'checkModelesOdtStatiques', $this->_checkModelesOdt() );
			$this->set( 'checkModelesOdtParametrables', $this->_checkModelesOdtParametrables() );
			$this->set( 'checkExtensions', $this->_checkExtensions() );
			$this->set( 'checkInis', $this->_checkInis() );
			$this->set( 'checkGedooo', $this->Gedooo->check( false, false, true ) );
			$this->set( 'checkPostgresql', $this->_checkPostgresql() );
			$this->set( 'checkCritereCerDelaiAvantEcheance', $this->_checkCritereCerDelaiAvantEcheance() );
		}

		/**
		*
		*/

		protected function _checkExtensions() {
			$extensions = array(
				'soap' => extension_loaded( 'soap' ),
				'xml' => extension_loaded( 'xml' ),
				'mbstring' => extension_loaded( 'mbstring' ),
				'curl' => extension_loaded( 'curl' ),
				'dom' => extension_loaded( 'dom' ),
			);

			return $extensions;
		}

		/**
		*
		*/

		protected function _checkInis() {
			$inis = array(
				'date.timezone' => ini_get( 'date.timezone' ),
			);

			return $inis;
		}

		/**
		*
		*/
		protected function _checkModelesOdtExistance( $modeles ) {
			$errors = array();
			$modelesVerifies = array();
			// Vérification des fichiers sur le filesystem
			foreach( $modeles as $modele ) {
				$modele_notif_file = APP.DS.'vendors'.DS.'modelesodt'.DS.$modele;//FIXME: constante

				$exists = file_exists( $modele_notif_file );
				$modelName = preg_replace( '/^([\w]+)\/.*$/', '\1', $modele );
				$fileName = preg_replace( '/^([\w]+)\/(.*)$/', '\2', $modele );
				$modelesVerifies[$modelName][$fileName] = $exists;

				if( !$exists ) {
					$errors[] = $modele_notif_file;
				}
			}

			return array( 'files' => $modelesVerifies, 'errors' => $errors );
		}

		/**
		*
		*/
		protected function _checkModelesOdt() {
			$modelesOdtStatiques = $this->_checkModelesOdtStatiques();
			$modelesOdtVariables = $this->_checkModelesOdtVariables();

			/*
			* Lecture des modèles de documents nécessaires pour chacune des
			* classes de modèle grâce à la variable modelesOdt.
			*/
			$connctions = array_keys( ConnectionManager::enumConnectionObjects() );
			$modeles = array();
			foreach( Configure::listObjects( 'model' ) as $modelName ) {
				if( !preg_match( '/([0-9]{2})$/', $modelName, $matches ) || ( $matches[1] == Configure::read( 'Cg.departement' ) ) ) {
					App::import( 'Model', $modelName );
					$attributes = get_class_vars( $modelName );

					// Peut-on instancier la classe ?
					if( $attributes['useTable'] !== false && in_array( $attributes['useDbConfig'], $connctions ) ) {
						$modelClass = ClassRegistry::init( $modelName );
						$modelesOdtVar = $modelClass->modelesOdt;
					}
					else {
						$modelesOdtVar = (array)( isset( $attributes['modelesOdt'] ) ? $attributes['modelesOdt'] : array() );
					}

					if( !empty( $modelesOdtVar ) ) {
						$alias = ( isset( $attributes['alias'] ) ? $attributes['alias'] : $modelName );
						$modeles = array_merge(
							$modeles,
							array_words_replace(
								$modelesOdtVar,
								array( '%s' => $alias )
							)
						);
					}
				}
			}

			$count = count( $modeles );
			$modeles = $this->_checkModelesOdtExistance( $modeles );

			$modeles['count'] = $count;
			$modeles['errors'] = Set::merge(
				$modelesOdtStatiques,
				$modelesOdtVariables,
				$modeles['errors']
			);

			return $modeles;
		}

		/**
		* Noms de modèles avec des variables
		*/

		protected function _checkModelesOdtVariables() {
			$errors = array();
			$modeles = array();

			if( Configure::read( 'Cg.departement' ) == 93 ) {
				/*// app/models/reorientationep93.php:774
				$modeles[] = "Reorientationep93/decision_accepte_poleemploi.odt";
				$enums = ClassRegistry::init( 'Decisionreorientationep93' )->enums();
				foreach( array_keys( $enums['Decisionreorientationep93']['decision'] ) as $decision ) {
					$modeles[] = "Reorientationep93/decision_{$decision}.odt";
				}

				// app/models/relancenonrespectsanctionep93.php:1330
				$enums = ClassRegistry::init( 'Nonrespectsanctionep93' )->enums();
				foreach( array_keys( $enums['Nonrespectsanctionep93']['origine'] ) as $origine ) {
					$numrelance = 2;

					for( $i = 1 ; $i <= $numrelance ; $i++ ) {
						$modeles[] = "Relancenonrespectsanctionep93/notification_{$origine}_relance{$i}.odt";
					}
				}*/

				// app/controllers/etatsliquidatifs_controller.php:370
				$modeles[] = 'APRE/Paiement/paiement_tiersprestataire.odt';

				// app/controllers/etatsliquidatifs_controller.php:379
                foreach( array( 'formation', 'horsformation' ) as $typeformation ) {
					$modeles[] = "APRE/Paiement/paiement_{$typeformation}_beneficiaire.odt";
				}

				// app/controllers/etatsliquidatifs_controller.php:486 et app/controllers/etatsliquidatifs_controller.php:491
                foreach( array( 'tiersprestataire', 'beneficiaire' ) as $dest ) {
					$modeles[] = "APRE/Paiement/paiement_{$dest}.odt";
				}

				// app/controllers/cohortescomitesapres_controller.php:406
				// app/controllers/cohortescomitesapres_controller.php:410
				// app/controllers/cohortescomitesapres_controller.php:414
				// app/controllers/cohortescomitesapres_controller.php:418
				foreach( array( 'Versement' ) as $typepaiement ) {
					foreach( array( 'Formation', 'HorsFormation' ) as $typeformation ) {
						foreach( array( 'Refus', 'Ajournement', 'Accord' ) as $typedecision ) {
							foreach( array( 'referent', 'beneficiaire', 'tiers' ) as $dest ) {
								if( ( $dest == 'beneficiaire' || $dest == 'referent' || $dest == 'tiers' ) && ( $typedecision == 'Refus' || $typedecision == 'Ajournement' ) ) {
									$modeles[] = "APRE/DecisionComite/Refus/Refus{$dest}.odt";
								}
								else if( $dest == 'beneficiaire' && $typedecision == 'Accord' ) {
									$modeles[] = "APRE/DecisionComite/{$typedecision}/{$typedecision}{$typeformation}{$dest}.odt";
								}
								else if( $dest == 'referent' && $typedecision == 'Accord' ) {
									$modeles[] = "APRE/DecisionComite/{$typedecision}/{$typedecision}{$dest}.odt";
								}
								else if( $dest == 'tiers' && !empty( $typedecision ) ) {
									$modeles[] = "APRE/DecisionComite/{$typedecision}/{$typedecision}{$typepaiement}{$dest}.odt";
								}
							}
						}
					}
				}
			}

			if( Configure::read( 'Cg.departement' ) != 58 ) {
				foreach( array( 'referent', 'beneficiaire', 'tiers' ) as $dest ) {
				// app/controllers/recoursapres_controller.php:233
				foreach( array( 'Oui', 'Non' ) as $recoursapre ) {
					$modeles[] = "APRE/DecisionComite/Recours/recours{$recoursapre}{$dest}.odt";
				}

				// app/controllers/recoursapres_controller.php:237
				$modeles[] = "APRE/DecisionComite/Recours/recours{$dest}.odt";
				}
			}

			foreach( array_unique( $modeles ) as $modele ) {
				$modele_notif_file = APP.DS.'vendors'.DS.'modelesodt'.DS.$modele;

				if( !file_exists( $modele_notif_file ) ) {
					$errors[] = $modele_notif_file;
				}
			}

			return $errors;
		}

		/**
		*
		*/

		protected function _checkModelesOdtStatiques() {
			/// Modèles en dur, suivant le CG pour certains d'entre eux
			$modeles = array(
				'Contratinsertion/notificationop.odt',			// app/controllers/contratsinsertion_controller.php:1089
				'CUI/cui.odt',									// app/controllers/cuis_controller.php:305
				'Contratinsertion/contratinsertion.odt',		// app/controllers/gedooos_controller.php:524
				'APRE/apre.odt',								// app/controllers/gedooos_controller.php:889
				'Candidature/fichecandidature.odt',				// app/controllers/actionscandidats_personnes_controller.php:588
				'PDO/propositiondecision.odt',					// app/models/decisionpropopdo.php:250
			);

			if( Configure::read( 'Cg.departement' ) == 58 ) {
				$modeles[] = 'Contratinsertion/contratinsertioncg58.odt';	// app/controllers/gedooos_controller.php:521
			}
			else if( Configure::read( 'Cg.departement' ) == 66 ) {
				$modeles[] = 'APRE/accordaide.odt';								// app/controllers/apres66_controller.php:705
				$modeles[] = 'APRE/refusaide.odt';								// app/controllers/apres66_controller.php:709
				$modeles[] = 'APRE/Relanceapre/relanceapre.odt';				// app/controllers/gedooos_controller.php:991
				$modeles[] = 'Bilanparcours/bilanparcours.odt';					// app/models/bilanparcours66.php:582
			}
			else {
				$modeles[] = 'APRE/apreforfaitaire.odt';						// app/controllers/etatsliquidatifs_controller.php:481 et app/controllers/etatsliquidatifs_controller.php:361
			}

			// Vérification des modèles nécessaires aux EPs
			/*$modeles = Set::merge(
				$modeles,
				array(
					'Commissionep/pv.odt',																	// app/models/commissionep.php:941
					'Commissionep/convocationep_participant.odt',											// app/models/commissionep.php:1388
					'Commissionep/ordredujour_participant_'.Configure::read( 'Cg.departement' ).'.odt',	// app/models/commissionep.php:1681
					'Commissionep/decisionep.odt',															// app/models/commissionep.php:1822
					'Commissionep/fichesynthese.odt',														// app/models/commissionep.php:1959
					'Commissionep/convocationep_beneficiaire.odt',											// Dans chacun des modèles des thématiques
				)
			);*/

			//------------------------------------------------------------------

			if( Configure::read( 'Cg.departement' ) == 58 ) {
				/*$modeles = Set::merge(
					$modeles,
					array(
					)
				);*/
			}
			else if( Configure::read( 'Cg.departement' ) == 66 ) {
				$modeles = Set::merge(
					$modeles,
					array(
						'Bilanparcours/courrierinformationavantep.odt',										// app/models/bilanparcours66.php:801
					)
				);
			}
			else if( Configure::read( 'Cg.departement' ) == 93 ) {
				/*$modeles = Set::merge(
					$modeles,
					array(
						'Nonrespectsanctionep93/convocationep_beneficiaire_1er_passage.odt', 				// app/models/nonrespectsanctionep93.php:699
						'Nonrespectsanctionep93/convocationep_beneficiaire_2eme_passage_suite_delai.odt', 	// app/models/nonrespectsanctionep93.php:771
						'Nonrespectsanctionep93/convocationep_beneficiaire_2eme_passage_suite_report.odt',	// app/models/nonrespectsanctionep93.php:774
						'Nonrespectsanctionep93/convocationep_beneficiaire_2eme_passage.odt',				// app/models/nonrespectsanctionep93.php:777
						'Nonrespectsanctionep93/decision_delai.odt',										// app/models/nonrespectsanctionep93.php:879
						'Nonrespectsanctionep93/decision_reduction_pdv.odt',								// app/models/nonrespectsanctionep93.php:890-900
						'Nonrespectsanctionep93/decision_reduction_ppae.odt',								// app/models/nonrespectsanctionep93.php:893-897
						'Nonrespectsanctionep93/decision_maintien.odt',										// app/models/nonrespectsanctionep93.php:904
						'Nonrespectsanctionep93/decision_suspensiontotale.odt',								// app/models/nonrespectsanctionep93.php:907
						'Nonrespectsanctionep93/decision_suspensionpartielle.odt',							// app/models/nonrespectsanctionep93.php:910
						'Nonrespectsanctionep93/decision_reporte.odt',										// app/models/nonrespectsanctionep93.php:913
						'Nonrespectsanctionep93/decision_annule.odt'										// app/models/nonrespectsanctionep93.php:916
					)
				);*/
			}

			$errors = array();
			// Vérification des fichiers sur le filesystem
			foreach( $modeles as $modele ) {
				$modele_notif_file = APP.DS.'vendors'.DS.'modelesodt'.DS.$modele;

				if( !file_exists( $modele_notif_file ) ) {
					$errors[] = $modele_notif_file;
				}
			}

			return $errors;
		}

		/**
		* TODO, quand ce sera en place:
		* 	- objetsentretien.modeledocument
		* 	- descriptionspdos.modelenotification
		* 	- objetsentretien.modeledocument
		*/

		protected function _checkModelesOdtParametrables() {
			$errors = array();

			// Type de rendez-vous
			$typesrdvs = ClassRegistry::init( 'Typerdv' )->find( 'all', array( 'recursive' => -1 ) );

			foreach( $typesrdvs as $typerdv ) {
				$modele_notif = trim( $typerdv['Typerdv']['modelenotifrdv'] );
				$modele_notif_file = APP.DS.'vendors'.DS.'modelesodt'.DS.'RDV'.DS.$modele_notif.'.odt';

				if( empty( $modele_notif ) || !file_exists( $modele_notif_file ) ) {
					$errors['Typerdv'][$typerdv['Typerdv']['libelle']] = $modele_notif_file;
				}
			}

			// Types d'orientation
			$typesorients = $this->Structurereferente->Typeorient->find(
				'all',
				array(
					'conditions' => array(
						'Typeorient.parentid IS NULL'
					),
					'recursive' => -1
				)
			);

			foreach( $typesorients as $typeorient ) {
				$modele_notif = trim( $typeorient['Typeorient']['modele_notif'] );
				// FIXME: apparemment, modele_notif_cohorte n'est plus utilisé
				$modele_notif_file = APP.DS.'vendors'.DS.'modelesodt'.DS.'Orientation'.DS.$modele_notif.'.odt';

				if( empty( $modele_notif ) || !file_exists( $modele_notif_file ) ) {
					$errors['Orientstruct'][$typeorient['Typeorient']['lib_type_orient']] = $modele_notif_file;
				}
			}


			return $errors;
		}

		/**
		* Vérifie la configuration de l'accès au système de gestion de contenu (Alfresco)
		*/

		protected function _checkCmis() {
			require_once( APPLIBS.'cmis.php' );

			$cmis = array(
				'curl' => extension_loaded( 'curl' ),
				'dom' => extension_loaded( 'dom' ),
				'connection' => false,
				'version' => false
			);

			if( extension_loaded( 'curl' ) && extension_loaded( 'dom' ) ) {
				try {
					$conn = Cmis::connect();

					$cmis['connection'] = ( is_a( $conn,'CMISService' ) && $conn->authenticated );
					$cmis['version'] = Cmis::configured();
				} catch( Exception $e ) {
				}
			}

			return $cmis;
		}

		/**
		* Vérifie les sous-requêtes de filtres (conditions SQL) liées aux services instructeurs
		*/

		protected function _checkSqrecherche() {
			$errors = array();

			if( Configure::read( 'Recherche.qdFilters.Serviceinstructeur' ) ) {
				$results = $this->Serviceinstructeur->find(
					'all',
					array(
						'fields' => array( 'id', 'lib_service', 'sqrecherche' ),
						'recursive' => -1,
						'conditions' => array( 'Serviceinstructeur.sqrecherche IS NOT NULL' )
					)
				);

				$debugLevel = Configure::read( 'debug' );
				Configure::write( 'debug', 0 );

				foreach( $results as $result ) {
					$error = $this->Serviceinstructeur->sqrechercheErrors( $result['Serviceinstructeur']['sqrecherche'] );
					if( !empty( $error ) ) {
						$errors[] = $result;
					}
				}

				Configure::write( 'debug', $debugLevel );
			}

			return $errors;
		}

		/**
		*
		*/

		private function __configureReadError( $keys ) {
			$errors = array();

			foreach( $keys as $key => $type ) {
				$value = Configure::read( $key );

				switch( $type ) {
					case 'integer':
						if( is_null( $value ) || !is_integer( $value ) ) {
							$errors[$key] = $type;
						}
						break;
					case 'numeric':
						if( is_null( $value ) || !is_numeric( $value ) ) {
							$errors[$key] = $type;
						}
						break;
					case 'string':
						if( is_null( $value ) || !is_string( $value ) ) {
							$errors[$key] = $type;
						}
						break;
				}
			}

			return $errors;
		}


		/**
		* Vérifie la présence des fichiers CSS et Javascript "compilés" et minifiés
		*/

		protected function _compressedAssets() {
			return array(
				'webrsa.css' => file_exists( CSS.'webrsa.css' ),
				'webrsa.js' => file_exists( JS.'webrsa.js' )
			);
		}

		/**
		*
		*/

		function _checkWebrsaInc( $paths = array() ) {
			$errorPaths = array();
			if( !empty( $paths ) ) {
				foreach( $paths as $path ) {
					$value = Configure::read( $path );
					if( empty( $value ) && !is_numeric( $value ) ) {
						$errorPaths[] = $path;
					}
				}
			}
			if( !empty( $errorPaths ) )
				return false;
			else
				return true;
		}

		/**
		*
		*/

		function _checkMissingBinaries( $binaries = array() ) {
			$missing = array();
			if( !empty( $binaries ) ) {
				foreach( $binaries as $binary ) {
					$which = exec( "which {$binary}" );
					if( empty( $which ) ) {
						$missing[] = $binary;
					}
				}
			}
			if( !empty( $missing ) )
				return false;
			else
				return true;
		}

		/**
		* Vérifie que pour toutes les structures référentes, le fait qu'elles gèrent
		* ou non l'Apre ou le contrat d'engagement soit décidé.
		* Si la décision n'a pas été prise pour au moins une structure, on bloque
		* l'utilisateur avec une erreur 401 et un message d'erreur approprié.
		*
		* INFO: n'est réellement exécuté que la première fois
		*
		* @access protected
		*/

		protected function _checkDecisionsStructures() {
			$structs = $this->Structurereferente->find(
				'all',
				array(
					'fields'=>array(
						'Structurereferente.lib_struc',
						'Structurereferente.apre',
						'Structurereferente.contratengagement'
					),
					'recursive'=>-1,
					'conditions' => array(
						'OR' => array(
							'Structurereferente.apre' => NULL,
							'Structurereferente.contratengagement' => NULL
						)
					)
				)
			);
			$this->set( compact( 'structs' ) );
		}

		/**
		* @access protected
		*/

		protected function _checkDonneesUtilisateursEtServices() {
			$users = $this->User->find(
				'all',
				array(
					'fields'=>array(
						'User.nom',
						'User.prenom',
						'User.serviceinstructeur_id',
						'User.date_deb_hab',
						'User.date_fin_hab',
						'Serviceinstructeur.lib_service',
						'Serviceinstructeur.numdepins',
						'Serviceinstructeur.typeserins',
						'Serviceinstructeur.numcomins',
						'Serviceinstructeur.numagrins',
					),
					'recursive'=>-1,
					'joins'=>array(
						array(
							'table'      => 'servicesinstructeurs',
							'alias'      => 'Serviceinstructeur',
							'type'       => 'LEFT OUTER',
							'foreignKey' => false,
							'conditions' => array(
								'User.serviceinstructeur_id = Serviceinstructeur.id'
							)
						)
					),
					'conditions' => array(
						'OR' => array(
							'User.nom IS NULL',
							'TRIM(User.nom)' => null,
							'User.prenom IS NULL',
							'TRIM(User.prenom)' => null,
							'User.serviceinstructeur_id IS NULL',
							'User.date_deb_hab IS NULL',
							'User.date_fin_hab IS NULL',
							'Serviceinstructeur.lib_service IS NULL',
							'TRIM(Serviceinstructeur.lib_service)' => null,
							'Serviceinstructeur.numdepins IS NULL',
							'TRIM(Serviceinstructeur.numdepins)' => null,
							'Serviceinstructeur.typeserins IS NULL',
							'TRIM(Serviceinstructeur.typeserins)' => null,
							'Serviceinstructeur.numcomins IS NULL',
							'TRIM(Serviceinstructeur.numcomins)' => null,
							'Serviceinstructeur.numagrins IS NULL'
						)
					)
				)
			);
			$this->set( compact( 'users' ) );
		}

		/**
		*
		*/

		protected function _checkDonneesApre() {
			$montantMaxComplementaires = Configure::read( 'Apre.montantMaxComplementaires' );
			$periodeMontantMaxComplementaires = Configure::read( 'Apre.periodeMontantMaxComplementaires' );

			$keys = array(
				'Apre.montantMaxComplementaires' => 'numeric',
				'Apre.periodeMontantMaxComplementaires' => 'integer'
			);

			$missing = $this->__configureReadError( $keys );
			
// 			$missing = null;

			if( empty( $montantMaxComplementaires ) )
				$missing[] = 'montantMaxComplementaires';
			if( empty( $periodeMontantMaxComplementaires ) )
				$missing[] = 'periodeMontantMaxComplementaires';

			return $missing;
		}

		/**
		*
		*/

		protected function _checkTmpPdfDirectory( $dir ) {
			$notWritable = array();
			$oldUmask = umask(0);

			if( !( is_dir( $dir ) && is_writable( $dir ) ) && !@mkdir( $dir, 0777, true ) ) {
				$notWritable[] = $dir;
			}

			umask( $oldUmask );

			if( !empty( $notWritable ) )
				return false;
			else
				return true;
		}

		/**
		* Vérification du paramétrage des EPs
		*/

		protected function _checkWebrsaIncEps() { // FIXME
			$keys = array( 'Cg.departement' => 'integer', );

			$errors = $this->__configureReadError( $keys );

			$departement = Configure::read( 'Cg.departement' );
			$method = "_checkWebrsaIncEps{$departement}";

			if( method_exists( $this, $method ) ) {
				$errors = Set::merge( $errors, $this->{$method}() );
			}
			else {
				$errors['Cg.departement'] = 'integer';
			}

			$return = ClassRegistry::init( 'Informationpe' )->checkConfigUpdateIntervalleDetectionNonInscritsPe();
			if( $return !== true ) {
				$errors['Selectionnoninscritspe.intervalleDetection'] = $return;
			}

			$return = ClassRegistry::init( 'Dossierep' )->checkConfigDossierepDelaiavantselection();
			if( $return !== true ) {
				$errors['Dossierep.delaiavantselection'] = $return;
			}
			
			return $errors;
		}

		/**
		* Vérification du paramétrage des EPs pour le CG 93
		*/

		protected function _checkWebrsaIncEps93() {
			$keys = array(
				'Nonrespectsanctionep93.relanceDecisionNonRespectSanctions' => 'integer',
				'Nonrespectsanctionep93.relanceOrientstructCer1' => 'integer',
				'Nonrespectsanctionep93.relanceOrientstructCer2' => 'integer',
// 				'Nonrespectsanctionep93.relanceOrientstructCer3' => 'integer',
				'Nonrespectsanctionep93.relanceCerCer1' => 'integer',
				'Nonrespectsanctionep93.relanceCerCer2' => 'integer',
				'Nonrespectsanctionep93.montantReduction' => 'numeric',
				'Nonrespectsanctionep93.dureeSursis' => 'integer',
				'Nonorientationproep93.delaiCreationContrat' => 'integer',
				'Nonrespectsanctionep93.delaiRegularisation' => 'integer',
				'Nonrespectsanctionep93.intervalleCerDo19' => 'string',
				'Signalementep93.montantReduction' => 'integer',
				'Signalementep93.dureeSursis' => 'integer',
				'Signalementep93.dureeTolerance' => 'integer',
			);

            $errors = $this->__configureReadError( $keys );

			$return = ClassRegistry::init( 'Nonrespectsanctionep93' )->checkConfigUpdateIntervalleCerDo19Cg93();
			if( $return !== true ) {
				$errors['Nonrespectsanctionep93.intervalleCerDo19'] = $return;
			}

			return $errors;
		}

		/**
		* Vérification du paramétrage des EPs pour le CG 66
		*/

		protected function _checkWebrsaIncEps66() {
			$keys = array(
				'traitementEnCoursId' => 'integer',
				'traitementClosId' => 'integer',
				'Traitementpcg66.fichecalcul_coefannee1' => 'numeric',// %
				'Traitementpcg66.fichecalcul_coefannee2' => 'numeric',// %
				'Traitementpcg66.fichecalcul_cavntmax' => 'numeric',
				'Traitementpcg66.fichecalcul_casrvmax' => 'numeric',
				'Traitementpcg66.fichecalcul_abattbicvnt' => 'numeric',// %
				'Traitementpcg66.fichecalcul_abattbicsrv' => 'numeric',// %
				'Traitementpcg66.fichecalcul_abattbncsrv' => 'numeric',// %
			);

			$errors = $this->__configureReadError( $keys );

			$return = ClassRegistry::init( 'Contratinsertion' )->checkConfigUpdateEncoursbilanCg66();
            if( $return !== true ) {
                $errors['Contratinsertion.Cg66.updateEncoursbilan'] = $return;
            }
// debug($return);

            
			return $errors;
		}

		/**
		* Vérification du paramétrage des EPs pour le CG 58
		*/

		protected function _checkWebrsaIncEps58() {
			return array();
		}

		/**
		* /usr/share/postgresql/8.4/contrib/fuzzystrmatch.sql
		*/
		private function __checkPostgresqlFuzzystrmatch() {
			$this->User->Behaviors->attach( 'Pgsqlcake.Schema' );
			$expected = array(
				'levenshtein',
				'metaphone',
				'soundex',
				'text_soundex',
				'difference',
				'dmetaphone',
				'dmetaphone_alt'
			);
			$pg_functions = $this->User->pgFunctions( $expected );
			$pg_functions = Set::extract( $pg_functions, '/Function/name' );
			$pg_functions = array_unique( $pg_functions );

			if( count( $pg_functions ) < count( $expected ) ) {
				$diff = implode( ', ', array_diff( $expected, $pg_functions ) );
				return "Problème avec les fonctions fuzzystrmatch (les fonctions suivantes sont manquantes: {$diff})<br/>Sous Ubuntu, il vous faut vérifier que le paquet postgresql-contrib-8.4 est bien installé. <br />Une fois fait, dans une console postgresql, en tant qu'administrateur, tapez: <code>\i /usr/share/postgresql/8.4/contrib/fuzzystrmatch.sql</code>";
			}
			else {
				return false;
			}
		}

		/**
		* Vérification de différents aspects liés à PostgreSQL
		*/
		protected function _checkPostgresql() {
			$errors['Fuzzystrmatch'] = $this->__checkPostgresqlFuzzystrmatch();

			return $errors;
		}

		
		
		/**
		* Vérification de la variable pour la détection des CERs arrivant à échéance
		*	dans les recherches par CER
		*/

		protected function _checkCritereCerDelaiAvantEcheance() {
			$errors = array();
			$return = ClassRegistry::init( 'Contratinsertion' )->checkConfigCriterecerDelaiavantecheance();
            if( $return !== true ) {
                $errors['Criterecer.delaiavanteecheance'] = $return;
            }
           
			return $errors;
		}
	}
?>