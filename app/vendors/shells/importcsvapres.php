<?php
    class ImportcsvapresShell extends Shell
    {
        var $uses = array( 'Apre', 'Integrationfichierapre', 'Domiciliationbancaire' );
		var $csv = null;
		var $csvFile = null;
		var $schema = array();
		var $rejects = array();
		var $defaultFields = array(
			'matricul' => 0,
			'respdos' => 1,
			'nomrespd' => 2,
			'prerespd' => 3,
			'nudemrsa' => 4,
			'nomact' => 5,
			'preact' => 6,
			'natprof' => 7,
			'nir' => 8,
			'nbenf12' => 9,
			'adralloc' => 10,
			'modpaiac' => 11,
			'titureac' => 12,
			'nptireac' => 13,
			'banreact' => 14,
			'guireact' => 15,
			'ncptreac' => 16,
			'cribreac' => 17,
			'date' => 18
		);
		var $fields = array();
		var $script = null;
		var $debug = null;
		var $config = array();
		var $defaultConfig = array(
			'headers' => true,
			'separator' => ';',
			'date' => true
		);
		var $outfile = null;
		var $output = '';

        /**
        *
        *
        */

        function err( $string ) {
			parent::err( $string );

			if( !empty( $this->outfile ) ) {
				$this->output .= "Erreur: {$string}\n";
			}
		}

        /**
        *
        *
        */

        function out( $string ) {
			parent::out( $string );

			if( !empty( $this->outfile ) ) {
				$this->output .= "{$string}\n";
			}
		}

        /**
        *
        *
        */

        function exportlog() {
			file_put_contents( $this->outfile, $this->output );
		}

        /**
        *
        *
        */

        function startup() {
			$this->script = $scriptName = strtolower( preg_replace( '/Shell$/', '', $this->name ) );

			if( Set::classicExtract( $this->params, '?' ) ) {
				$this->out( "Paramètres possibles:" );
				$this->out( "\t-headers\tvaleurs possibles: true ou false (par défaut true)" );
				$this->out( "\t-separator\tle caractère utilisé comme séparateur (par défaut ;)" );
				$this->out( "\t-date\tvaleurs possibles: true ou false (par défaut true)" );
				$this->out( "Exemple: cake/console/cake {$scriptName} /tmp/APRE_stock-Juin-Oct_v2.csv -headers true -separator \";\" -date true" );
				exit( 0 );
			}

            if( count( $this->args ) == 0 ) {
				$this->out( "Veuillez entrer un fichier CSV en paramètre." );
				$this->out( "Faites cake/console/cake {$scriptName} -? pour obtenir de l'aide." );
                exit( 1 );
            }
            else {
				$this->csvFile = $this->args[0];
                $this->encoding = trim( preg_replace( '/^.* charset=(.*)$/', '\1', shell_exec( "file -i \"{$this->args[0]}\"" ) ) );

                $this->csv = new File( $this->csvFile );
				if( !$this->csv->exists() ) {
					$this->err( "Le fichier ".$this->args[0]." n'existe pas." );
					exit( 1 );
				}
				else if( !$this->csv->readable() ) {
					$this->err( "Le fichier ".$this->args[0]." n'est pas lisible." );
					exit( 1 );
				}
			}

			$this->debug = ( Configure::read( 'debug' ) > 0 );
			$this->config = Set::merge( $this->defaultConfig, array_filter_keys( $this->params, array( 'headers', 'separator', 'date' ) ) );

			if( !is_string( $this->config['separator'] ) ) {
				$this->err( "Le séparateur n'est pas correct (valeur actuelle: {$this->config['separator']}); n'oubliez pas d'échapper le caractère (par exemple: \";\" plutôt que ;)" );
				exit( 1 );
			}

			foreach( array( 'headers', 'date' ) as $f ) {
				if( isset( $this->config[$f] ) && is_string( $this->config[$f] ) ) {
					$this->config[$f] = ( $this->config[$f] == 'true' ? true : false );
				}
			}

			if( !$this->config['date'] ) {
				unset( $this->fields['date'] );
			}

			$this->outfile = APP_DIR.sprintf( '/tmp/logs/%s-%s.log', $this->script, date( 'Ymd-His' ) );
        }

        /**
        *
        */

        function main() {
			$this->Apre->begin();

			$fields = array();
			$nLignesVides = 0;
			$nLignes = 0;
			$nLignesTraitees = 0;
			$success = true;

            $fileLines = $this->csv->read();
            if( $this->encoding != 'UTF-8' ) {
                $fileLines = mb_convert_encoding( $fileLines, 'UTF-8', $this->encoding );
            }

			$lines = explode( "\n", $fileLines );

			foreach( $lines as $nLigne => $line ) {
				$line = trim( $line );
				$parts = explode( $this->config['separator'], $line );
				$cleanedParts = Set::filter( $parts );
				$nLignes++;
				if( !empty( $cleanedParts ) ) {
					if( $this->config['headers'] && ( $nLigne == 0 ) ) {
						foreach( $parts as $offset => $title ) {
							$this->fields[strtolower( trim(  trim( trim( $title ), '"' ) ) )] = $offset;
						}
						$diff = array_diff( $this->fields, $this->defaultFields );
						if( !empty( $diff ) ) {
							$errMsg = "En-têtes de colonnes mal formés (reçu ".implode( ", ", $this->fields )." - attendu ".implode( ", ", $this->defaultFields )." )";
							$this->err( "{$errMsg}, ".count( $parts )." parties au lieu des ".count( $this->fields )." attendues (ligne {$nLigne}): {$line}" );
							exit( 2 );
						}
					}
					else if( !$this->config['headers'] && ( $nLigne == 0 ) ) {
						$this->fields = $this->defaultFields;
					}

					if( !$this->config['headers'] || ( $nLigne != 0 ) ) {
						if( $this->debug ) {
							$this->out( "Traitement de la ligne $nLigne" );
						}

						if( preg_match( '/'.$this->config['separator'].'[0-9,\.]+E\+[0-9]+'.$this->config['separator'].'/i', $line, $matches ) ) {
							$errMsg = "Erreur de format";
							$this->err( "{$errMsg} (ligne {$nLigne}): \"{$matches[0]}\"" );
						}
						else if( $this->config['date'] && !preg_match( '/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/i', $parts[$this->fields['date']], $matches ) ) {
							$errMsg = "Erreur de format";
							$this->err( "{$errMsg} (ligne {$nLigne}): \"{$parts[$this->fields['nudemrsa']]}\"" );
						}
						else if( count( $parts ) == count( $this->fields ) ) {
							$numdemrsa = trim( $parts[$this->fields['nudemrsa']], '"' );

							// Recherche ou ajout Paiementfoyer
							/// FIXME: dem ou cjt
							$titurib = trim( trim( $parts[$this->fields['titureac']], '"' ) );
							$nomprenomtiturib = trim( trim( $parts[$this->fields['nptireac']], '"' ) );
							$etaban = trim( trim( $parts[$this->fields['banreact']], '"' ) );
							$guiban = trim( trim( $parts[$this->fields['guireact']], '"' ) );
							$numcomptban = trim( strtoupper( trim( $parts[$this->fields['ncptreac']], '"' ) ) );
							$clerib = trim( trim( $parts[$this->fields['cribreac']], '"' ) );
							$modpaiac =  trim( trim( $parts[$this->fields['modpaiac']], '"' ) );

							// Formattage du RIB avec les 0 devant
                            $numdemrsa = str_pad( $numdemrsa, 11, '0', STR_PAD_LEFT); // FIXME: ajout suite au problème du fichier "apre_1109_et_FORCG.csv" du 29/12/2009
							$etaban = str_pad( $etaban, 5, '0', STR_PAD_LEFT);
							$guiban = str_pad( $guiban, 5, '0', STR_PAD_LEFT);
							$numcomptban= str_pad( $numcomptban, 11, '0', STR_PAD_LEFT);
							$clerib = str_pad( $clerib, 2, '0', STR_PAD_LEFT);

							//date -> FIXME MM/AAAA
							$date = mktime();
							if( isset( $this->fields['date'] ) ) {
								$date = trim( $parts[$this->fields['date']], '"' );
								list( $jour, $mois, $annee ) = explode( '/', $date );
								$date = strtotime( "{$annee}-{$mois}-{$jour}" );
							}

							$validRib = validRib( $etaban, $guiban, $numcomptban, $clerib );
							$dossier = $this->Apre->Personne->Foyer->Dossier->findByNumdemrsa( $numdemrsa, null, null, -1 );

							if( $validRib && !empty( $dossier ) ) {
								$foyer = $this->Apre->Personne->Foyer->findByDossierRsaId( $dossier['Dossier']['id'], null, null, -1 );

								if( !empty( $foyer ) ) {
									$nom = strtoupper( replace_accents( trim( $parts[$this->fields['nomact']], '"' ) ) );
									$prenom = strtoupper( replace_accents( trim( $parts[$this->fields['preact']], '"' ) ) );
									$nir = trim( $parts[$this->fields['nir']], '"' );

									$this->Apre->Personne->unbindModelAll();
									/*$prestation = array(
										'hasOne' => array(
											'Prestation' => array(
												'foreignKey' => 'personne_id',
												'conditions' => array (
													'Prestation.natprest' => array( 'RSA' ),
													'Prestation.rolepers' => array( 'DEM', 'CJT' )
												)
											)
										)
									);
									$this->Apre->Personne->bindModel( $prestation );*/

									$fields = Set::merge(
										array_keys( Set::flatten( array( 'Personne' => Set::normalize( array_keys( $this->Apre->Personne->schema() ) ) ) ) ),
										array_keys( Set::flatten( array( 'Prestation' => Set::normalize( array_keys( $this->Apre->Personne->Prestation->schema() ) ) ) ) )
									);

									// Conditions pour le message d'erreur
									$conditionsBeneficiaire = "Personne.foyer_id = '{$foyer['Foyer']['id']}' AND ( Personne.nir = '{$nir}' OR ( Personne.nom ILIKE '".strtoupper( replace_accents( $nom ) )."' AND Personne.prenom ILIKE '".strtoupper( replace_accents( $prenom ) )."' ) )";
									$beneficiaire = $this->Apre->Personne->find(
										'first',
										array(
											'fields' => $fields,
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
											'joins' => array(
												array(
													'table'      => 'prestations',
													'alias'      => 'Prestation',
													'type'       => 'INNER',
													'foreignKey' => false,
													'conditions' => array(
														'Prestation.personne_id = Personne.id',
														'Prestation.natprest' => array( 'RSA' ),
														'Prestation.rolepers' => array( 'DEM', 'CJT' )
													)
												)
											),
											'recursive' => 0
										)
									);

									if( !empty( $beneficiaire ) ) {
										$nbenf12 = trim( $parts[$this->fields['nbenf12']], '"' );

										// FIXME: nature situation professionnelle
										$apre = array(
											'Apre' => array(
												'personne_id' => $beneficiaire['Personne']['id'],
												'numeroapre' => date('Ym').sprintf( "%010s",  $this->Apre->find( 'count' ) + 1 ), // FIXME
												'typedemandeapre' => 'AU',
												'datedemandeapre' => date( 'Y-m-d', $date ),
												'mtforfait' => ( 400 + min( array( 400, ( 100 * $nbenf12 ) ) ) ), // FIXME
												'statutapre' => 'F',
												'nbenf12' => $nbenf12,
												'etatdossierapre' => 'COM',
												'eligibiliteapre' => 'O'
											)
										);

										// Recherche demandes d'APRE précédente
										$nbApresPrec = $this->Apre->find(
											'count',
											array(
												'conditions' => array(
													'personne_id' => $beneficiaire['Personne']['id'],
													'statutapre' => 'F',
													'datedemandeapre BETWEEN \''.date( 'Y-m-d', ( strtotime( '-1 year', $date ) ) ).'\' AND \''.date( 'Y-m-d', $date ).'\''
												)
											)
										);

										$nDomiciliationbancaire = $this->Domiciliationbancaire->find(
											'count',
											array(
												'conditions' => array(
													'codebanque' => $etaban,
													'codeagence' => $guiban
												)
											)
										);

										if( $nDomiciliationbancaire == 1 ) {
											if( $nbApresPrec == 0 ) {
												$this->Apre->create( $apre );
												$tmpSuccess = $this->Apre->save();

												if( !$tmpSuccess && $this->debug ) {
													debug( $this->Apre->validationErrors );
												}
												$success = $tmpSuccess && $success;

												// Recherche ou ajout Paiementfoyer
												$topribconj = ( ( Set::classicExtract( $beneficiaire, 'Prestation.rolepers' ) == 'CJT' ) ? true : false );

												$paiementfoyer = $this->Apre->Personne->Foyer->Paiementfoyer->find(
													'first',
													array(
														'conditions' => array(
															'foyer_id' => $foyer['Foyer']['id'],
															'etaban' => $etaban,
															'guiban' => $guiban,
															'numcomptban' => $numcomptban,
															'topribconj' => $topribconj,
															'clerib' => $clerib,
														),
														'recursive' => -1
													)
												);

												// Mise à jour du paiement foyers
												$paiementfoyer['Paiementfoyer'] = Set::merge(
													$paiementfoyer['Paiementfoyer'],
													array(
														'foyer_id' => $foyer['Foyer']['id'],
														'titurib' => $titurib,
														'nomprenomtiturib' => $nomprenomtiturib,
														'etaban' => $etaban,
														'guiban' => $guiban,
														'numcomptban' => $numcomptban,
														'clerib' => $clerib,
														'topribconj' => $topribconj,
														'modepai' => $modpaiac
													)
												);

												$this->Apre->Personne->Foyer->Paiementfoyer->create( $paiementfoyer );
												$tmpSuccess = $this->Apre->Personne->Foyer->Paiementfoyer->save();

												if( !$tmpSuccess && $this->debug ) {
													debug( $this->Apre->Personne->Foyer->Paiementfoyer->validationErrors );
												}
												$success = $tmpSuccess && $success;

												$nLignesTraitees++;
											}
											else {
												$errMsg = "Demande d'APRE forfaitaire datant de moins de 12 mois trouvée pour la date d'importation du ".date( 'd/m/Y', $date )." et pour la personne ";
												$this->err( "{$errMsg}: {$beneficiaire['Personne']['nom']} {$beneficiaire['Personne']['prenom']} (nir: {$beneficiaire['Personne']['nir']})" );
												$this->rejects[] = "{$line};{$errMsg}";
											}
										}
										else {
											$errMsg = "Demande d'APRE forfaitaire rejetée pour cause d'entrée non trouvée dans la table domiciliationsbancaires (codebanque = {$etaban} et codeagence = {$guiban}) pour la personne ";
											$this->err( "{$errMsg}: {$beneficiaire['Personne']['nom']} {$beneficiaire['Personne']['prenom']} (nir: {$beneficiaire['Personne']['nir']})" );
											$this->rejects[] = "{$line};{$errMsg}";
										}
									}
									else {
										$errMsg = "Bénéficiaire de l'APRE non trouvé au sein du dossier de demande RSA n°{$numdemrsa} ({$conditionsBeneficiaire})";
										$this->err( "{$errMsg} (ligne {$nLigne}): {$line}" );
										$this->rejects[] = "{$line};{$errMsg}";
									}
								}
								else {
									$errMsg = "Foyer non trouvé pour le dossier de demande RSA n°{$numdemrsa}";
									$this->err( "{$errMsg} (ligne {$nLigne}): {$line}" );
									$this->rejects[] = "{$line};{$errMsg}";
								}
							}
							else {
								if( !$validRib ) {
									$errMsg = "RIB non valide (".implode( "-", array( $etaban, $guiban, $numcomptban, $clerib ) ).")";
									$this->err( "{$errMsg} (ligne {$nLigne}): {$line}" );
								}

								if( empty( $dossier ) ) {
									$errMsg = "Dossier de demande RSA n°{$numdemrsa} non trouvé";
									$this->err( "{$errMsg} (ligne {$nLigne}): {$line}" );
								}
								$this->rejects[] = "{$line};{$errMsg}";
							}
						}
						else {
							$errMsg = "Ligne mal formée";
							$this->err( "{$errMsg}, ".count( $parts )." parties au lieu des ".count( $this->fields )." attendues (ligne {$nLigne}): {$line}" );
							$this->rejects[] = "{$line};{$errMsg}";
						}
					}
				}
				else {
					$nLignesVides++;
				}
			}

			$nLignesATraiter = $nLignes - $nLignesVides - ( $this->config['headers'] ? 1 : 0 );
			$nLignesEnRejet = max( 0, count( $this->rejects ) );

			$integrationfichierapre = array(
				'Integrationfichierapre' => array(
					'date_integration' => date( 'Y-m-d  H:i:s' ),
					'nbr_atraiter' => $nLignesATraiter,
					'nbr_succes' => $nLignesTraitees,
					'nbr_erreurs' => $nLignesEnRejet,
					'fichier_in' => basename( $this->csvFile ),
					'erreurs' => implode( "\n", $this->rejects )
				)
			);

			$this->Integrationfichierapre->create( $integrationfichierapre );
			$tmpSuccess = $this->Integrationfichierapre->save();
			if( !$tmpSuccess && $this->debug ) {
				debug( $this->Integrationfichierapre->validationErrors );
			}
			$success = $tmpSuccess && $success;

            $message = "%s: ".$nLignesATraiter." lignes à traiter, $nLignesTraitees lignes traitées, ".$nLignesEnRejet." lignes rejetées.";
            /// Fin de la transaction
            if( $success ) {
                $this->out( sprintf( $message, "Script terminé avec succès" ) );
                $this->Apre->commit();
            }
            else {
                $this->out( sprintf( $message, "Script terminé avec erreurs" ) );
                $this->Apre->rollback();
            }

			$this->exportlog();

			return ( $success ? 0 : 1 );
        }
    }
?>