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
			'verbose' => true,
			'limit' => 10
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
			$this->out( 'Shell de transfert de documents PDF de la table pdfs dans le système de gestion de contenu.' );
			$this->out();
			$this->hr();
		}

		/**
		*
		*/

		protected function _transfertPdfs( $modele ) {
// 			Cmis::config(
// 				Configure::read( 'Cmis.url' ),
// 				Configure::read( 'Cmis.username' ),
// 				Configure::read( 'Cmis.password' ),
// 				Configure::read( 'Cmis.prefix' )
// 			);

			if( !Cmis::configured() ) {
				$this->err( 'Veuillez configurer ...' ); // FIXME
				$this->_stop( 1 );
			}

			$this->Pdf = ClassRegistry::init( 'Pdf' );
			$conditions = array( 'Pdf.modele' => $modele );
			$conditions[] = 'Pdf.cmspath IS NULL';//FIXME

			/*$documentsPresents = Cmis::read( "/{$modele}", true );//FIXME: maximum 1000
			if( !empty( $documentsPresents['content'] ) ) {
				$conditions['NOT']['Pdf.fk_value'] = array();
				foreach( $documentsPresents['content'] as $documentPresent ) {
					$id = preg_replace( '/\.pdf$/i', '', $documentPresent['cmis:name'] );
					$conditions['NOT']['Pdf.fk_value'][] = $id;
				}
			}*/

			$pdfs = $this->Pdf->find(
				'all',
				array(
					'conditions' => $conditions,
					'limit' => $this->limit
				)
			);

			$this->out( sprintf( "%s documents (%s) à traiter", count( $pdfs ), $modele ) );

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

					usleep( 200000 ); // FIXME: param
				}
			}

			$this->_stop( ( $success ? 0 : 1 ) );
		}

		/**
		*
		*/

		public function main() { // FIXME: fonctions ?
			$this->_transfertPdfs( 'Orientstruct' );
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
			$this->out("\t-verbose <booléen>\n\t\tDoit-on afficher les étapes de lecture / écriture ?\n\t\tPar défaut: ".$this->_defaultToString( 'verbose' )."\n");
			$this->out("\t-limit <entier>\n\t\tLimite sur le nombre de tables à traiter.\n\t\tPar défaut: ".$this->_defaultToString( 'limit' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
    }
?>