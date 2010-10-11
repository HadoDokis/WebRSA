<?php
	/**
	* INFO: http://docs.postgresqlfr.org/8.2/maintenance.html
	*/

	App::import( 'Core', 'ConnectionManager' ); // CakePHP 1.2 fix
	App::import( 'Core', 'Router' );
	define( 'FULL_BASE_URL', 'http://localhost/adullact/webrsa/trunk' ); // FIXME -> à paramétrer

    class AnomaliesrShell extends AppShell
    {
		public $allConnections = array();

		public $defaultParams = array(
			'connection' => 'default',
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false,
			'limit' => null
		);

		public $verbose;
		public $limit;
		public $connection = null;

		protected $_checks = array(
			array(
				'text' => 'dossiers sans foyer',
				'model' => 'Foyer',
				'queryData' => array(
					'conditions' => array(
						'Foyer.id IN (
							SELECT DISTINCT( dossiers.id )
								FROM dossiers
							EXCEPT
							SELECT DISTINCT( foyers.dossier_id )
								FROM foyers
						)'
					)
				)
			),
			array(
				'text' => 'foyers sans aucune personne',
				'model' => 'Foyer',
				'queryData' => array(
					'conditions' => array(
						'Foyer.id IN (
							SELECT DISTINCT( foyers.id )
								FROM foyers
							EXCEPT
							SELECT DISTINCT( personnes.foyer_id )
								FROM personnes
						)'
					)
				)
			),
			array(
				'text' => 'foyers sans demandeur RSA',
				'model' => 'Foyer',
				'queryData' => array(
					'conditions' => array(
						'Foyer.id IN (
							SELECT DISTINCT( foyers.id )
								FROM foyers
							EXCEPT
							SELECT DISTINCT( personnes.foyer_id )
								FROM personnes
								INNER JOIN prestations ON (
									prestations.personne_id = personnes.id
									AND prestations.natprest = \'RSA\'
									AND prestations.rolepers = \'DEM\'
								)
						)'
					)
				)
			),
			array(
				'text' => 'foyers sans adressefoyer',
				'model' => 'Foyer',
				'queryData' => array(
					'conditions' => array(
						'Foyer.id IN (
							SELECT DISTINCT( foyers.id )
								FROM foyers
							EXCEPT
							SELECT DISTINCT( adressesfoyers.foyer_id )
								FROM adressesfoyers
						)'
					)
				)
			),
			array(
				'text' => 'foyers sans adressefoyer de rang 01',
				'model' => 'Foyer',
				'queryData' => array(
					'conditions' => array(
						'Foyer.id IN (
							SELECT DISTINCT( foyers.id )
								FROM foyers
							EXCEPT
							SELECT DISTINCT( adressesfoyers.foyer_id )
								FROM adressesfoyers
								WHERE adressesfoyers.rgadr = \'01\'
						)'
					)
				)
			),
			array(
				'text' => 'adressesfoyers de rang incorrect',
				'model' => 'Adressefoyer',
				'queryData' => array(
					'conditions' => array(
						'Adressefoyer.rgadr NOT IN ( \'01\', \'02\', \'03\' )'
					)
				)
			),
			array(
				'text' => 'adressesfoyers en doublons',
				'model' => 'Adressefoyer',
				'queryData' => array(
					'conditions' => array(
						'Adressefoyer.id IN (
							SELECT DISTINCT(a1.id)
								FROM adressesfoyers AS a1,
									adressesfoyers AS a2
								WHERE
									a1.id < a2.id
									AND a1.foyer_id = a2.foyer_id
									AND a1.rgadr = a2.rgadr
						)'
					)
				)
			),
			array(
				'text' => 'adressesfoyers faisant reference au meme adresse_id',
				'model' => 'Adressefoyer',
				'queryData' => array(
					'conditions' => array(
						'Adressefoyer.id IN (
							SELECT DISTINCT(a1.id)
								FROM adressesfoyers AS a1,
									adressesfoyers AS a2
								WHERE
									a1.id < a2.id
									AND a1.adresse_id = a2.adresse_id
						)'
					)
				)
			),
			array(
				'text' => 'adresses sans adressesfoyers',
				'model' => 'Adresse',
				'queryData' => array(
					'conditions' => array(
						'Adresse.id IN (
							SELECT DISTINCT( adresses.id )
								FROM adresses
							EXCEPT
							SELECT DISTINCT( adressesfoyers.adresse_id )
								FROM adressesfoyers
						)'
					)
				)
			),
			array(
				'text' => 'personnes en doublons',
				'model' => 'Personne',
				'queryData' => array(
					'conditions' => array(
						'Personne.id IN (
							SELECT DISTINCT(p1.id)
							FROM personnes p1,
								personnes p2
							WHERE p1.id < p2.id
								AND
								(
									( LENGTH(TRIM(p1.nir)) = 15 AND p1.nir = p2.nir )
									OR ( p1.nom = p2.nom AND p1.prenom = p2.prenom AND p1.dtnai = p2.dtnai )
								)
						)'
					)
				)
			),
			array(
				'text' => 'personnes sans prestation RSA',
				'model' => 'Personne',
				'queryData' => array(
					'conditions' => array(
						'Personne.id IN (
							SELECT DISTINCT( personnes.id )
								FROM personnes
							EXCEPT
							SELECT DISTINCT( prestations.personne_id )
								FROM prestations
								WHERE prestations.natprest = \'RSA\'
						)'
					)
				)
			),
			array(
				'text' => 'personnes avec des noms ou des prenoms contenant des caracteres inattendus',
				'model' => 'Personne',
				'queryData' => array(
					'conditions' => array(
						'or' => array(
							"Personne.nom NOT SIMILAR TO '^[A-Z]+([A-Z \'\-]*)[A-Z]+$'",
							"Personne.prenom NOT SIMILAR TO '^[A-Z]+([A-Z \'\-]*)[A-Z]+$'"
						)
					)
				)
			),
			array(
				'text' => 'prestations de meme nature et de meme role pour une personne donnee',
				'model' => 'Prestation',
				'queryData' => array(
					'conditions' => array(
						'Prestation.id IN (
							SELECT p1.id
								FROM prestations p1,
									prestations p2
								WHERE p1.id < p2.id
									AND p1.personne_id = p2.personne_id
									AND p1.natprest = p2.natprest
									AND p1.rolepers = p2.rolepers
						)'
					)
				)
			),
			array(
				'text' => 'prestations de meme nature pour une personne donnee',
				'model' => 'Prestation',
				'queryData' => array(
					'conditions' => array(
						'Prestation.id IN (
							SELECT p1.id
								FROM prestations p1,
									prestations p2
								WHERE p1.id < p2.id
									AND p1.personne_id = p2.personne_id
									AND p1.natprest = p2.natprest
						)'
					)
				)
			),
			array(
				'text' => 'non demandeurs ou non conjoints RSA possedant des orientsstrcuts orientees',
				'model' => 'Personne',
				'queryData' => array(
					'conditions' => array(
						'Personne.id IN (
							SELECT DISTINCT( orientsstructs.personne_id )
								FROM orientsstructs
								WHERE orientsstructs.statut_orient = \'Orienté\'
							EXCEPT
							SELECT DISTINCT( prestations.personne_id )
								FROM prestations
								WHERE prestations.natprest = \'RSA\'
									AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
						)'
					)
				)
			),
		);

		protected $_personnesLinkedQuery = array(
			'text' => 'non demandeurs ou non conjoints RSA possedant des %table%',
			'model' => 'Personne',
			'queryData' => array(
				'conditions' => array(
					'Personne.id IN (
						SELECT DISTINCT( %table%.personne_id )
							FROM %table%
						EXCEPT
						SELECT DISTINCT( prestations.personne_id )
							FROM prestations
							WHERE prestations.natprest = \'RSA\'
								AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
					)'
				)
			)
		);

		/**
		* INFO: SELECT
		*		--		tc.constraint_name,
		*				tc.table_name,
		*		--		kcu.column_name,
		*		--		ccu.table_name AS foreign_table_name,
		*		--		ccu.column_name AS foreign_column_name
		*			FROM
		*				information_schema.table_constraints AS tc
		*				JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
		*				JOIN information_schema.constraint_column_usage AS ccu ON ccu.constraint_name = tc.constraint_name
		*			WHERE constraint_type = 'FOREIGN KEY'
		*				AND kcu.column_name='personne_id';
		*/

		protected $_personnesLinkedTables = array(
			'apres',
			'avispcgpersonnes',
			'calculsdroitsrsa',
			'contratsinsertion',
			'dsps',
			'informationseti',
			'infosagricoles',
			'infospoleemploi',
			'orientations',
			'parcours',
			'personnes_referents',
			'rendezvous',
			'suivisappuisorientation',
		);

		/**
		* Initialisation: lecture des paramètres, on s'assure d'avoir une connexion
		* valide
		*/

		public function initialize() {
			parent::initialize();

			$this->verbose = $this->_getNamedValue( 'verbose', 'boolean' );
			$this->log = ( $this->log || $this->verbose );
			$connectionName = $this->_getNamedValue( 'connection', 'string' );
			$this->limit = $this->_getNamedValue( 'limit', 'integer' );

			$connections = array_keys( ConnectionManager::enumConnectionObjects() );

			if( in_array( $connectionName, $connections ) ) {
				try {
					$this->connection = ConnectionManager::getDataSource( $connectionName );
				} catch (Exception $e) {
				}
			}

			if( !$this->connection || !$this->connection->connected ) {
				$this->error( "Impossible de se connecter avec la connexion {$connectionName} (essayez une connexion parmis ".implode( ", ", $connections ).")", null );
			}
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
			$this->out();
			$this->out( 'Détection des anomalies sur une BDD webrsa' );
			$this->out();
			$this->hr();
			$this->out();
			$this->out( 'Connexion : '. $this->connection->configKeyName );
			$this->out( 'Base de donnees : '. $this->connection->config['database'] );
			$this->out( 'Limite : '. $this->_valueToString( $this->limit ) );
			$this->out( 'Journalisation : '. $this->_valueToString( $this->log ) );
			$this->out( 'Fichiers de rapport : '. $this->_valueToString( $this->verbose ) );
			$this->out();
			$this->hr();
		}

		/**
		* Traitement d'une vérification
		*/

		protected function _check( $check, $generalOutfile ) {
			$model = ClassRegistry::init( array('class' => $check['model'], 'ds' => $this->connection->configKeyName ) );
			$check['queryData']['recursive'] = -1;

			if( $this->verbose ) {
				$outfile = preg_replace( '/(\.log)$/', '_'.Inflector::slug( $check['text'] ).'.log.html', $generalOutfile );

				if( !empty( $this->limit ) ) {
					$check['queryData']['limit'] = $this->limit;
				}

				$items = $model->find( 'all', $check['queryData'] );

				$this->out(
					sprintf(
						"%s\t%s",
						str_pad( $check['text'], 80, " ", STR_PAD_RIGHT ),
						count( $items )
					)
				);

				if( !empty( $items ) ) {
					$table = '';
					foreach( $items as $item ) {
						$row = '';

						foreach( $item[$check['model']] as $field => $value ) {
							if( $field == 'dossier_id' ) {
								$row .= '<td><a href="'.Router::url(
									array(
										'controller' => 'dossiers',
										'action' => 'view',
										$value
									),
									true
								).'">'.$value.'</a></td>';
							}
							else if( $field == 'foyer_id' || ( $check['model'] == 'Foyer' && $field == 'id' ) ) {
								$row .= '<td><a href="'.Router::url(
									array(
										'controller' => 'personnes',
										'action' => 'index',
										$value
									),
									true
								).'">'.$value.'</a></td>';
							}
							else if( $field == 'personne_id' || ( $check['model'] == 'Personne' && $field == 'id' ) ) {
								$row .= '<td><a href="'.Router::url(
									array(
										'controller' => 'personnes',
										'action' => 'view',
										$value
									),
									true
								).'">'.$value.'</a></td>';
							}
							else {
								$row .= '<td>'.$value.'</td>';
							}
						}
						$row = '<tr>'.$row.'</tr>';
						$table .= $row;
					}

					$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
							"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
							<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
								<head>
									<title>'.$check['text'].'</title>
									<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
									<style type="text/css" media="all">
										body { font-size: 12px; }
										table { border-collapse: collapse; }
										thead, tbody { border: 3px solid black; }
										th, td { border: 1px solid black; padding: 0.125em 0.25em; }
										tr.odd { background: #eee; }
									</style>
								</head><body>';
					$html .= '<h1>'.$check['text'].'</h1><p>Résultats: '.count( $items ).'</p><table><thead><tr><th>'.implode( '</th><th>', array_keys( $model->schema( true ) ) ).'</th></tr></thead><tbody>'.$table.'</tbody></table>';
					$html .= '</body></html>';

					file_put_contents( $outfile, $html );
				}
			}
			else {
				$count = $model->find( 'count', $check['queryData'] );
				$this->out(
					sprintf(
						"%s\t%s",
						str_pad( $check['text'], 80, " ", STR_PAD_RIGHT ),
						$count
					)
				);
			}

			return ( !$this->connection->error );
		}

		/**
		* Par défaut, on affiche l'aide
		*/

		public function main() {
			$generalOutfile = rtrim( $this->logpath, '/' ).'/'.$this->outfile;
			$escapedApp = str_replace( '/', '\/', APP );
			if( preg_replace( '/^'.$escapedApp.'/', '', $generalOutfile ) ) {
				$generalOutfile = 'app/'.preg_replace( '/^'.$escapedApp.'/', '', $generalOutfile );
			}

			$success = true;
			$this->out();

			foreach( $this->_checks as $check ) {
				$success = $this->_check( $check, $generalOutfile ) && $success;
			}

			// Tables liéées à un demandeur ou conjoint RSA
			foreach( $this->_personnesLinkedTables as $table ) {
				$check = $this->_personnesLinkedQuery;
				$check['text'] = str_replace( '%table%', $table, $check['text'] );
				$check['queryData']['conditions'] = str_replace( '%table%', $table, $check['queryData']['conditions'] );

				$success = $this->_check( $check, $generalOutfile ) && $success;
			}

			$this->out();

			$this->_stop( !$success );
		}

		/**
		* Aide
		*/

		public function help() {
			$this->log = false;

			$this->out("Usage: cake/console/cake {$this->script} <commande> <paramètres>");
			$this->hr();
			$this->out();
			$this->out('Commandes:');
			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-connection <connexion>\n\t\tLe nom d'une connexion PostgreSQL défini dans app/config/database.php\n\t\tPar défaut: ".$this->_defaultToString( 'connection' )."\n");
			$this->out("\t-limit <entier>\n\t\t...\n\t\tPar défaut: ".$this->_defaultToString( 'limit' )."\n");
			$this->out("\t-log <booléen>\n\t\tDoit-on journaliser la sortie du programme ?\n\t\tPar défaut: ".$this->_defaultToString( 'log' )."\n");
			$this->out("\t-logpath <répertoire>\n\t\tLe répertoire dans lequel enregistrer les fichiers de journalisation.\n\t\tPar défaut: ".$this->_defaultToString( 'logpath' )."\n");
			$this->out("\t-verbose <booléen>\n\t\t...\n\t\tPar défaut: ".$this->_defaultToString( 'verbose' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
    }
?>