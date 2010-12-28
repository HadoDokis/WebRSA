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
			'Historiquecessationpe' => array(
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
			'Historiqueradiationpe' => array(
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
			'Historiqueinscriptionpe' => array(
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
			'identifiantpe',
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

		protected function _import( $modelClass ) {
			$lines = explode( "\n", $this->csv->read() );
			$this->{$modelClass} = ClassRegistry::init( $modelClass );
			$lignespresentes = 0;

			$offsetDtnai = array_search( 'dtnai', $this->map[$modelClass] );
			$offsetAutreDate = $offsetDtnai + 1;

			$success = true;
			$this->{$modelClass}->begin();

			foreach( $lines as $numLine => $line ) {
				if( !( $numLine == 0 && $this->headers ) && trim( $line ) != '' ) {
					$numLine++; // La numérotation des lignes commence à 1

					$parts = explode( $this->separator, $line );

					// Reformattage du NIR
					$parts[0] = str_replace( ' ', '', $parts[0] );
					$parts[count($parts)-1] = str_replace( ' ', '', $parts[count($parts)-1] );

					// Reformattage de l'identifiant Pôle Emploi
					$parts[1] = str_replace( ' ', '', $parts[1] );

					// Le nombre de colonnes de cette ligne ne correspond pas au nombre de colonnes attendu
					if( count( $parts ) != count( $this->map[$modelClass] ) ) {
						$nParts = count($parts);
						$nPartsType = count( $this->map[$modelClass] );
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

						// Recherche / remplissage des tables -> FIXME: en faire une fonction

						// Table informationspe
						$informationpe = array( 'Informationpe' => array( ) );
						foreach( $this->fieldsInformationpe as $column ) {
							$key = array_search( $column, $this->map[$modelClass] );

							if( $column == 'dtnai' ) {
								// Formattage de la date du format JJ/MM/AAAA au format SQL AAAA-MM-JJ
								// Concerne le champ dtnai -- FIXME, function
								$dateParts = explode( '/', $parts[$key] );
								$parts[$key] = "{$dateParts[2]}-{$dateParts[1]}-{$dateParts[0]}";
							}
							$informationpe['Informationpe'][$column] = $parts[$key];
						}

						$oldInformationpe = $this->{$modelClass}->Informationpe->find(
							'first',
							array(
								'conditions' => array(
									'OR' => array(
										'Informationpe.nir' => $informationpe['Informationpe']['nir'],
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
							$this->{$modelClass}->Informationpe->create( $informationpe );
							$tmpSuccessInformationpe = $this->{$modelClass}->Informationpe->save();
							$success =  $tmpSuccessInformationpe && $success;
							$informationpe_id = $this->{$modelClass}->Informationpe->id;
						}
						else {
							$tmpSuccessInformationpe = true;
							$informationpe_id = $oldInformationpe['Informationpe']['id'];
						}

						// Tables historiquecessationspe, historiqueinscriptionspe, historiqueradiationspe
						$record = array( $modelClass => array( ) );
						foreach( $this->map[$modelClass] as $key => $column ) {
							// Formattage de la date du format JJ/MM/AAAA au format SQL AAAA-MM-JJ
							// Concerne le champ date -- FIXME, function
							if( !in_array( $column, $this->fieldsInformationpe ) ) {
								if( $column == 'date' ) {
									$dateParts = explode( '/', $parts[$key] );
									$parts[$key] = "{$dateParts[2]}-{$dateParts[1]}-{$dateParts[0]}";
								}
								$record[$modelClass][$column] = $parts[$key];
							}
						}
						$record[$modelClass]['informationpe_id'] = $informationpe_id;

						$conditions = array(
							'informationpe_id' => $informationpe_id,
							'date' => $record[$modelClass]['date'],
							'code' => $record[$modelClass]['code']
						);
						if( isset( $record[$modelClass]['motif'] ) ) {
							$conditions['motif'] = $record[$modelClass]['motif'];
						}

						$oldRecord = $this->{$modelClass}->find(
							'first',
							array(
								'conditions' => $conditions,
								'contain' => false
							)
						);

						if( empty( $oldRecord ) ) {
							$this->{$modelClass}->create( $record );
							$tmpSuccessModelClass = $this->{$modelClass}->save();
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

				$this->{$modelClass}->commit();
				$this->out( "{$nlines} lignes traitées ({$nouveaux} nouveaux enregistrement, {$nrejects} rejets, {$lignespresentes} enregistrements déjà présents) avec succès." );

				$this->out( "Mise à jour de la relation entre les personnes et les informations Pôle Emploi." );
				$this->update();
			}
			else {
				$this->{$modelClass}->rollback();
				$this->out( "Erreur lors de l\'enregistrement." );
			}
		}

		/**
		* Import des cessations
		*/

		public function cessations() {
			$this->_import( 'Historiquecessationpe' );
		}

		/**
		* Import des radiations
		*/

		public function radiations() {
			$this->_import( 'Historiqueradiationpe' );
		}

		/**
		* Import des inscriptions
		*/

		public function inscriptions() {
			$this->_import( 'Historiqueinscriptionpe' );
		}

		/**
		* Mise à jour du champ personne_id de la table informationspe
		*/

		public function update() {
			$this->Informationpe = ClassRegistry::init( 'Informationpe' );
			$this->Informationpe->begin();

			$sql = 'UPDATE informationspe
				SET personne_id = (
					SELECT personnes.id
						FROM personnes
							INNER JOIN prestations ON (
								personnes.id = prestations.personne_id
								AND prestations.natprest = \'RSA\'
								AND prestations.rolepers IN ( \'DEM\', \'CJT\' )
							)
						WHERE
							(
								personnes.nir IS NOT NULL
								AND informationspe.nir IS NOT NULL
								AND CHAR_LENGTH( TRIM( both \' \' FROM personnes.nir ) ) = 15
								AND CHAR_LENGTH( TRIM( both \' \' FROM informationspe.nir ) ) = 15
								AND personnes.nir = informationspe.nir
							)
							OR (
								personnes.nom = informationspe.nom
								AND personnes.prenom = informationspe.prenom
								AND personnes.dtnai = informationspe.dtnai
							)
						LIMIT 1
				)
				WHERE personne_id IS NULL;';

			$this->Informationpe->query( $sql );
			$this->Informationpe->commit();
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


	/*
	Attention:
		les personnes trouvées par le script importcsvinfope et pour lesquelles on
		rajoute une entrée dans infospoleemploi ne sont pas toutes les personnes
		"concernées" (une même personne pouvant appartenir à plusieurs foyers, il
		faudrait refléter cette réalité, et voir si la personne est DEM ou CJT RSA,
		car pour l'instant, c'est simplement la première personne qui vient à
		PostgreSQL).
	*/

	/*
	Questions / remarques:
		1°) Pourquoi passait-on par les tables temporaires (parce que la personne
			n'était peut-être pas encore dans la liste des allocataires) ?
		2°) Quand les entrées de la table tempcessations (etc) sont-elles supprimées
			(lorsqu'on trouve la personne dans la liste des allocataires dans le script traitementcsvinfope) ?
		3°) A quels endroits se sert-on actuellement d'Infopoleemploi (infospoleemploi) dans WebRSA ?
			grep -lR "\(Infopoleemploi\|infospoleemploi\)" app | grep -v "\/\(fixtures\|sql\|tests\|\.svn\)\/"
				* app/models/personne.php
				* app/models/critere.php
				* app/models/infopoleemploi.php
				* app/config/inflections.php
				* app/views/criteres/exportcsv.ctp
				* app/views/criteres/index.ctp
				* app/views/dossiers/view.ctp
				* app/controllers/dossiers_controller.php
				* app/vendors/shells/anomalies.php
				* app/vendors/shells/traitementcsvinfope.php
				* app/vendors/shells/anomaliesr.php
				* app/vendors/shells/integrationfluxpe.php
		4°) Même question que pour le point 2, mais pour tempcessations, tempinscriptions, tempradiations
			grep -lR "\(tempcessations\|Tempcessation\|tempinscriptions\|Tempinscription\|tempradiations\|Tempradiation\)" app | grep -v "\/\(fixtures\|sql\|tests\|\.svn\)\/"
				* app/models/tempinscription.php
				* app/models/tempcessation.php
				* app/models/tempradiation.php
				* app/vendors/shells/traitementcsvinfope.php
				* app/vendors/shells/integrationfluxpe.php
				* app/vendors/shells/importcsvinfope.php
		5°) Inscription, radiation, réinscription, ... -> même identifiant PE ? -> comment structurer les tables ?
		6°) Garde-t'on l'identifiant PE à vie ?
		7°) Format de l'identifiant: 6666666S 046 (8 chiffres ou 7 chiffres + 1 lettre, puis identifiant bureau du PE ?)
		8°) Peut-on avoir la liste des codes/libellés (pour en faire une table de paramétrage éventuellement, et mettre à jour les codes pour les entrées que l'on a déjà) ?
	*/

	/*
	-- Vrais doublons dans la table tempcessations (CG 66, 20101217_dump_webrsaCG66_rc9.sql.gz -> 0)
	SELECT t.* FROM (
		SELECT
				nir,
				identifiantpe,
				nom,
				prenom,
				dtnai,
				datecessation,
				motifcessation,
				COUNT(id) AS rows
			FROM tempcessations
			GROUP BY identifiantpe, nir, nom, prenom, dtnai, datecessation, motifcessation
			ORDER BY COUNT(identifiantpe) DESC
		) AS t
		WHERE t.rows > 1
	*/

	/*
	-- Vrais doublons dans la table infospoleemploi (CG 66, 20101217_dump_webrsaCG66_rc9.sql.gz -> 3713 personnes)
	SELECT i.* FROM (
		SELECT
				personne_id,
				identifiantpe,
				dateinscription,
				categoriepe,
				datecessation,
				motifcessation,
				dateradiation,
				motifradiation,
				COUNT(id) AS rows
			FROM infospoleemploi
			GROUP BY personne_id, identifiantpe, dateinscription, categoriepe, datecessation, motifcessation, dateradiation, motifradiation
			ORDER BY COUNT(personne_id) DESC
		) AS i
		WHERE i.rows > 1
	*/

	/*
	-- Infos Pôle Emploi pour des personnes non demandeurs ou non conjoints RSA (doublons en base):
	--	* dans le même dossier (cf. personne_id 39460  - CG 66, 20101217_dump_webrsaCG66_rc9.sql.gz)
	--	* dans des dossiers différents (cf. personne_id 35670 - CG 66, 20101217_dump_webrsaCG66_rc9.sql.gz)
	SELECT
			infospoleemploi.personne_id,
			infospoleemploi.identifiantpe,
			infospoleemploi.dateinscription,
			infospoleemploi.categoriepe,
			infospoleemploi.datecessation,
			infospoleemploi.motifcessation,
			infospoleemploi.dateradiation,
			infospoleemploi.motifradiation,
			COUNT(infospoleemploi.id) AS rows
		FROM infospoleemploi
		WHERE infospoleemploi.personne_id NOT IN (
			SELECT
					prestations.personne_id
				FROM prestations
				WHERE prestations.personne_id = infospoleemploi.personne_id
					AND prestations.natprest = 'RSA'
					AND prestations.rolepers IN ( 'DEM', 'CJT' )
		)
		GROUP BY infospoleemploi.personne_id, infospoleemploi.identifiantpe, infospoleemploi.dateinscription, infospoleemploi.categoriepe, infospoleemploi.datecessation, infospoleemploi.motifcessation, infospoleemploi.dateradiation, infospoleemploi.motifradiation
		ORDER BY COUNT(infospoleemploi.personne_id) DESC
	*/

	/*
	SELECT personnes.foyer_id, personnes.id, prestations.rolepers, ipe.*
		FROM(
			SELECT
					nir,
					nom,
					prenom,
					dtnai
				FROM tempcessations
			UNION
			SELECT
					nir,
					nom,
					prenom,
					dtnai
				FROM tempradiations
			UNION
			SELECT
					nir,
					nom,
					prenom,
					dtnai
				FROM tempinscriptions
			) AS ipe
			INNER JOIN personnes ON (
	--			( ipe.nir || '%' ) = personnes.nir
	--			OR (
					ipe.nom = personnes.nom
					AND ipe.prenom = personnes.prenom
					AND ipe.dtnai = personnes.dtnai
	--			)
			)
			INNER JOIN prestations ON (
				personnes.id = prestations.personne_id
				AND prestations.natprest = 'RSA'
			)
		GROUP BY ipe.nir, ipe.nom, ipe.dtnai, ipe.prenom, personnes.id, personnes.foyer_id, prestations.rolepers
		ORDER BY personnes.foyer_id, personnes.id
	*/

	/*
	-- Dans les informations PE, a-t'on des NIR / identifiants PE différents pour une personne donnée ?
	-- (CG 66, 20101217_dump_webrsaCG66_rc9.sql.gz -> 70 personnes)
	SELECT *
		FROM (
			SELECT ipe.nom, ipe.prenom, ipe.dtnai, COUNT(DISTINCT(ipe.nir)) AS nbnirs, COUNT(DISTINCT(ipe.identifiantpe)) AS nbidentifiants
				FROM(
					SELECT
							nir,
							identifiantpe,
							nom,
							prenom,
							dtnai
						FROM tempcessations
					UNION
					SELECT
							nir,
							identifiantpe,
							nom,
							prenom,
							dtnai
						FROM tempradiations
					UNION
					SELECT
							nir,
							identifiantpe,
							nom,
							prenom,
							dtnai
						FROM tempinscriptions
					) AS ipe
				GROUP BY ipe.nom, ipe.prenom, ipe.dtnai
		) AS liste
		WHERE liste.nbnirs > 1 OR liste.nbidentifiants > 1
		ORDER BY nbnirs DESC, nbidentifiants DESC
	*/

	/*
	-- Exemples concernant les informations PE avec des NIR / identifiants PE différents pour une personne donnée.
	-- (CG 66, 20101217_dump_webrsaCG66_rc9.sql.gz)
	SELECT *
		FROM(
			SELECT
					nir,
					identifiantpe,
					nom,
					prenom,
					dtnai
				FROM tempcessations
			UNION
			SELECT
					nir,
					identifiantpe,
					nom,
					prenom,
					dtnai
				FROM tempradiations
			UNION
			SELECT
					nir,
					identifiantpe,
					nom,
					prenom,
					dtnai
				FROM tempinscriptions
			) AS ipe
		WHERE ipe.nom = ( SELECT nom FROM personnes WHERE personnes.id = 34057 )
			OR ipe.nom = ( SELECT nom FROM personnes WHERE personnes.id = 87505 )
		ORDER BY ipe.nom, ipe.prenom
	*/

	/*
	-- A quoi ressemble le parcours PE pour les personnes ayant de multiples entrées
	-- (inscription, cessation, radiation)
	SELECT
			ipe.nir,
			ipe.identifiantpe,
			ipe.nom,
			ipe.prenom,
			ipe.dtnai,
			tmpipe2.nb,
			ipe.date,
			ipe.action
		FROM(
			SELECT
					nir,
					identifiantpe,
					nom,
					prenom,
					dtnai,
					'cessation' AS action,
					datecessation AS date
				FROM tempcessations
			UNION
			SELECT
					nir,
					identifiantpe,
					nom,
					prenom,
					dtnai,
					'radiation' AS action,
					dateradiation AS date
				FROM tempradiations
			UNION
			SELECT
					nir,
					identifiantpe,
					nom,
					prenom,
					dtnai,
					'inscription' AS action,
					dateinscription AS date
				FROM tempinscriptions
			) AS ipe
		INNER JOIN (
			SELECT tmpipe.nom, tmpipe.prenom, tmpipe.dtnai, COUNT(tmpipe.id) AS nb
				FROM(
					SELECT
							id,
							nom,
							prenom,
							dtnai
						FROM tempcessations
					UNION
					SELECT
							id,
							nom,
							prenom,
							dtnai
						FROM tempradiations
					UNION
					SELECT
							id,
							nom,
							prenom,
							dtnai
						FROM tempinscriptions
					) AS tmpipe
				GROUP BY tmpipe.nom, tmpipe.prenom, tmpipe.dtnai
				ORDER BY COUNT(tmpipe.id) DESC
		) AS tmpipe2
		ON (
			tmpipe2.nom = ipe.nom
			AND tmpipe2.prenom = ipe.prenom
			AND tmpipe2.dtnai = ipe.dtnai
			AND tmpipe2.nb > 1
		)
		ORDER BY tmpipe2.nb DESC, ipe.nom, ipe.prenom, ipe.date ASC
	*/
?>