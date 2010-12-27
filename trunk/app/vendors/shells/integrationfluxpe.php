<?php
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
			'Tempcessation' => array(
				'nir',
				'identifiantpe',
				'nom',
				'prenom',
				'dtnai',
				'datecessation',
				'codecessation',
				'motifcessation',
				'nir2'
			),
			'Tempinscription' => array(
				'NIR',
				'Identifiant Pôle Emploi',
				'Nom',
				'Prénom',
				'Date de naissance',
				'Date de l\'inscription',
				'Catégorie de l\'inscription',
				'NIR2',
			),
			'Tempradiation' => array(
				'NIR',
				'Identifiant Pôle Emploi',
				'Nom',
				'Prénom',
				'Date de naissance',
				'Date de radiation',
				'Code',
				'Motif de la radiation',
				'NIR2',
			)
		);

		public $typesCsv = array( 'cessations', 'inscriptions', 'radiations' );

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
			else {
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
			$this->hr();
		}

		/**
		* Import des cessations
		*/

		public function cessations() {
			$modelClass = 'Tempcessation';
			$lines = explode( "\n", $this->csv->read() );
			$this->{$modelClass} = ClassRegistry::init( $modelClass );

			$this->{$modelClass}->begin();

			foreach( $lines as $numLine => $line ) {
				if( !( $numLine == 0 && $this->headers ) ) {
					$numLine++; // La numérotation des lignes commence à 1

					$parts = explode( $this->separator, $line );
					// Reformattage du NIR
					$parts[0] = str_replace( ' ', '', $parts[0] );
					// Reformattage de l'identifiant Pôle Emploi
					$parts[1] = str_replace( ' ', '', $parts[1] );

					// Le nombre de colonnes de cette ligne ne correspond pas au nombre de colonnes attendu
					if( count( $parts ) != count( $this->map[$modelClass] ) ) {
						$nParts = count($parts);
						$nPartsType = count( $this->map[$modelClass] );
						$this->err( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} (Le nombre de colonnes de cette ligne ({$nParts}) ne correspond pas au nombre de colonnes attendu ({$nPartsType}))." );
					}
					// Colonnes NIR et NIR2 différentes ?
					else if( $parts[0] != $parts[count($parts)-1] ) {
						$this->err( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} (les deux NIR sont différents: \"{$parts[0]}\" et \"{$parts[count($parts)-1]}\")." );
					}
					// Le NIR n'est pas sur 13 caractères
					else if( strlen( $parts[0] ) != 13 ) {
						$this->err( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} (le NIR \"{$parts[0]}\" ne comporte pas 13 caractères)." );
					}
					// La date de naissance n'est pas formattée corretement -- TODO
					else if( false ) {
// 						$this->err( "Non traitement de la ligne {$numLine} du fichier {$this->csv->path} (le NIR \"{$parts[0]}\" ne comporte pas 13 caractères)." );
					}
					// La ligne a l'air correcte, essai de traitement
					else {
						$record = array( $modelClass => array( ) );
						foreach( $this->map[$modelClass] as $key => $column ) {
							// Formattage de la date du format JJ/MM/AAAA au format SQL AAAA-MM-JJ
							// Concerne les champs dtnai et datecessation, -- FIXME
							if( in_array( $column, array( 'dtnai', 'datecessation' ) ) ) {
								$dateParts = explode( '/', $parts[$key] );
								$parts[$key] = "{$dateParts[2]}-{$dateParts[1]}-{$dateParts[0]}";
							}
							$record[$modelClass][$column] = $parts[$key];
						}

						$this->Personne = ClassRegistry::init( 'Personne' );
						$nPersonnes = $this->Personne->find(
							'count',
							array(
								'conditions' => array(
									'OR' => array(
										// Les NIR que l'on a dans la table personnes sont sur 15 caractères (avec la clé)
										'Personne.nir' => $record[$modelClass]['nir'].cle_nir( $record[$modelClass]['nir'] ),
										// FIXME: si one trouve pas le NIR, on cherche quand même nom/prenom/dtnai ?
										array(
											'Personne.nom' => $record[$modelClass]['nom'],
											'Personne.prenom' => $record[$modelClass]['prenom'],
											'Personne.dtnai' => $record[$modelClass]['dtnai']
										)
									)
								)
							)
						);
						debug( $record );
						debug( $nPersonnes );
					}
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

/*
Schéma possible:
	- informationspe
		id
		nir
		identifiantpe
		nom
		prenom
		dtnai
	- cessationspe
		id
		informationpe_id
		dtcessation
		codecessation
		motifcessation
	- inscriptionspe
		id
		informationpe_id
		dtinscription
		categorie
	- radiationspe
		id
		informationpe_id
		dtradiation
		coderadiation
		motifradiation
	- informationspe_personnes
		id
		informationpe_id
		personne_id
*/


			$this->{$modelClass}->rollback();
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

			/*$this->out("Usage: cake/console/cake postgresql <commande> <paramètres>");
			$this->hr();
			$this->out();
			$this->out('Commandes:');
			$this->out("\n\t{$this->shell} all\n\t\tEffectue toutes les opérations de maintenance ( ".implode( ', ', $this->operations )." ).");
			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");
			$this->out("\n\t{$this->shell} reindex\n\t\t{$this->commandDescriptions['reindex']}");
			$this->out("\n\t{$this->shell} sequences\n\t\t{$this->commandDescriptions['sequences']}");
			$this->out("\n\t{$this->shell} vacuum\n\t\t{$this->commandDescriptions['vacuum']}");
			$this->out();
			$this->out('Paramètres:');
			$this->out("\t-connection <connexion>\n\t\tLe nom d'une connexion PostgreSQL défini dans app/config/database.php\n\t\tPar défaut: ".$this->_defaultToString( 'connection' )."\n");
			$this->out("\t-log <booléen>\n\t\tDoit-on journaliser la sortie du programme ?\n\t\tPar défaut: ".$this->_defaultToString( 'log' )."\n");
			$this->out("\t-logpath <répertoire>\n\t\tLe répertoire dans lequel enregistrer les fichiers de journalisation.\n\t\tPar défaut: ".$this->_defaultToString( 'logpath' )."\n");
			$this->out("\t-verbose <booléen>\n\t\tDoit-on afficher les commandes SQL exéctuées ?\n\t\tPar défaut: ".$this->_defaultToString( 'verbose' )."\n");
			$this->out();*/

			$this->_stop( 0 );
		}
    }
?>