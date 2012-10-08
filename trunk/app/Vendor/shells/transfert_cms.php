<?php
	/**
	*
	*/

	require_once( APPLIBS.'cmis.php' );
	@ini_set( 'memory_limit', '1024M' );

	class TransfertCmsShell extends AppShell
	{
		public $defaultParams = array(
			'log' => false,
			'logpath' => LOGS,
			'limit' => 10,
			'modele' => 'Orientstruct',
			'usleep' => 200000,
		);

		public $modele;
		public $usleep;

		/**
		*
		*/

		public function initialize() {
			parent::initialize();

			$this->limit = $this->_getNamedValue( 'limit', 'integer' );
			$this->modele = $this->_getNamedValue( 'modele', 'string' );
			$this->usleep = $this->_getNamedValue( 'usleep', 'integer' );

			if( !Cmis::configured() ) {
				$this->err( 'Veuillez configurer la connexion au serveur CMS dans votre fichier app/config/webrsa.inc' );
				$this->_stop( 1 );
			}

			$this->Pdf = ClassRegistry::init( 'Pdf' );

			$modeles = $this->Pdf->find(
				'all',
				array(
					'fields' => array( 'DISTINCT( "Pdf"."modele" )' ),
					'conditions' => array(
						'Pdf.cmspath IS NULL',
						'Pdf.document IS NOT NULL'
					)
				)
			);

			$this->modeles = Set::classicExtract( $modeles, '{n}.0.modele' );
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
			$this->out();
			$this->out( 'Shell de transfert de documents PDF de la table pdfs dans le système de gestion de contenu.' );
			$this->out();
			$this->hr();
		}

		/**
		*
		*/

		protected function _transfertPdfs( $modele ) {
			if( !in_array( $modele, $this->modeles ) ) {
				$this->err( "Il n'existe aucun document à transférer pour le modèle {$modele}." );
				$this->_stop( 1 );
			}

			$conditions = array(
				'Pdf.modele' => $modele,
				'Pdf.cmspath IS NULL',
				'Pdf.document IS NOT NULL'
			);

			$pdfs = $this->Pdf->find(
				'all',
				array(
					'conditions' => $conditions,
					'limit' => $this->limit
				)
			);

			$this->out( sprintf( "%s documents (%s) à traiter", count( $pdfs ), $modele ) );
			$this->out();

			$success = true;
			if( !empty( $pdfs ) ) {
				foreach( $pdfs as $i => $pdf ) {
					$this->out( sprintf( "Traitement du document %s (%s %s)", $i + 1, $modele, $pdf['Pdf']['fk_value'] ) );
					$cmsPath = "/{$modele}/{$pdf['Pdf']['fk_value']}.pdf";

					$tmpSuccess = Cmis::write( $cmsPath, $pdf['Pdf']['document'], 'application/pdf', true );

					if( $tmpSuccess ) {
						$pdf['Pdf']['cmspath'] = $cmsPath;
						$this->Pdf->create( $pdf );
						$tmpSuccess = $this->Pdf->save() && $tmpSuccess;
						if( !$tmpSuccess ) {
							Cmis::delete( $cmsPath );
						}
					}

					if( !$tmpSuccess ) {
						$this->err( sprintf( "Erreur lors de l'écriture du document %s (%s %s)", $i + 1, $modele, $pdf['Pdf']['fk_value'] ) );
						$this->_stop( 1 );// FIXME
					}
					$success = $tmpSuccess && $success;

					usleep( $this->usleep ); // FIXME: param
				}
			}
			$this->out();
			$this->_stop( ( $success ? 0 : 1 ) );
		}

		/**
		*
		*/

		public function main() { // FIXME: fonctions ?
			$this->_transfertPdfs( $this->modele );
		}

		/**
		* Aide
		*/

		public function help() {
			$this->log = false;

			$this->out("Usage: cake/console/cake transfert_cms <paramètres>");
			$this->hr();
			$this->out();
			$this->out('Commandes:');
			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-modele <chaîne>\n\t\tLe type de fichiers à déplacer.\n\t\tPar défaut: ".$this->_defaultToString( 'modele' )."\n\t\tModèles disponibls: ".implode( ', ', $this->modeles )."\n" );
			$this->out("\t-usleep <entier>\n\t\tLe temps d'attente entre deux envois (en micro-secondes).\n\t\tPar défaut: ".$this->_defaultToString( 'usleep' )."\n");
			$this->out("\t-limit <entier>\n\t\tLimite sur le nombre d\'enregistrements à traiter.\n\t\tPar défaut: ".$this->_defaultToString( 'limit' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
	}
?>