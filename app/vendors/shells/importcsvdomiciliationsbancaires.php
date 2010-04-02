<?php
    class ImportcsvdomiciliationsbancairesShell extends Shell
    {
        var $uses = array( 'Domiciliationsbancaire' );
		var $csv = null;
		var $schema = array();
		var $config = array();
		var $defaultConfig = array(
			'headers' => true,
			'separator' => ';'
		);

        /**
        *
        *
        */

        function startup() {
			$scriptName = strtolower( preg_replace( '/Shell$/', '', $this->name ) );

			if( Set::classicExtract( $this->params, '?' ) ) {
				$this->out( "Paramètres possibles:" );
				$this->out( "\t-headers\tvaleurs possibles: true ou false (par défaut true)" );
				$this->out( "\t-separator\tle caractère utilisé comme séparateur (par défaut ;)" );
				$this->out( "Exemple: cake/console/cake {$scriptName} /tmp/domiciliations_bancaires.csv -headers true -separator \";\"" );
				exit( 0 );
			}

            if( count( $this->args ) == 0 ) {
				$this->out( "Veuillez entrer un fichier CSV en paramètre." );
				$this->out( "Faites cake/console/cake {$scriptName} -? pour obtenir de l'aide." );
                exit( 1 );
            }
            else {
				$this->csv = new File( $this->args[0] );
				if( !$this->csv->exists() ) {
					echo "Le fichier ".$this->args[0]." n'existe pas.\n";
					exit( 1 );
				}
				else if( !$this->csv->readable() ) {
					echo "Le fichier ".$this->args[0]." n'est pas lisible.\n";
					exit( 1 );
				}
			}

			$this->config = Set::merge( $this->defaultConfig, array_filter_keys( $this->params, array( 'headers', 'separator' ) ) );

			if( !is_string( $this->config['separator'] ) ) {
				$this->out( "Le séparateur n'est pas correct (valeur actuelle: {$this->config['separator']}); n'oubliez pas d'échapper le caractère (par exemple: \";\" plutôt que ;)" );
				exit( 1 );
			}
        }

        /**
        *
        */

        function main() {
			$this->Domiciliationsbancaire->begin();

			$fields = array(
				'codebanque',
				'codeagence',
				'libelledomiciliation'
			);
			$nLignes = 0;
			$nLignesTraitees = 0;
			$nLignesNonTraitees = 0;
			$nLignesPresentes = 0;
			$success = true;

 			$this->schema = array_keys( $this->Domiciliationsbancaire->schema() );
			$lines = explode( "\n", $this->csv->read() );

			foreach( $lines as $nLigne => $line ) {
				if( !$this->config['headers'] || ( $nLigne != 0 ) ) {
					$nLignes++;
					$parts = explode( $this->config['separator'], $line );
					$cleanedParts = Set::filter( $parts );
					if( !empty( $cleanedParts ) ) {
						$domiciliationsbancaire = array( 'Domiciliationsbancaire' => array() );

						foreach( $parts as $key => $part ) {
							if( in_array( $fields[$key], $this->schema ) ) {
								$domiciliationsbancaire['Domiciliationsbancaire'][$fields[$key]] = trim( trim( $part, '"' ) );
							}
						}

						$cleanedDomiciliationsbancaire = Set::filter( $domiciliationsbancaire['Domiciliationsbancaire'] );

						$cleanedDomiciliationsbancaire['codebanque'] = str_pad( $cleanedDomiciliationsbancaire['codebanque'], 5, '0', STR_PAD_LEFT );
						$cleanedDomiciliationsbancaire['codeagence'] = str_pad( $cleanedDomiciliationsbancaire['codeagence'], 5, '0', STR_PAD_LEFT );

						if( !empty( $cleanedDomiciliationsbancaire ) ) {
							// Vérification de la présence du libellé
							$libelledomiciliation = trim( Set::classicExtract( $cleanedDomiciliationsbancaire, 'libelledomiciliation' ) );
							if( empty( $libelledomiciliation ) ) {
								$this->out( "Ligne non traitée à cause de libellé manquant (ligne {$nLigne}): {$line}" );
								$nLignesNonTraitees++;
							}
							else {
								if( $this->Domiciliationsbancaire->find( 'count', array( 'conditions' => array( $cleanedDomiciliationsbancaire ) ) ) == 0 ) {
									$this->Domiciliationsbancaire->create( array( 'Domiciliationsbancaire' => $cleanedDomiciliationsbancaire ) );
									if( $tmpSuccess = $this->Domiciliationsbancaire->save() ) {
										$nLignesTraitees++;
									}
									$success =  $tmpSuccess && $success;
								}
								else {
									$nLignesPresentes++;
								}
							}
						}
					}
				}
			}

            $message = "%s: $nLignes lignes trouvées, $nLignesTraitees lignes traitées, $nLignesPresentes lignes déjà présentes, $nLignesNonTraitees lignes non traitées.\n";

            /// Fin de la transaction
            if( $success ) {
                echo sprintf( $message, "Script terminé avec succès" );
                $this->Domiciliationsbancaire->commit();
                return 0;
            }
            else {
                echo sprintf( $message, "Script terminé avec erreurs" );
                $this->Domiciliationsbancaire->rollback();
                return 1;
            }
        }
    }
?>