<?php
	/**
	*	FIXME: vérifier le format du fichier, ...
	*/

    class ImportcsvcantonsShell extends Shell
    {
        var $uses = array( 'Canton' );
		var $csv = null;
		var $schema = array();

        /**
        *
        *
        */

        function startup() {
            if( count( $this->args ) == 0 ) {
                echo "Veuillez entrer un fichier CSV en paramètre.\n";
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
        }

        /**
        *
        */

        function main() {
			$this->Canton->begin();

			$fields = array();
			$nLignes = 0;
			$nLignesTraitees = 0;
			$nLignesPresentes = 0;
			$success = true;

			$this->schema = $this->Canton->schema();
			$lines = explode( "\n", $this->csv->read() );

			foreach( $lines as $line ) {
				$nLignes++;
				$parts = explode( ';', $line );
				$cleanedParts = Set::filter( $parts );

				if( !empty( $cleanedParts ) ) {
					if( $nLignes == 1 ) {
						foreach( $parts as $key => $part ) {
							$fields[$key] = strtolower( replace_accents( $part ) );
						}
					}
					else {
						$canton = array( 'Canton' => array() );

						foreach( $parts as $key => $part ) {
							if( in_array( $fields[$key], array_keys( $this->schema ) ) ) {
								$canton['Canton'][$fields[$key]] = $part;
							}
						}

						$cleanedCanton = Set::filter( $canton['Canton'] );

						if( !empty( $cleanedCanton ) ) {
							// Mise en majuscules et suppression des accents
							foreach( $this->schema as $field => $infos ) {
								if( Set::classicExtract( $infos, 'type' ) == 'string' ) {
									$canton['Canton'][$field] = strtoupper( replace_accents( $canton['Canton'][$field] ) );
								}
							}

							// Si cette entrée n'est pas encore présente, on l'insère
							if( $this->Canton->find( 'count', array( 'conditions' => array( $canton['Canton'] ) ) ) == 0 ) {
								$this->Canton->create( $canton );
								if( $tmpSuccess = $this->Canton->validates() ) {
									if( $tmpSuccess = $this->Canton->save() ) {
										$nLignesTraitees++;
									}
									$success =  $tmpSuccess && $success;
								}

								// Si la sauvegarde n'a pas eu lieu, on affiche les erreurs
								if( !$tmpSuccess ) {
									echo "Sauvegarde échouée (ligne $nLignes): $line\n";
									foreach( $this->Canton->validationErrors as $field => $error ) {
										echo "\t$field ('".Set::classicExtract( $canton, "Canton.$field" )."') => $error\n";
									}
								}
							}
							else {
								$nLignesPresentes++;
							}
						}
						else {
							echo "Ligne non traitée (ligne $nLignes): $line\n";
						}
					}
				}
				else {
					echo "Ligne non traitée (ligne $nLignes): $line\n";
				}
			}

			$this->hr();

            $message = "%s: $nLignes lignes trouvées, $nLignesTraitees lignes traitées, $nLignesPresentes lignes déjà présentes.\n";

            /// Fin de la transaction
            if( $success ) {
                echo sprintf( $message, "Script terminé avec succès" );
                $this->Canton->commit();
                return 0;
            }
            else {
                echo sprintf( $message, "Script terminé avec erreurs" );
                $this->Canton->rollback();
                return 1;
            }
        }
    }
?>