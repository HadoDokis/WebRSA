<?php
	/**
	 * Code source de la classe Webrsacheck.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	require_once( APPLIBS.'cmis.php' );

	/**
	 * Classe permettant de connaître la liste des modèles de documents (odt),
	 * la liste des chemins devant être configurés dans le webrsa.inc et
	 * qui permet de varifier que les intervalles sont correctement paramétrés
	 * pour l'installation courante.
	 *
	 * @package app.Model
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

		/*public function getModels( $query ) {
			$models = array();

			$default = array(
				'methods' => null,
				'behaviors' => null,
				'attributes' => null,
				'useDbConfig' => $connections = array_keys( ConnectionManager::enumConnectionObjects() ),
				'cg' => null,
			);
			$query = Set::merge( $default, $query );

			foreach( App::objects( 'model' ) as $modelName ) {
				$preg_success = preg_match( '/([0-9]{2}$|[0-9]{2}(?=[A-Z]))/', $modelName, $matches );
				if( is_null( $query['cg'] ) || !$preg_success || ( $matches[0] == $query['cg'] ) ) {
//					App::import( 'Model', $modelName );
//					$classVars = get_class_vars( $modelName );
//
//					if( $classVars['useTable'] !== false && in_array( $classVars['useDbConfig'], $query['useDbConfig'] ) ) {
//						$modelClass = ClassRegistry::init( $modelName );
//						$classVars = get_object_vars( $modelClass );
//						$attributes = array_keys( $classVars );
//						$methods = get_class_methods( $modelClass );
//						$behaviors = $classVars['actsAs'];
//					}
//					else {
//						$attributes = array_keys( $classVars );
//						$methods = get_class_methods( $modelName );
//						$behaviors = $classVars['actsAs'];
//					}

					$found = true;
//					foreach( array( 'methods', 'behaviors', 'attributes' ) as $key ) {
//						if( !is_null( $query[$key] ) ) {
//							$query[$key] = (array)$query[$key];
//							$results = array_intersect( $query[$key], ${$key} );
//							debug( $results );
//						}
//					}

					if( $found ) {
						$models[] = $modelName;
					}
				}
				debug( array( $modelName => array( $preg_success, var_export( $matches, true ) ) ) );
			}

			sort( $models );
			return $models;
		}*/

		/**
		 * Lecture des modèles de documents nécessaires pour chacune des
		 * classes de modèle grâce à la variable modelesOdt et à la fonction
		 * modelesOdt lorsque celle-ci est présente dans les classes de modèles
		 * (ModelesodtConditionnablesBehavior complète la variable modelesOdt
		 * lorsque le modèle est instancié).
		 *
		 * @see grep -lri "\.odt" "app/models/" | sed "s/app\/models\/\(.*\)\.php/\1/p" | sort | uniq
		 * @see grep -lri "\.odt" "app/controllers/" | sed "s/app\/controllers\/\(.*\)\.php/\1/p" | sort | uniq
		 *
		 * @return array
		 */
		public function allModelesOdt( $departement ) {
			$connections = array_keys( ConnectionManager::enumConnectionObjects() );
			$modelesStatiques = array( );
			$modelesParametrables = array();

			foreach( App::objects( 'model' ) as $modelName ) {
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
		 * Retourne les clés de configuration communes aux différents CGs.
		 *
		 * @return array
		 */
		protected function _allConfigureKeysCommon() {
			return array(
				'ActioncandidatPersonne.suffixe' => 'string',
				'Admin.unlockall' => 'boolean',
				'AjoutOrientationPossible.situationetatdosrsa' => 'isarray',
				'AjoutOrientationPossible.toppersdrodevorsa' => 'string',
				'Apre.forfaitaire.montantbase' => 'numeric',
				'Apre.forfaitaire.montantenfant12' => 'numeric',
				'Apre.forfaitaire.nbenfant12max' => 'integer',
				'Apre.montantMaxComplementaires' => 'numeric',
				'Apre.periodeMontantMaxComplementaires' => 'integer',
				'Apre.pourcentage.montantversement' => 'numeric',
				'Apre.suffixe' =>  array(
					array( 'rule' => 'inList', array( 66 ), 'allowEmpty' =>true ),
				),
				'CG.cantons' => 'boolean',
				'Cg.departement' => array(
					array( 'rule' => 'inList', array( 58, 66, 93 ) ),
				),
				'Cohorte.dossierTmpPdfs' => 'string',
				'Criterecer.delaiavanteecheance' => 'string',
				'Cui.taux.financementexclusif' => 'numeric',
				'Cui.taux.fixe' => 'numeric',
				'Cui.taux.prisencharge' => 'numeric',
				'Detailcalculdroitrsa.natpf.socle' => 'isarray',
				'Dossierep.delaiavantselection' => array(
					array( 'rule' => 'string', 'allowEmpty' => true ),
				),
				'FULL_BASE_URL' => 'url',
				'Jetons2.disabled' => 'boolean',
				'Optimisations.progressivePaginate' => 'boolean',
				'Optimisations.useTableDernierdossierallocataire' => array(
					array( 'rule' => 'boolean', 'allowEmpty' => true ),
				),
				'Periode.modifiable.nbheure' => 'integer',
				'Recherche.identifiantpecourt' => 'boolean',
				'Recherche.qdFilters.Serviceinstructeur' => 'boolean',
				'Selectionnoninscritspe.intervalleDetection' => 'string',
				'Situationdossierrsa.etatdosrsa.ouvert' => 'isarray',
				'UI.menu.large' => 'boolean',
				'UI.menu.lienDemandeur' => array(
					array( 'rule' => 'url', 'allowEmpty' =>true ),
				),
				'User.adresse' => 'boolean',
				'Utilisateurs.multilogin' => 'boolean',
				'Zonesegeographiques.CodesInsee' => 'boolean',
				'alerteFinSession' => 'boolean',
				'nb_limit_print' => 'integer',
				'nom_form_apre_cg' => array(
					array( 'rule' => 'inList', array( 'cg58', 'cg66', 'cg93' ) ),
				),
				'nom_form_bilan_cg' => array(
					array( 'rule' => 'inList', array( 'cg58', 'cg66', 'cg93' ) ),
				),
				'nom_form_ci_cg' => array(
					array( 'rule' => 'inList', array( 'cg58', 'cg66', 'cg93' ) ),
				),
				'nom_form_cui_cg' => array(
					array( 'rule' => 'inList', array( 'cg58', 'cg66', 'cg93' ) ),
				),
				'nom_form_pdo_cg' => array(
					array( 'rule' => 'inList', array( 'cg58', 'cg66', 'cg93' ) ),
				),
				'traitementClosId' => 'integer',
				'traitementEnCoursId' => 'integer',
				'with_parentid' => 'boolean',
			);
		}

		/**
		 * Retourne les clés de configuration propres au CG 58.
		 *
		 * @return array
		 */
		protected function _allConfigureKeys58() {
			return array(
				'Nonorientationproep58.delaiCreationContrat' => 'integer',
				'Sanctionep58.nonrespectcer.dureeTolerance' => 'integer',
				'Selectionradies.conditions' => 'isarray',
				'Typeorient.emploi_id' => 'integer',
				'traitementResultatId' => 'integer',
			);
		}

		/**
		 * Retourne les clés de configuration propres au CG 66.
		 *
		 * @return array
		 */
		protected function _allConfigureKeys66() {
			return array(
				'AjoutOrientationPossible.toppersdrodevorsa' => 'isarray',
				'Fraisdeplacement66.forfaithebergt' => 'numeric',
				'Fraisdeplacement66.forfaitrepas' => 'numeric',
				'Fraisdeplacement66.forfaitvehicule' => 'numeric',
				'Apre66.EmailPiecesmanquantes.from' => 'string',
				'Apre66.EmailPiecesmanquantes.replyto' => 'string',
				'Chargeinsertion.Secretaire.group_id' => 'isarray',
				'Contratinsertion.Cg66.updateEncoursbilan' => 'string',
				'Criterecer.delaidetectionnonvalidnotifie' => 'string',
				'Email.smtpOptions' => 'isarray',
				'Nonorientationproep66.delaiCreationContrat' => 'integer',
				'Orientstruct.typeorientprincipale.Emploi' => 'isarray',
				'Orientstruct.typeorientprincipale.SOCIAL' => 'isarray',
				'Periode.modifiablecer.nbheure' => 'integer',
				'Periode.modifiableorientation.nbheure' => 'integer',
				'Traitementpcg66.fichecalcul_abattbicsrv' => 'integer',
				'Traitementpcg66.fichecalcul_abattbicvnt' => 'integer',
				'Traitementpcg66.fichecalcul_abattbncsrv' => 'integer',
				'Traitementpcg66.fichecalcul_casrvmax' => 'integer',
				'Traitementpcg66.fichecalcul_cavntmax' => 'integer',
				'Traitementpcg66.fichecalcul_coefannee1' => 'integer',
				'Traitementpcg66.fichecalcul_coefannee2' => 'integer',
				'Nonoriente66.notisemploi.typeorientId' => 'isarray',
				'Nonoriente66.TypeorientIdSocial' => 'integer',
				'Nonoriente66.TypeorientIdPrepro' => 'integer',
				'Contratinsertion.Cg66.Rendezvous' => 'isarray',
				'Corbeillepcg.descriptionpdoId' => 'isarray'
			);
		}

		/**
		 * Retourne les clés de configuration propres au CG 93.
		 *
		 * @return array
		 */
		protected function _allConfigureKeys93() {
			return array(
				'traitementResultatId' => 'integer',
				'Dossierep.nbJoursEntreDeuxPassages' => 'integer',
				'Filtresdefaut.Cohortes_enattente' => 'isarray',
				'Filtresdefaut.Cohortes_nouvelles' => 'isarray',
				'Filtresdefaut.Cohortes_orientees' => 'isarray',
				'Nonorientationproep93.delaiCreationContrat' => 'integer',
				'Nonrespectsanctionep93.decisionep.delai' => 'integer',
				'Nonrespectsanctionep93.delaiRegularisation' => 'integer',
				'Nonrespectsanctionep93.dureeSursis' => 'integer',
				'Nonrespectsanctionep93.intervalleCerDo19' => 'string',
				'Nonrespectsanctionep93.montantReduction' => 'numeric',
				'Nonrespectsanctionep93.relanceCerCer1' => 'integer',
				'Nonrespectsanctionep93.relanceCerCer2' => 'integer',
				'Nonrespectsanctionep93.relanceDecisionNonRespectSanctions' => 'integer',
				'Nonrespectsanctionep93.relanceOrientstructCer1' => 'integer',
				'Nonrespectsanctionep93.relanceOrientstructCer2' => 'integer',
				'Signalementep93.decisionep.delai' => 'integer',
				'Signalementep93.dureeSursis' => 'integer',
				'Signalementep93.dureeTolerance' => 'integer',
				'Signalementep93.montantReduction' => 'numeric',
				'apache_bin' => 'string',
				'Cohortescers93.saisie.periodeRenouvellement' => 'string',
				'Contratinsertion.RdvAuto.active' => 'boolean',
				'Contratinsertion.RdvAuto.typerdv_id' => 'integer',
				'Contratinsertion.RdvAuto.statutrdv_id' => 'integer',
			);
		}

		/**
		 * Retourne les clés de configuration actuellement utilisées.
		 *
		 * @return array
		 */
		/*protected function _existingConfigureKeys() {
			$configureKeys = array();
			foreach( Set::flatten( (array)Configure::getInstance() ) as $key => $value ) {
				$configureKeys[] = preg_replace( '/\.[0-9]+$/', '', $key );
			}
			$configureKeys = array_unique( $configureKeys );
			sort( $configureKeys );
			return $configureKeys;
		}*/

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
			$configure = $this->_allConfigureKeysCommon();

			switch( $departement ) {
				case 58:
					$configure = Set::merge( $configure, $this->_allConfigureKeys58() );
					break;
				case 66:
					$configure = Set::merge( $configure, $this->_allConfigureKeys66() );
					break;
				case 93:
					$configure = Set::merge( $configure, $this->_allConfigureKeys93() );
					break;
			}

			uksort( $configure, 'strnatcasecmp' );

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

			foreach( App::objects( 'model' ) as $modelName ) {
				// Si le CG se sert de la classe
				if( !preg_match( '/([0-9]{2})$/', $modelName, $matches ) || ( $matches[1] == $departement ) ) {
					App::import( 'Model', $modelName );
					$attributes = get_class_vars( $modelName );
					$methods = get_class_methods( $modelName );

					// Possède-t-on la classe et la fonction existe-t'elle ?
					if( in_array( $attributes['useDbConfig'], $connections ) && in_array( 'checkPostgresqlIntervals', $methods ) ) {
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
		 * TODO: vérifier la présence de la fonction comme ci-dessus, mais attention aux sous-classes (	covstructurereferentes)
		 */
		public function allStoredDataErrors() {
			return array(
				'servicesinstructeurs' => ClassRegistry::init( 'Serviceinstructeur' )->storedDataErrors(),
				'structuresreferentes' => ClassRegistry::init( 'Structurereferente' )->storedDataErrors(),
				'users' => ClassRegistry::init( 'User' )->storedDataErrors(),
				'regroupementseps' => ClassRegistry::init( 'Regroupementep' )->storedDataErrors(),
				'derniersdossiersallocataires' => ClassRegistry::init( 'Dernierdossierallocataire' )->storedDataErrors()
			);
		}

		/**
		 * Vérifie le bon fonctionnement du service Gedooo
		 *
		 * @return array
		 */
		protected function _serviceGedooo() {
			App::import( 'Behavior', 'Gedooo.Gedooo' );

			$GedModel = ClassRegistry::init( 'User' );
			$GedModel->Behaviors->attach( 'Gedooo.Gedooo' );

			return array(
				'configure' => @$GedModel->Behaviors->Gedooo->gedConfigureKeys( $GedModel ),
				'tests' => @$GedModel->gedTests() // FIXME: le faire sur les autres aussi
			);
		}

		/**
		 * Vérifie le bon fonctionnement du service CMIS.
		 *
		 * @return array
		 */
		protected function _serviceCmis() {
			$connected =Cmis::configured();

			return array(
				'configure' => array(
					'Cmis.url' => 'string',
					'Cmis.username' => 'string',
					'Cmis.password' => 'string',
					'Cmis.prefix' => 'string'
				),
				'tests' => array(
					'Connexion au serveur' => array(
						'success' => $connected,
						'message' => ( $connected ? null : 'Impossible de se connecter au serveur' )
					)
				)
			);
		}

		/**
		 * Retourne la liste des serveurs configurés, les configurations prises
		 * en compte et les erreurs.
		 *
		 * @return array
		 */
		public function services() {
			return array(
				'Alfresco' => $this->_serviceCmis(),
				'Gedooo' => $this->_serviceGedooo(),
			);
		}

		/**
		 *
		 * @return array
		 */
		public function allSqRechercheErrors() {
			$errors = array( );
			$modelNames = array( 'Serviceinstructeur' );

			$debugLevel = Configure::read( 'debug' );
			foreach( $modelNames as $modelName ) {
				$errors[$modelName] = array();

				if( Configure::read( "Recherche.qdFilters.{$modelName}" ) ) {
					$Model = ClassRegistry::init( $modelName );

					$results = $Model->find(
						'all',
						array(
							'fields' => array(
								"{$Model->primaryKey} AS \"{$modelName}__id\"",
								"{$Model->displayField} AS \"{$modelName}__name\"",
								"sqrecherche AS \"{$modelName}__sqrecherche\""
							),
							'recursive' => -1,
							'conditions' => array( "{$modelName}.sqrecherche IS NOT NULL" )
						)
					);

					Configure::write( 'debug', 0 );

					foreach( $results as $result ) {
						$error = $Model->sqrechercheErrors( $result[$modelName]['sqrecherche'] );
						if( !empty( $error ) ) {
//							$result[$modelName]['errors'] = $error;
							$errors[$modelName][] = $result;
						}
					}
				}
			}
			Configure::write( 'debug', $debugLevel );

			return $errors;
		}
	}
?>