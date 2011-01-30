<?php
	/**
	* INFO: http://bakery.cakephp.org/articles/melgior/2010/01/26/simple-excel-spreadsheet-helper
	* http://onlamp.com/pub/a/onlamp/2006/05/11/postgresql-plpgsql.html
	*/

	App::import( 'Core', 'ConnectionManager' ); // CakePHP 1.2 fix

    class MpdShell extends AppShell
    {
		public $allConnections = array();

		public $defaultParams = array(
			'connection' => 'default',
			'log' => false,
			'logpath' => LOGS,
			'verbose' => true,
			'limit' => null,
			'module' => null,
			'schema' => null
		);

		public $verbose;

		/**
		* Initialisation: lecture des paramètres, on s'assure d'avoir une connexion
		* PostgreSQL valide
		*/

		public function initialize() {
			parent::initialize();

			$this->verbose = $this->_getNamedValue( 'verbose', 'boolean' );
			$this->module = $this->_getNamedValue( 'module', 'string' );
			$this->schema = $this->_getNamedValue( 'schema', 'string' );
			$this->limit = $this->_getNamedValue( 'limit', 'integer' );
			$connectionName = $this->_getNamedValue( 'connection', 'string' );

			try {
				$this->connection = @ConnectionManager::getDataSource( $connectionName );
			} catch (Exception $e) {
			}

			if( !$this->connection || !$this->connection->connected ) {
				$this->error( "Impossible de se connecter avec la connexion {$connectionName}" );
			}

			if( $this->connection->config['driver'] != 'postgres' ) {
				$this->error( "La connexion {$connectionName} n'utilise pas le driver postgres" );
			}
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
			$psqlVersion = $this->connection->query( 'SELECT version();' );
			$psqlVersion = Set::classicExtract( $psqlVersion, '0.0.version' );

			$this->out();
			$this->out( 'Shell de génération de MPD au format Dia pour PostgreSQL' );
			$this->out();
			$this->hr();
			$this->out();
			$this->out( 'Connexion : '. $this->connection->configKeyName );
			$this->out( 'Base de données : '. $this->connection->config['database'] );
			$this->out( $psqlVersion );
			$this->out();
			$this->hr();
		}

		/**
		*
		*/

		protected function _foreignKeys( $table ) {
			$sql = "SELECT
--						tc.constraint_type || ' ( ' || ccu.table_name || '.' ||ccu.column_name || ' )'
						ccu.table_name
					FROM information_schema.table_constraints tc
						LEFT JOIN information_schema.key_column_usage kcu ON (
							tc.constraint_catalog = kcu.constraint_catalog
							AND tc.constraint_schema = kcu.constraint_schema
							AND tc.constraint_name = kcu.constraint_name
						)
						LEFT JOIN information_schema.referential_constraints rc ON (
							tc.constraint_catalog = rc.constraint_catalog
							AND tc.constraint_schema = rc.constraint_schema
							AND tc.constraint_name = rc.constraint_name
						)
						LEFT JOIN information_schema.constraint_column_usage ccu ON (
							rc.unique_constraint_catalog = ccu.constraint_catalog
							AND rc.unique_constraint_schema = ccu.constraint_schema
							AND rc.unique_constraint_name = ccu.constraint_name
						)
					WHERE tc.table_name = '{$table}'
						AND tc.constraint_type = 'FOREIGN KEY';";

			$foreignKeys = $this->connection->query( $sql );
			return Set::classicExtract( $foreignKeys, '{n}.0.table_name' );
		}

		/**
		*
		*/

		protected function _schemas() {
			// FIXME: column length INT, FLOAT, ETC, ..
			$sql = 'SELECT nspname AS "Schema__name"
						FROM pg_namespace
						WHERE
							has_schema_privilege( nspname, \'USAGE\' )
							AND nspname NOT IN ( \'pg_catalog\', \'information_schema\' )
						ORDER BY nspname;';

			$schemas = $this->connection->query( $sql );
			if( empty( $schemas ) ) {
				$this->_stop( 1 );
			}

			foreach( $schemas as $i => $schema ) {
				if( ( empty( $this->schema ) || $this->schema == $schema['Schema']['name'] )
					&& ( $schema['Schema']['name'] == 'public' || empty( $this->module ) ) ) {

					$conditions = array( "information_schema.tables.table_schema = '{$schema['Schema']['name']}'" );

					if( $schema['Schema']['name'] == 'public' && $this->module == 'public.eps' ) {
						$tables_eps = array(
							'personnes',
							// EPs
							'dossierseps',
							// Thèmes
							'defautsinsertionseps66',
							'nonrespectssanctionseps93',
							'saisinesepdspdos66',
							'saisinesepsbilansparcours66',
							'saisinesepsreorientsrs93',
							'saisinesepssignalementsnrscers93',
							'saisineseps66',
							// Autres informations
							'relancesdetectionscontrats93',
							'bilansparcours66',
							// Paramétrage
							'motifsreorients',
							'maintiensreorientseps',
							'relancesnonrespectssanctionseps93',
							// Décisions
							'decisionsdefautsinsertionseps66',
							'decisionsnonrespectssanctionseps93',
							'nvsepdspdos66',
							'nvsrsepsreorient66',
							'nvsrsepsreorientsrs93',
							'avissrmreps93',
							// Séances
							'seanceseps', // FIXME: dossierseps_seanceseps ?
							// EPs
							'regroupementseps',
							'eps',
							'eps_zonesgeographiques',
							'eps_membreseps',
							'membreseps',
							'fonctionsmembreseps',
							'membreseps_seanceseps',
						);

						$conditions[] = "information_schema.tables.table_name IN ( '".implode( "',\n'", $tables_eps )."' )\n";
					}
					else if( $schema['Schema']['name'] == 'public' && $this->module == 'public.apres' ) {
						$tables_apres = array(
							'personnes',
							'aidesapres66_piecesaides66',
							'budgetsapres',
							'comitesapres_participantscomites',
							'integrationfichiersapre',
							'piecesapre',
							'relancesapres',
							'suivisaidesaprestypesaides',
							'suivisaidesapres',
							'themesapres66',
							'apres',
							'apres_piecesapre',
							'aidesapres66_piecescomptables66',
							'typesaidesapres66',
							'apres_comitesapres',
							'apres_etatsliquidatifs',
							'aidesapres66',
							'comitesapres',
							'tiersprestatairesapres',
							'piecesaides66_typesaidesapres66',
							'piecescomptables66_typesaidesapres66',
						);

						$conditions[] = "information_schema.tables.table_name IN ( '".implode( "',\n'", $tables_apres )."' )\n";
					}

					$sql = "SELECT
									information_schema.tables.table_name AS \"Table__name\",
									( select obj_description(oid) from pg_class where relname = information_schema.tables.table_name LIMIT 1) AS \"Table__comment\"
								FROM information_schema.tables
								WHERE
									".implode( ' AND ', $conditions )."
								ORDER BY table_name ASC".
								( empty( $this->limit ) ? null : " LIMIT {$this->limit}"  ).";";

					$tables = $this->connection->query( $sql );
					$schemas[$i]['Table'] = Set::classicExtract( $tables, '{n}.Table' );
					if( empty( $schemas[$i]['Table'] ) ) {
						$this->_stop( 1 );
					}

					foreach( $schemas[$i]['Table'] as $j => $table ) {
						if( $this->verbose ) {
							$this->out( "Lecture de la table {$schema['Schema']['name']}.{$table['name']}" );
						}
						$schemas[$i]['Table'][$j]['foreignkeys'] = $this->_foreignKeys( $table['name'] );
					}
				}
			}

			return $schemas;
		}

		/**
		*
		*/

		protected function _hash( $string, $length ) {
			$hash = hash( 'ripemd160', $string );
			$hashlength = strlen( $hash );

			$return = substr( $hash, 0, 12 );

			/*$remainder = substr( $hash, 12, ( $hashlength - $length ) );
			$remainder = preg_split( '//', $remainder );
			debug( $remainder );*/

			return $return;
		}

		/**
		*
		*/

		protected function _toXmi( $schemas ) {
			/*$entities = '';
			$listitems = '';
			$widgets = '';
			$associations = ''; // FIXME: + assocwidgets*/

			/*$rects = '';
			$connectors = '';*/

			$dias = '<?xml version="1.0" encoding="UTF-8"?>
<dia:diagram xmlns:dia="http://www.lysator.liu.se/~alla/dia/">
  <dia:diagramdata>
    <dia:attribute name="background">
      <dia:color val="#ffffff"/>
    </dia:attribute>
    <dia:attribute name="pagebreak">
      <dia:color val="#000099"/>
    </dia:attribute>
    <dia:attribute name="paper">
      <dia:composite type="paper">
        <dia:attribute name="name">
          <dia:string>#A4#</dia:string>
        </dia:attribute>
        <dia:attribute name="tmargin">
          <dia:real val="2.8222000598907471"/>
        </dia:attribute>
        <dia:attribute name="bmargin">
          <dia:real val="2.8222000598907471"/>
        </dia:attribute>
        <dia:attribute name="lmargin">
          <dia:real val="2.8222000598907471"/>
        </dia:attribute>
        <dia:attribute name="rmargin">
          <dia:real val="2.8222000598907471"/>
        </dia:attribute>
        <dia:attribute name="is_portrait">
          <dia:boolean val="true"/>
        </dia:attribute>
        <dia:attribute name="scaling">
          <dia:real val="1"/>
        </dia:attribute>
        <dia:attribute name="fitto">
          <dia:boolean val="false"/>
        </dia:attribute>
      </dia:composite>
    </dia:attribute>
    <dia:attribute name="grid">
      <dia:composite type="grid">
        <dia:attribute name="width_x">
          <dia:real val="1"/>
        </dia:attribute>
        <dia:attribute name="width_y">
          <dia:real val="1"/>
        </dia:attribute>
        <dia:attribute name="visible_x">
          <dia:int val="1"/>
        </dia:attribute>
        <dia:attribute name="visible_y">
          <dia:int val="1"/>
        </dia:attribute>
        <dia:composite type="color"/>
      </dia:composite>
    </dia:attribute>
    <dia:attribute name="color">
      <dia:color val="#d8e5e5"/>
    </dia:attribute>
    <dia:attribute name="guides">
      <dia:composite type="guides">
        <dia:attribute name="hguides"/>
        <dia:attribute name="vguides"/>
      </dia:composite>
    </dia:attribute>
  </dia:diagramdata>
  <dia:layer name="Background" visible="true">';
			$associations = '';

			foreach( $schemas as $schema ) {
				$tableindex = Set::classicExtract( $schema, 'Table.{n}.name' );
				if( !empty( $tableindex ) ) {
					foreach( $schema['Table'] as $i => $table ) {
						if( $this->verbose ) {
							$this->out( "Écriture de la table {$schema['Schema']['name']}.{$table['name']}" );
						}

						// Xmi
						//$id = $this->_hash( $table['name'], 12 );
						/*$entities .= '<UML:Entity visibility="public" isSpecification="false" namespace="80c790qoU1wd" isAbstract="false" isLeaf="false" isRoot="false" xmi.id="'.$id.'" name="'.$table['name'].'"/>'."\n";
						$listitems .= '<listitem open="1" type="832" id="'.$id.'"/>'."\n";
						$widgets .= '<entitywidget width="'.( 12 * strlen( $table['name'] ) ).'" x="90" y="'.( $i * 36 ).'" usesdiagramusefillcolor="1" usesdiagramfillcolor="1" isinstance="0" fillcolor="none" height="28" linecolor="none" xmi.id="'.$id.'" usefillcolor="1" linewidth="none" font="Sans Serif,12,-1,5,50,0,0,0,0,0"/>'."\n";*/

						// .odg
						/*$rects .= '<draw:rect draw:style-name="gr1" draw:text-style-name="P1" draw:id="id'.( $i + 1 ).'" draw:layer="layout" svg:width="'.str_replace( ',', '.', round( 0.21 * strlen( $table['name'] ), 2 ) ).'cm" svg:height="1cm" svg:x="3.54cm" svg:y="'.str_replace( ',', '.', ( $i * 1.1 ) ).'cm">
			<text:p text:style-name="P1">'.$table['name'].'</text:p>
		</draw:rect>'."\n";

						if( !empty( $table['foreignkeys'] ) ) {
							foreach( $table['foreignkeys'] as $foreigntable ) {
								$key = array_search( $foreigntable, $tableindex );
								if( $key !== false ) {
									$connectors .= '<draw:connector draw:style-name="gr2" draw:text-style-name="P1" draw:layer="layout" svg:x1="3.54cm" svg:y1="6.08cm" svg:x2="3.54cm" svg:y2="7.985cm" draw:start-shape="id'.( $i + 1 ).'" draw:start-glue-point="3" draw:end-shape="id'.( $key + 1 ).'" draw:end-glue-point="3" svg:d="m3540 6080h-501v1905h501">
							<text:p/>
						</draw:connector>'."\n";
								}
							}
						}*/

						$dias .= '<dia:object type="ER - Entity" version="0" id="O'.$i.'">
									<dia:attribute name="obj_pos">
										<dia:point val="27.45,8.5"/>
									</dia:attribute>
									<dia:attribute name="obj_bb">
										<dia:rectangle val="27.4,'.( $i * 2 ).'.0;30.65,'.( ( $i + 1 ) * 2 ).'.0"/>
									</dia:attribute>
									<dia:attribute name="elem_corner">
										<dia:point val="27.45,'.( $i * 2 ).'"/>
									</dia:attribute>
									<dia:attribute name="elem_width">
										<dia:real val="3.1499999999999999"/>
									</dia:attribute>
									<dia:attribute name="elem_height">
										<dia:real val="1.8"/>
									</dia:attribute>
									<dia:attribute name="border_width">
										<dia:real val="0.10000000000000001"/>
									</dia:attribute>
									<dia:attribute name="border_color">
										<dia:color val="#000000"/>
									</dia:attribute>
									<dia:attribute name="inner_color">
										<dia:color val="#ffffff"/>
									</dia:attribute>
									<dia:attribute name="name">
										<dia:string>#'.$table['name'].'#</dia:string>
									</dia:attribute>
									<dia:attribute name="weak">
										<dia:boolean val="false"/>
									</dia:attribute>
									<dia:attribute name="associative">
										<dia:boolean val="false"/>
									</dia:attribute>
									<dia:attribute name="font">
										<dia:font family="monospace" style="0" name="Courier"/>
									</dia:attribute>
									<dia:attribute name="font_height">
										<dia:real val="0.80000000000000004"/>
									</dia:attribute>
									</dia:object>'."\n";

						if( !empty( $table['foreignkeys'] ) ) {
							foreach( $table['foreignkeys'] as $j => $foreigntable ) {
								$key = array_search( $foreigntable, $tableindex );
								if( $key !== false ) {
									$associations .= '<dia:object type="ER - Participation" version="1" id="O'.( count( $tableindex ) + $i + $j ).'">
													<dia:attribute name="obj_pos">
														<dia:point val="30.225,15.4995"/>
													</dia:attribute>
													<dia:attribute name="obj_bb">
														<dia:rectangle val="30.175,10.3005;30.3,15.5495"/>
													</dia:attribute>
													<dia:attribute name="orth_points">
														<dia:point val="30.225,15.4995"/>
														<dia:point val="30.225,12.925"/>
														<dia:point val="30.25,12.925"/>
														<dia:point val="30.25,10.3505"/>
													</dia:attribute>
													<dia:attribute name="orth_orient">
														<dia:enum val="1"/>
														<dia:enum val="0"/>
														<dia:enum val="1"/>
													</dia:attribute>
													<dia:attribute name="autorouting">
														<dia:boolean val="true"/>
													</dia:attribute>
													<dia:attribute name="total">
														<dia:boolean val="false"/>
													</dia:attribute>
													<dia:connections>
														<dia:connection handle="0" to="O'.$i.'" connection="8"/>
														<dia:connection handle="1" to="O'.$key.'" connection="8"/>
													</dia:connections>
													</dia:object>'."\n";
								}
							}
						}
					}
				}
			}

			//return $entities."\n".$listitems."\n".$widgets."\n".$associations;
// 			return $entities."\n".$listitems."\n".$widgets."\n".$associations;
// 			return $rects.$connectors;
			return $dias.$associations.'  </dia:layer>
</dia:diagram>';
		}

		/**
		*
		*/

		public function main() {
			$this->out( "Démarrage à ".date( 'H:i:s' ) );
			if( $this->verbose ) {
				$this->hr();
			}

			$schemas = $this->_schemas();

			if( $this->verbose ) {
				$this->hr();
			}

			$html = $this->_toXmi( $schemas );
			file_put_contents( 'mpd.dia', $html );

			if( $this->verbose ) {
				$this->hr();
			}
			$this->out( "Terminé à ".date( 'H:i:s' ) );
		}

		/**
		* Aide
		*/

		public function help() {
			$this->log = false;

			$this->out("Usage: cake/console/cake mpd <paramètres>");
			$this->hr();
// 			$this->out();
// 			$this->out('Commandes:');
// 			$this->out("\n\t{$this->shell} all\n\t\tEffectue toutes les opérations de maintenance ( ".implode( ', ', $this->operations )." ).");
// 			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-connection <connexion>\n\t\tLe nom d'une connexion PostgreSQL défini dans app/config/database.php\n\t\tPar défaut: ".$this->_defaultToString( 'connection' )."\n");
// 			$this->out("\t-log <booléen>\n\t\tDoit-on journaliser la sortie du programme ?\n\t\tPar défaut: ".$this->_defaultToString( 'log' )."\n");
// 			$this->out("\t-logpath <répertoire>\n\t\tLe répertoire dans lequel enregistrer les fichiers de journalisation.\n\t\tPar défaut: ".$this->_defaultToString( 'logpath' )."\n");
			$this->out("\t-verbose <booléen>\n\t\tDoit-on afficher les étapes de lecture / écriture ?\n\t\tPar défaut: ".$this->_defaultToString( 'verbose' )."\n");
			$this->out("\t-limit <entier>\n\t\tLimite sur le nombre de tables à traiter.\n\t\tPar défaut: ".$this->_defaultToString( 'limit' )."\n");
			$this->out("\t-module <string>\n\t\tNom du module à traiter (disponible: public.apres, public.eps).\n\t\tPar défaut: ".$this->_defaultToString( 'module' )."\n");
			$this->out("\t-module <string>\n\t\tNom du schéma à traiter.\n\t\tPar défaut: ".$this->_defaultToString( 'schema' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
    }
?>