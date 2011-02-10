<?php
	/*
		Constats (CG 66, 20101217_dump_webrsaCG66_rc9.sql.gz):
			- une personne garde en général son identifiant PE à vie (?)
			- une personne peut changer d'identifiant PE lors d'une nouvelle inscription (cas rare, cf. personne 34057)
// 			- les personnes qui nous viennent par le flux ne sont pas toujours DEM/CJT RSA (?)
		TODO:
			- ajouter l'aide
			- ne serait-il pas opportun de supprimer les NIR que l'on sait incorrects
				(table personnes, et les tables traitées ici) ?
			- bouger l'identifiant PE dans les tables d'historique ?
			- ajouter motif lié au code (Catégorie de l'inscription) pour les inscriptions ?
	*/

    class IntegrationfluxpeShell extends AppShell
    {
		public $defaultParams = array(
			'log' => false,
			'logpath' => LOGS,
			'verbose' => false,
			'headers' => true,
			'separator' => ';',
		);

		public $verbose = false;

		public $headers = true;

		public $separator = ';';

		public $csv = false;

		public $map = array(
			'cessation' => array(
				'nir',
				'identifiantpe',
				'nom',
				'prenom',
				'dtnai',
				'date',
				'code',
				'motif',
				'nir2'
			),
			'radiation' => array(
				'nir',
				'identifiantpe',
				'nom',
				'prenom',
				'dtnai',
				'date',
				'code',
				'motif',
				'nir2'
			),
			'inscription' => array(
				'nir',
				'identifiantpe',
				'nom',
				'prenom',
				'dtnai',
				'date',
				'code',
				'nir2'
			),
		);

		public $typesCsv = array( 'cessations', 'inscriptions', 'radiations' );

		public $fieldsInformationpe = array(
			'nir',
			'nom',
			'prenom',
			'dtnai',
			'nir2'
		);

		protected $_rejects = array();

		/**
		* Initialisation: lecture des paramètres
		*/

		public function initialize() {
			parent::initialize();

			$this->verbose = $this->_getNamedValue( 'verbose', 'boolean' );
			$this->header = $this->_getNamedValue( 'headers', 'boolean' );
			$this->separator = $this->_getNamedValue( 'separator', 'string' );

			if( count( $this->args ) == 2 ) {
				if( !in_array( $this->args[0], $this->typesCsv ) ) {
					$this->err( "Veuillez spécifier en premier paramètre une valeur pour le type de fichier parmi ".implode( ', ', $this->typesCsv ) );

					$this->_stop( 1 );
				}

				$this->csv = new File( $this->args[1] );
				if( !$this->csv->exists() ) {
					$this->err( "Le fichier {$this->args[1]} n'existe pas." );
					$this->_stop( 1 );
				}
				else if( !$this->csv->readable() ) {
					$this->err( "Le fichier {$this->args[1]} n'est pas lisible." );
					$this->_stop( 1 );
				}
			}
			else if( !( count( $this->args ) == 0 || ( count( $this->args ) == 1 && $this->args[0] == 'update' ) ) ) {
				$this->err( "Veuillez fournir deux paramètres au script: le type de fichier à intégrer et le chemin vers le fichier à intégrer (ex. {$this->shell} cessations /tmp/cessationspe-20101227.csv)" );

				$this->_stop( 1 );
			}

			$this->Informationpe = ClassRegistry::init( 'Informationpe' ); // FIXME: le shell devrait le faire tout seul avec $uses
		}

		/**
		* Affiche l'en-tête du shell (message d'accueil avec quelques informations)
		*/

		public function _welcome() {
			$this->out();
			$this->out( 'Script d\'importation des flux venant de Pôle Emploi' );
			$this->out();
			if( $this->command == 'update' ) {
				$this->out( "Mise à jour de la relation entre les personnes et les informations Pôle Emploi." );
			}
			else if( count( $this->args ) == 1 ) {
				$this->out( "Fichier traité: {$this->csv->path}" );
			}
			$this->out();
			$this->hr();
		}

		/**
		*
		*/

		protected function _import( $etat ) {
			$lines = explode( "\n", $this->csv->read() );
			$lignespresentes = 0;

			$offsetDtnai = array_search( 'dtnai', $this->map[$etat] );
			$offsetAutreDate = $offsetDtnai + 1;

			$success = true;
			$this->Informationpe->Historiqueetatpe->begin();

			foreach( $lines as $numLine => $line ) {
				$line = preg_replace( '/(,+)$/', '', trim( $line ) );
				$line = preg_replace( '/^"(.*)"$/', '\1', trim( $line ) );

				if( !( $numLine == 0 && $this->headers ) && trim( $line ) != '' ) {
					$numLine++; // La numérotation des lignes commence à 1

					$parts = explode( $this->separator, $line );

					// Reformattage du NIR
					$parts[0] = str_replace( ' ', '', $parts[0] );
					$parts[count($parts)-1] = str_replace( ' ', '', $parts[count($parts)-1] );

					// Reformattage de l'identifiant Pôle Emploi
					$parts[1] = str_replace( ' ', '', $parts[1] );

					// Le nombre de colonnes de cette ligne ne correspond pas au nombre de colonnes attendu
					if( count( $parts ) != count( $this->map[$etat] ) ) {
						$nParts = count($parts);
						$nPartsType = count( $this->map[$etat] );
						$this->err( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} (Le nombre de colonnes de cette ligne ({$nParts}) ne correspond pas au nombre de colonnes attendu ({$nPartsType}))." );
						$this->_rejects[] = $line;
					}
					// Colonnes NIR et NIR2 différentes ?
					else if( $parts[0] != $parts[count($parts)-1] ) {
						$this->err( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} (les deux NIR sont différents: \"{$parts[0]}\" et \"{$parts[count($parts)-1]}\")." );
						$this->_rejects[] = $line;
					}
					// Le NIR n'est pas sur 13 caractères
					else if( strlen( $parts[0] ) != 13 ) {
						$this->err( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} (le NIR \"{$parts[0]}\" ne comporte pas 13 caractères)." );
						$this->_rejects[] = $line;
					}
					// L'identifiant PE n'est pas formatté correctement
					else if( !preg_match( '/^([0-9]{7})([A-Z0-9])([0-9]{3})$/', $parts[1] ) ) {
						$this->err( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} (l\'identifiant Pôle Emploi \"{$parts[1]}\" n'est pas formatté correctement)." );
						$this->_rejects[] = $line;
					}
					// La date de naissance n'est pas formattée corretement
					else if( !preg_match( '/^([0-9]){2}\/([0-9]){2}\/([0-9]){4}$/', $parts[$offsetDtnai] ) ) {
						$this->err( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} (la date \"{$parts[$offsetDtnai]}\" n'est pas correcte)." );
						$this->_rejects[] = $line;
					}
					// L'autre date n'est pas formattée correctement
					else if( !preg_match( '/^([0-9]){2}\/([0-9]){2}\/([0-9]){4}$/', $parts[$offsetAutreDate] ) ) {
						$this->err( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} (la date \"{$parts[$offsetAutreDate]}\" n'est pas correcte)." );
						$this->_rejects[] = $line;
					}
					// La ligne a l'air correcte, essai de traitement
					else {
						// Ajout de la clé pour le NIR (on a le NIR sur 15 caractères d'habitude)
						$parts[0] = $parts[0].cle_nir( $parts[0] );
						$parts[count($parts)-1] = $parts[count($parts)-1].cle_nir( $parts[count($parts)-1] );

						// Si le NIR n'est pas valide, on rejette la ligne
						if( !valid_nir( $parts[count($parts)-1] ) || !valid_nir( $parts[0] ) ) {
							$parts[0] = substr( $parts[0], 0, 13 );
							$this->err( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} (le NIR \"{$parts[0]}\" n'est pas valide." );
							$this->_rejects[] = $line;
						}
						else {
							// Recherche / remplissage des tables -> FIXME: en faire une fonction

							// Table informationspe
							$informationpe = array( 'Informationpe' => array( ) );
							foreach( $this->fieldsInformationpe as $column ) {
								$key = array_search( $column, $this->map[$etat] );

								if( $column == 'dtnai' ) {
									// Formattage de la date du format JJ/MM/AAAA au format SQL AAAA-MM-JJ
									// Concerne le champ dtnai -- FIXME, function
									$dateParts = explode( '/', $parts[$key] );
									$parts[$key] = "{$dateParts[2]}-{$dateParts[1]}-{$dateParts[0]}";
								}
								$informationpe['Informationpe'][$column] = $parts[$key];
							}

							$oldInformationpe = $this->Informationpe->find(
								'first',
								array(
									'conditions' => array(
										'OR' => array(
											'Informationpe.nir' => $informationpe['Informationpe']['nir'], // FIXME: not null ?
											array(
												'Informationpe.nom' => $informationpe['Informationpe']['nom'],
												'Informationpe.prenom' => $informationpe['Informationpe']['prenom'],
												'Informationpe.dtnai' => $informationpe['Informationpe']['dtnai']
											)
										)
									),
									'contain' => false
								)
							);

							if( empty( $oldInformationpe ) ) {
								$this->Informationpe->create( $informationpe );
								$tmpSuccessInformationpe = $this->Informationpe->save();
								$success =  $tmpSuccessInformationpe && $success;
								$informationpe_id = $this->Informationpe->id;
							}
							else {
								$tmpSuccessInformationpe = true;
								$informationpe_id = $oldInformationpe['Informationpe']['id'];
							}

							// Tables historiquecessationspe, historiqueinscriptionspe, historiqueradiationspe
							$record = array( 'Historiqueetatpe' => array( ) );
							foreach( $this->map[$etat] as $key => $column ) {
								// Formattage de la date du format JJ/MM/AAAA au format SQL AAAA-MM-JJ
								// Concerne le champ date -- FIXME, function
								if( !in_array( $column, $this->fieldsInformationpe ) ) {
									if( $column == 'date' ) {
										$dateParts = explode( '/', $parts[$key] );
										$parts[$key] = "{$dateParts[2]}-{$dateParts[1]}-{$dateParts[0]}";
									}
									$record['Historiqueetatpe'][$column] = $parts[$key];
								}
							}
							$record['Historiqueetatpe']['informationpe_id'] = $informationpe_id;
							$record['Historiqueetatpe']['etat'] = $etat;

							$conditions = array(
								'informationpe_id' => $informationpe_id,
								'etat' => $etat,
								'date' => $record['Historiqueetatpe']['date'],
								'code' => $record['Historiqueetatpe']['code']
							);
							if( isset( $record['Historiqueetatpe']['motif'] ) ) {
								$conditions['motif'] = $record['Historiqueetatpe']['motif'];
							}

							$oldRecord = $this->Informationpe->Historiqueetatpe->find(
								'first',
								array(
									'conditions' => $conditions,
									'contain' => false
								)
							);

							if( empty( $oldRecord ) ) {
								$this->Informationpe->Historiqueetatpe->create( $record );
								$tmpSuccessModelClass = $this->Informationpe->Historiqueetatpe->save();
								$success = $tmpSuccessModelClass && $success;

								if( $tmpSuccessInformationpe && $tmpSuccessModelClass ) {
									$this->out( "Enregistrement des données de la ligne {$numLine} du fichier {$this->csv->path} effectué." );
								}
								else {
									$this->out( "Erreur lors de l\'enregistrement des données de la ligne {$numLine} du fichier {$this->csv->path}." );
								}
							}
							else {
								$this->out( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} (ligne déjà présente en base)." );
								$lignespresentes++;
							}
						}
					}
				}
			}

			// A-t'on des lignes rejetées à exporter dans un fichier CSV ?
			if( !empty( $this->_rejects ) ) {
				$titleLine = "";
				if( $this->headers ) {
					$titleLine = "{$lines[0]}\n";
				}
				$output = $titleLine.implode( "\n", $this->_rejects )."\n";

				$outfile = rtrim( $this->logpath, '/' ).'/'.$this->outfile;
				$escapedApp = str_replace( '/', '\/', APP );
				if( preg_replace( '/^'.$escapedApp.'/', '', $outfile ) ) {
					$outfile = 'app/'.preg_replace( '/^'.$escapedApp.'/', '', $outfile );
				}
				$outfile = preg_replace( '/\.log$/', '.csv', $outfile );
				$outfile = preg_replace( '/_shell-/', "_rejets_{$this->command}-", $outfile );

				$this->hr();
				file_put_contents( $outfile, $output );
				$this->out( "Le fichier de rejets se trouve dans {$outfile}" );
			}

			// Fin du shell, résultats
			$this->hr();
			if( $success ) {
				$nlines = ( $numLine - 1 );
				$nrejects = count( $this->_rejects );
				$nouveaux = ( $nlines - $nrejects - $lignespresentes );

				$this->Informationpe->Historiqueetatpe->commit();
				$this->out( "{$nlines} lignes traitées ({$nouveaux} nouveaux enregistrement, {$nrejects} rejets, {$lignespresentes} enregistrements déjà présents) avec succès." );
			}
			else {
				$this->Informationpe->Historiqueetatpe->rollback();
				$this->out( "Erreur lors de l\'enregistrement." );
			}
		}

		/**
		* Import des cessations
		*/

		public function cessations() {
			$this->_import( 'cessation' );
		}

		/**
		* Import des radiations
		*/

		public function radiations() {
			$this->_import( 'radiation' );
		}

		/**
		* Import des inscriptions
		*/

		public function inscriptions() {
			$this->_import( 'inscription' );
		}

		/**
		* Par défaut, on affiche l'aide
		*/

		public function main() {
			$this->help();
		}

		/**
		* Aide
		*/

		public function help() {
			$this->log = false;

			$this->out("Usage: cake/console/cake {$this->shell} <commande> <paramètres>");
			$this->hr();
			$this->out();
			$this->out('Commandes:');
			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");
			$this->out("\n\t{$this->shell} cessations <fichier>\n\t\tImporte les informations Pôle Emploi de type cessation à partir du fichier CSV passé en second paramètre.");
			$this->out("\n\t{$this->shell} inscriptions <fichier>\n\t\tImporte les informations Pôle Emploi de type inscriptions à partir du fichier CSV passé en second paramètre.");
			$this->out("\n\t{$this->shell} radiations <fichier>\n\t\tImporte les informations Pôle Emploi de type radiations à partir du fichier CSV passé en second paramètre.");
			$this->out("\n\t{$this->shell} update\n\t\tMise à jour de la relation entre les personnes et les informations Pôle Emploi.");
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-separator <caractère>\n\t\tQuel est le séparateur utilisé dans le fichier CSV ?\n\t\tPar défaut: ".$this->_defaultToString( 'separator' )."\n");
			$this->out("\t-headers <booléen>\n\t\tSi la première ligne du fichier CSV est une ligne de titre ?\n\t\tPar défaut: ".$this->_defaultToString( 'headers' )."\n");
			$this->out("\t-log <booléen>\n\t\tDoit-on journaliser la sortie du programme ?\n\t\tPar défaut: ".$this->_defaultToString( 'log' )."\n");
			$this->out("\t-logpath <répertoire>\n\t\tLe répertoire dans lequel enregistrer les fichiers de journalisation et les fichiers CSV contenant les rejets.\n\t\tPar défaut: ".$this->_defaultToString( 'logpath' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
    }
?>