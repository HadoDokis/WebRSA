<?php
	/**
	 * Fichier source de la classe ImportcsvapresShell.
	 *
	 * PHP 5.3
	 *
	 * @package app.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'XShell', 'Console/Command' );

	/**
	 * La classe ImportcsvapresShell ...
	 *
	 * @package app.Console.Command
	 */
	class ImportcsvapresShell extends XShell
	{

		/**
		 *
		 * @var type
		 */
		public $uses = array( 'Apre', 'Integrationfichierapre', 'Domiciliationbancaire' );

		/**
		 *
		 * @var type
		 */
		public $csv = null;

		/**
		 *
		 * @var type
		 */
		public $csvFile = null;

		/**
		 *
		 * @var type
		 */
		public $schema = array( );

		/**
		 *
		 * @var type
		 */
		public $rejects = array( );

		/**
		 *
		 * @var type
		 */
		public $defaultFields = array(
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

		/**
		 *
		 * @var type
		 */
		public $fields = array( );

		/**
		 *
		 * @return type
		 */
		public function getOptionParser() {
			$parser = parent::getOptionParser();
			$parser->description( 'Ce script permet, au CG93, d\'importer, via des fichiers .csv, des APREs forfaitaires transmis par la CAF.' );
			$options = array(
				'headers' => array(
					'short' => 'H',
					'help' => 'précise si le fichier à importer commence par une colonne d\'en-tête ou s\'il commence directement par des données à intégrées',
					'choices' => array( 'true', 'false' ),
					'default' => 'true'
				),
				'separator' => array(
					'short' => 's',
					'help' => 'le caractère utilisé comme séparateur',
					'default' => ';'
				),
				'date' => array(
					'short' => 'd',
					'help' => 'Prise en compte du champ date',
					'default' => 'false',
					'boolean' => true
				)
			);
			$parser->addOptions( $options );
			$args = array(
				'csv' => array(
					'help' => 'chemin et nom du fichier à importer',
					'required' => true
				)
			);
			$parser->addArguments( $args );
			return $parser;
		}

		/**
		 *
		 */
		public function _showParams() {
			parent::_showParams();
			$this->out( '<info>Présence de la ligne d\'entête csv</info> : <important>'.$this->params['headers'].'</important>' );
			$this->out( '<info>Caractère de séparation</info> : <important>'.$this->params['separator'].'</important>' );
			$this->out( '<info>Prise en compte du champ date</info> : <important>'.($this->params['date'] ? 'true' : 'false').'</important>' );
		}

		/**
		 *
		 *
		 */
		public function startup() {
			parent::startup();
			$error = false;
			$out = array( );
			$settings = array( 'Apre.forfaitaire.montantbase', 'Apre.forfaitaire.montantenfant12', 'Apre.forfaitaire.nbenfant12max' );
			foreach( $settings as $path ) {
				$setting = Configure::read( $path );
				if( empty( $setting ) ) {
					$out[] = "Veuillez renseigner une valeur pour {$path} dans le fichier app/config/webrsa.inc";
					$error = true;
				}
			}


			$this->csv = new File( $this->args[0] );
			if( !$this->csv->exists() ) {
				$out[] = "Le fichier ".$this->args[0]." n'existe pas.";
				$error = true;
			}
			else if( !$this->csv->readable() ) {
				$out[] = "Le fichier ".$this->args[0]." n'est pas lisible.";
				$error = true;
			}


			if( !is_string( $this->params['separator'] ) ) {
				$out[] = "Le séparateur n'est pas correct, n'oubliez pas d'échapper le caractère (par exemple: \";\" plutôt que ;)";
				$error = true;
			}

			if( $error ) {
				$this->out();
				for( $i = 0; $i < count( $out ); $i++ ) {
					$this->out( '<error>'.$out[$i].'</error>' );
				}

				$this->out();
				$this->out( $this->OptionParser->help() );
				$this->_stop( 1 );
			}

			if( !$this->params['date'] ) {
				unset( $this->fields['date'] );
			}
		}

		/**
		 *
		 */
		public function main() {
			$this->Apre->begin();

			$fields = array( );
			$nLignesVides = 0;
			$nLignes = 0;
			$nLignesTraitees = 0;
			$success = true;

			mb_detect_order( array( 'UTF-8', 'ISO-8859-1', 'ASCII' ) );
			$fileLines = $this->csv->read();
			$this->encoding = mb_detect_encoding( $fileLines );
			if( $this->encoding != 'UTF-8' ) {
				$fileLines = mb_convert_encoding( $fileLines, 'UTF-8', $this->encoding );
			}

			$lines = explode( "\n", $fileLines );

			$out = array( );
			$this->XProgressBar->start( count( $lines ) );

			foreach( $lines as $nLigne => $line ) {
				$error = false;

				$line = trim( $line );
				$parts = explode( $this->params['separator'], $line );
				$cleanedParts = Hash::filter( (array)$parts );
				$nLignes++;

				if( !empty( $cleanedParts ) ) {
					if( $this->params['headers'] == 'true' && ( $nLigne == 0 ) ) {
						foreach( $parts as $offset => $title ) {
							$this->fields[strtolower( trim( $title, '" ' ) )] = $offset;
						}
						$diff = array_diff( $this->defaultFields, $this->fields );
						if( !empty( $diff ) ) {


							$this->out( array(
								'<error>En-têtes de colonnes mal formés</error>',
								str_repeat( ' ', 5 ).'<info>reçu :</info>',
								str_repeat( ' ', 10 )."<important>".implode( ", ", $this->fields )."</important>",
								str_repeat( ' ', 5 ).'<info>attendu :</info>',
								str_repeat( ' ', 10 )."<important>".implode( ", ", $this->defaultFields ).'</important>',
								str_repeat( ' ', 5 )."<important>".count( $parts )."</important><info> parties au lieu des </info><important>".count( $this->defaultFields )."</important><info> attendues</info>",
								str_repeat( ' ', 10 ).'<important>(ligne '.$nLigne.'): '.$line."</important>"
							) );


//							$errMsg = "En-têtes de colonnes mal formés (reçu ".implode( ", ", $this->fields )." - attendu ".implode( ", ", $this->defaultFields )." )";
//							$this->out( "{$errMsg}, ".count( $parts )." parties au lieu des ".count( $this->fields )." attendues (ligne {$nLigne}): {$line}" );
							$this->_stop( 1 );
						}
					}
					else if( $this->params['headers'] != 'true' && ( $nLigne == 0 ) ) {
						$this->fields = $this->defaultFields;
					}


					if( !$this->params['headers'] || ( $nLigne != 0 ) ) {
						$date = ( isset( $parts[$this->fields['date']] ) ? $parts[$this->fields['date']] : null );

						if( preg_match( '/'.$this->params['separator'].'[0-9,\.]+E\+[0-9]+'.$this->params['separator'].'/i', $line, $matches ) ) {
							$error = true;
							$errMsg = "Erreur de format";
							$out[] = "{$errMsg} (ligne {$nLigne}): \"{$matches[0]}\"";
							$this->rejects[] = "{$line};{$errMsg}";
						}
						else if( $this->params['date'] && !preg_match( '/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/i', $date, $matches ) ) {
							$error = true;
							$errMsg = "Erreur de format de la date (on s'attend à JJ/MM/AAAA)";
							$out[] = "{$errMsg} (ligne {$nLigne}): \"{$date}\"";
							$this->rejects[] = "{$line};{$errMsg}";
						}
						else if( count( $parts ) == count( $this->fields ) ) {
							$numdemrsa = trim( $parts[$this->fields['nudemrsa']], '"' );

							// Recherche ou ajout Paiementfoyer
							/// FIXME: dem ou cjt
							$titurib = trim( $parts[$this->fields['titureac']], '" ' );
							$nomprenomtiturib = trim( $parts[$this->fields['nptireac']], '" ' );
							$etaban = trim( $parts[$this->fields['banreact']], '" ' );
							$guiban = trim( $parts[$this->fields['guireact']], '" ' );
							$numcomptban = strtoupper( trim( $parts[$this->fields['ncptreac']], '" ' ) );
							$clerib = trim( $parts[$this->fields['cribreac']], '" ' );
							$modpaiac = trim( $parts[$this->fields['modpaiac']], '" ' );

							// Formattage du RIB avec les 0 devant
							$numdemrsa = str_pad( $numdemrsa, 11, '0', STR_PAD_LEFT ); // FIXME: ajout suite au problème du fichier "apre_1109_et_FORCG.csv" du 29/12/2009
							$etaban = str_pad( $etaban, 5, '0', STR_PAD_LEFT );
							$guiban = str_pad( $guiban, 5, '0', STR_PAD_LEFT );
							$numcomptban = str_pad( $numcomptban, 11, '0', STR_PAD_LEFT );
							$clerib = str_pad( $clerib, 2, '0', STR_PAD_LEFT );

							//date -> FIXME MM/AAAA
							$date = time();
							if( isset( $this->fields['date'] ) ) {
								$date = trim( $parts[$this->fields['date']], '"' );
								list( $jour, $mois, $annee ) = explode( '/', $date );
								$date = strtotime( "{$annee}-{$mois}-{$jour}" );
							}

							$validRib = validRib( $etaban, $guiban, $numcomptban, $clerib );
							$qd_dossier = array(
								'conditions' => array(
									'Dossier.numdemrsa' => $numdemrsa
								),
								'fields' => null,
								'order' => null,
								'recursive' => -1
							);
							$dossier = $this->Apre->Personne->Foyer->Dossier->find( 'first', $qd_dossier );


							if( $validRib && !empty( $dossier ) ) {
								$qd_foyer = array(
									'conditions' => array(
										'Foyer.dossier_id' => $dossier['Dossier']['id']
									),
									'fields' => null,
									'order' => null,
									'recursive' => -1
								);
								$foyer = $this->Apre->Personne->Foyer->find( 'first', $qd_foyer );


								if( !empty( $foyer ) ) {
									$nom = strtoupper( replace_accents( trim( $parts[$this->fields['nomact']], '"' ) ) );
									$prenom = strtoupper( replace_accents( trim( $parts[$this->fields['preact']], '"' ) ) );
									$nir = trim( $parts[$this->fields['nir']], '"' );

									$this->Apre->Personne->unbindModelAll();

									$fields = Set::merge(
													array_keys( Hash::flatten( array( 'Personne' => Set::normalize( array_keys( $this->Apre->Personne->schema() ) ) ) ) ), array_keys( Hash::flatten( array( 'Prestation' => Set::normalize( array_keys( $this->Apre->Personne->Prestation->schema() ) ) ) ) )
									);

									// Conditions pour le message d'erreur
//									$conditionsBeneficiaire = "Personne.foyer_id = '{$foyer['Foyer']['id']}' AND ( Personne.nir = '{$nir}' OR ( Personne.nom ILIKE '".strtoupper( replace_accents( $nom ) )."' AND Personne.prenom ILIKE '".strtoupper( replace_accents( $prenom ) )."' ) )";
									$conditionsBeneficiaire = "Personne.foyer_id = '{$foyer['Foyer']['id']}' AND ( SUBSTRING( TRIM( BOTH ' ' FROM Personne.nir ) FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH ' ' FROM '{$nir}' ) FROM 1 FOR 13 ) OR ( Personne.nom ILIKE '".strtoupper( replace_accents( $nom ) )."' AND Personne.prenom ILIKE '".strtoupper( replace_accents( $prenom ) )."' ) )";
									$beneficiaire = $this->Apre->Personne->find(
											'first', array(
										'fields' => $fields,
										'conditions' => array(
											'Personne.foyer_id' => $foyer['Foyer']['id'],
											'or' => array(
												"SUBSTRING( TRIM( BOTH ' ' FROM Personne.nir ) FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH ' ' FROM '{$nir}' ) FROM 1 FOR 13 )",
//													'Personne.nir' => $nir,
												'and' => array(
													'Personne.nom ILIKE' => strtoupper( replace_accents( $nom ) ),
													'Personne.prenom ILIKE' => strtoupper( replace_accents( $prenom ) ),
												)
											)
										),
										'joins' => array(
											array(
												'table' => 'prestations',
												'alias' => 'Prestation',
												'type' => 'INNER',
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
												'numeroapre' => date( 'Ym' ).sprintf( "%010s", $this->Apre->find( 'count' ) + 1 ), // FIXME
												'typedemandeapre' => 'AU',
												'datedemandeapre' => date( 'Y-m-d', $date ),
												'mtforfait' => (
												Configure::read( 'Apre.forfaitaire.montantbase' ) + (
												Configure::read( 'Apre.forfaitaire.montantenfant12' )
												* min( $nbenf12, Configure::read( 'Apre.forfaitaire.nbenfant12max' ) )
												)
												),
												'statutapre' => 'F',
												'nbenf12' => $nbenf12,
												'etatdossierapre' => 'COM',
												'eligibiliteapre' => 'O'
											)
										);

										// Recherche demandes d'APRE précédente
										$nbApresPrec = $this->Apre->find(
												'count', array(
											'conditions' => array(
												'personne_id' => $beneficiaire['Personne']['id'],
												'statutapre' => 'F',
												'datedemandeapre BETWEEN \''.date( 'Y-m-d', ( strtotime( '-1 year', $date ) ) ).'\' AND \''.date( 'Y-m-d', $date ).'\''
											)
												)
										);

										$nDomiciliationbancaire = $this->Domiciliationbancaire->find(
												'count', array(
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

												if( empty( $tmpSuccess ) && Configure::read( 'debug' ) > 0 ) {
													$out[] = var_export( $this->Apre->validationErrors, true );
												}
												$success = empty( $tmpSuccess ) && $success;

												// Recherche ou ajout Paiementfoyer
												$topribconj = ( ( Set::classicExtract( $beneficiaire, 'Prestation.rolepers' ) == 'CJT' ) ? true : false );

												$paiementfoyer = $this->Apre->Personne->Foyer->Paiementfoyer->find(
														'first', array(
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
																$paiementfoyer['Paiementfoyer'], array(
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

												if( empty( $tmpSuccess ) && Configure::read( 'debug' ) > 0 ) {
													$out[] = var_export( $this->Apre->Personne->Foyer->Paiementfoyer->validationErrors, true );
												}
												$success = empty( $tmpSuccess ) && $success;

												$nLignesTraitees++;
											}
											else {
												$errMsg = "Demande d'APRE forfaitaire datant de moins de 12 mois trouvée pour la date d'importation du ".date( 'd/m/Y', $date )." et pour la personne ";
												$out[] = "{$errMsg}: {$beneficiaire['Personne']['nom']} {$beneficiaire['Personne']['prenom']} (nir: {$beneficiaire['Personne']['nir']})";
												$this->rejects[] = "{$line};{$errMsg}";
											}
										}
										else {
											$errMsg = "Demande d'APRE forfaitaire rejetée pour cause d'entrée non trouvée dans la table domiciliationsbancaires (codebanque = {$etaban} et codeagence = {$guiban}) pour la personne ";
											$out[] = "{$errMsg}: {$beneficiaire['Personne']['nom']} {$beneficiaire['Personne']['prenom']} (nir: {$beneficiaire['Personne']['nir']})";
											$this->rejects[] = "{$line};{$errMsg}";
										}
									}
									else {
										$errMsg = "Bénéficiaire de l'APRE non trouvé au sein du dossier de demande RSA n°{$numdemrsa} ({$conditionsBeneficiaire})";
										$out[] = "{$errMsg} (ligne {$nLigne}): {$line}";
										$this->rejects[] = "{$line};{$errMsg}";
									}
								}
								else {
									$errMsg = "Foyer non trouvé pour le dossier de demande RSA n°{$numdemrsa}";
									$out[] = "{$errMsg} (ligne {$nLigne}): {$line}";
									$this->rejects[] = "{$line};{$errMsg}";
								}
							}
							else {
								if( !$validRib ) {
									$errMsg = "RIB non valide (".implode( "-", array( $etaban, $guiban, $numcomptban, $clerib ) ).")";
									$out[] = "{$errMsg} (ligne {$nLigne}): {$line}";
								}

								if( empty( $dossier ) ) {
									$errMsg = "Dossier de demande RSA n°{$numdemrsa} non trouvé";
									$out[] = "{$errMsg} (ligne {$nLigne}): {$line}";
								}
								$this->rejects[] = "{$line};{$errMsg}";
							}
						}
						else {
							$errMsg = "Ligne mal formée";
							$out[] = "{$errMsg}, ".count( $parts )." parties au lieu des ".count( $this->fields )." attendues (ligne {$nLigne}): {$line}";
							$this->rejects[] = "{$line};{$errMsg}";
						}
					}
				}
				else {
					$nLignesVides++;
				}
				$this->XProgressBar->next();
			}



			$nLignesATraiter = $nLignes - $nLignesVides - ( $this->params['headers'] ? 1 : 0 );
			$nLignesEnRejet = max( 0, count( $this->rejects ) );

			$integrationfichierapre = array(
				'Integrationfichierapre' => array(
					'date_integration' => date( 'Y-m-d  H:i:s' ),
					'nbr_atraiter' => $nLignesATraiter,
					'nbr_succes' => $nLignesTraitees,
					'nbr_erreurs' => $nLignesEnRejet,
					'fichier_in' => basename( $this->args[0] ),
					'erreurs' => implode( "\n", $this->rejects )
				)
			);

			$this->Integrationfichierapre->create( $integrationfichierapre );
			$tmpSuccess = $this->Integrationfichierapre->save();
			if( empty( $tmpSuccess ) && Configure::read( 'debug' ) > 0 ) {
				debug( $this->Integrationfichierapre->validationErrors );
			}
			$success = empty( $tmpSuccess ) && $success;

			$message = "%s: ".$nLignesATraiter." lignes à traiter, $nLignesTraitees lignes traitées, ".$nLignesEnRejet." lignes rejetées.";
			/// Fin de la transaction
			if( $success ) {
				$out[] = sprintf( $message, "<success>Script terminé avec succès</success>" );
				$this->Apre->commit();
			}
			else {
				$out[] = sprintf( $message, "<error>Script terminé avec erreurs</error>" );
				$this->Apre->rollback();
			}


			$this->out();
			$this->out();
			for( $i = 0; $i < count( $out ) - 1; $i++ ) {
				$this->out( '<important>'.$out[$i].'</important>' );
			}
			$this->out();
			$this->out( $out[count( $out ) - 1] );
		}

	}
?>