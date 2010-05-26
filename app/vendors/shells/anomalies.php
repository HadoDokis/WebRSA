<?php
	/**
	* INFO: http://docs.postgresqlfr.org/8.2/maintenance.html
	*/

	App::import( 'Core', 'ConnectionManager' ); // CakePHP 1.2 fix

    class AnomaliesShell extends AppShell
    {
		public $allConnections = array();

		public $defaultParams = array(
			'connection' => 'default',
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false
		);

		public $verbose; // TODO

		protected $_checks = array(
			array(
				'text' => 'dossiers sans foyer',
 				'sql' => 'SELECT COUNT(*)
							FROM
							(
									SELECT DISTINCT( dossiers_rsa.id )
										FROM dossiers_rsa
								EXCEPT
									SELECT DISTINCT( foyers.dossier_rsa_id )
										FROM foyers
							) AS FOO'
			),
			array(
				'text' => 'foyers sans aucune personne',
 				'sql' => 'SELECT COUNT(*)
							FROM
							(
									SELECT DISTINCT( foyers.id )
										FROM foyers
								EXCEPT
									SELECT DISTINCT( personnes.foyer_id )
										FROM personnes
							) AS FOO'
			),
			array(
				'text' => 'foyers sans demandeur RSA',
 				'sql' => 'SELECT COUNT(*)
							FROM
							(
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
							) AS FOO'
			),
			array(
				'text' => 'foyers sans adresse_foyer',
				'sql' => 'SELECT COUNT(*)
							FROM
							(
									SELECT DISTINCT( foyers.id )
										FROM foyers
								EXCEPT
									SELECT DISTINCT( adresses_foyers.foyer_id )
										FROM adresses_foyers
							) AS FOO;'
			),
			array(
				'text' => 'foyers sans adresse_foyer de rang 01',
				'sql' => 'SELECT COUNT(*)
							FROM
							(
									SELECT DISTINCT( foyers.id )
										FROM foyers
								EXCEPT
									SELECT DISTINCT( adresses_foyers.foyer_id )
										FROM adresses_foyers
										WHERE adresses_foyers.rgadr = \'01\'
							) AS FOO;'
			),
			array(
				'text' => 'adresses_foyers de rang incorrect',
				'sql' => 'SELECT COUNT(adresses_foyers.*)
							FROM adresses_foyers
							WHERE
								adresses_foyers.rgadr NOT IN ( \'01\', \'02\', \'03\' );'
			),
			array(
				'text' => 'adresses_foyers en doublons',
				'sql' => 'SELECT COUNT(a1.*)
							FROM adresses_foyers AS a1,
								adresses_foyers AS a2
							WHERE
								a1.id < a2.id
								AND a1.foyer_id = a2.foyer_id
								AND a1.rgadr = a2.rgadr;'
			),
			array(
				'text' => 'adresses_foyers faisant reference au meme adresse_id',
				'sql' => 'SELECT COUNT(a1.*)
							FROM adresses_foyers AS a1,
								adresses_foyers AS a2
							WHERE
								a1.id < a2.id
								AND a1.adresse_id = a2.adresse_id;'
			),
			array(
				'text' => 'adresses sans adresses_foyers',
				'sql' => 'SELECT COUNT(*)
							FROM
							(
									SELECT DISTINCT( adresses.id )
										FROM adresses
								EXCEPT
									SELECT DISTINCT( adresses_foyers.adresse_id )
										FROM adresses_foyers
							) AS FOO;'
			),
			array(
				'text' => 'personnes en doublons',
				'sql' => 'SELECT COUNT(DISTINCT(p1.id))
							FROM personnes p1,
								personnes p2
							WHERE p1.id < p2.id
								AND
								(
									( LENGTH(TRIM(p1.nir)) = 15 AND p1.nir = p2.nir )
									OR ( p1.nom = p2.nom AND p1.prenom = p2.prenom AND p1.dtnai = p2.dtnai )
								)'
			),
			array(
				'text' => 'personnes sans prestation RSA',
 				'sql' => 'SELECT COUNT(*)
							FROM
							(
									SELECT DISTINCT( personnes.id )
										FROM personnes
								EXCEPT
									SELECT DISTINCT( prestations.personne_id )
										FROM prestations
										WHERE prestations.natprest = \'RSA\'
							) AS FOO'
			),
			array(
				'text' => 'prestations de meme nature et de meme role pour une personne donnee',
				'sql' => 'SELECT COUNT(p1.*)
							FROM prestations p1,
								prestations p2
							WHERE p1.id < p2.id
								AND p1.personne_id = p2.personne_id
								AND p1.natprest = p2.natprest
								AND p1.rolepers = p2.rolepers'
			),
			array(
				'text' => 'prestations de meme nature pour une personne donnee',
				'sql' => 'SELECT COUNT(p1.*)
							FROM prestations p1,
								prestations p2
							WHERE p1.id < p2.id
								AND p1.personne_id = p2.personne_id
								AND p1.natprest = p2.natprest'
			),
			array(
				'text' => 'non demandeurs ou non conjoints RSA possedant des orientsstrcuts orientees',
				'sql' => 'SELECT COUNT(*)
							FROM
							(
									SELECT DISTINCT( orientsstructs.personne_id )
										FROM orientsstructs
										WHERE orientsstructs.statut_orient = \'Orienté\'
								EXCEPT
									SELECT DISTINCT( prestations.personne_id )
										FROM prestations
										WHERE prestations.natprest = \'RSA\'
											AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
							) AS FOO'
			),
// 			array(
// 				'text' => 'orientsstrcuts pour des non demandeurs ou non conjoints RSA',
// 				'sql' => 'SELECT COUNT(orientsstructs.*)
// 							FROM orientsstructs
// 							WHERE
// 								orientsstructs.statut_orient = \'Orienté\'
// 								AND orientsstructs.personne_id NOT IN (
// 									SELECT prestations.personne_id
// 										FROM prestations
// 										WHERE prestations.natprest = \'RSA\'
// 											AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
// 								);'
// 			),
// 			array(
// 				'text' => 'non demandeurs ou non conjoints RSA possedant des apres',
// 				'sql' => 'SELECT COUNT(*)
// 							FROM
// 							(
// 									SELECT DISTINCT( apres.personne_id )
// 										FROM apres
// 								EXCEPT
// 									SELECT DISTINCT( prestations.personne_id )
// 										FROM prestations
// 										WHERE prestations.natprest = \'RSA\'
// 											AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
// 							) AS FOO'
// 			),
// 			array(
// 				'text' => 'apres pour des non demandeurs ou non conjoints RSA',
// 				'sql' => 'SELECT COUNT(apres.*)
// 							FROM apres
// 							WHERE
// 								apres.personne_id NOT IN (
// 									SELECT prestations.personne_id
// 										FROM prestations
// 										WHERE prestations.natprest = \'RSA\'
// 											AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
// 								);'
// 			),
// 			array(
// 				'text' => 'non demandeurs ou non conjoints RSA possedant des dsps',
// 				'sql' => 'SELECT COUNT(*)
// 							FROM
// 							(
// 									SELECT DISTINCT( dsps.personne_id )
// 										FROM dsps
// 								EXCEPT
// 									SELECT DISTINCT( prestations.personne_id )
// 										FROM prestations
// 										WHERE prestations.natprest = \'RSA\'
// 											AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
// 							) AS FOO'
// 			),
// 			array(
// 				'text' => 'dsps pour des non demandeurs ou non conjoints RSA',
// 				'sql' => 'SELECT COUNT(dsps.*)
// 							FROM dsps
// 							WHERE
// 								dsps.personne_id NOT IN (
// 									SELECT prestations.personne_id
// 										FROM prestations
// 										WHERE prestations.natprest = \'RSA\'
// 											AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
// 								);'
// 			)
// 			array(
// 				'text' => 'non demandeurs ou non conjoints RSA possedant des contratsinsertion',
// 				'sql' => 'SELECT COUNT(*)
// 							FROM
// 							(
// 									SELECT DISTINCT( contratsinsertion.personne_id )
// 										FROM contratsinsertion
// 								EXCEPT
// 									SELECT DISTINCT( prestations.personne_id )
// 										FROM prestations
// 										WHERE prestations.natprest = \'RSA\'
// 											AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
// 							) AS FOO'
// 			),
		);

		protected $_personnesLinkedQuery = array(
			'text' => 'non demandeurs ou non conjoints RSA possedant des %table%',
			'sql' => 'SELECT COUNT(*)
						FROM
						(
								SELECT DISTINCT( %table%.personne_id )
									FROM %table%
							EXCEPT
								SELECT DISTINCT( prestations.personne_id )
									FROM prestations
									WHERE prestations.natprest = \'RSA\'
										AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
						) AS FOO'
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
// 			'actionscandidats_personnes',
			'apres',
			'avispcgpersonnes',
			'calculsdroitsrsa',
			'contratsinsertion',
			'demandesreorient',
// 			'dspps',
			'dsps',
			'informationseti',
			'infosagricoles',
			'infospoleemploi',
			'orientations',
// 			'orientsstructs', // INFO: orientées, voir plus haut
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
			$connectionName = $this->_getNamedValue( 'connection', 'string' );

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
			$this->out();
			$this->hr();
		}

		/**
		* Par défaut, on affiche l'aide
		*/

		public function main() {
			$success = true;
			$this->out();

			foreach( $this->_checks as $check ) {
				$result = $this->connection->query( $check['sql'] );
				$result = Set::classicExtract( $result, '0.0.count' );
				$this->out(
					sprintf(
						"%s\t%s\t (%s ms)",
						str_pad( $check['text'], 80, " ", STR_PAD_RIGHT ),
						( $this->connection->error ? 'erreur' : $result ),
						$this->connection->took
					)
				);
				$success = ( !$this->connection->error ) && $success;
			}

			// Tables liéées à un demandeur ou conjoint RSA
			foreach( $this->_personnesLinkedTables as $table ) {
				$result = $this->connection->query( str_replace( '%table%', $table, $this->_personnesLinkedQuery['sql'] ) );
				$result = Set::classicExtract( $result, '0.0.count' );
				$this->out(
					sprintf(
						"%s\t%s\t (%s ms)",
						str_pad( str_replace( '%table%', $table, $this->_personnesLinkedQuery['text'] ), 80, " ", STR_PAD_RIGHT ),
						( $this->connection->error ? 'erreur' : $result ),
						$this->connection->took
					)
				);
				$success = ( !$this->connection->error ) && $success;
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
			$this->out("\t-log <booléen>\n\t\tDoit-on journaliser la sortie du programme ?\n\t\tPar défaut: ".$this->_defaultToString( 'log' )."\n");
			$this->out("\t-logpath <répertoire>\n\t\tLe répertoire dans lequel enregistrer les fichiers de journalisation.\n\t\tPar défaut: ".$this->_defaultToString( 'logpath' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
    }
?>