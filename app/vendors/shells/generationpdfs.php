<?php
	/**
	* TODO: faire un seul script (merge) avec le shell cohortepdfs
	*/

    class GenerationpdfsShell extends AppShell
    {
		public $defaultParams = array(
			'log' => false,
			'logpath' => LOGS,
			'verbose' => true,
			'limit' => null
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
			$this->out( 'Shell de génération de Pdf' );
			$this->out();
			$this->hr();
		}

		/**
		*
		*/

		public function relancenonrespectsanctionep93() {
			$this->Relancenonrespectsanctionep93 = ClassRegistry::init( 'Relancenonrespectsanctionep93' );

			$queryData = array(
				'fields' => array(
					'Relancenonrespectsanctionep93.id'
				),
				'conditions' => array(
					'Relancenonrespectsanctionep93.id NOT IN (
						SELECT pdfs.fk_value
							FROM pdfs
							WHERE pdfs.modele = \'Relancenonrespectsanctionep93\'
					)'
				)
			);

			if( !empty( $this->limit ) && is_numeric( $this->limit ) ) {
				$queryData['limit'] = $this->limit;
			}

			$relances = $this->Relancenonrespectsanctionep93->find( 'all', $queryData );

			$this->out( sprintf( "%s impressions à générer", count( $relances ) ) );

			$success = true;
			foreach( $relances as $i => $relance ) {
				$this->out( sprintf( "Impression de la relance %s (id %s)", $i + 1, $relance['Relancenonrespectsanctionep93']['id'] ) );
				$success = $this->Relancenonrespectsanctionep93->generatePdf( $relance['Relancenonrespectsanctionep93']['id'] ) && $success;
			}

			$this->_stop( ( $success ? 0 : 1 ) );
		}

		/**
		*
		*/

		public function main() {
			$this->help();
		}

		/**
		* Aide -> FIXME: un paramètre pour le type de pdf à générer
		*/

		public function help() {
			$this->log = false;

			$this->out("Usage: cake/console/cake generationpdfs <paramètres>");
			$this->hr();
			$this->out();
			$this->out('Commandes:');
			$this->out("\n\t{$this->shell}\n\t\tAffiche cette aide.");
			$this->out("\n\t{$this->shell} relancenonrespectsanctionep93\n\t\tGénère les impressions des relances pour pour non respect et sanctions (CG 93).");
			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-connection <connexion>\n\t\tLe nom d'une connexion PostgreSQL défini dans app/config/database.php\n\t\tPar défaut: ".$this->_defaultToString( 'connection' )."\n");
// 			$this->out("\t-log <booléen>\n\t\tDoit-on journaliser la sortie du programme ?\n\t\tPar défaut: ".$this->_defaultToString( 'log' )."\n");
// 			$this->out("\t-logpath <répertoire>\n\t\tLe répertoire dans lequel enregistrer les fichiers de journalisation.\n\t\tPar défaut: ".$this->_defaultToString( 'logpath' )."\n");
			$this->out("\t-verbose <booléen>\n\t\tDoit-on afficher les étapes de lecture / écriture ?\n\t\tPar défaut: ".$this->_defaultToString( 'verbose' )."\n");
			$this->out("\t-limit <entier>\n\t\tLimite sur le nombre de tables à traiter.\n\t\tPar défaut: ".$this->_defaultToString( 'limit' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
    }
?>