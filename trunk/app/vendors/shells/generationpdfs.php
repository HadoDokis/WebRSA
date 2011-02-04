<?php
	/**
	*
	*/

	App::import( 'Core', 'ConnectionManager' ); // CakePHP 1.2 fix

    class GenerationpdfsShell extends AppShell
    {
		public $allConnections = array();

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

		public function main() {
			$this->Relancenonrespectsanctionep93 = ClassRegistry::init( 'Relancenonrespectsanctionep93' );

			$relances = $this->Relancenonrespectsanctionep93->find(
				'all',
				array(
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
				)
			);

			$success = true;
			foreach( $relances as $relance ) {
				$this->Relancenonrespectsanctionep93->id = $relance['Relancenonrespectsanctionep93']['id'];
				$success = $this->Relancenonrespectsanctionep93->afterSave( false ) && $success;
			}

			$this->_stop( ( $success ? 0 : 1 ) );
		}

		/**
		* Aide -> FIXME: un paramètre pour le type de pdf à générer
		*/

		public function help() {
			$this->log = false;

			$this->out("Usage: cake/console/cake generationpdfs <paramètres>");
			$this->hr();
/*// 			$this->out();
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
			$this->out();*/

			$this->_stop( 0 );
		}
    }
?>