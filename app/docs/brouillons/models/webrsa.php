<?php
	/**
	* Domaine métier: permet de lister les modules, tables et classes de modèles
	* de l'application.
	*
	* FIXME: Tables à supprimer ?
	*	- decisionsparcours
	*	- ressourcesmensuelles_detailsressourcesmensuelles
	*/

	class Webrsa extends AppModel
	{
		public $alias = 'Webrsa';

		public $useTable = false;

		protected $_models = array();

		protected $_tables = array();

		protected $_dataSource = null;

		protected $_modules = array(
			'apres' => array(
				'regex' => '(apres([0-9]{2}){0,1}$|apres([0-9]{2}){0,1}_)',
				'tables' => array(
					'accscreaentr',
					'accscreaentr_piecesaccscreaentr',
					'acqsmatsprofs',
					'acqsmatsprofs_piecesacqsmatsprofs',
					'actsprofs',
					'actsprofs_piecesactsprofs',
					'aidesapres66',
					'amenagslogts',
					'amenagslogts_piecesamenagslogts',
					'apres_comitesapres',
					'apres_etatsliquidatifs',
					'apres_piecesapre',
					'comitesapres_participantscomites',
					'etatsliquidatifs',
					'formspermsfimo',
					'formspermsfimo_piecesformspermsfimo',
					'formsqualifs',
					'formsqualifs_piecesformsqualifs',
					'fraisdeplacements66',
					'integrationfichiersapre',
					'locsvehicinsert',
					'locsvehicinsert_pieceslocsvehicinsert',
					'montantsconsommes',
					'parametresfinanciers',
					'participantscomites',
					'permisb',
					'permisb_piecespermisb',
					'piecesaccscreaentr',
					'piecesacqsmatsprofs',
					'piecesactsprofs',
					'piecesamenagslogts',
					'piecesapre',
					'piecesaides66',
					'piecescomptables66',
					'piecesformspermsfimo',
					'piecesformsqualifs',
					'pieceslocsvehicinsert',
					'piecespermisb',
					'precosreorients',
					'relancesapres',
					'suivisaidesaprestypesaides',
				)
			),
			'caf' => array(
				'tables' => array(
					'activites',
					'adresses',
					'adressesfoyers',
					'aidesagricoles',
					'allocationssoutienfamilial',
					'anomalies',
					'avispcgdroitsrsa',
					'avispcgpersonnes',
					'calculsdroitsrsa',
					'conditionsactivitesprealables',
					'condsadmins',
					'controlesadministratifs',
					'creances',
					'creancesalimentaires',
					'derogations',
					'detailsaccosocfams',
					'detailsaccosocindis',
					'detailscalculsdroitsrsa',
					'detailsconforts',
					'detailsdifdisps',
					'detailsdiflogs',
					'detailsdifsocpros',
					'detailsdifsocs',
					'detailsdroitsrsa',
					'detailsfreinforms',
					'detailsmoytrans',
					'detailsnatmobs',
					'detailsprojpros',
					'detailsressourcesmensuelles',
					'detailsressourcesmensuelles_ressourcesmensuelles',
					'dossiers',
					'dossierscaf',
					'dsps',
					'evenements',
					'foyers',
					'grossesses',
					'identificationsflux',
					'informationseti',
					'infosagricoles',
					'infosfinancieres',
					'liberalites',
					'modescontact',
					'orientations',
					'paiementsfoyers',
					'parcours',
					'personnes',
					'prestations',
					'rattachements',
					'reducsrsa',
					'ressources',
					'ressources_ressourcesmensuelles',
					'ressourcesmensuelles',
					'ressourcesmensuelles_detailsressourcesmensuelles',
					'situationsdossiersrsa',
					'suivisappuisorientation',
					'suivisinstruction',
					'suspensionsdroits',
					'suspensionsversements',
					'titressejour',
					'totalisationsacomptes',
					'transmissionsflux',
				)
			),
			'insertion' => array(
				'tables' => array(
					'actions',
					'actionsinsertion',
					'actionscandidats',
					'actionscandidats_partenaires',
					'actionscandidats_personnes',
					'actionscandidats_zonesgeographiques',
					'aidesdirectes',
					'autresavisradiation',
					'autresavissuspension',
					'bilanparcours',
					'bilansparcours66',
					'contactspartenaires',
					'contactspartenaires_partenaires',
					'contratsinsertion',
					'contratsinsertion_users',
					'cuis',
					'decisionsparcours',
					'historiqueetatspe',
					'informationspe',
					'motifssortie',
					'orientsstructs',
					'orientsstructs_servicesinstructeurs',
					'partenaires',
					'periodesimmersion',
					'permanences',
					'personnes_referents',
					'prestsform',
					'referents',
					'refsprestas',
					'structuresreferentes',
					'typesactions',
					'typesorients',
					'typoscontrats',
				)
			),
			'pdos' => array(
				'regex' => '((pdo|pcg)s([0-9]{2}){0,1}$|(pdo|pcg)s([0-9]{2}){0,1}_)',
				'tables' => array(
				)
			),
			'impressions' => array(
				'regex' => null,
				'tables' => array(
					'personnes',
					'orientsstructs',
					'contratsinsertion',
					'typesorients',
					'structuresreferentes',
					'dossierseps',
					'passagescommissionseps',
					'relancesnonrespectssanctionseps93',
					'decisionsnonrespectssanctionseps93',
					'nonrespectssanctionseps93',
					'propospdos',
					'historiqueetatspe',
					'informationspe',
					'reorientationseps93',
					'decisionsreorientationseps93',
					'users',
				)
			),
			'webrsa' => array(
				'tables' => array(
					'acos',
					'aros',
					'aros_acos',
					'cantons',
					'connections',
					'contratsinsertion',
					'cuis',
					'departements',
					'detailsaccosocfams_revs',
					'detailsaccosocindis_revs',
					'detailscalculsdroitsrsa',
					'detailsconforts_revs',
					'detailsdifdisps_revs',
					'detailsdiflogs_revs',
					'detailsdifsocpros_revs',
					'detailsdifsocs_revs',
					'detailsfreinforms_revs',
					'detailsmoytrans_revs',
					'detailsnatmobs_revs',
					'detailsprojpros_revs',
					'domiciliationsbancaires',
					'dsps_revs',
					'entretiens',
					'fichiersmodules',
					'groups',
					'jetons',
					'jetonsfonctions',
					'memos',
					'objetsentretien',
					'pdfs',
					'regroupementszonesgeo',
					'regroupementszonesgeo_zonesgeographiques',
					'rendezvous',
					'servicesinstructeurs',
					'statutsrdvs',
					'structuresreferentes_zonesgeographiques',
					'typesrdv',
					'users',
					'users_zonesgeographiques',
					'zonesgeographiques',
				)
			)
		);

		/**
		*
		*/

		public function modules() {
			return array(
				'apres',
				'caf',
				'covs',
				'eps',
				'insertion',
				'pdos',
				'webrsa',
			);
		}

		/**
		*
		*/

		protected function _dataSource() {
			if( is_null( $this->_dataSource ) ) {
				$modelClass = ClassRegistry::init( 'User' );
				$this->_dataSource = $modelClass->getDataSource( $modelClass->useDbConfig );
			}

			return $this->_dataSource;
		}

		/**
		*
		*/

		protected function _cacheKey() {
			$args = func_get_args();
			$args =  Set::filter( $args );
			$ds = $this->_dataSource();
			$cacheKey = Inflector::underscore( __CLASS__ ).'_'.$ds->configKeyName;

			if( !empty( $args ) ) {
				foreach( $args as $arg ) {
					$cacheKey .= '_'.Inflector::underscore( $arg );
				}
			}

			return $cacheKey;
		}

		/**
		*
		*/

		protected function _tablesConditions( $field, $module = null ) {
			if( empty( $module ) ) {
				return "{$field} IS NOT NULL";
			}
			else if( isset( $this->_modules[$module] ) ) {
				$conditions = array();
				if( isset( $this->_modules[$module]['regex'] ) && !empty( $this->_modules[$module]['regex'] ) ) {
					$conditions[] = "{$field} ~ '{$this->_modules[$module]['regex']}'";
				}
				if( isset( $this->_modules[$module]['tables'] ) && !empty( $this->_modules[$module]['tables'] ) ) {
					$conditions[] = "{$field} IN ( '".implode( "', '", (array)$this->_modules[$module]['tables'] )."' )";
				}
				return implode( ' OR ', $conditions );
			}
			else {
				return "{$field} ~ '({$module}([0-9]{2}){0,1}$|{$module}([0-9]{2}){0,1}_)'";
			}
		}

		/**
		*
		*/

		protected function _filterModels( $models, $module = null ) {
			if( empty( $module ) ) {
				return $models;
			}

			$return = array();
			if( isset( $this->_modules[$module] ) ) {
				$regex = "/^$/i";
				$tables = array();

				if( isset( $this->_modules[$module]['regex'] ) && !empty( $this->_modules[$module]['regex'] ) ) {
					$regex = "/{$this->_modules[$module]['regex']}/i";
				}
				if( isset( $this->_modules[$module]['tables'] ) && !empty( $this->_modules[$module]['tables'] ) ) {
					$tables = $this->_modules[$module]['tables'];
				}

				foreach( $models as $model ) {
					$tableName = Inflector::tableize( $model );
					if( preg_match( $regex, $tableName ) || in_array( $tableName, $tables ) ) {
						$return[] = $model;
					}
				}
			}
			else {
				$regex = "/({$module}([0-9]{2}){0,1}$|{$module}([0-9]{2}){0,1}_)/i";

				foreach( $models as $model ) {
					if( preg_match( $regex, Inflector::tableize( $model ) ) ) {
						$return[] = $model;
					}
				}
			}

				return $return;
		}

		/**
		*
		*/

		public function tables( $module = null ) {
			if( !isset( $this->_tables[$module] ) ) {
				$cacheKey = $this->_cacheKey( __FUNCTION__, $module );
				$this->_tables[$module] = Cache::read( $cacheKey );

				if( $this->_tables[$module] === false ) {
					$dataSource = $this->_dataSource();

					$sql = "SELECT
									table_name AS \"Webrsa__table\"
								FROM INFORMATION_SCHEMA.tables AS \"Webrsa\"
								WHERE
									table_schema = '{$dataSource->config['schema']}'
									AND ".$this->_tablesConditions( 'table_name', $module )."
								ORDER BY table_name ASC;";

					$this->_tables[$module] = $dataSource->query( $sql );
					$this->_tables[$module] = Set::extract( '/Webrsa/table', $this->_tables[$module] );

					Cache::write( $cacheKey, $this->_tables[$module] );
				}
			}

			return $this->_tables[$module];
		}

		/**
		*
		*/

		public function models( $module = null ) {
			if( !isset( $this->_models[$module] ) ) {
				$cacheKey = $this->_cacheKey( __FUNCTION__, $module );
				$this->_models[$module] = Cache::read( $cacheKey );

				if( $this->_models[$module] === false ) {
					$this->_models[$module] = Configure::listObjects( 'model' );
					sort( $this->_models[$module] );
					$this->_models[$module] = $this->_filterModels( $this->_models[$module], $module );

					Cache::write( $cacheKey, $this->_models[$module] );
				}
			}

			return $this->_models[$module];
		}

		/**
		* FIXME: nom
		*/

		public function moduleDeLaTable( $tableName ) {
			foreach( $this->modules() as $module ) {
				if( in_array( $tableName, $this->tables( $module ) ) ) {
					return $module;
				}
			}

			return null;
		}

		/**
		* FIXME: nom
		*/

		public function moduleDuModele( $modelName ) {
			return $this->moduleDeLaTable( Inflector::tableize( $modelName ) );
		}
	}
?>