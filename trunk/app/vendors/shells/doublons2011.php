<?php
	/**
	*
	*/

    class Doublons2011Shell extends AppShell
    {
		public $defaultParams = array(
			'log' => false,
			'logpath' => LOGS,
			'verbose' => true,
			'limit' => null,
			'username' => null,
		);

		public $verbose;

		/**
		*
		*/

		public function initialize() {
			parent::initialize();

			$this->verbose = $this->_getNamedValue( 'verbose', 'boolean' );
			$this->limit = $this->_getNamedValue( 'limit', 'integer' );
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
			$this->out();
			$this->out( 'Shell de vérification de doublons' );
			$this->out();
			$this->hr();
		}

		/**
		*
		*/

		public function main() {
			$this->Personne = ClassRegistry::init( 'Personne' );
			$sql = "SELECT
							*
						FROM information_schema.columns
						WHERE column_name = 'personne_id'
							AND table_schema = 'public'
							AND table_name NOT IN ( 'rattachements', 'dossierscaf', 'prestations', 'activites', 'calculsdroitsrsa' )
							AND table_name NOT LIKE '%58'
							AND table_name !~ 'eps[0-9]*$'
						ORDER BY table_name";
			$tables = $this->Personne->query( $sql );

			$countable = array( 1 => array(), 2 => array() );
			$fields = array( 'p1.id AS "P1__id"', 'p2.id AS "P2__id"' );
			foreach( $tables as $table ) {
				// On ne se préoccupe pas des tables n'ayant pas d'enregistrement
				$count = $this->Personne->query( "SELECT COUNT(*) FROM {$table[0]['table_name']};" );
				if( !empty( $count[0][0]['count'] ) ) {
					for( $i = 1 ; $i <= 2 ; $i++ ) {
						$countable[$i][] = "\"P{$i}__{$table[0]['table_name']}\"";

						if( $table[0]['table_name'] != 'orientsstructs' ) { // TODO: enlever prestations ?
							$fields[] = "( SELECT COUNT({$table[0]['table_name']}.*) FROM {$table[0]['table_name']} WHERE {$table[0]['table_name']}.personne_id = p{$i}.id ) AS \"P{$i}__{$table[0]['table_name']}\"";
						}
						else {
							$fields[] = "( SELECT COUNT({$table[0]['table_name']}.*) FROM {$table[0]['table_name']} WHERE {$table[0]['table_name']}.personne_id = p{$i}.id AND {$table[0]['table_name']}.statut_orient = 'Orienté' ) AS \"P{$i}__{$table[0]['table_name']}\"";
						}
					}
				}
			}

			$sql = "SELECT
							*,
							( ".implode( ' + ', $countable[1] )." ) AS \"P1__total\",
							( ".implode( ' + ', $countable[2] )." ) AS \"P2__total\"
						FROM (
							SELECT
									".implode( ', ', $fields )."
								FROM personnes p1, personnes p2
								WHERE
									p1.id <> p2.id
									AND p1.foyer_id = p2.foyer_id
									AND (
										( LENGTH(TRIM(p1.nir)) = 15 AND p1.nir = p2.nir AND p1.dtnai = p2.dtnai )
										OR ( p1.nom = p2.nom AND p1.prenom = p2.prenom AND p1.dtnai = p2.dtnai )
									)
						) AS S
						ORDER BY \"P1__total\" DESC, \"P2__total\" DESC
						LIMIT 10;";
			$results = $this->Personne->query( $sql );

			// -----------------------------------------------------------------

			$tbody = '';
			$thead = '';
			foreach( $results as $key => $result ) {
				if( $key == 0 ) {
					for( $i = 1 ; $i <= 2 ; $i++ ) {
						foreach( array_keys( $result["P{$i}"] ) as $table ) {
							$thead .= "<th>P{$i}.{$table}</th>";
						}
					}
					$thead = "<tr>{$thead}</tr>";
				}

				$row = '';

				for( $i = 1 ; $i <= 2 ; $i++ ) {
					foreach( $result["P{$i}"] as $table_name => $count ) {

						if( $table_name != 'total' ) {
							$class = null;
							if( $table_name == 'id' ) {
								$class = ' class="identifiant"';
							}
							else if( $count > 0 ) {
								$class = ' class="high"';
							}
							$row .= "<td style=\"text-align: right;\" $class title=\"{$table_name}\">{$count}</td>";
						}
						else {
							$class = null;
							if( $results[$key]["P{$i}"]['total'] > 0 ) { $class = ' class="high"'; }
							$row .= "<td style=\"text-align: right;\" {$class}><strong>{$results[$key]["P{$i}"]['total']}</strong></td>";
						}
					}
				}

				$tbody .= '<tr>'.$row.'</tr>';
			}

			$html = "<html><head><style type=\"text/css\" media=\"all\">table { border-collapse: collapse; } th, td { border: 1px solid silver; } td.high { background: yellow; } td.identifiant { background: #ffaaff; }</style></head><body><table><thead>$thead</thead><tbody>{$tbody}</tbody></table></body></html>";
			file_put_contents( 'doublons2011.html', $html );
		}

		/**
		*
		*/

		public function discriminants() {
			$this->Personne = ClassRegistry::init( 'Personne' );

			$fields = array( 'qual', 'nom', 'prenom', 'nomnai', 'prenom2', 'prenom3', /*'nomcomnai',*/ 'dtnai', 'rgnai', 'typedtnai', 'nir' );
			$qFields = array( 'p1.id AS "P1__id"', 'p2.id AS "P2__id"', 'p1.foyer_id AS "P1__foyer_id"', 'p2.foyer_id AS "P2__foyer_id"' );
			foreach( $fields as $field ) {
				$qFields[] = "p1.{$field} AS \"P1__{$field}\"";
				$qFields[] = "p2.{$field} AS \"P2__{$field}\"";
			}

			$sql = "SELECT
						".implode( ', ', $qFields )."
					FROM personnes p1, personnes p2
					WHERE
						p1.id <> p2.id
						AND p1.foyer_id = p2.foyer_id
						AND (
							( LENGTH(TRIM(p1.nir)) = 15 AND p1.nir = p2.nir AND p1.dtnai = p2.dtnai )
							OR ( p1.nom = p2.nom AND p1.prenom = p2.prenom AND p1.dtnai = p2.dtnai )
						)
						LIMIT 1000;";
			$results = $this->Personne->query( $sql );

			$thead = '<th>id</th><th>foyer_id</th>';
			foreach( $fields as $field ) {
				$thead .= "<th>{$field}</th>";
			}
			$thead = "<tr>{$thead}{$thead}</tr>";

			$tbody = '';
			foreach( $results as $key => $result ) {
				$row = '';
				for( $i = 1 ; $i <= 2 ; $i++ ) {
					$key = "P{$i}";
					$row .= "<td title=\"id\">{$result[$key]['id']}</td>";
					$row .= "<td title=\"foyer_id\">{$result[$key]['foyer_id']}</td>";

					foreach( $fields as $field ) {
						$class = null;
						if( $result['P1'][$field] != $result['P2'][$field] ) {
							$class = ' class="high"';
						}

						$row .= "<td {$class} title=\"{$field}\">{$result[$key][$field]}</td>";
					}
				}

				$tbody .= '<tr>'.$row.'</tr>';
			}

			$html = "<html><head><style type=\"text/css\" media=\"all\">table { border-collapse: collapse; } th, td { border: 1px solid silver; } td.high { background: yellow; } td.identifiant { background: #ffaaff; }</style></head><body><table><thead>$thead</thead><tbody>{$tbody}</tbody></table></body></html>";
			file_put_contents( 'discriminants.html', $html );
		}

		/**
		* Aide
		*/

		public function help() {
			$this->log = false;

			$this->out("Usage: cake/console/cake generationpdfs <paramètres>");
			$this->hr();
			$this->out();
			$this->out('Commandes:');
			$this->out("\n\t{$this->shell}\n\t\tAffiche cette aide.");
			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");

			$this->_stop( 0 );
		}
    }
?>