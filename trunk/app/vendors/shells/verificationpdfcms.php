<?php
	require_once( APPLIBS.'cmis.php' );

    class VerificationpdfcmsShell extends AppShell
    {
		public $defaultParams = array(
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false
		);

		public $verbose = false;

		public $modeles = array();

		public $uses = array( 'Pdf' );

		/**
		* Initialisation: lecture des paramètres
		*/
		public function initialize() {
			parent::initialize();

			if( !Cmis::configured() ) {
				$this->err( 'Veuillez configurer la connexion au serveur CMS dans votre fichier app/config/webrsa.inc' );
				$this->_stop( 1 );
			}

			if( !isset( $this->args[0] ) ) {
				$this->err( 'Veuillez entrer le nom du modèle (colonne modele de la table pdfs) en paramètre' );
				$this->_stop( 1 );
			}

			$this->Pdf = ClassRegistry::init( 'Pdf' );

			$modeles = $this->Pdf->find(
				'all',
				array(
					'fields' => array( 'DISTINCT( "Pdf"."modele" )' ),
					'contain' => false
				)
			);

			$this->modeles = Set::classicExtract( $modeles, '{n}.0.modele' );

			if( !empty( $this->uses ) ) {
				foreach( $this->uses as $use ) {
					$this->{$use} = ClassRegistry::init( $use );
				}
			}

			if( !in_array( $this->args[0], $this->modeles ) ) {
				$this->err( "Le modèle {$this->args[0]} n'existe pas, veuillez choisir une valeur parmi: ".implode( ', ', $this->modeles )."." );
				$this->help();
				$this->_stop( 1 );
			}
		}

		/**
		*
		*/
		public function main() {
			$modele = $this->args[0];

			$this->Pdf->begin();

			$pdfs = $this->Pdf->find(
				'all',
				array(
					'conditions' => array(
						'Pdf.cmspath IS NOT NULL',
						'Pdf.modele' => $modele
					),
					'contain' => false
				)
			);

			$idsASupprimer = array();

			if( !empty( $pdfs ) ) {
				foreach( $pdfs as $pdf ) {
					$cmisDocument = Cmis::read( $pdf['Pdf']['cmspath'] );
					if( empty( $cmisDocument ) ) {
						$idsASupprimer[] = $pdf['Pdf']['id'];
					}
				}
			}

			$success = true;
			if( !empty( $idsASupprimer ) ) {
				$conditions = array( 'Pdf.id' => $idsASupprimer );
				$this->Pdf->deleteAll( $conditions, false, false );
				$success = ( $this->Pdf->find( 'count', array( 'conditions' => $conditions, 'contain' => false ) ) == 0 );
				$this->out( sprintf( 'Suppression de %s enregistrements invalides dans la table pdfs: %s.', count( $idsASupprimer ), ( $success ? 'succès' : 'erreur' ) ) );
			}
			else {
				$this->out( 'Aucun enregistrement invalide dans la table pdfs.' );
			}

			if( $success ) {
				$this->Pdf->commit();
			}
			else {
				$this->Pdf->rollback();
			}

			$this->_stop( ( $success ? 0 : 1 ) );
		}

		/**
		* Aide
		*/
		public function help() {
			$this->log = false;

			$this->out( "Shell permettant de vérifier l'existance sur le serveur CMS des fichiers dont le chemin se trouve dans la colonne cmspath de la table pdfs. Si le fichier n'existe pas sur le serveur CMS, la ligne de la table pdfs est supprimée. Il faudra utiliser le shell generationpdfs pour regénérer les fichiers." );
			$this->out();
			$this->out( "Usage: cake/console/cake {$this->shell} <modèle> <paramètres>" );
			$this->hr();
			$this->out();
			$this->out( "\tLe paramètre <modèle> correspond à une valeur de la colonne modele de la table pdfs" );
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-log <booléen>\n\t\tDoit-on journaliser la sortie du programme ?\n\t\tPar défaut: ".$this->_defaultToString( 'log' )."\n");
			$this->out("\t-logpath <répertoire>\n\t\tLe répertoire dans lequel enregistrer les fichiers de journalisation et les fichiers CSV contenant les rejets.\n\t\tPar défaut: ".$this->_defaultToString( 'logpath' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
    }
?>