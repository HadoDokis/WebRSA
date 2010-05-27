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

		protected $_checks = array(
			array(
				'text' => 'dossiers sans foyer',
				'model' => 'Foyer',
				'queryData' => array(
					'conditions' => array(
						'Foyer.id IN (
							SELECT DISTINCT( dossiers_rsa.id )
								FROM dossiers_rsa
							EXCEPT
							SELECT DISTINCT( foyers.dossier_rsa_id )
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
			// .........
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
			// ..........
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
			// ..........
			array(
				'text' => 'non demandeurs ou non conjoints RSA possedant des dsps',
				'model' => 'Personne',
				'queryData' => array(
					'conditions' => array(
						'Personne.id IN (
							SELECT DISTINCT( orientsstructs.personne_id )
								FROM orientsstructs
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

			try {
				$this->connection = @ConnectionManager::getDataSource( $connectionName );
			} catch (Exception $e) {
			}

			if( !$this->connection || !$this->connection->connected ) {
				$this->error( "Impossible de se connecter avec la connexion {$connectionName}" );
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
								if( $field == 'dossier_rsa_id' ) {
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