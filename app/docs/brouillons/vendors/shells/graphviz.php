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
	* neato -Tpng -o graphviz.png mpd.dot
	* dot -Tpng -o graphviz.png mpd.dot
	*
	* cake/console/cake graphviz -schema public -module pcgs -linked true && dot -K fdp -T png -o ./graphviz_public_pcgs_linked.png ./graphviz_public_pcgs_linked.dot && gwenview ./graphviz_public_pcgs_linked.png > /dev/null  2>&1
	* cake/console/cake graphviz -schema public -module insertion -linked true && dot -K fdp -T png -o ./graphviz_public_insertion_linked.png ./graphviz_public_insertion_linked.dot && gwenview ./graphviz_public_insertion_linked.png > /dev/null  2>&1
	*/

	App::import( 'Core', 'ConnectionManager' ); // CakePHP 1.2 fix

	class GraphvizShell extends AppShell
	{
		public $allConnections = array();

		public $defaultParams = array(
			'connection' => 'default',
			'log' => false,
			'logpath' => LOGS,
			'verbose' => true,
			'linked' => false,
			'limit' => null,
			'module' => null,
			'schema' => null
		);

		public $verbose;

		/**
		*
		*/

		protected function _elementFillString( $table ) {
			$module = $this->Webrsa->moduleDeLaTable( $table );

			$color = "#FCFBD6";
			if( $module == 'eps' ) {
				$color = '#DCD9FF';
			}
			else if( $module == 'apres' ) {
				$color = '#E1FFD9';
			}
			else if( $module == 'pcgs' ) {
				$color = '#FFD9D9';
			}
			else if( $module == 'covs' ) {
				$color = '#D9FFFD';
			}
			else if( $module == 'caf' ) {
				$color = '#FFF0D9';
			}

			return ", style = \"filled\", fillcolor = \"{$color}\"";
		}

		/**
		* Initialisation: lecture des paramètres, on s'assure d'avoir une connexion
		* PostgreSQL valide
		*/

		public function initialize() {
			parent::initialize();

			$this->verbose = $this->_getNamedValue( 'verbose', 'boolean' );
			$this->module = $this->_getNamedValue( 'module', 'string' );
			$this->schema = $this->_getNamedValue( 'schema', 'string' );
			$this->linked = $this->_getNamedValue( 'linked', 'boolean' );
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

			$this->Webrsa = ClassRegistry::init( 'Webrsa' );
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
			$psqlVersion = $this->connection->query( 'SELECT version();' );
			$psqlVersion = Set::classicExtract( $psqlVersion, '0.0.version' );

			$this->out();
			$this->out( 'Shell de génération de MPD au format DOT pour PostgreSQL' );
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

		protected function _foreignKeys( $table, $module = null ) { // FIXME: + schema
			$tables = true;
			if( !empty( $module ) ) {
				$tables = $this->Webrsa->tables( $module );
			}

			$modelClass = ClassRegistry::init( Inflector::classify( $table ) );
			$modelClass->Behaviors->attach( 'Pgsqlcake.Schema' );

			return Set::merge( $modelClass->foreignKeysTo( $tables ), $modelClass->foreignKeysFrom( $tables ) );

			/*if( !empty( $module ) ) {
				$tables = $this->Webrsa->tables( $module );
				$module = "AND ( ccu.table_name IN ( '".implode( "', '", $tables )."' ) )";
			}
			else {
				$module = "";
			}

			$sql = "SELECT
						tc.constraint_name AS \"Foreignkey__name\",
						kcu.table_schema AS \"From__schema\",
						kcu.table_name AS \"From__table\",
						kcu.column_name AS \"From__column\",
						( CASE WHEN kcc.is_nullable = 'NO' THEN false ELSE true END ) AS \"From__nullable\",
						EXISTS(
							SELECT
									*
								FROM pg_catalog.pg_class c, pg_catalog.pg_class c2, pg_catalog.pg_index i
								WHERE
									c.oid = (
										SELECT
												c.oid
											FROM pg_catalog.pg_class c
											LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
											WHERE
												c.relname = kcu.table_name
												AND pg_catalog.pg_table_is_visible(c.oid)
												AND n.nspname = kcu.table_schema
									)
									AND c.oid = i.indrelid
									AND i.indexrelid = c2.oid
									AND i.indisunique
									AND regexp_replace( pg_catalog.pg_get_indexdef(i.indexrelid, 0, true), E'^.*\\((.*)\\)$', E'\\1', 'g') = kcu.column_name
						) AS \"From__unique\",
						ccu.table_schema AS \"To__schema\",
						ccu.table_name AS \"To__table\",
						ccu.column_name AS \"To__column\",
						( CASE WHEN ccc.is_nullable = 'NO' THEN false ELSE true END ) AS \"To__nullable\",
						EXISTS(
							SELECT
									*
								FROM pg_catalog.pg_class c, pg_catalog.pg_class c2, pg_catalog.pg_index i
								WHERE
									c.oid = (
										SELECT
												c.oid
											FROM pg_catalog.pg_class c
											LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
											WHERE
												c.relname = ccu.table_name
												AND pg_catalog.pg_table_is_visible(c.oid)
												AND n.nspname = ccu.table_schema
									)
									AND c.oid = i.indrelid
									AND i.indexrelid = c2.oid
									AND i.indisunique
									AND regexp_replace( pg_catalog.pg_get_indexdef(i.indexrelid, 0, true), E'^.*\\((.*)\\)$', E'\\1', 'g') = ccu.column_name
						) AS \"To__unique\"
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
						LEFT JOIN information_schema.columns kcc ON (
							kcu.table_schema = kcc.table_schema
							AND kcu.table_name = kcc.table_name
							AND kcu.column_name = kcc.column_name
						)
						LEFT JOIN information_schema.columns ccc ON (
							ccu.table_schema = ccc.table_schema
							AND ccu.table_name = ccc.table_name
							AND ccu.column_name = ccc.column_name
						)
					WHERE
						kcu.table_name = '{$table}'
						{$module}
						AND tc.constraint_type = 'FOREIGN KEY';";
			return $this->connection->query( $sql );*/
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

					if( $schema['Schema']['name'] == 'public' && !empty( $this->module ) ) {
						$tables = $this->Webrsa->tables( $this->module );
						$conditions[] = "information_schema.tables.table_name IN ( '".implode( "',\n'", $tables )."' )\n";
					}

					$sql = "SELECT
									information_schema.tables.table_schema AS \"Table__schema\",
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

					$tablesLiees = array();
					foreach( $schemas[$i]['Table'] as $j => $table ) {
						if( $this->verbose ) {
							$this->out( "Lecture de la table {$schema['Schema']['name']}.{$table['name']}" );
						}
						$foreignKeys = $this->_foreignKeys( $table['name'], ( $this->linked ? null : $this->module ) ); // FIXME: + schema
						$schemas[$i]['Table'][$j]['foreignkeys'] = $foreignKeys;

						if( !empty( $this->module ) && $this->linked ) {
							foreach( $foreignKeys as $foreignKey ) {
								$tablesLiees[] = array(
									'schema' => $foreignKey['From']['schema'], // FIXME
									'name' => $foreignKey['From']['table']
								);
								$tablesLiees[] = array(
									'schema' => $foreignKey['To']['schema'], // FIXME
									'name' => $foreignKey['To']['table']
								);
							}
						}
					}

					$schemas[$i]['Table'] = Set::merge( $schemas[$i]['Table'], $tablesLiees );
				}
			}

			return $schemas;
		}

		/**
		* cake/console/cake mpd -schema public -module eps && dot -Tpng -o mpd.png mpd.dia
		* http://en.wikipedia.org/wiki/Class_diagram
		* http://cyberzoide.developpez.com/graphviz/
		* sfdp -Goverlap=prism -Tpng -o mpd.public.png mpd.public.gd
		* http://www.graphviz.org/content/root
		* sfdp -Gsize=67! -Goverlap=prism -Tpng mpd.public.gd -o mpd.public.png
		*/

		protected function _toDot( $schemas ) {
			$tables = '';
			$associations = '';

			foreach( $schemas as $schema ) {
				$tableindex = Set::classicExtract( $schema, 'Table.{n}.name' );
				if( !empty( $tableindex ) ) {
					foreach( $schema['Table'] as $i => $table ) {
						if( $this->verbose ) {
							$this->out( "Écriture de la table {$schema['Schema']['name']}.{$table['name']}" );
						}

						$tables .= "\t\"{$table['name']}\" [ shape = \"record\" ".$this->_elementFillString( $table['name'] )." ];\n";

						if( !empty( $table['foreignkeys'] ) ) {
							foreach( $table['foreignkeys'] as $j => $foreignkey ) {
								// FIXME: prévoir un paramètre permettant de générer un fichier type entité/relation ou type diagramme de classes UML
								// http://upload.wikimedia.org/wikipedia/commons/thumb/f/f1/ERD_Representation.svg/320px-ERD_Representation.svg.png
								if( !$foreignkey['From']['nullable'] && !$foreignkey['From']['unique'] ) {
									$cardinalityFrom = '0..n';
									$cardinalityTo = '1';
								}
								else if( !$foreignkey['From']['nullable'] && $foreignkey['From']['unique'] ) {
									$cardinalityFrom = '1';
									$cardinalityTo = '1';
								}
								else if( $foreignkey['From']['nullable'] && !$foreignkey['From']['unique'] ) {
									$cardinalityFrom = '0..n';
									$cardinalityTo = '0..1';
								}
								else if( $foreignkey['From']['nullable'] && $foreignkey['From']['unique'] ) {
									$cardinalityFrom = '0..1';
									$cardinalityTo = '0..1';
								}

								$associations .= "\t\"{$foreignkey['From']['table']}\" -> \"{$foreignkey['To']['table']}\" [taillabel=\"{$cardinalityFrom}\", headlabel=\"{$cardinalityTo}\"];\n";//, label=\"{$foreignkey['Foreignkey']['name']}\"
							}
						}
					}
				}
			}

			$contents = "\n \toverlap = false;\n \tconcentrate = \"true\";\n \tsplines = \"polyline\";\n \toutputorder = \"nodesfirst\";\n \tpack = true;\n \tpackmode = \"clust\";\n\tfontname = \"Bitstream Vera Sans\"\n \tfontsize = 8\n \tpack = false\n \tpackMode = clust\n \n \tnode [\n \t\tfontname = \"Bitstream Vera Sans\"\n \t\tfontsize = 8\n \t\tshape = \"record\"\n \t]\n \n \tedge [\n \t\tfontname = \"Bitstream Vera Sans\"\n \t\tfontsize = 8\n \t\tarrowhead = \"none\"\n \t]\n\n";
			return "digraph G {{$tables}\n{$contents}\n{$associations}\n}";
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

			$html = $this->_toDot( $schemas );

			file_put_contents(
				'graphviz'
				.( !empty( $this->schema ) ? "_{$this->schema}" : '' )
				.( !empty( $this->module ) ? "_{$this->module}" : '' )
				.( !empty( $this->linked ) ? "_linked" : '' )
				.'.dot', $html );

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

			$this->out("Usage: cake/console/cake graphviz <paramètres>");
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