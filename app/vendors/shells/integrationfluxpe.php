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
			'apdnovembre2011' => true,
			'logerror' => true,
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
				'nir2',
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
				'nir2',
			),
			'inscription' => array(
				'nir',
				'identifiantpe',
				'nom',
				'prenom',
				'dtnai',
				'date',
				'code',
				'nir2',
			),
			'stock' => array(
				'nir',
				'identifiantpe',
				'nom',
				'prenom',
				'dtnai',
				'date',
				'code',
				'nir2',
			),
		);

		// Nouvelles données novembre 2011
		public $colonnesApdnovembre2011 = array(
			'codeinsee',
			'localite',
			'adresse',
			'ale'
		);

		public $typesCsv = array( 'cessations', 'inscriptions', 'radiations', 'stock' );

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
			$this->apdnovembre2011 = $this->_getNamedValue( 'apdnovembre2011', 'boolean' );
			$this->logerror = $this->_getNamedValue( 'logerror', 'boolean' );

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
			else if( !( count( $this->args ) == 0 ) ) {
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
			if( count( $this->args ) == 1 ) {
				$this->out( "Fichier traité: {$this->csv->path}" );
			}
			$this->out();
			$this->hr();
		}

		/**
		*
		*/
		protected function _rejectLine( $file, $numLine, $line, $error ) {
			$this->err( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} ({$error})." );
			if( $this->logerror ) {
				$line = "{$line};\"$error\"";
			}
			$this->_rejects[] = $line;
		}

		/**
		*
		*/
		protected function _import( $etat ) {
			// Si on veut travailler sur les flux à partir de novembre 2011, on aura
			// 4 colonne en plus; voir $this->colonnesApdnovembre2011
			if( $this->apdnovembre2011 ) {
				$this->map[$etat] = array_merge( $this->map[$etat], $this->colonnesApdnovembre2011 );
			}

			$lines = explode( "\n", $this->csv->read() );
			$lignespresentes = 0;

			$offsets = array();
			$offsets['dtnai'] = array_search( 'dtnai', $this->map[$etat] );
			$offsets['date'] = $offsets['dtnai'] + 1;
			$offsets['nir'] = array_search( 'nir', $this->map[$etat] );
			$offsets['nir2'] = array_search( 'nir2', $this->map[$etat] );
			$offsets['identifiantpe'] = array_search( 'identifiantpe', $this->map[$etat] );
			$offsets['code'] = array_search( 'code', $this->map[$etat] );

			$success = true;
			$this->Informationpe->Historiqueetatpe->begin();

			foreach( $lines as $numLine => $line ) {
				$line = preg_replace( '/(,+)$/', '', trim( $line ) );
				$line = preg_replace( '/^"(.*)"$/', '\1', trim( $line ) );

				if( !( $numLine == 0 && $this->headers ) && trim( $line ) != '' ) {
					$numLine++; // La numérotation des lignes commence à 1

					$parts = explode( $this->separator, $line );

					foreach( array_keys( $parts ) as $i ) {
						$parts[$i] = trim( $parts[$i], '"' );
						$parts[$i] = trim( $parts[$i], ' ' );
					}

					// Reformattage du NIR
					$parts[$offsets['nir']] = str_replace( ' ', '', $parts[$offsets['nir']] );
					$parts[$offsets['nir2']] = str_replace( ' ', '', $parts[$offsets['nir2']] );

					// Reformattage de l'identifiant Pôle Emploi
					$parts[$offsets['identifiantpe']] = str_replace( ' ', '', $parts[$offsets['identifiantpe']] );

					// Le nombre de colonnes de cette ligne ne correspond pas au nombre de colonnes attendu
					if( count( $parts ) != count( $this->map[$etat] ) ) {
						$nParts = count($parts);
						$nPartsType = count( $this->map[$etat] );
						$this->_rejectLine( $this->csv->path, $numLine, $line, "Le nombre de colonnes de cette ligne ({$nParts}) ne correspond pas au nombre de colonnes attendu ({$nPartsType})" );
					}
					// Colonnes NIR et NIR2 différentes ?
					else if( $parts[$offsets['nir']] != $parts[$offsets['nir2']] ) {
						$this->_rejectLine( $this->csv->path, $numLine, $line, "Les deux NIR sont différents: \"{$parts[$offsets['nir']]}\" et \"{$parts[$offsets['nir2']]}\"" );
					}
					// Le NIR n'est pas sur 13 caractères
					else if( strlen( $parts[$offsets['nir']] ) != 13 ) {
						$this->_rejectLine( $this->csv->path, $numLine, $line, "Le NIR \"{$parts[$offsets['nir']]}\" ne comporte pas 13 caractères" );
					}
					// L'identifiant PE n'est pas formatté correctement
					else if( !preg_match( '/^([0-9]{7})([A-Z0-9])([0-9]{3})$/', $parts[$offsets['identifiantpe']] ) ) {
						$this->_rejectLine( $this->csv->path, $numLine, $line, "L'identifiant Pôle Emploi \"{$parts[$offsets['identifiantpe']]}\" n'est pas formatté correctement" );
					}
					// La date de naissance n'est pas formattée corretement
					else if( !preg_match( '/^([0-9]){2}\/([0-9]){2}\/([0-9]){4}$/', $parts[$offsets['dtnai']] ) ) {
						$this->_rejectLine( $this->csv->path, $numLine, $line, "La date \"{$parts[$offsets['dtnai']]}\" n'est pas correcte" );
					}
					// L'autre date n'est pas formattée correctement
					else if( !preg_match( '/^([0-9]){2}\/([0-9]){2}\/([0-9]){4}$/', $parts[$offsets['date']] ) ) {
						$this->_rejectLine( $this->csv->path, $numLine, $line, "La date \"{$parts[$offsets['date']]}\" n'est pas correcte" );
					}
					// L'autre date n'est pas formattée correctement
					else if( !preg_match( '/^[^ ]+$/', $parts[$offsets['code']] ) ) {
						$this->_rejectLine( $this->csv->path, $numLine, $line, "Le code \"{$parts[$offsets['code']]}\" n'est pas présent" );
					}
					// La ligne a l'air correcte, essai de traitement
					else {
						// Ajout de la clé pour le NIR (on a le NIR sur 15 caractères d'habitude)
						$parts[$offsets['nir']] = $parts[$offsets['nir']].cle_nir( $parts[$offsets['nir']] );
						$parts[$offsets['nir2']] = $parts[$offsets['nir2']].cle_nir( $parts[$offsets['nir2']] );

						// Si le NIR n'est pas valide, on rejette la ligne
						if( !valid_nir( $parts[$offsets['nir2']] ) || !valid_nir( $parts[$offsets['nir']] ) ) {
							$parts[$offsets['nir']] = substr( $parts[$offsets['nir']], 0, 13 );
							$this->_rejectLine( $this->csv->path, $numLine, $line, "Le NIR \"{$parts[$offsets['nir']]}\" n'est pas valide" );
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
									'conditions' => $this->Informationpe->qdConditionsJoinPersonneOnValues( 'Informationpe', $informationpe['Informationpe'] ),
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
									$this->_rejectLine( $this->csv->path, $numLine, $line, "Erreur lors de l'enregistrement des données" );
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
					$headers = rtrim( $lines[0], "\r\n" );
					if( $this->logerror ) {
						$headers = "{$headers};\"Erreur\"";
					}
					$titleLine = "{$headers}\n";
				}
				$output = $titleLine.implode( "\n", $this->_rejects )."\n";

				// Nom du fichier de rejets
				$outfile = rtrim( $this->logpath, '/' ).'/'.Inflector::underscore( preg_replace( '/Shell$/', '', $this->name ) ).'_rejets_'.$this->command.'_'.date( 'Ymd-His' ).'_'.$this->csv->name().'.csv';
				$escapedApp = str_replace( '/', '\/', APP );
				if( preg_replace( '/^'.$escapedApp.'/', '', $outfile ) ) {
					$outfile = 'app/'.preg_replace( '/^'.$escapedApp.'/', '', $outfile );
				}

				$this->hr();
				file_put_contents( $outfile, $output );
				chmod( $outfile, 0777 );
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
				$this->out( "Erreur lors de l'enregistrement." );
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
		* Import des stock
		*/

		public function stock() {
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
			$this->out("\n\t{$this->shell} stock <fichier>\n\t\tImporte les informations Pôle Emploi de type stock à partir du fichier CSV passé en second paramètre.");
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-apdnovembre2011 <booléen>\n\t\tLe fichier CSV à intégrer contient-il les colonnes \"code INSEE\", \"localité\", \"adresse\" et \"n° ALE\" (dans les flux à partir de novembre 2011) ?\n\t\tPar défaut: ".$this->_defaultToString( 'apdnovembre2011' )."\n");
			$this->out("\t-separator <caractère>\n\t\tQuel est le séparateur utilisé dans le fichier CSV ?\n\t\tPar défaut: ".$this->_defaultToString( 'separator' )."\n");
			$this->out("\t-headers <booléen>\n\t\tSi la première ligne du fichier CSV est une ligne de titre ?\n\t\tPar défaut: ".$this->_defaultToString( 'headers' )."\n");
			$this->out("\t-log <booléen>\n\t\tDoit-on journaliser la sortie du programme ?\n\t\tPar défaut: ".$this->_defaultToString( 'log' )."\n");
			$this->out("\t-logpath <répertoire>\n\t\tLe répertoire dans lequel enregistrer les fichiers de journalisation et les fichiers CSV contenant les rejets.\n\t\tPar défaut: ".$this->_defaultToString( 'logpath' )."\n");
			$this->out("\t-logerror <booléen>\n\t\tDoit-on ajouter au fichier CSV des rejets une colonne contenant la raison du rejet ?\n\t\tPar défaut: ".$this->_defaultToString( 'logerror' )."\n");
			$this->out();

			$this->_stop( 0 );
		}
    }
?>