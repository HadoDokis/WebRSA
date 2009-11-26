<?php
    class ImportcsvapresShell extends Shell
    {
        var $uses = array( 'Apre' );
		var $csv = null;
		var $schema = array();
		var $rejects = array();

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
			// TODO: séparateur
			// FIXME: ordre des champs, titre, champ manquant
        }

        /**
        *
        */

        function main() {
			$this->Apre->begin();

			$fields = array();
			$nLignes = 0;
			$nLignesTraitees = 0;
			$success = true;

			$lines = explode( "\n", $this->csv->read() );

			foreach( $lines as $line ) {
				$parts = explode( ',', $line );
				$cleanedParts = Set::filter( $parts );
				if( !empty( $cleanedParts ) ) {
					$nLignes++;
					if( $nLignes == 1 ) {
						$rejects[] = $line;
					}
					else {
						$numdemrsa = trim( $parts[4], '"' );

						$dossier = $this->Apre->Personne->Foyer->Dossier->findByNumdemrsa( $numdemrsa, null, null, -1 );

						if( !empty( $dossier ) ) {
							$foyer = $this->Apre->Personne->Foyer->findByDossierRsaId( $dossier['Dossier']['id'], null, null, -1 );

							if( !empty( $foyer ) ) {
								$nom = trim( $parts[5], '"' );
								$prenom = trim( $parts[6], '"' );
								$nir = trim( $parts[7], '"' );

								$this->Apre->Personne->unbindModelAll();
								$prestation = array(
									'hasOne' => array(
										'Prestation' => array(
											'foreignKey' => 'personne_id',
											'conditions' => array (
												'Prestation.natprest' => array( 'RSA' )
											)
										)
									)
								);
								$this->Apre->Personne->bindModel( $prestation );
								$beneficiaire = $this->Apre->Personne->find(
									'first',
									array(
										'conditions' => array(
											'Personne.foyer_id' => $foyer['Foyer']['id'],
											'or' => array(
												'Personne.nir' => $nir,
												'and' => array(
													'Personne.nom ILIKE' => strtoupper( replace_accents( $nom ) ),
													'Personne.prenom ILIKE' => strtoupper( replace_accents( $prenom ) ),
												)
											)
										),
										'recursive' => 0
									)
								);

								if( !empty( $beneficiaire ) ) {
									$nenfants = trim( $parts[8], '"' );

									// FIXME: nature situation professionnelle
									$apre = array(
										'Apre' => array(
											'personne_id' => $beneficiaire['Personne']['id'],
											'numeroapre' => date('Ym').sprintf( "%010s",  $this->Apre->find( 'count' ) + 1 ), // FIXME
											'typedemandeapre' => 'AU',
											'datedemandeapre' => date( 'Y-m-d' ),
											'mtforfait' => ( 400 + min( array( 400, ( 100 * $nenfants ) ) ) ), // FIXME
											'statutapre' => 'ATT',
											'nenfants' => $nenfants,
											'etatdossierapre' => 'INC'
										)
									);

									$this->Apre->create( $apre );
									$success = $this->Apre->save() && $success;

									// Recherche ou ajout Paiementfoyer
									$nomprenomtiturib = strtoupper( replace_accents( trim( $parts[13], '"' ) ) ).' '.strtoupper( replace_accents( trim( $parts[14], '"' ) ) );
									$etaban = trim( $parts[16], '"' );
									$guiban = trim( $parts[17], '"' );
									$numcomptban = trim( $parts[15], '"' );
									$clerib = trim( $parts[18], '"' );
									$topribconj = ( ( Set::classicExtract( $beneficiaire, 'Prestation.rolepers' ) == 'CJT' ) ? true : false );

									$nPaiementfoyer = $this->Apre->Personne->Foyer->Paiementfoyer->find(
										'count',
										array(
											'conditions' => array(
												'foyer_id' => $foyer['Foyer']['id'],
												'etaban' => $etaban,
												'guiban' => $guiban,
												'numcomptban' => $numcomptban,
												'clerib' => $clerib,
												'topribconj' => $topribconj
											),
											'recursive' => -1
										)
									);

									if( $nPaiementfoyer == 0 ) {
										// FIXME: vérification n° compte
										$paiementfoyer = array(
											'Paiementfoyer' => array(
												'foyer_id' => $foyer['Foyer']['id'],
												'nomprenomtiturib' => $nomprenomtiturib,
												'etaban' => $etaban,
												'guiban' => $guiban,
												'numcomptban' => $numcomptban,
												'clerib' => $clerib,
												'topribconj' => $topribconj
											)
										);

										$this->Apre->Personne->Foyer->Paiementfoyer->create( $paiementfoyer );
										$success = $this->Apre->Personne->Foyer->Paiementfoyer->save() && $success;
									}
									$nLignesTraitees++;
								}
								else {
									$rejects[] = $line;
								}
							}
							else {
								$rejects[] = $line;
							}
						}
						else {
							$rejects[] = $line;
						}
					}
				}
			}

            $message = "%s: ".( $nLignes - 1 )." lignes à traiter, $nLignesTraitees lignes traitées, ".( count( $rejects ) - 1 )." lignes rejetées.\n";
			if( count( $rejects ) > 1 ) {
				file_put_contents( 'rejets-importcsvapres-'.date( 'Ymd-His' ).'.csv', implode( "\n", $rejects ) );
			}

            /// Fin de la transaction
            if( $success ) {
                echo sprintf( $message, "Script terminé avec succès" );
                $this->Apre->commit();
                return 0;
            }
            else {
                echo sprintf( $message, "Script terminé avec erreurs" );
                $this->Apre->rollback();
                return 1;
            }
        }
    }
?>