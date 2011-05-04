<?php
// 	App::import( 'Core', 'ConnectionManager' ); // CakePHP 1.2 fix

    class AutomatisationsepsShell extends AppShell
    {
		/*public $allConnections = array();

		public $commandDescriptions = array(
			'reindex' => 'Reconstruction des indexes',
			'sequences' => 'Mise à jour des compteurs des champs auto-incrémentés',
			'vacuum' => 'Nettoyage de la base de données et mise à jour des statistiques du planificateur'
		);*/

		public $defaultParams = array(
			'connection' => 'default',
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false
		);

		public $verbose;

		/**
		* Initialisation: lecture des paramètres, on s'assure d'avoir une connexion
		* PostgreSQL valide
		*/

		public function initialize() {
			parent::initialize();

			$this->verbose = $this->_getNamedValue( 'verbose', 'boolean' );
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
			/*$this->out();
			$this->out( 'Script de maintenance de base de données PostgreSQL' );
			$this->out();
			$this->hr();
			$this->out();
			$this->out( 'Connexion : '. $this->connection->configKeyName );
			$this->out( 'Base de données : '. $this->connection->config['database'] );
			$this->out( $psqlVersion );
			$this->out();
			$this->hr();*/
		}

		public function main() {
			$this->Propopdo = ClassRegistry::init( 'Propopdo' );
			$this->Nonrespectsanctionep93 = ClassRegistry::init( 'Nonrespectsanctionep93' );

			$propospdos = $this->Propopdo->find(
				'all',
				array(
					'joins' => array(
						array(
							'table'      => 'decisionspropospdos',
							'alias'      => 'Decisionpropopdo',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Decisionpropopdo.propopdo_id = Propopdo.id' )
						),
						array(
							'table'      => 'decisionspdos',
							'alias'      => 'Decisionpdo',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Decisionpropopdo.decisionpdo_id = Decisionpdo.id' )
						),
					),
					'contain' => false,
					'conditions' => array(
						'Decisionpdo.libelle LIKE' => 'DO 19%',
						'Decisionpropopdo.datedecisionpdo IS NOT NULL',
						//La date de décision de la PDO doit être supérieure à celle de la validation du CER
						// Une fois la décision émise, le CEr doit être validé par la suite et non pas avant
						// + intervalle d'1 mois entre la date de décision et la validation du CER
						'Propopdo.personne_id NOT IN (
							SELECT contratsinsertion.personne_id
								FROM contratsinsertion
								WHERE
									contratsinsertion.personne_id = Propopdo.personne_id
									AND date_trunc( \'day\', contratsinsertion.datevalidation_ci ) >= Decisionpropopdo.datedecisionpdo
									AND date_trunc( \'day\', contratsinsertion.datevalidation_ci ) <= ( Decisionpropopdo.datedecisionpdo + INTERVAL \''.Configure::read( 'Nonrespectsanctionep93.intervalleCerDo19' ).'\' )
						)',
						// Et qui ne sont pas en EP
// 						'Propopdo.personne_id NOT IN (
// 							SELECT dossierseps.personne_id
// 								FROM dossierseps
// 								WHERE
// 									dossierseps.personne_id = Propopdo.personne_id
//  									AND dossierseps.etapedossierep <> \'traite\'
// 									AND dossierseps.themeep = \'nonrespectssanctionseps93\'
// 						)',
                        'Propopdo.personne_id NOT IN (
                            SELECT dossierseps.personne_id
                                FROM dossierseps
                                INNER JOIN passagescommissionseps ON (
                                    passagescommissionseps.dossierep_id = dossierseps.id )
                                WHERE
                                    dossierseps.personne_id = Propopdo.personne_id
                                    AND dossierseps.themeep = \'nonrespectssanctionseps93\'
                                    -- AND passagescommissionseps.etatdossierep NOT IN ( \'traite\', \'annule\' )
                        )',

					)
				)
			);
// debug($propospdos);
			if( count( $propospdos ) > 0 ) {
				$this->Propopdo->begin();
				$success = true;
				foreach( $propospdos as $propopdo ) {
					$nbpassagespcd = $this->Nonrespectsanctionep93->Dossierep->find(
						'count',
						array(
							'conditions' => array(
								'Dossierep.personne_id' => $propopdo['Propopdo']['personne_id'],
								'Dossierep.themeep' => 'nonrespectssanctionseps93',
							)
						)
					);

                 $dossierep = array(
                     'Dossierep' => array(
                         'personne_id' => $propopdo['Propopdo']['personne_id'],
                         'themeep' => 'nonrespectssanctionseps93',
                     ),
                     'Nonrespectsanctionep93' => array(
                         'propopdo_id' => $propopdo['Propopdo']['id'],
                         'origine' => 'pdo',
                         'rgpassage' => ( $nbpassagespcd + 1 )
                     )
                 );

					$success = $this->Nonrespectsanctionep93->saveAll( $dossierep, array( 'atomic' => false ) ) && $success;
				}

				if( $success ) {
					$this->Propopdo->commit();
					$this->out( sprintf( 'Succès pour l\'enregistrement des %s dossiers EP pour la thématique "non respect / sanctions (CG 93)"', count( $propospdos ) ) );
				}
				else {
					$this->Propopdo->rollback();
					$this->err( sprintf( 'Erreur(s) lors de l\'enregistrement des %s dossiers EP pour la thématique "non respect / sanctions (CG 93)"', count( $propospdos ) ) );
				}
			}
			else {
				$this->out( 'Aucun dossier EP pour la thématique "non respect / sanctions (CG 93)" à traiter' );
			}
		}

		/**
		* Aide
		*/

		/*public function help() {
			$this->log = false;

			$this->out("Usage: cake/console/cake postgresql <commande> <paramètres>");
			$this->hr();
			$this->out();
			$this->out('Commandes:');
			$this->out("\n\t{$this->shell} all\n\t\tEffectue toutes les opérations de maintenance ( ".implode( ', ', $this->operations )." ).");
			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");
			$this->out("\n\t{$this->shell} reindex\n\t\t{$this->commandDescriptions['reindex']}");
			$this->out("\n\t{$this->shell} sequences\n\t\t{$this->commandDescriptions['sequences']}");
			$this->out("\n\t{$this->shell} vacuum\n\t\t{$this->commandDescriptions['vacuum']}");
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-connection <connexion>\n\t\tLe nom d'une connexion PostgreSQL défini dans app/config/database.php\n\t\tPar défaut: ".$this->_defaultToString( 'connection' )."\n");
			$this->out("\t-log <booléen>\n\t\tDoit-on journaliser la sortie du programme ?\n\t\tPar défaut: ".$this->_defaultToString( 'log' )."\n");
			$this->out("\t-logpath <répertoire>\n\t\tLe répertoire dans lequel enregistrer les fichiers de journalisation.\n\t\tPar défaut: ".$this->_defaultToString( 'logpath' )."\n");
			$this->out("\t-verbose <booléen>\n\t\tDoit-on afficher les commandes SQL exéctuées ?\n\t\tPar défaut: ".$this->_defaultToString( 'verbose' )."\n");
			$this->out();

			$this->_stop( 0 );
		}*/
    }
?>
