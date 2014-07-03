<?php
	/**
	 * Fichier source de la classe DictionnaireShell.
	 *
	 * PHP 5.3
	 *
	 * @package app.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'XShell', 'Console/Command' );

	/**
	 * La classe DictionnaireShell ...
	 *
	 * @url http://bakery.cakephp.org/articles/melgior/2010/01/26/simple-excel-spreadsheet-helper
	 * @url http://onlamp.com/pub/a/onlamp/2006/05/11/postgresql-plpgsql.html
	 *
	 * @package app.Console.Command
	 */
	class DictionnaireShell extends XShell
	{
		/**
		 * Les expressions régulières à utiliser pour les différents modules.
		 *
		 * @var array
		 */
		public $modules = array(
			'apres' => '(.*apres([0-9]{2}){0,1}_.*$|.*apres([0-9]{2}){0,1}$)',
			'eps' => '(.*eps([0-9]{2}){0,1}_.*$|.*eps([0-9]{2}){0,1}$)',
			'fichesprescriptions93' => '(.*fichesprescriptions93_.*$|.*fichesprescriptions93$|.*fps93_.*$|.*fps93$)',
		);

		/**
		 *
		 * @return type
		 */
		public function getOptionParser() {
			$parser = parent::getOptionParser();

			$parser->description( 'Ce script se charge de générer un fichier dictionnaire.html dans lequel se trouvent toutes les tables présentes en base de données avec la liste de leur champ, nom, valeur, traduction, …' );

			$options = array(
				'limit' => array(
					'short' => 'L',
					'help' => 'Limite sur le nombre de tables à traiter.',
					'default' => 0
				),
				'module' => array(
					'short' => 'm',
					'help' => 'le nom du module à traiter, pour limiter le dictionnaire aux tables de ce module uniquement.',
					'default' => '',
					'choices' => Hash::merge( array_keys( $this->modules ), array( '' ) )
				),
				'schema' => array(
					'short' => 's',
					'help' => 'le nom d\'un schéma pour limiter le dictionnaire aux tables de ce schéma uniquement',
					'default' => 'public'
				),
				'table' => array(
					'short' => 't',
					'help' => 'le nom d\'une table pour limiter le dictionnaire à cette table uniquement',
					'default' => null
				),
			);
			$parser->addOptions( $options );
			return $parser;
		}

		protected function _showParams() {
			parent::_showParams();
			if( !empty( $this->params['limit'] ) ) {
				$this->out( '<info>Nombre de table à traiter</info> : <important>'.$this->params['limit'].'</important>' );
			}
			if( !empty( $this->params['module'] ) ) {
				$this->out( '<info>Nom du module</info> : <important>'.$this->params['module'].'</important>' );
			}
			$this->out( '<info>Schéma</info> : <important>'.$this->params['schema'].'</important>' );
			if( !empty( $this->params['table'] ) ) {
				$this->out( '<info>Table</info> : <important>'.$this->params['table'].'</important>' );
			}
		}

		/**
		 *
		 */
		protected function _sqForeignKeyDetails( $table, $column ) {
			return "SELECT
						tc.constraint_type || ' ( ' || ccu.table_name || '.' ||ccu.column_name || ' )'
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
						AND tc.constraint_type = 'FOREIGN KEY'
						AND kcu.column_name = {$column}
					LIMIT 1";
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
							'.(!empty( $this->params['schema'] ) ? "AND nspname = '{$this->params['schema']}'" : '' ).'
						ORDER BY nspname;';

			$schemas = $this->connection->query( $sql );

			if( empty( $schemas ) ) {
				$this->_stop( 1 );
			}

			foreach( $schemas as $i => $schema ) {
				$conditionsModule = null;
				if( !empty( $this->params['module'] ) ) {
					$regexp = $this->modules[$this->params['module']];
					$conditionsModule = "AND ( information_schema.tables.table_name ~ '{$regexp}' )";
				}

				$sql = "SELECT
								information_schema.tables.table_name AS \"Table__name\",
								( select obj_description(oid) from pg_class where relname = information_schema.tables.table_name LIMIT 1 ) AS \"Table__comment\"
							FROM information_schema.tables
							WHERE information_schema.tables.table_schema = '{$schema['Schema']['name']}'
							{$conditionsModule}
							".( empty( $this->params['table'] ) ? "" : "AND ( information_schema.tables.table_name = '{$this->params['table']}' )\n" )."
							ORDER BY table_name ASC".
						( empty( $this->params['limit'] ) ? null : " LIMIT {$this->params['limit']}" ).";";

				$tables = $this->connection->query( $sql );
				$schemas[$i]['Table'] = Set::classicExtract( $tables, '{n}.Table' );

				if( empty( $schemas[$i]['Table'] ) ) {
					$this->_stop( 1 );
				}


				$this->_wait( 'Lecture des informations' );
				$this->XProgressBar->start( count( $schemas[$i]['Table'] ) );
				foreach( $schemas[$i]['Table'] as $j => $table ) {
					$this->XProgressBar->next( 1, "<info>Lecture de la table {$schema['Schema']['name']}.{$table['name']}</info>" );

					// TODO: foreign key
					// TODO: traductions enum
					$sql = "SELECT
									information_schema.columns.column_name AS \"Column__name\",
									( CASE WHEN information_schema.columns.data_type = 'USER-DEFINED' THEN UPPER( information_schema.columns.udt_name ) ELSE UPPER( information_schema.columns.data_type ) END ) AS \"Column__type\",
									information_schema.columns.character_maximum_length AS \"Column__length\",
									( CASE WHEN information_schema.constraint_column_usage.constraint_name LIKE '%_pkey' THEN 'PRIMARY KEY' ELSE ( ".$this->_sqForeignKeyDetails( $table['name'], 'information_schema.columns.column_name' )." ) END ) AS \"Column__key\",
									( select col_description( ( select oid from pg_class where relname = '{$table['name']}' LIMIT 1 ), information_schema.columns.ordinal_position ) ) AS \"Column__comment\",
									( CASE WHEN information_schema.columns.data_type = 'USER-DEFINED' THEN information_schema.columns.udt_name ELSE NULL END ) AS \"Column__options\"
								FROM information_schema.columns
									LEFT OUTER JOIN information_schema.constraint_column_usage ON (
										information_schema.columns.table_schema = information_schema.constraint_column_usage.table_schema
										AND information_schema.columns.table_name = information_schema.constraint_column_usage.table_name
										AND information_schema.columns.column_name = information_schema.constraint_column_usage.column_name
										AND information_schema.constraint_column_usage.constraint_name ILIKE '%_pkey'
									)
								WHERE
									information_schema.columns.table_schema = '{$schema['Schema']['name']}'
									AND information_schema.columns.table_name = '{$table['name']}'
								ORDER BY
									information_schema.columns.table_name,
									information_schema.columns.ordinal_position;";
					$columns = $this->connection->query( $sql );

					foreach( $columns as $k => $column ) {
						if( !empty( $column['Column']['options'] ) ) {
							$sql = "SELECT enum_range(null::{$column['Column']['options']});";
							$options = $this->connection->query( $sql );
							$options = $options[0][0]['enum_range'];
							$options = preg_replace( '/^\{(.*)\}$/', '\1', $options );
							$columns[$k]['Column']['options'] = explode( ',', $options );
						}
						else {
							$columns[$k]['Column']['options'] = array( );
						}
					}

					$schemas[$i]['Table'][$j]['Column'] = Set::classicExtract( $columns, '{n}.Column' );
					if( empty( $schemas[$i]['Table'][$j]['Column'] ) ) {
						$this->_stop( 1 );
					}
				}
			}

			return $schemas;
		}

		/**
		 *
		 */
		protected function _toHtml( $schemas ) {
			$title = 'Dictionnaire de données - '.$this->connection->config['database'].' - '.date( 'd/m/Y' );
			$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
					"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
						<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							<title>'.$title.'</title>
							<style type="text/css" media="all">
								body, table { font-size: 11px; font-family: sans-serif; }
								table { border-collapse: collapse; width: 100%;}
								th, td { border: 1px solid silver; padding: 0.25em 0.5em; font-weight: normal; }
								th { background: #f0f0f0; color: black; }
								td { vertical-align: top; }
								tr.even td { background: #fcfcfc; }
								pre {padding: 0; margin: 0;}
								h1, h2, h3 { font-weight: normal; }
								h1 { font-size: 2.2em; }
								h2 { font-size: 1.8em; }
								h3 { font-size: 1.5em; }
								th.name		{ width: 20%; }
								th.label	{ width: 15%; }
								th.comment	{ width: 15%; }
								th.type		{ width: 15%; }
								th.size		{ width: 5%; }
								th.options	{ width: 15%; }
								th.key		{ width: 15%; }
								td ul, td li { padding: 0; margin: 0; }
								td ul { margin-left: 1.2em; }
								/*dl { display: block; width: 100%; padding: 0; margin: 0; }
								dt, dd { padding: 0; margin: 0; display: inline-block; }
								dt { width: 33%; }
								dd { width: 66%; }*/
							</style>
						</head>
						<body>';

			$html .= "<h1>{$title}</h1>";


			$this->_wait( 'Ecriture des informations' );
			foreach( $schemas as $schema ) {
				$html .= "<h2>{$schema['Schema']['name']}</h2>";
				$this->XProgressBar->start( count( $schema['Table'] ) );
				foreach( $schema['Table'] as $table ) {
					$this->XProgressBar->next( 1, "<info>Génération des informations de la table {$schema['Schema']['name']}.{$table['name']}</info>" );
					$html .= "<h3>{$table['name']}</h3>";

					if( !empty( $table['comment'] ) ) {
						$html .= "<p>{$table['comment']}</p>";
					}

					$html .= '<table>';
					$html .= '<thead>
							<tr>
								<th class="name">Nom du champ</th>
								<th class="label">Libellé du champ</th>
								<th class="comment">Commentaire</th>
								<th class="type">Type de données</th>
								<th class="size">Taille du champ</th>
								<th class="options">Liste de choix / valeur</th>
								<th class="key">Origine / contrainte</th>
							</tr>
					</thead><tbody>';

					foreach( $table['Column'] as $i => $column ) {
						// Format label
						$modelField = Inflector::classify( $table['name'] ).".{$column['name']}";
						$label = __d( Inflector::singularize( $table['name'] ), $modelField );
						if( $label == $modelField ) {
							$label = null;
						}

						// Format options
						$options = $column['options'];
						if( !empty( $options ) ) {
							foreach( $options as $l => $option ) {
								$type = strtoupper( preg_replace( '/^type_(.*)$/i', '\1', $column['type'] ) );
								$labelkey = "ENUM::{$type}::{$option}";
								$labeloption = __( $labelkey );
								if( $labeloption == $labelkey ) {
									$labelkey = "ENUM::".strtoupper( $column['name'] )."::{$option}";
									$labeloption = __d( Inflector::singularize( $table['name'] ), $labelkey );
								}
								$options[$l] = "<li><strong>{$option}</strong> {$labeloption}</li>";
							}
							$options = '<ul>'.implode( '', $options ).'</ul>';
						}
						else {
							$options = null;
						}

						// Format row
						$html .= "<tr class=\"".( ( ( $i + 1 ) % 2 ) ? 'odd' : 'even' )."\">
								<td>".h( $column['name'] )."</td>
								<td>".h( $label )."</td>
								<td>".h( $column['comment'] )."</td>
								<td>".str_replace( ' ', '&nbsp;', h( $column['type'] ) )."</td>
								<td>".h( $column['length'] )."</td>
								<td>{$options}</td>
								<td>".str_replace( ' ', '&nbsp;', h( $column['key'] ) )."</td>
							</tr>";
					}

					$html .= '</tbody></table>';
				}
			}

			return $html.'	</body>
			</html>';
		}

		/**
		 *
		 */
		public function main() {
			$schemas = $this->_schemas();

			$filename = LOGS.'dictionnaire.html';
			file_put_contents( $filename, $this->_toHtml( $schemas ) );

			$this->out();
			$this->out();
			$this->out( '<info>Fichier généré : </info><important>'.$filename.'</important>' );
		}

	}
?>