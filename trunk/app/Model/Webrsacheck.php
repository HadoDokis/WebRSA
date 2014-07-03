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
	 * pour l'installation courante.partenaire
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
				'AncienAllocataire.enabled' => 'boolean',
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
                'Cui.Numeroconvention' => 'string',
				'Detailcalculdroitrsa.natpf.socle' => 'isarray',
				'Dossierep.delaiavantselection' => array(
					array( 'rule' => 'string', 'allowEmpty' => true ),
				),
				'FULL_BASE_URL' => 'url',
				'Gestiondoublon.Situationdossierrsa2.etatdosrsa' => array(
					array( 'rule' => 'isarray' ),
					// array( 'rule' => 'inList', array( 'Z', 1, 2, 3, 4, 5, 6 ) ), // TODO: inList
				),
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
				'with_parentid' => 'boolean',
				'Utilisateurs.reconnection' => 'boolean',
				'Statistiqueministerielle.conditions_indicateurs_organismes' => 'isarray',
				'Statistiqueministerielle.conditions_types_cers' => 'isarray',
                'Rendezvous.useThematique' => 'boolean',
			);

			$tmp = Configure::read( 'Rendezvous.thematiqueAnnuelleParStructurereferente' );
			if( !empty( $tmp ) ) {
				if( is_array( $tmp ) ) {
					$return['Rendezvous.thematiqueAnnuelleParStructurereferente'] = 'isarray';
				}
				else {
					$return['Rendezvous.thematiqueAnnuelleParStructurereferente'] = 'integer';
				}
			}
		}

		/**
		 * Retourne les clés de configuration propres au CG 58.
		 *
		 * @return array
		 */
		protected function _allConfigureKeys58() {
			$return = array(
				'Nonorientationproep58.delaiCreationContrat' => 'integer',
				'Sanctionep58.nonrespectcer.dureeTolerance' => 'integer',
				'Selectionradies.conditions' => 'isarray',
				'Typeorient.emploi_id' => 'integer',
				'Dossierseps.conditionsSelection' => 'isarray',
			);

			$structurereferente_id = Configure::read( 'Sanctionseps58.selection.structurereferente_id' );
			if( is_array( $structurereferente_id ) ) {
				$return['Sanctionseps58.selection.structurereferente_id'] = 'isarray';
			}
			else {
				$return['Sanctionseps58.selection.structurereferente_id'] = 'integer';
			}

			return $return;
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
				'Chargeinsertion.Secretaire.group_id' => 'isarray',
				'Contratinsertion.Cg66.updateEncoursbilan' => 'string',
				'Criterecer.delaidetectionnonvalidnotifie' => 'string',
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
				'Corbeillepcg.descriptionpdoId' => 'isarray',
                'ActioncandidatPersonne.Actioncandidat.typeregionId' => 'isarray',
                'ActioncandidatPersonne.Partenaire.id' => 'isarray',
                'Rendezvous.Ajoutpossible.statutrdv_id' => 'integer',
                'Generationdossierpcg.Orgtransmisdossierpcg66.id' => 'isarray',
				'Nonorganismeagree.Structurereferente.id' => 'isarray',
                'ActioncandidatPersonne.Actioncandidat.typeregionPoursuitecgId' => 'isarray'
			);
		}

		/**
		 * Retourne les clés de configuration propres au CG 93.
		 *
		 * @return array
		 */
		protected function _allConfigureKeys93() {
			$return = array(
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
				'Tableausuivipdv93.typerdv_id' => 'isarray',
				'Tableausuivipdv93.statutrdv_id' => 'isarray',
				'Tableausuivipdv93.numcodefamille.acteurs_sociaux' => 'isarray',
				'Tableausuivipdv93.numcodefamille.acteurs_sante' => 'isarray',
				'Tableausuivipdv93.numcodefamille.acteurs_culture' => 'isarray',
				'Tableausuivipdv93.conditionsPdv' => 'isarray',
				'Tableausuivipdv93.Tableau1b6.typerdv_id' => 'isarray',
				'Tableausuivipdv93.Tableau1b6.statutrdv_id_prevu_honore' => 'isarray',
				'Tableausuivipdv93.Tableau1b6.map_thematiques_themes' => 'isarray',
				'Cataloguepdifp93.urls' => 'isarray',
				'Ficheprescription93.regexpNumconventionFictif' => 'string',
				'Tableausuivi93.tableau1b4.conditions' => 'isarray',
				'Tableausuivi93.tableau1b4.categories' => 'isarray',
				'Tableausuivi93.tableau1b5.conditions' => 'isarray',
				'Tableausuivi93.tableau1b5.categories' => 'isarray',
			);

			if( Configure::read( 'Contratinsertion.RdvAuto.active' ) ) {
				$return = Hash::merge(
					$return,
					array(
						'Contratinsertion.RdvAuto.typerdv_id' => 'integer',
						'Contratinsertion.RdvAuto.statutrdv_id' => 'integer',
						'Contratinsertion.RdvAuto.thematiquerdv_id' => 'integer',
					)
				);
			}

			return $return;
		}

		/**
		 * Supprime les valeurs corespondant à des clés de configuration.
		 *
		 * @param array $configure
		 * @param array $remove Si remove est vide, les clés correspondent à celles du coeur de Cake
		 * @return array
		 */
		protected function _removeConfigureKeys( array $configure, array $remove = array() ) {
			if( empty( $remove ) ) {
				$remove = array( 'Acl', 'App', 'Cache', 'Cake', 'Config', 'Dispatcher', 'Error', 'Exception', 'Security', 'Session' );
			}

			$removeRegexp = '/^(('.implode( '|', $remove ).')\.|debug$)/';

			foreach( $configure as $key => $value ) {

				if( preg_match( $removeRegexp, $value ) ) {
					unset( $configure[$key] );
				}
			}

			$configure = array_values( $configure );

			return $configure;
		}

		/**
		 * Retourne les clés de configuration actuellement utilisées.
		 *
		 * @return array
		 */
		protected function _existingConfigureKeys( $core = false ) {
			$existing = array();
			foreach( Hash::flatten( (array)Configure::read() ) as $key => $value ) {
				$existing[] = preg_replace( '/\.[0-9]+$/', '', $key );
			}
			$existing = array_unique( $existing );
			sort( $existing );

			// On enlève les clés du coeur de Cake
			if( $core === false ) {
				$existing = $this->_removeConfigureKeys( $existing );
			}

			return $existing;
		}

		protected function _configureKeysDiff() {
			$existing = $this->_existingConfigureKeys();
			$expected = array_keys( $this->allConfigureKeys( Configure::read( 'Cg.departement' ) ) );

			$remove = array( 'Cmis', 'Filtresdefaut', 'Optimisations', 'Password' );
			$existing = $this->_removeConfigureKeys( $existing, $remove );
			$expected = $this->_removeConfigureKeys( $expected, $remove );

			debug( array_diff( $expected, $existing ) ); // Vérifiées mais non existantes
			debug( array_diff( $existing, $expected ) ); // Existantes mais non vérifiées
			// debug( $existing );
			// debug( $expected );
			// $this->_configureKeysDiff();
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
			$method = '_allConfigureKeys'.Configure::read( 'Cg.departement' );

			$configure = method_exists( $this, $method ) ? $this->{$method}() : array();
			$configure = Hash::merge( $this->_allConfigureKeysCommon(), $configure );

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
			$connected = Cmis::configured();

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
		 * @see Webrsacheck::querydataFragmentsErrors()
		 * @see Allocataire::testSearchConditions()
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

		/**
		 * Liste des clés de configurations de mails pour le CG 58.
		 *
		 * @return array
		 */
		protected function _allEmailConfigKeys58() {
			$return = array();

			if( Configure::read( 'Password.mail_forgotten' ) ) {
				$return[] = 'user_generation_mdp';
			}

			return $return;
		}

		/**
		 * Liste des clés de configurations de mails pour le CG 66.
		 *
		 * @return array
		 */
		protected function _allEmailConfigKeys66() {
			$return = array( 'apre66_piecesmanquantes', 'fiche_candidature', 'avis_technique_cui' );

			if( Configure::read( 'Password.mail_forgotten' ) ) {
				$return[] = 'user_generation_mdp';
			}

			return $return;
		}

		/**
		 * Liste des clés de configurations de mails pour le CG 93.
		 *
		 * @return array
		 */
		protected function _allEmailConfigKeys93() {
			$return = array();

			if( Configure::read( 'Password.mail_forgotten' ) ) {
				$return[] = 'user_generation_mdp';
			}

			return $return;
		}

		/**
		 * Vérification de la présence des configurations de mails suivant le CG.
		 *
		 * @return array
		 */
		public function allEmailConfigs() {
			$method = '_allEmailConfigKeys'.Configure::read( 'Cg.departement' );

			$configs = method_exists( $this, $method ) ? $this->{$method}() : array();

			return $configs;
		}

		/**
		 * Retourne les clés de configuration ainsi que le nom du modèle concerné,
		 * contenant une référence vers une clé primaire d'une table, suivant le
		 * CG connecté.
		 *
		 * @return array
		 */
		public function allConfigurePrimaryKeys() {
			$return = array();

			if( Configure::read( 'Cg.departement' ) == 58 ) {
				$return = array(
					'Typeorient.emploi_id' => 'Typeorient',
					// TODO: 'Selectionradies.conditions' ?
					'Sanctionseps58.selection.structurereferente_id' => 'Structurereferente',
				);
			}
			else if( Configure::read( 'Cg.departement' ) == 66 ) {
				$return = array(
					'Orientstruct.typeorientprincipale.SOCIAL' => 'Typeorient',
					'Orientstruct.typeorientprincipale.Emploi' => 'Typeorient',
					'Chargeinsertion.Secretaire.group_id' => 'Group',
					'Nonoriente66.notisemploi.typeorientId' => 'Typeorient',
					'Nonoriente66.TypeorientIdSocial' => 'Typeorient',
					'Nonoriente66.TypeorientIdPrepro' => 'Typeorient',
					// TODO: Contratinsertion.Cg66.Rendezvous ?
					'Corbeillepcg.descriptionpdoId' => 'Descriptionpdo',
                    'Rendezvous.Ajoutpossible.statutrdv_id' => 'Statutrdv',
					'Generationdossierpcg.Orgtransmisdossierpcg66.id' => 'Orgtransmisdossierpcg66',
					'Nonorganismeagree.Structurereferente.id' => 'Structurereferente',
					'ActioncandidatPersonne.Partenaire.id' => 'Partenaire',
					'ActioncandidatPersonne.Actioncandidat.typeregionId' => 'Actioncandidat',
					'ActioncandidatPersonne.Actioncandidat.typeregionPoursuitecgId' => 'Actioncandidat',
				);

			}
			else if( Configure::read( 'Cg.departement' ) == 93 ) {
				$return = array(
					'Chargeinsertion.Secretaire.group_id' => 'Group',
					'Orientstruct.typeorientprincipale.Socioprofessionnelle' => 'Typeorient',
					'Orientstruct.typeorientprincipale.Social' => 'Typeorient',
					'Orientstruct.typeorientprincipale.Emploi' => 'Typeorient',
					'Questionnaired1pdv93.rendezvous.statutrdv_id' => 'Statutrdv',
					// Tableaux PDV
					'Tableausuivipdv93.typerdv_id' => 'Typerdv',
					'Tableausuivipdv93.statutrdv_id' => 'Statutrdv',
					'Tableausuivipdv93.Tableau1b6.typerdv_id' => 'Typerdv',
					'Tableausuivipdv93.Tableau1b6.statutrdv_id_prevu_honore' => 'Statutrdv',
					'Tableausuivipdv93.Tableau1b6.map_thematiques_themes' => array(
						'modelName' => 'Thematiquerdv',
						'array_keys' => true
					),
				);

				if( Configure::read( 'Contratinsertion.RdvAuto.active' ) ) {
					$return = Hash::merge(
						$return,
						array(
							'Contratinsertion.RdvAuto.typerdv_id' => 'Typerdv',
							'Contratinsertion.RdvAuto.statutrdv_id' => 'Statutrdv',
							'Contratinsertion.RdvAuto.thematiquerdv_id' => 'Thematiquerdv',
						)
					);
				}

				$tmp = (array)Configure::read( 'Rendezvous.checkThematiqueAnnuelleParStructurereferente.statutrdv_id' );
				if( !empty( $tmp ) ) {
					$return['Rendezvous.checkThematiqueAnnuelleParStructurereferente.statutrdv_id'] = 'Statutrdv';
				}

				$tmp = (array)Configure::read( 'Rendezvous.thematiqueAnnuelleParStructurereferente' );
				if( !empty( $tmp ) ) {
					$return['Rendezvous.thematiqueAnnuelleParStructurereferente'] = 'Thematiquerdv';
				}
			}

			return $return;
		}


		/**
		 * Vérifie les fragments de querydata se trouvant en paramétrage dans le
		 * webrsa.inc pour tous les modèles concernés.
		 *
		 * @see Webrsacheck::allSqRechercheErrors()
		 *
		 * @return array
		 */
		public function allQuerydataFragmentsErrors() {
			$errors = array( );
			$modelNames = array( 'Statistiqueministerielle' );

			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$modelNames[] = 'Tableausuivipdv93';
			}

			foreach( $modelNames as $modelName ) {
				$Model = ClassRegistry::init( $modelName );

				$errors[$modelName] = $Model->querydataFragmentsErrors();

			}

			return $errors;
		}

		/**
		 * Vérifie la présence de l'ensemble des fonctions de la librairie
		 * PostgreSQL fuzzystrmatch.
		 *
		 * @return array
		 */
		public function checkPostgresFuzzystrmatchFunctions() {
			$Dbo = ClassRegistry::init( 'User' )->getDataSource();

			$version = $Dbo->getPostgresVersion();
			$shortversion = preg_replace( '/^([0-9]+\.[0-9]+).*/', '\1', $version );

			$functions = array(
				'levenshtein',
				'metaphone',
				'soundex',
				'text_soundex',
				'difference',
				'dmetaphone',
				'dmetaphone_alt',
				'cakephp_validate_in_list'
			);
			$conditions = array(
				'pg_proc.proname IN ( \''.implode( '\', \'', $functions ).'\' )'
			);
			$results = $Dbo->getPostgresFunctions( $conditions );
			$results = array_unique( Hash::extract( $results, '{n}.Function.name' ) );

			$missing = array_diff( $functions, $results );
			if( empty( $missing ) ) {
				$check = array(
					'success' => true,
					'message' => null
				);
			}
			else {
				$check = array(
					'success' => false,
					'message' => sprintf(
						"Problème avec les fonctions fuzzystrmatch (les fonctions suivantes sont manquantes: %s)<br/>Sous Ubuntu, il vous faut vérifier que le paquet postgresql-contrib-%s est bien installé. <br />Une fois fait, dans une console postgresql, en tant qu'administrateur, tapez: <code>\i /usr/share/postgresql/%s/contrib/fuzzystrmatch.sql</code>",
						implode( ', ', $missing ),
						$shortversion,
						$shortversion
					)
				);
			}

			return $check;
		}

		/**
		 * Vérifie si la date du serveur PostgreSQL correspond à la date du serveur Web.
		 * La tolérance est de moins d'une minute.
		 *
		 * Permet de déprécier PgsqlSchemaBehavior::pgCheckTimeDifference().
		 *
		 * @return array
		 */
		public function checkPostgresTimeDifference() {
			$Dbo = ClassRegistry::init( 'User' )->getDataSource();

			$message = 'Différence de date entre le serveur Web et le serveur de base de données trop importante.';

			$sqlAge = 'AGE( DATE_TRUNC( \'second\', localtimestamp ), \''.date( 'Y-m-d H:i:s' ).'\' )';
			$sqlAgeSuccess = "{$sqlAge} < '1 min'";
			$sql = "SELECT
						{$sqlAge} as value,
						$sqlAgeSuccess AS success,
						( CASE WHEN {$sqlAgeSuccess} THEN NULL ELSE '{$message}' END ) AS message;";
			$result = $Dbo->query( $sql );
			return $result[0][0];
		}

		/**
		 * Retourne la liste de toutes les clés contenant des expressions rationnelles
		 * configurées dans le webrsa.inc, par CG.
		 */
		public function allConfigureRegexps() {
			$return = array();

			$departement = Configure::read( 'Cg.departement' );
			if( $departement == 93 ) {
				$return[] = 'Ficheprescription93.regexpNumconventionFictif';
			}

			return $return;
		}

		/**
		 * Vérifie les expressions rationnelles configurées dans le fichier
		 * webrsa.inc.
		 */
		public function allConfigureRegexpsErrors() {
			$return = array();
			$paths = $this->allConfigureRegexps();

			foreach( $paths as $path ) {
				$pattern = Configure::read( $path );

				if( preg_test( $pattern ) ) {
					$check = array(
						'success' => true,
						'message' => null
					);
				}
				else {
					$check = array(
						'success' => false,
						'message' => sprintf(
							'L\'expression rationnelle «%s» définie par la clé «%s» dans le webrsa.inc est incorrecte.',
							$pattern,
							$path
						)
					);
				}

				$return[$path] = $check;
			}

			return $return;
		}
	}
?>