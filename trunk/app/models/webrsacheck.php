<?php
	/**
	 * Classe permettant de connaître la liste des modèles de documents (odt),
	 * la liste des chemins devant être configurés dans le webrsa.inc et
	 * qui permet de varifier que les intervalles sont correctement paramétrés
	 * pour l'installation courante
	 *
	 * PHP 5.3
	 *
	 * @package       app.models
	 */
	class Webrsacheck extends AppModel
	{
		/**
		 * @var string
		 */
		public $name = 'Webrsacheck';

		/**
		 * @var string
		 */
		public $useTable = false;

		/**
		 * Lecture des modèles de documents nécessaires pour chacune des
		 * classes de modèle grâce à la variable modelesOdt et à la fonction
		 * modelesOdt lorsque celle-ci est présente dans les classes de modèles
		 * (ModelesodtConditionnablesBehavior complète la variable modelesOdt
		 * lorsque le modèle est instancié).
		 *
		 * @see grep -lri "\.odt" "app/models/" | sed "s/app\/models\/\(.*\)\.php/\1/p" | sort | uniq
		 * TODO
		 * @see grep -lri "\.odt" "app/controllers/" | sed "s/app\/controllers\/\(.*\)\.php/\1/p" | sort | uniq
		 *
		 * Dernière vérification le 28/02/2012 (à revérifier!)
		 *
		 * OK -> abstractclasses/nonorientationproep (dans les classes filles)
		 * OK -> actioncandidat_personne (FIXME: actioncandidat ?)
		 * OK -> apre66
		 * OK -> bilanparcours66
		 * OK -> commissionep
		 * OK -> contratcomplexeep93
		 * OK -> contratinsertion
		 * OK -> courrierpdo_traitementpcg66 (INFO: dans courrierpdo + une autre fonction à mettre en commun ?)
		 * OK -> courrierpdo_traitementpdo (INFO: dans courrierpdo + une autre fonction à mettre en commun ?)
		 * OK -> cov58
		 * OK -> decisiondossierpcg66
		 * OK -> decisionpropopdo (tous les CG ?) -> encore utilisé ? Tous sauf le 66
		 * OK -> defautinsertionep66
		 * OK -> descriptionpdo
		 * OK -> nonorientationproep58 (?)
		 * OK -> nonorientationproep66
		 * OK -> nonorientationproep93
		 * OK -> nonrespectsanctionep93
		 * OK -> objetentretien
		 * OK -> orientstruct
		 * OK -> propoorientationcov58
		 * OK -> propopdo (FIXME: tous les cg ?) -> tous sauf le 66
		 * OK -> regressionorientationep58
		 * OK -> relancenonrespectsanctionep93
		 * OK -> reorientationep93
		 * OK -> saisinebilanparcoursep66
		 * OK -> saisinepdoep66
		 * OK -> sanctionep58
		 * OK -> sanctionrendezvousep58
		 * OK -> signalementep93
		 * OK -> traitementpcg66
		 * OK -> typeorient
		 * OK -> typerdv
		 *
		 * @return array
		 */
		public function allModelesOdt( $departement ) {
			$connections = array_keys( ConnectionManager::enumConnectionObjects() );
			$modelesStatiques = array( );
			$modelesParametrables = array();

			foreach( Configure::listObjects( 'model' ) as $modelName ) {
				// Si le CG se sert de la classe
				if( !preg_match( '/([0-9]{2})$/', $modelName, $matches ) || ( $matches[1] == $departement ) ) {
					App::import( 'Model', $modelName );
					$attributes = get_class_vars( $modelName );

					// Peut-on instancier la classe ?
					if( $attributes['useTable'] !== false && in_array( $attributes['useDbConfig'], $connections ) ) {
						// Récupération de la valeur de l'attribut modelesOdt (avec utilisation possible de ModelsodtConditionnablesBehavior)
						$modelClass = ClassRegistry::init( $modelName );
						$varModelesOdt = $modelClass->modelesOdt;

						// Récupération des valeurs de la méthode modelesOdt lorsqu'elle est présente
						if( in_array( 'modelesOdt', get_class_methods( $modelName ) ) ) {
							$modelesParametrables = Set::merge( $modelesParametrables, $modelClass->modelesOdt() );
						}
					}
					else {
						// Récupération de la valeur de l'attribut modelesOdt (sans utilisation possible de ModelsodtConditionnablesBehavior)
						$varModelesOdt = (array) ( isset( $attributes['modelesOdt'] ) ? $attributes['modelesOdt'] : array( ) );
					}

					if( !empty( $varModelesOdt ) ) {
						$alias = ( isset( $attributes['alias'] ) ? $attributes['alias'] : $modelName );
						foreach( $varModelesOdt as $modeleOdt ) {
							$modelesStatiques[] = str_replace( '%s', $alias, $modeleOdt );
						}
					}
				}
			}

			return array(
				'parametrables' => array_unique( $modelesParametrables ),
				'statiques' => array_unique( $modelesStatiques ),
			);
		}

		/**
		 * Retourne la liste des chemins devant être configurés, suivant le département.
		 * Chaque entrée a en clé le chemin et en valeur le type de valeur
		 * (array, boolean, integer, numeric, string) autorisé.
		 *
		 * TODO: à utiliser dans chacun des modèles concernés ? ... plus contrôleurs, ....
		 *
		 * @return array
		 */
		public function allConfigureKeys( $departement ) {
			$configure = array(
				'Apre.montantMaxComplementaires' => 'numeric',
				'Apre.periodeMontantMaxComplementaires' => 'integer',
				'Cg.departement' => 'integer',
				'Cohorte.dossierTmpPdfs' => 'string',
			);

			if( $departement == 66 ) {
				$configure = Set::merge(
					$configure,
					array(
						'traitementEnCoursId' => 'integer',
						'traitementClosId' => 'integer',
						'Traitementpcg66.fichecalcul_coefannee1' => 'numeric',
						'Traitementpcg66.fichecalcul_coefannee2' => 'numeric',
						'Traitementpcg66.fichecalcul_cavntmax' => 'numeric',
						'Traitementpcg66.fichecalcul_casrvmax' => 'numeric',
						'Traitementpcg66.fichecalcul_abattbicvnt' => 'numeric',
						'Traitementpcg66.fichecalcul_abattbicsrv' => 'numeric',
						'Traitementpcg66.fichecalcul_abattbncsrv' => 'numeric',
						'Chargeinsertion.Secretaire.group_id' => 'array',
					)
				);
			}
			else if( $departement == 93 ) {
				$configure = Set::merge(
					$configure,
					array(
						'Nonrespectsanctionep93.relanceDecisionNonRespectSanctions' => 'integer',
						'Nonrespectsanctionep93.relanceOrientstructCer1' => 'integer',
						'Nonrespectsanctionep93.relanceOrientstructCer2' => 'integer',
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
					)
				);
			}

			return $configure;
		}

		/**
		 * FIXME:
		 *	1°) au changement de checks_controller, supprimer
		 *		- AppModel::_checkSqlIntervalSyntax
		 *		- Informationpe::checkConfigUpdateIntervalleDetectionNonInscritsPe
		 *		- Dossierep::checkConfigDossierepDelaiavantselection
		 *		- Nonrespectsanctionep93::checkConfigUpdateIntervalleCerDo19Cg93
		 *		- Contratinsertion::checkConfigUpdateEncoursbilanCg66
		 *		- Contratinsertion::checkConfigCriterecerDelaiavantecheance
		 *	2°) Bouger AppModel::_checkPostgresqlIntervals ici ?
		 *  3°) Voir si on ne peut pas combiner la boucle avec celle ci-dessus ?
		 *  4°) Les anciennes fonctions se trouvant dans les modèles sont-elles encore utilisées ?
		 *
		 * app/models/informationpe.php:299:                       return $this->_checkSqlIntervalSyntax( Configure::read( 'Selectionnoninscritspe.intervalleDetection' ) );
		 * app/models/nonrespectsanctionep93.php:1080:                     return $this->_checkSqlIntervalSyntax( Configure::read( 'Nonrespectsanctionep93.intervalleCerDo19' ) );
		 * app/models/contratinsertion.php:852:                    return $this->_checkSqlIntervalSyntax( Configure::read( 'Contratinsertion.Cg66.updateEncoursbilan' ) );
		 * app/models/contratinsertion.php:861:                    return $this->_checkSqlIntervalSyntax( Configure::read( 'Criterecer.delaiavanteecheance' ) );
		 * app/models/dossierep.php:548:                   return $this->_checkSqlIntervalSyntax( $delaiavantselection );
		 */
		public function checkAllPostgresqlIntervals( $departement ) {
			$connections = array_keys( ConnectionManager::enumConnectionObjects() );
			$results = array( );

			foreach( Configure::listObjects( 'model' ) as $modelName ) {
				// Si le CG se sert de la classe
				if( !preg_match( '/([0-9]{2})$/', $modelName, $matches ) || ( $matches[1] == $departement ) ) {
					App::import( 'Model', $modelName );
					$attributes = get_class_vars( $modelName );
					$methods = get_class_methods( $modelName );

					// Peut-on instancier la classe et la fonction existe-t'elle ?
					if( $attributes['useTable'] !== false && in_array( $attributes['useDbConfig'], $connections ) && in_array( 'checkPostgresqlIntervals', $methods ) ) {
						$modelClass = ClassRegistry::init( $modelName );

						$results = Set::merge( $results, $modelClass->checkPostgresqlIntervals() );
					}
				}
			}

			ksort( $results );

			return $results;
		}

		/**
		 * Récupère les enregistrements incomplèts de tous les modèles possédant
		 * la méthode storedDataErrors.
		 * TODO: vérifier la présence de la fonction comme ci-dessus.
		 */
		public function allStoredDataErrors() {
			return array(
				'servicesinstructeurs' => ClassRegistry::init( 'Serviceinstructeur' )->storedDataErrors(),
				'structuresreferentes' => ClassRegistry::init( 'Structurereferente' )->storedDataErrors(),
				'users' => ClassRegistry::init( 'User' )->storedDataErrors()
			);
		}

		/**
		 * Retourne la liste des serveurs configurés, les configurations prises
		 * en compte et les erreurs.
		 */
		public function services() {
			App::import( 'Behavior', array( 'Gedooo.Gedooo' ) );

			$GedModel = ClassRegistry::init( 'User' );
			$GedModel->Behaviors->attach( 'Gedooo' );

			return array(
				'Gedooo' => array(
					'configure' => $GedModel->Behaviors->Gedooo->gedConfigureKeys( $GedModel ),
					'tests' => $GedModel->gedTests() // FIXME: le faire sur les autres aussi
				)
			);
		}
	}
?>