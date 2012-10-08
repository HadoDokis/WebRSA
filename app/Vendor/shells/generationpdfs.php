<?php
	/**
	*
	*/

	class GenerationpdfsShell extends AppShell
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

		public function orientsstructs() {
			// A-t-on spécifié l'identifiant d'un utilisateur (obligatoire dans ce cas-ci) ?
			$this->username = $this->_getNamedValue( 'username', 'string' );
			if( empty( $this->username ) ) {
				$this->err( "Veuillez spécifier l'identifiant d'un utilisateur qui sera utilisé pour la récupération d'informations lors de l'impression pour les impressions d'orientations (exemple: -username webrsa)." );
				$this->_stop( 1 );
			}

			// L'utilisateur existe-t'il
			$this->User = ClassRegistry::init( 'User' );
			$user = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'User.username' => $this->username
					),
					'recursive' => -1,
					'contain' => false,
				)
			);

			if( empty( $user ) ) {
				$this->err( "L'identifiant d'utilisateur spécifié n'existe pas." );
				$this->_stop( 1 );
			}


			$this->Orientstruct = ClassRegistry::init( 'Orientstruct' );

			$queryData = array(
				'fields' => array( 'Orientstruct.id' ),
				'conditions' => array(
					'Orientstruct.statut_orient' => 'Orienté',
					'Orientstruct.id NOT IN ( SELECT pdfs.fk_value FROM pdfs WHERE pdfs.modele = \'Orientstruct\' )'
				),
				'order' => array( 'Orientstruct.date_valid ASC' ),
				'recursive' => -1
			);

			if( !empty( $this->limit ) && is_numeric( $this->limit ) ) {
				$queryData['limit'] = $this->limit;
			}

			$orientsstructs = $this->Orientstruct->find( 'all', $queryData );

			$this->out( sprintf( "%s impressions à générer", count( $orientsstructs ) ) );

			$success = true;
			foreach( $orientsstructs as $i => $orientstruct ) {
				$this->out( sprintf( "Impression de l'orientation %s (id %s)", $i + 1, $orientstruct['Orientstruct']['id'] ) );
				$success = $this->Orientstruct->generatePdf( $orientstruct['Orientstruct']['id'], $user['User']['id'] ) && $success;
				if( !$success ) { // FIXME: pour les autres aussi
					$this->err( sprintf( "Erreur lors de l'impression de l'orientation %s (id %s)", $i + 1, $orientstruct['Orientstruct']['id'] ) );
					$this->_stop( ( $success ? 0 : 1 ) );
				}
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
		* Aide
		*/

		public function help() {
			$this->log = false;

			$this->out("Usage: cake/console/cake generationpdfs <paramètres>");
			$this->hr();
			$this->out();
			$this->out('Commandes:');
			$this->out("\n\t{$this->shell}\n\t\tAffiche cette aide.");
			$this->out("\n\t{$this->shell} relancenonrespectsanctionep93\n\t\tGénère les impressions des relances pour pour non respect et sanctions (CG 93).");
			$this->out("\n\t{$this->shell} orientsstructs\n\t\tGénère les impressions des orientations (le paramètre -username est obligatoire, voir ci-dessous).");
			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-username <string>\n\t\tL'identifiant de l'utilisateur qui sera utilisé pour la récupération d'informations lors de l'impression (pour les orientations seulement).\n\t\tPar défaut: ".$this->_defaultToString( 'username' )."\n");
			$this->out("\t-verbose <booléen>\n\t\tDoit-on afficher les étapes de lecture / écriture ?\n\t\tPar défaut: ".$this->_defaultToString( 'verbose' )."\n");
			$this->out("\t-limit <entier>\n\t\tLimite sur le nombre d'enregistrements à traiter.\n\t\tPar défaut: ".$this->_defaultToString( 'limit' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
	}
?>