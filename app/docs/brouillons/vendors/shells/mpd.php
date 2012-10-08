<?php
	/**
	* INFO: http://bakery.cakephp.org/articles/melgior/2010/01/26/simple-excel-spreadsheet-helper
	* http://onlamp.com/pub/a/onlamp/2006/05/11/postgresql-plpgsql.html
	* INFO: placement -> http://www.graphviz.org/Theory.php
	* http://en.wikipedia.org/wiki/Graphviz
	* http://en.wikipedia.org/wiki/Dot_language (undirected graphs)
	* http://www.rbt.ca/autodoc/output-graphviz.html
	* http://lasmux.blogspot.com/2009/08/how-to-reverse-engineer-postgresql.html
	* dot -Tps g6.gd -o g6.ps
	* fdp -Tps g6.gd -o g6.ps
	*
	* sudo aptitude install postgresql-autodoc
	* postgresql_autodoc -h localhost -p 5432 -U webrsa -d cg93_20110621_0359_v2_1 -W
	* dot -Tpng -o output_lasma.png cg93_20110621_0359_v2_1.neato
	*
	* ratio="1";
	*
	* dot -Tpng -o mpd.png mpd.dia
	*
	* https://mailman.research.att.com/pipermail/graphviz-interest/2011q1/007622.html
	* ccomps -x mpd.dia | unflatten -l 3 | dot | gvpack | neato -n2 -Tpng > mpd.png
	*
	* http://www.karakas-online.de/forum/viewtopic.php?t=2647
	*
	* circo -Tpng -o mpd.public.png mpd.public.gd
	*
	* http://www.graphviz.org/doc/info/attrs.html
	* http://www.graphviz.org/doc/info/attrs.html#a:aspect
	* http://www.graphviz.org/Documentation/dotguide.pdf
	*
	* http://permalink.gmane.org/gmane.comp.video.graphviz/5980
	* http://www.adp-gmbh.ch/blog/2005/october/3.html
	* unflatten -fl2 mpd.public.gd > mpd.public.gd.test
	* dot -Tpng -o mpd.public.png mpd.public.test.gd.test
	* neato -Tpng -o mpd.public.png mpd.public.gd.test
	*
	* http://ridgway.co.za/archive/2007/01/05/GraphVizTtoGenerateERDsforSQL.aspx
	* https://mailman.research.att.com/pipermail/graphviz-interest/2006q2/003577.html
	*
	* cake/console/cake mpd -schema public && dot -K fdp -T png -o ./mpd.png ./mpd.dia && gwenview ./mpd.png > /dev/null 2>&1
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

/*
SELECT
--		c2.relname,
--		i.indisprimary,
--		i.indisunique,
--		i.indisclustered,
--		i.indisvalid,
--		pg_catalog.pg_get_indexdef(i.indexrelid, 0, true) as statement,
--		c2.reltablespace,
		regexp_replace( pg_catalog.pg_get_indexdef(i.indexrelid, 0, true), E'^.*\\((.*)\\)$', E'\\1', 'g') AS field
	FROM pg_catalog.pg_class c, pg_catalog.pg_class c2, pg_catalog.pg_index i
	WHERE
		c.oid = ( SELECT c.oid FROM pg_catalog.pg_class c LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace WHERE c.relname ~ '^(users)$' AND pg_catalog.pg_table_is_visible(c.oid) AND n.nspname ~ '^(public)$' )
		AND c.oid = i.indrelid AND i.indexrelid = c2.oid
		AND pg_catalog.pg_get_indexdef(i.indexrelid, 0, true) LIKE 'CREATE UNIQUE INDEX %'
		AND i.indisprimary = false
		AND pg_catalog.pg_get_indexdef(i.indexrelid, 0, true) NOT LIKE '%,%'
	ORDER BY i.indisprimary DESC, i.indisunique DESC, c2.relname;
*/

		/**
		*
		*/

		protected function _foreignKeys( $table ) {
			$sql = "SELECT
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
		* http://code.google.com/p/pgutils/source/browse/trunk/sql/pgutils.sql
		*/

		protected function _test( $tableFrom, $tableTo ) {
			$sql = "SELECT
							fkn.nspname AS fk_namespace,
							fkr.relname AS fk_relation,
							fka.attname AS fk_column,
							fka.attnotnull AS fk_notnull,
							(EXISTS (SELECT pg_index.indexrelid, pg_index.indrelid, pg_index.indkey, pg_index.indclass, pg_index.indnatts, pg_index.indisunique, pg_index.indisprimary, pg_index.indisclustered, pg_index.indexprs, pg_index.indpred FROM pg_index WHERE ((pg_index.indrelid = fkr.oid) AND (pg_index.indkey[0] = fka.attnum)))) AS fk_indexed,
							pkn.nspname AS pk_namespace,
							pkr.relname AS pk_relation,
							pka.attname AS pk_column,
							(EXISTS (SELECT pg_index.indexrelid, pg_index.indrelid, pg_index.indkey, pg_index.indclass, pg_index.indnatts, pg_index.indisunique, pg_index.indisprimary, pg_index.indisclustered, pg_index.indexprs, pg_index.indpred FROM pg_index WHERE ((pg_index.indrelid = pkr.oid) AND (pg_index.indkey[0] = pka.attnum)))) AS pk_indexed,
							((c.confupdtype)::text || (c.confdeltype)::text) AS ud,
							cn.nspname AS c_namespace,
							c.conname AS c_name
						FROM (((((((pg_constraint c JOIN pg_namespace cn ON ((cn.oid = c.connamespace))) JOIN pg_class fkr ON ((fkr.oid = c.conrelid))) JOIN pg_namespace fkn ON ((fkn.oid = fkr.relnamespace))) JOIN pg_attribute fka ON (((fka.attrelid = c.conrelid) AND (fka.attnum = ANY (c.conkey))))) JOIN pg_class pkr ON ((pkr.oid = c.confrelid))) JOIN pg_namespace pkn ON ((pkn.oid = pkr.relnamespace))) JOIN pg_attribute pka ON (((pka.attrelid = c.confrelid) AND (pka.attnum = ANY (c.confkey))))) WHERE (c.contype = 'f'::\"char\") AND fkr.relname = '{$tableFrom}' AND pkr.relname = '{$tableTo}';";

			$foreignKeys = $this->connection->query( $sql );
			debug( $foreignKeys );
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
							AND nspname NOT LIKE \'pg_%\'
						ORDER BY nspname;';

			$schemas = $this->connection->query( $sql );
			if( empty( $schemas ) ) {
				$this->_stop( 1 );
			}

			foreach( $schemas as $i => $schema ) {
				if( ( empty( $this->schema ) || $this->schema == $schema['Schema']['name'] )
					&& ( $schema['Schema']['name'] == 'public' || empty( $this->module ) ) ) {

					$conditions = array( "information_schema.tables.table_schema = '{$schema['Schema']['name']}'" );

					$Webrsa = ClassRegistry::init( 'Webrsa' );
					$modulesWebrsa = $Webrsa->modules();
					if( !empty( $this->module ) && in_array( $this->module, $modulesWebrsa ) ) {
						$conditions[] = "information_schema.tables.table_name IN ( '".implode( "',\n'", $Webrsa->tables( $this->module ) )."' )\n";
					}
					else if( !in_array( $this->module, $modulesWebrsa ) ) {
					}

					/*if( $schema['Schema']['name'] == 'public' && $this->module == 'eps' ) {
						$conditions[] = "( information_schema.tables.table_name ~ '.*eps[0-9]{0,2}$' ) OR ( information_schema.tables.table_name ~ '.*eps[0-9]{0,2}_.*$' )\n";
					}
					else if( $schema['Schema']['name'] == 'public' && $this->module == 'apres' ) {
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
					}*/

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
						//$this->_test( $table['name'], $schemas[$i]['Table'][$j]['foreignkeys'][0] );die();
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

			return $return;
		}

		/**
		*
		*/

		protected function _toXmi( $schemas ) {
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

			return $dias.$associations.'  </dia:layer>
</dia:diagram>';
		}

		/**
		* cake/console/cake mpd -schema public -module eps && dot -Tpng -o mpd.png mpd.dia
		* http://en.wikipedia.org/wiki/Class_diagram
		* http://cyberzoide.developpez.com/graphviz/
		* sfdp -Goverlap=prism -Tpng -o mpd.public.png mpd.public.gd
		* http://www.graphviz.org/content/root
		* sfdp -Gsize=67! -Goverlap=prism -Tpng mpd.public.gd -o mpd.public.png
		*/

		protected function _toGd( $schemas ) {
			$tables = '';
			$associations = '';

			foreach( $schemas as $schema ) {
				$tableindex = Set::classicExtract( $schema, 'Table.{n}.name' );
				if( !empty( $tableindex ) ) {
					foreach( $schema['Table'] as $i => $table ) {
						if( $this->verbose ) {
							$this->out( "Écriture de la table {$schema['Schema']['name']}.{$table['name']}" );
						}

						$tables .= "\t\"{$table['name']}\" [shape = record];\n";

						// FIXME: attention aux tables qui ne sont pas liées
						if( !empty( $table['foreignkeys'] ) ) {
							foreach( $table['foreignkeys'] as $j => $foreigntable ) {
								$key = array_search( $foreigntable, $tableindex );
								if( $key !== false ) {
									//$associations .= "\t\"{$table['name']}\" -> \"{$foreigntable}\" [taillabel=\"tail\", headlabel=\"head\"];\n";//[label=\"users_zonesgeographiques_zonegeographique_id_fkey\"]
									$associations .= "\t\"{$table['name']}\" -> \"{$foreigntable}\";\n";
								}
							}
						}
					}
				}
			}

// 			return "digraph g {\n node [ fontsize = \"10\", shape = record ];\n edge [];\n{$associations}\n}";
			$contents = "digraph G {\n \toverlap = false;\n \tconcentrate = \"true\";\n \tsplines = \"polyline\";\n \toutputorder = \"nodesfirst\";\n \tpack = true;\n \tpackmode = \"clust\";\n\tfontname = \"Bitstream Vera Sans\"\n \tfontsize = 8\n \tpack = false\n \tpackMode = clust\n \n \tnode [\n \t\tfontname = \"Bitstream Vera Sans\"\n \t\tfontsize = 8\n \t\tshape = \"record\"\n \t]\n \n \tedge [\n \t\tfontname = \"Bitstream Vera Sans\"\n \t\tfontsize = 8\n \t\tarrowhead = \"none\"\n \t]\n\n";
			$contents .= "{$tables}\n";
			$contents .= "{$associations}\n";
			$contents .= "\n}";
			return $contents;
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

			//$html = $this->_toXmi( $schemas );
			$html = $this->_toGd( $schemas );
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

			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-connection <connexion>\n\t\tLe nom d'une connexion PostgreSQL défini dans app/config/database.php\n\t\tPar défaut: ".$this->_defaultToString( 'connection' )."\n");

			$this->out("\t-verbose <booléen>\n\t\tDoit-on afficher les étapes de lecture / écriture ?\n\t\tPar défaut: ".$this->_defaultToString( 'verbose' )."\n");
			$this->out("\t-limit <entier>\n\t\tLimite sur le nombre de tables à traiter.\n\t\tPar défaut: ".$this->_defaultToString( 'limit' )."\n");
			$this->out("\t-module <string>\n\t\tNom du module à traiter (disponible: apres, eps).\n\t\tPar défaut: ".$this->_defaultToString( 'module' )."\n");
			$this->out("\t-schema <string>\n\t\tNom du schéma à traiter.\n\t\tPar défaut: ".$this->_defaultToString( 'schema' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
	}
?>