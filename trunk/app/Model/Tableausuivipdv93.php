<?php
	/**
	 * Code source de la classe Tableausuivipdv93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe Tableausuivipdv93 ...
	 *
	 * @package app.Model
	 */
	class Tableausuivipdv93 extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = 'Tableausuivipdv93';

		/**
		 * Récursivité par défaut de ce modèle.
		 *
		 * @var integer
		 */
		public $recursive = -1;

		/**
		 * Behaviors utilisés.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Pgsqlcake.PgsqlAutovalidate',
			'Formattable',
		);

		public $belongsTo = array(
			'Pdv' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => null,
				'fields' => null,
				'order' => null
			),
			'Photographe' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
				'conditions' => null,
				'fields' => null,
				'order' => null
			),
		);

		/**
		 * Problématiques à utiliser dans le tableau 1 B3
		 *
		 * @var array
		 */
		public $problematiques = array(
			'sante',
			'logement',
			'familiales',
			'modes_gardes',
			'surendettement',
			'administratives',
			'linguistiques',
			'mobilisation',
			'qualification_professionnelle',
			'acces_emploi',
			'autres',
		);

		/**
		 * Problématiques à utiliser dans le tableau 1 B3
		 *
		 * @var array
		 */
		public $acteurs = array(
			'acteurs_sociaux',
			'acteurs_sante',
			'acteurs_culture',
		);

		/**
		 * Liste des tableaux disponibles
		 *
		 * @var array
		 */
		public $tableaux = array(
			'tableaud1' => 'D 1',
			'tableau1b3' => '1 B 3',
			'tableau1b4' => '1 B 4',
			'tableau1b5' => '1 B 5',
			'tableau1b6' => '1 B 6',
		);

		/**
		 * Liste des tranches d'âges pour le tableau D1
		 *
		 * @var array
		 */
		public $tranches_ages = array(
			'0_14' => 'Participants de moins de 15 ans',
			'15_24' => 'Participants de 15 à 24 ans',
			'25_44' => 'Participants de 25 à 44 ans',
			'45_54' => 'Participants de 45 à 54 ans',
			'55_64' => 'Participants de 55 à 64 ans',
			'65_999' => 'Participants de 65 ans et plus',
		);

		/**
		 * Liste des nationalités.
		 *
		 * @var array
		 */
		public $natpf = array(
			'socle' => 'Bénéficiaires RSA socle',
			'majore' => 'Bénéficiaires RSA majoré',
			'socle_activite' => 'Bénéficiaires  RSA socle+activité',
		);

		/**
		 * Liste des nationalités.
		 *
		 * @var array
		 */
		public $nati = array(
			'F' => 'Française',
			'C' => 'Union Européenne',
			'A' => 'Hors Union Européenne',
		);

		/**
		 * Liste des situations familiales.
		 *
		 * @var array
		 */
		public $sitfam = array(
			'isole_sans_enfant' => 'Isolé(e) sans enfant(s) à charge',
			'isole_avec_enfant' => 'Isolé(e) avec enfant(s) à charge',
			'en_couple_sans_enfant' => 'En couple sans enfant(s) à charge',
			'en_couple_avec_enfant' => 'En couple avec enfant(s) à charge',
		);

		/**
		 * Liste des inscriptions à Pôle Emploi.
		 *
		 * @var array
		 */
		public $inscritpe = array(
			'1' => 'Inscrits',
			'0' => 'Non inscrits',
		);

		/**
		 * Liste des catégories d'ancienneté du dispositif pour le tableau D1
		 *
		 * @var array
		 */
		public $anciennetes_dispositif = array(
			'0_0' => 'Moins de 1 an',
			'1_2' => 'De 1 an à moins de 3 ans',
			'3_5' => 'De 3 ans à moins de 6 ans',
			'3_8' => 'De 6 ans à moins de 9 ans',
			'9_999' => 'Plus de 9 ans',
		);

		/**
		 * Liste des non scolarisés.
		 *
		 * @var array
		 */
		public $non_scolarise = array(
			'1207' => 'Non scolarisé',
		);

		/**
		 * Liste des diplômes étrangers non reconnus en France.
		 *
		 * @var array
		 */
		public $diplomes_etrangers = array(
			'1' => 'Diplômes étrangers non reconnus en France',
		);

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1Sexe( array $search ) {
			$fields = array(
				'"Situationallocataire"."sexe" AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1MarcheTravail( array $search ) {
			$fields = array(
				'"Questionnaired1pdv93"."marche_travail" AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( 'Questionnaired1pdv93.marche_travail', 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1TrancheAge( array $search ) {
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			$cases = array();
			foreach( array_keys( $this->tranches_ages ) as $tranche_age ) {
				list( $min, $max ) = explode( '_', $tranche_age );
				$cases[] = 'WHEN EXTRACT( YEAR FROM AGE(TIMESTAMP\''.$annee.'-12-31\', "Situationallocataire"."dtnai" ) ) BETWEEN '.$min.' AND '.$max.' THEN \''.$tranche_age.'\'';
			}

			$tranche_age = '(
				CASE
					'.implode( "\n", $cases ).'
					ELSE \'NC\'
				END
			)';

			$fields = array(
				$tranche_age.' AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( $tranche_age, 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1Vulnerable( array $search ) {
			$fields = array(
				'"Questionnaired1pdv93"."vulnerable" AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( 'Questionnaired1pdv93.vulnerable', 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1Nivetu( array $search ) {
			$fields = array(
				'"Questionnaired1pdv93"."nivetu" AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( 'Questionnaired1pdv93.nivetu', 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1CategorieSociopro( array $search ) {
			$fields = array(
				'"Questionnaired1pdv93"."categorie_sociopro" AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( 'Questionnaired1pdv93.categorie_sociopro', 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1AutreCaracteristique( array $search ) {
			$fields = array(
				'"Questionnaired1pdv93"."autre_caracteristique" AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( 'Questionnaired1pdv93.autre_caracteristique', 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1Natpf( array $search ) {
			$natpf = '(
				CASE
					WHEN "Situationallocataire"."natpf_socle" = \'1\' AND "Situationallocataire"."natpf_activite" = \'1\' THEN \'socle_activite\'
					WHEN "Situationallocataire"."natpf_socle" = \'1\' THEN \'socle\'
					WHEN "Situationallocataire"."natpf_majore" = \'1\' THEN \'majore\'
					ELSE \'NC\'
				END
			)';

			$fields = array(
				$natpf.' AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( $natpf, 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 * FIXME: Nationalité vide (dans le tableau HTML)
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1Nati( array $search ) {
			$nati = '(
				CASE
					WHEN "Situationallocataire"."nati" IS NOT NULL THEN "Situationallocataire"."nati"
					ELSE \'NC\'
				END
			)';

			$fields = array(
				$nati.' AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( 'Situationallocataire.nati', 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1Sitfam( array $search ) {
			$parts = array(
				'isole' => '"Situationallocataire"."sitfam" IN (\'CEL\', \'DIV\', \'ISO\', \'SEF\', \'SEL\', \'VEU\')',
				'en_couple' => '"Situationallocataire"."sitfam" IN (\'MAR\', \'PAC\', \'RPA\', \'RVC\', \'RVM\', \'VIM\')',
				'sans_enfant' => '"Situationallocataire"."nbenfants" = 0',
				'avec_enfant' => '"Situationallocataire"."nbenfants" > 0',
			);

			$sitfam = '(
				CASE
					WHEN '.$parts['isole'].' AND '.$parts['sans_enfant'].' THEN \'isole_sans_enfant\'
					WHEN '.$parts['isole'].' AND '.$parts['avec_enfant'].' THEN \'isole_avec_enfant\'
					WHEN '.$parts['en_couple'].' AND '.$parts['sans_enfant'].' THEN \'en_couple_sans_enfant\'
					WHEN '.$parts['en_couple'].' AND '.$parts['avec_enfant'].' THEN \'en_couple_avec_enfant\'
					ELSE \'NC\'
				END
			)';

			$fields = array(
				$sitfam.' AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( $sitfam, 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1ConditionsLogement( array $search ) {
			$fields = array(
				'"Questionnaired1pdv93"."conditions_logement" AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( 'Questionnaired1pdv93.conditions_logement', 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1Inscritpe( array $search ) {
			$fields = array(
				'"Questionnaired1pdv93"."inscritpe" AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( 'Questionnaired1pdv93.inscritpe', 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 * FIXME: est-ce bien sur dtdemrsa ?
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1AncienneteDispositif( array $search ) {
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			$cases = array();
			foreach( array_keys( $this->anciennetes_dispositif ) as $anciennete_dispositif ) {
				list( $min, $max ) = explode( '_', $anciennete_dispositif );
				$cases[] = 'WHEN EXTRACT( YEAR FROM AGE(TIMESTAMP\''.$annee.'-12-31\', "Situationallocataire"."dtdemrsa" ) ) BETWEEN '.$min.' AND '.$max.' THEN \''.$anciennete_dispositif.'\'';
			}

			$anciennete_dispositif = '(
				CASE
					'.implode( "\n", $cases ).'
					ELSE \'NC\'
				END
			)';

			$fields = array(
				$anciennete_dispositif.' AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( $anciennete_dispositif, 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1NonScolarise( array $search ) {
			$fields = array(
				'"Questionnaired1pdv93"."nivetu" AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( 'Questionnaired1pdv93.nivetu', 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud1DiplomesEtrangers( array $search ) {
			$fields = array(
				'"Questionnaired1pdv93"."diplomes_etrangers" AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."personne_id" ) ) AS "count"'
			);

			$group = array( 'Questionnaired1pdv93.diplomes_etrangers', 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 *
		 * @return array
		 */
		public function tableaud1Categories() {
			$Questionnaired1pdv93 = ClassRegistry::init( 'Questionnaired1pdv93' );

			$enums = Hash::merge(
				$Questionnaired1pdv93->enums(),
				$Questionnaired1pdv93->Situationallocataire->enums()
			);

			unset( $enums['Questionnaired1pdv93']['nivetu']['1207'] ); // Les non scolarisés ont une catégorie à part

			$categories = array(
				'sexe' => array(
					1 => 'Hommes',
					2 => 'Femmes',
				),
				'marche_travail' => $enums['Questionnaired1pdv93']['marche_travail'],
				'tranche_age' => $this->tranches_ages,
				'vulnerable' => $enums['Questionnaired1pdv93']['vulnerable'],
				'nivetu' => $enums['Questionnaired1pdv93']['nivetu'],
				'categorie_sociopro' => $enums['Questionnaired1pdv93']['categorie_sociopro'],
				'autre_caracteristique' => $enums['Questionnaired1pdv93']['autre_caracteristique'],
				'natpf' => $this->natpf,
				'nati' => $this->nati + array( 'NC' => 'Non renseigné' ),
				'sitfam' => $this->sitfam,
				'conditions_logement' =>  $enums['Questionnaired1pdv93']['conditions_logement'],
				'inscritpe' => $this->inscritpe,
				'anciennete_dispositif' => $this->anciennetes_dispositif,
				'non_scolarise' => $this->non_scolarise,
				'diplomes_etrangers' => $this->diplomes_etrangers,
			);

			return $categories;
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		public function tableaud1( array $search ) {
			$Questionnaired1pdv93 = ClassRegistry::init( 'Questionnaired1pdv93' );

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "Rendezvous.structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// Soumis à droits et devoirs / au moins soumis une fois durant l'année
			$conditiondd = 'Situationallocataire.toppersdrodevorsa = \'1\'';
			$dd_annee = Hash::get( $search, 'Search.soumis_dd_dans_annee' );
			if( $dd_annee ) {
				$sq = $Questionnaired1pdv93->Personne->Historiquedroit->sq(
					array(
						'alias' => 'historiquesdroits',
						'fields' => array( 'historiquesdroits.personne_id' ),
						'contain' => false,
						'conditions' => array(
							'historiquesdroits.personne_id = Questionnaired1pdv93.personne_id',
							'historiquesdroits.toppersdrodevorsa' => 1,
							"( historiquesdroits.created, historiquesdroits.modified ) OVERLAPS ( DATE '{$annee}-01-01', DATE '{$annee}-12-31' )"
							/*'OR' => array(
								'EXTRACT( \'YEAR\' FROM historiquesdroits.created )' => $annee, // FIXME
								'EXTRACT( \'YEAR\' FROM historiquesdroits.modified )' => $annee
							)*/
						)
					)
				);
				$conditiondd = array(
					'OR' => array(
						$conditiondd,
						"Questionnaired1pdv93.personne_id IN ( {$sq} )"
					)
				);
			}

			$results = array();
			$categories = array_keys( $this->tableaud1Categories() );

			foreach( $categories as $categorie ) {
				$method = '_tableaud1'.Inflector::camelize( $categorie );

				list( $fields, $group ) = $this->{$method}( $search );

				$querydata = array(
					'fields' => $fields,
					'conditions' => array(
						'EXTRACT( \'YEAR\' FROM Questionnaired1pdv93.date_validation )' => $annee,
						$conditionpdv,
						$conditiondd
					),
					'contain' => false,
					'joins' => array(
						$Questionnaired1pdv93->join( 'Rendezvous', array( 'type' => 'INNER' ) ),
						$Questionnaired1pdv93->join( 'Situationallocataire', array( 'type' => 'INNER' ) )
					),
					'group' => $group
				);

				$lines = $Questionnaired1pdv93->find( 'all', $querydata );
				if( !empty( $lines ) ) {
					foreach( $lines as $line ) {
						$results[$categorie][$line[0]['categorie']]['entrees'][$line[0]['sexe']] = $line[0]['count'];
					}
				}
				else {
					$results[$categorie]['N/C']['entrees']['homme'] = 0;
					$results[$categorie]['N/C']['entrees']['femmes'] = 0;
				}
			}

			return $results;
		}

		/**
		 * TODO: documentation
		 *
		 * @param string $field
		 * @return string
		 */
		protected function _conditionStatutRdv( $field = 'statutrdv_id' ) {
			$values = "'".implode( "', '", (array)Configure::read( 'Tableausuivipdv93.statutrdv_id' ) )."'";
			return "{$field} IN ( {$values} )";
		}

		/**
		 * TODO: documentation
		 *
		 * @param string $field
		 * @return string
		 */
		protected function _conditionNumcodefamille( $field = 'numcodefamille', $typeacteur = null ) {
			$configureKey = 'Tableausuivipdv93.numcodefamille';
			if( !is_null( $typeacteur ) ) {
				$configureKey = "{$configureKey}.{$typeacteur}";
			}

			$values = "'".implode( "', '", Hash::flatten( (array)Configure::read( $configureKey ) ) )."'";
			return "numcodefamille IN ( {$values} )";
		}

		/**
		 * Retourne une condition permettant de limiter les résultats du niveau
		 * CG aux seuls PDV définis dans la configuration.
		 *
		 * @see Tableausuivipdv93::listePdvs()
		 *
		 * @param string $field
		 * @return string
		 */
		protected function _conditionStructurereferenteIsPdv( $field = 'structurereferente_id' ) {
			$ids = array_keys( (array)$this->listePdvs() );

			if( !empty( $ids ) ) {
				return $field.' IN ( '.implode( ',', $ids ).' )';
			}

			return '1 = 0';
		}

		/**
		 * Volet I problématiques 1-B-3: problématiques des bénéficiaires de
		 * l'opération.
		 *
		 * FIXME: RDV individuel
		 *
		 * @param array $search
		 * @return array
		 */
		public function tableau1b3( array $search ) {
			$Dsp = ClassRegistry::init( 'Dsp' );

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "AND structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// Filtre sur les DSP mises à jour dans l'année
			$conditionmaj = null;
			$dsp_maj = Hash::get( $search, 'Search.dsps_maj_dans_annee' );
			if( !empty( $dsp_maj ) ) {
				$conditionmaj = "AND dsps_revs.id IS NOT NULL AND EXTRACT( 'YEAR' FROM dsps_revs.modified ) = '{$annee}'";
			}

			$sql = "SELECT CASE
				-- dsps : si pas de DSP CG, on prend la DSP CAF
				WHEN dsps_revs.id IS NULL AND detailsdifsocs.difsoc IN ('0402','0403')	THEN 'sante'
				WHEN dsps_revs.id IS NULL AND detailsdiflogs.diflog IN ('1004', '1005', '1006', '1007', '1008', '1009') THEN 'logement'
				WHEN dsps_revs.id IS NULL AND detailsaccosocfams.nataccosocfam = '0412' THEN 'familiales'
				WHEN dsps_revs.id IS NULL AND detailsdifdisps.difdisp IN ('0502', '0503', '0504')  THEN 'modes_gardes'
				WHEN dsps_revs.id IS NULL AND detailsdifsocs.difsoc = '0406'		THEN 'surendettement'
				WHEN dsps_revs.id IS NULL AND detailsdifsocs.difsoc = '0405'		THEN 'administratives'
				WHEN dsps_revs.id IS NULL AND detailsdifsocs.difsoc = '0404'		THEN 'linguistiques'
				WHEN dsps_revs.id IS NULL AND dsps.nivetu IN ('1206','1207')		THEN 'qualification_professionnelle'
				WHEN dsps_revs.id IS NULL AND dsps.topengdemarechemploi ='0'		THEN 'acces_emploi'
				WHEN dsps_revs.id IS NULL AND detailsaccosocindis.nataccosocindi = '0420' THEN 'autres'
				--dsps_revs : si DSP CG, on la prend
				WHEN dsps_revs.id IS NOT NULL AND detailsdifsocs_revs.difsoc IN ('0402','0403')	THEN 'sante'
				WHEN dsps_revs.id IS NOT NULL AND detailsdiflogs_revs.diflog IN ('1004', '1005', '1006', '1007', '1008', '1009')	THEN 'logement'
				WHEN dsps_revs.id IS NOT NULL AND detailsaccosocfams_revs.nataccosocfam = '0412' THEN 'familiales'
				WHEN dsps_revs.id IS NOT NULL AND detailsdifdisps_revs.difdisp IN ('0502', '0503', '0504')  THEN 'modes_gardes'
				WHEN dsps_revs.id IS NOT NULL AND detailsdifsocs_revs.difsoc = '0406'		THEN 'surendettement'
				WHEN dsps_revs.id IS NOT NULL AND detailsdifsocs_revs.difsoc = '0405'		THEN 'administratives'
				WHEN dsps_revs.id IS NOT NULL AND detailsdifsocs_revs.difsoc = '0404'		THEN 'linguistiques'
				WHEN dsps_revs.id IS NOT NULL AND dsps_revs.nivetu IN ('1206','1207')		THEN 'qualification_professionnelle'
				WHEN dsps_revs.id IS NOT NULL AND dsps_revs.topengdemarechemploi ='0'		THEN 'acces_emploi'
				WHEN dsps_revs.id IS NOT NULL AND detailsaccosocindis_revs.nataccosocindi = '0420' THEN 'autres'
				END AS \"difficultes_exprimees\",
				COUNT(*)
			FROM dsps
				INNER JOIN rendezvous ON (dsps.personne_id = rendezvous.personne_id)
				LEFT OUTER JOIN detailsdifsocs ON (dsps.id = detailsdifsocs.dsp_id)
				LEFT OUTER JOIN detailsdiflogs ON (dsps.id = detailsdiflogs.dsp_id)
				LEFT OUTER JOIN detailsaccosocfams ON (dsps.id = detailsaccosocfams.dsp_id)
				LEFT OUTER JOIN detailsdifdisps ON (dsps.id = detailsdifdisps.dsp_id)
				LEFT OUTER JOIN detailsnatmobs ON (dsps.id = detailsnatmobs.dsp_id)
				LEFT OUTER JOIN detailsaccosocindis ON (dsps.id = detailsaccosocindis.dsp_id)
				LEFT OUTER JOIN dsps_revs ON (dsps.personne_id = dsps_revs.personne_id
					AND (dsps_revs.personne_id, dsps_revs.id) IN (
						SELECT personne_id, MAX(dsps_revs.id) FROM dsps_revs GROUP BY personne_id))
				LEFT OUTER JOIN detailsdifsocs_revs ON (dsps_revs.id = detailsdifsocs_revs.dsp_rev_id)
				LEFT OUTER JOIN detailsdiflogs_revs ON (dsps_revs.id = detailsdiflogs_revs.dsp_rev_id)
				LEFT OUTER JOIN detailsaccosocfams_revs ON (dsps_revs.id = detailsaccosocfams_revs.dsp_rev_id)
				LEFT OUTER JOIN detailsdifdisps_revs ON (dsps.id = detailsdifdisps_revs.dsp_rev_id)
				LEFT OUTER JOIN detailsnatmobs_revs ON (dsps_revs.id = detailsnatmobs_revs.dsp_rev_id)
				LEFT OUTER JOIN detailsaccosocindis_revs ON (dsps_revs.id = detailsaccosocindis_revs.dsp_rev_id)
			WHERE
				-- Dont le type de RDV est individuel
				rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
				-- avec un RDV honoré durant l'année N
				AND EXTRACT('YEAR' FROM daterdv) = '{$annee}'
				AND ".$this->_conditionStatutRdv()."
				-- pour la structure referente X (éventuellement)
				{$conditionpdv}
				{$conditionmaj}
				-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
				AND ".$this->_conditionStructurereferenteIsPdv()."
			GROUP BY \"difficultes_exprimees\";";

			$results = $Dsp->query( $sql );
			$results = Hash::combine( $results, '{n}.0.difficultes_exprimees', '{n}.0.count' );

			unset( $results[''] );
			$results['total'] = array_sum( array_values( $results ) );

			return $results;
		}

		/**
		 *
		 *
		 * @param array $search
		 * @param string $operand
		 * @return string
		 */
		protected function _conditionRendezvousPdv( array $search, $operand ) {
			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "AND structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// S'assure-ton qu'il existe au moins un RDV individuel ?
			$rdv_structurereferente = Hash::get( $search, 'Search.rdv_structurereferente' );
			if( $rdv_structurereferente ) {
				return "{$operand} actionscandidats_personnes.personne_id IN (
					SELECT DISTINCT personne_id FROM rendezvous
					WHERE
						-- avec un RDV honoré durant l'année N
						EXTRACT('YEAR' FROM daterdv) = '{$annee}'
						-- Dont le type de RDV est individuel
						rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
						AND ".$this->_conditionStatutRdv()."
						-- dont la SR du référent de la fiche est la SR du RDV
						AND referents.structurereferente_id = structurereferente_id
						{$conditionpdv}
				)";
			}

			return null;
		}

		/**
		 * Tableau 1-B-4: prescriptions vers les acteurs sociaux,
		 * culturels et de sante
		 *
		 * FIXME: RDV individuel
		 *
		 * @param array $search
		 * @return array
		 */
		public function tableau1b4( array $search ) {
			$ActioncandidatPersonne = ClassRegistry::init( 'ActioncandidatPersonne' );

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "AND structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// FIXME: le PDV du RDV doit être le même que la SR du référent de la fiche de candidature ?
			// FIXME: on ne se préoccupe d'aucune date de la fiche de prescription (se baser sur datesignature ?)

			$sql = "
				(
					SELECT
						CASE
							WHEN ".$this->_conditionNumcodefamille( 'numcodefamille', 'acteurs_sociaux' )." THEN 'acteurs_sociaux'
							WHEN ".$this->_conditionNumcodefamille( 'numcodefamille', 'acteurs_sante' )." THEN 'acteurs_sante'
							WHEN ".$this->_conditionNumcodefamille( 'numcodefamille', 'acteurs_culture' )." THEN 'acteurs_culture'
						END AS libelle,
						COUNT(*) AS \"nombre\",
						COUNT(DISTINCT personne_id) AS \"nombre_unique\"
					FROM actionscandidats_personnes
						INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
						INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
					WHERE
						".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
						-- dont la date de signature est dans l'année N
						AND EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
						-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
						AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
						".$this->_conditionRendezvousPdv( $search, 'AND' )."
					GROUP BY libelle
				)
				UNION
				(
					SELECT
							'total' AS libelle,
							COUNT(*) AS \"nombre\",
							COUNT(DISTINCT personne_id) AS \"nombre_unique\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
							INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
						WHERE
							".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
							-- dont la date de signature est dans l'année N
							AND EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
							-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
							AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
							".$this->_conditionRendezvousPdv( $search, 'AND' )."
				);";

			$results = array();
			$tmp_results = $ActioncandidatPersonne->query( $sql );
			if( !empty( $tmp_results ) ) {
				foreach( $tmp_results as $tmp_result ) {
					$tmp_result = $tmp_result[0];

					$results[$tmp_result['libelle']] = array(
						'nombre' => $tmp_result['nombre'],
						'nombre_unique' => $tmp_result['nombre_unique']
					);
				}
			}

			return $results;
		}

		/**
		 * Tableau 1-B-5: prescription sur les actions à caractère socio-professionnel
		 * et professionnel.
		 *
		 * FIXME: RDV individuel
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableau1b5totaux( array $search ) {
			$ActioncandidatPersonne = ClassRegistry::init( 'ActioncandidatPersonne' );
			$results = array();

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "AND structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// Requête 0: total
			$sql = "
				SELECT
					(
						SELECT COUNT(DISTINCT personne_id)
							FROM actionscandidats_personnes
								INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
								INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
							WHERE
								-- dont la date de signature est dans l'année N
								EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
								-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
								AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
								".$this->_conditionRendezvousPdv( $search, 'AND' )."

					) AS \"Tableau1b5__distinct_personnes_prescription\",
					(
						SELECT COUNT(DISTINCT personne_id)
							FROM actionscandidats_personnes
								INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
								INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
							WHERE
								-- dont la date de signature est dans l'année N
								EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
								-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
								AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
								AND bilanvenu = 'VEN'
								".$this->_conditionRendezvousPdv( $search, 'AND' )."

					) AS \"Tableau1b5__distinct_personnes_action\";";
			$results = Hash::merge( $results, $ActioncandidatPersonne->query( $sql ) );


			// Requête 5: Motifs pour lesquels la prescription n'est pas effective
			$sql = "SELECT
						--nbre de bénéficiaires qui ne se sont pas déplacés : retenu + non venu
						(
							SELECT COUNT(*)
								FROM actionscandidats_personnes
									INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
									INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
								WHERE
									EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
									-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
									AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
									".$this->_conditionRendezvousPdv( $search, 'AND' )."
									AND bilanretenu = 'RET'
									AND bilanvenu != 'VEN'
						) AS \"Tableau1b5__beneficiaires_pas_deplaces\",
						--nbre de fiches de prescription en attente d'un retour : venu + dfaction IS NULL
						(
							SELECT COUNT(*)
								FROM actionscandidats_personnes
									INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
									INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
								WHERE
									EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
									".$this->_conditionRendezvousPdv( $search, 'AND' )."
									AND bilanvenu = 'VEN'
									AND actionscandidats_personnes.dfaction IS NULL
						) AS \"Tableau1b5__nombre_fiches_attente\";";
			$results = Hash::merge( $results, $ActioncandidatPersonne->query( $sql ) );

			return $results[0];
		}

		/**
		 * TOD: nom de la fonction
		 *
		 * @param array $results
		 * @param string $sql
		 * @param array $map
		 * @param string $nameKey
		 * @param string $valueKey
		 * @return array
		 */
		protected function _foo( array $results, $sql, $map, $nameKey, $valueKey ) {
			$Actioncandidat = ClassRegistry::init( array( 'class' => 'Actioncandidat', 'alias' => 'Tableau1b5' ) );
			list( $modelName, $fieldName ) = model_field( $valueKey );

			$tmpresults = $Actioncandidat->query( $sql );
			$keysDiff = array_diff( array_keys( $map ), Hash::extract( $tmpresults, "{n}.{$nameKey}" ) );

			if( !empty( $tmpresults ) ) {
				foreach( $tmpresults as $tmpresult ) {
					$name = Hash::get( $tmpresult, $nameKey );
					$value = Hash::get( $tmpresult, $valueKey );
					$index = Hash::get( $map, $name );

					$results = Hash::insert( $results, "{$index}.Tableau1b5.{$fieldName}", $value ); // FIXME: nom du modèle
				}
			}

			if( !empty( $keysDiff ) ) {
				foreach( $keysDiff as $name ) {
					$index = Hash::get( $map, $name );

					$results = Hash::insert( $results, "{$index}.Tableau1b5.{$fieldName}", 0 ); // FIXME: nom du modèle
				}
			}

			return $results;
		}

		/**
		 * Tableau 1-B-5: prescription sur les actions à caractère socio-professionnel
		 * et professionnel.
		 *
		 * FIXME: on ne prend pas en compte la date de la prescription ?
		 * FIXME: RDV individuel
		 *
		 * @param array $search
		 * @return array
		 */
		public function tableau1b5( array $search ) {
			$Actioncandidat = ClassRegistry::init( array( 'class' => 'Actioncandidat', 'alias' => 'Tableau1b5' ) );

			// On obtient la liste des actions
			$results = $Actioncandidat->find(
				'all',
				array(
					'fields' => array(
						'Tableau1b5.id',
						'Tableau1b5.name',
					),
					'contain' => false,
					'order' => array( 'Tableau1b5.name ASC' )
				)
			);
			$map = array_flip( Hash::extract( $results, '{n}.Tableau1b5.name' ) );

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "AND structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// Requête 1: Nb de prescriptions effectuées : total des prescriptions
			$sql = "
					SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescription_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
							INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
						WHERE
							'1' = '1'
							-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
							AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
							".$this->_conditionRendezvousPdv( $search, 'AND' )."
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescription_count' );

			// Requête 2: nombre de prescription effectives : venu
			$sql = "SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescriptions_effectives_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
							INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
						WHERE
							bilanvenu = 'VEN'
							-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
							AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
							".$this->_conditionRendezvousPdv( $search, 'AND' )."
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_effectives_count' );

			// Requête 3: Raisons de la non participation,
			// Requête 3.1: Refus du bénéficiaire
			$sql = "SELECT
						NULL AS \"Tableausuivipdv93__prescription_name\",
						NULL AS \"Tableausuivipdv93__prescriptions_refus_beneficiaire_count\"
					FROM actionscandidats_personnes
					WHERE false;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_refus_beneficiaire_count' );

			// Requête 3.2: Refus de l'organisme : non retenu + non venu
			$sql = "SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescriptions_refus_organisme_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
							INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
						WHERE
							bilanretenu != 'RET'
							AND bilanvenu != 'VEN'
							-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
							AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
							".$this->_conditionRendezvousPdv( $search, 'AND' )."
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_refus_organisme_count' );

			// Requête 3.3: En attente : ddaction > now ?
			$sql = "SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescriptions_en_attente_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
							INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
						WHERE
							actionscandidats_personnes.ddaction > NOW()
							-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
							AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
							".$this->_conditionRendezvousPdv( $search, 'AND' )."
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_en_attente_count' );

			// Requête 3.3: En attente : ddaction > now ?
			$sql = "SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescriptions_retenu_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
							INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
						WHERE
							bilanretenu = 'RET'
							-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
							AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
							".$this->_conditionRendezvousPdv( $search, 'AND' )."
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_retenu_count' );

			// Requête 4 : Abandon en cours d'action
			$sql = "SELECT
						NULL AS \"Tableausuivipdv93__prescription_name\",
						NULL AS \"Tableausuivipdv93__prescriptions_abandon_count\"
					FROM actionscandidats_personnes
					WHERE false;";
			$results = $this->_foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_abandon_count' );

			return array(
				'totaux' => $this->_tableau1b5totaux( $search ),
				'results' => $results
			);
		}

		/**
		 * Tableau 1-B-6: Actions collectives
		 *
		 * @param array $search
		 * @return array
		 */
		public function tableau1b6( array $search ) {
			$Thematiquerdv = ClassRegistry::init( array( 'class' => 'Thematiquerdv', 'alias' => 'Tableau1b6' ) );

			$cases = array();
			foreach( (array)Configure::read( 'Tableausuivipdv93.Tableau1b6.map_thematiques_themes' ) as $thematique_id => $theme ) {
				$cases[] = "WHEN id = {$thematique_id} THEN '{$theme}'";
			}

			$results = $Thematiquerdv->find(
				'all',
				array(
					'fields' => array(
						'Tableau1b6.id',
						'Tableau1b6.name',
						'( CASE WHEN false THEN NULL '.implode( '', $cases ).' ELSE NULL END ) AS "Tableau1b6__theme"'
					),
					'contain' => false,
					'conditions' => array(
						'Tableau1b6.typerdv_id' => (array)Configure::read( 'Tableausuivipdv93.Tableau1b6.typerdv_id' )
					),
					'order' => array( 'Tableau1b6.name ASC' )
				)
			);

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "AND rendezvous.structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// Possède au moins un RDV honoré dans la SR
			$conditionrdv = null;
			if( !empty( $pdv_id ) ) {
				$conditionrdv = "AND rendezvous.personne_id IN (
									SELECT DISTINCT personne_id FROM rendezvous
									-- avec un RDV honoré durant l'année N
								WHERE
									EXTRACT('YEAR' FROM rendezvous.daterdv) = '{$annee}'
									AND ".$this->_conditionStatutRdv( 'rendezvous.statutrdv_id' )."
									-- pour la structure referente X
									{$conditionpdv}
						)";
			}

			// Liste des thématiques collectives
			$thematiquesrdvs_ids = (array)Hash::extract( $results, '{n}.Tableau1b6.id' );
			if( empty( $thematiquesrdvs_ids ) ) {
				$thematiquesrdvs_ids = array( 0 );
			}

			// --1-- Nbre de personnes invitées ou positionnées : honoré ou prévu
			$sql = "SELECT
							thematiquesrdvs.name AS \"Tableau1b6__name\",
							COUNT(DISTINCT rendezvous.personne_id) AS \"Tableau1b6__count_personnes_prevues\"
						FROM rendezvous
							INNER JOIN typesrdv ON ( typesrdv.id = rendezvous.typerdv_id )
							INNER JOIN rendezvous_thematiquesrdvs ON ( rendezvous.id = rendezvous_thematiquesrdvs.rendezvous_id )
							INNER JOIN thematiquesrdvs ON ( thematiquesrdvs.id = rendezvous_thematiquesrdvs.thematiquerdv_id )
						WHERE
							rendezvous_thematiquesrdvs.thematiquerdv_id IN ( ".implode( ',', $thematiquesrdvs_ids )." )
							AND EXTRACT( 'YEAR' FROM rendezvous.daterdv ) = '{$annee}'
							AND rendezvous.statutrdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.Tableau1b6.statutrdv_id_prevu_honore' ) )." )
							{$conditionpdv}
							{$conditionrdv}
						GROUP BY
							thematiquesrdvs.name,
							rendezvous_thematiquesrdvs.thematiquerdv_id";
			$results1 = $Thematiquerdv->query( $sql );

			$sql = "SELECT
							thematiquesrdvs.name AS \"Tableau1b6__name\",
							COUNT(DISTINCT rendezvous.id) AS \"Tableau1b6__count_seances\",
							COUNT(DISTINCT rendezvous.personne_id) AS \"Tableau1b6__count_personnes\"
						FROM rendezvous
							INNER JOIN typesrdv ON ( typesrdv.id = rendezvous.typerdv_id )
							INNER JOIN rendezvous_thematiquesrdvs ON ( rendezvous.id = rendezvous_thematiquesrdvs.rendezvous_id )
							INNER JOIN thematiquesrdvs ON ( thematiquesrdvs.id = rendezvous_thematiquesrdvs.thematiquerdv_id )
						WHERE
							rendezvous_thematiquesrdvs.thematiquerdv_id IN ( ".implode( ',', $thematiquesrdvs_ids )." )
							AND EXTRACT('YEAR' FROM rendezvous.daterdv) = '{$annee}'
							AND ".$this->_conditionStatutRdv( 'rendezvous.statutrdv_id' )."
							{$conditionpdv}
							{$conditionrdv}
						GROUP BY
							thematiquesrdvs.name,
							rendezvous_thematiquesrdvs.thematiquerdv_id";
			$results2 = $Thematiquerdv->query( $sql );

			// Formattage des résultats
			foreach( $results as $key => $result ) {
				$name = $result['Tableau1b6']['name'];
				foreach( $results1 as $result1 ) {
					if( $result1['Tableau1b6']['name'] == $name ) {
						$value = (int)Hash::get( $result1, 'Tableau1b6.count_personnes_prevues' );
						if( !isset( $results[$key]['Tableau1b6']['count_personnes_prevues'] ) ) {
							$results[$key]['Tableau1b6']['count_personnes_prevues'] = 0;
						}
						$results[$key]['Tableau1b6']['count_personnes_prevues'] += $value;
					}
					else {
						if( !isset( $results[$key]['Tableau1b6']['count_personnes_prevues'] ) ) {
							$results[$key]['Tableau1b6']['count_personnes_prevues'] = 0;
						}
					}
				}
				foreach( $results2 as $result2 ) {
					foreach( array( 'count_seances', 'count_personnes' ) as $field ) {
						if( $result2['Tableau1b6']['name'] == $name ) {
							$value = (int)Hash::get( $result2, "Tableau1b6.{$field}" );
							if( !isset( $results[$key]['Tableau1b6'][$field] ) ) {
								$results[$key]['Tableau1b6'][$field] = 0;
							}
							$results[$key]['Tableau1b6'][$field] += $value;
						}
						else {
							if( !isset( $results[$key]['Tableau1b6'][$field] ) ) {
								$results[$key]['Tableau1b6'][$field] = 0;
							}
						}
					}
				}
			}

			return $results;
		}

		/**
		 * Retourne une liste ordonnée et traduite.
		 *
		 * @param string $type
		 * @param string $tableauName
		 * @return array
		 */
		protected function _listes( $type, $tableauName ) {
			$options = array();
			$domain = Inflector::tableize( $this->name );

			foreach( $this->{$type} as $intitule ) {
				$options[$intitule] = __d( $domain, "{$tableauName}.{$intitule}" );
			}

			return $options;
		}

		/**
		 * Retourne la liste des problématiques, ordonnées et traduites.
		 *
		 * @return array
		 */
		public function problematiques() {
			return $this->_listes( 'problematiques', 'Tableau1b3' );
		}

		/**
		 * Retourne la liste des types d'acteurs, ordonnées et traduites.
		 *
		 * @return array
		 */
		public function acteurs() {
			return $this->_listes( 'acteurs', 'Tableau1b4' );
		}

		/**
		 * Historisation de critères de recherches et de leurs résultats.
		 *
		 * @param string $action
		 * @param array $search
		 * @param integer $user_id
		 * @return boolean
		 */
		public function historiser( $action, $search, $user_id = null ) {
			$results = $this->{$action}( $search );

			$tableausuivipdv93 = array(
				'Tableausuivipdv93' => array(
					'name' => $action,
					'annee' => Hash::get( $search, 'Search.annee' ),
					'structurereferente_id' => Hash::get( $search, 'Search.structurereferente_id' ),
					'version' => app_version(),
					'search' => serialize( $search ),
					'results' => serialize( $results ),
					'user_id' => $user_id
				)
			);

			// On sauvegarde au maximum une fois par jour les mêmes requêtes et résultats
			$conditions = Hash::flatten( $tableausuivipdv93 );
			$conditions["DATE_TRUNC( 'day', \"Tableausuivipdv93\".\"modified\" )"] = date( 'Y-m-d' );

			// A-t'on déjà sauvegardé exactement ce résultat ?
			$found = $this->find( 'first', array( 'conditions' => $conditions ) );

			// Si c'est le cas, on se contente de le réenregistrer pour qe la date de modifcation soit mise à jour
			if( !empty( $found ) ) {
				$tableausuivipdv93 = $found;
				unset(
					$tableausuivipdv93['Tableausuivipdv93']['created'],
					$tableausuivipdv93['Tableausuivipdv93']['modified']
				);
			}

			$this->create( $tableausuivipdv93 );
			return $this->save();
		}

		/**
		 * Retourne la liste des PDV pour lesquels les tableaux de PDV doivent
		 * être calculés.
		 *
		 * @see Tableausuivipdv93.conditionsPdv dans le webrsa.inc
		 *
		 * @return array
		 */
		public function listePdvs() {
			return $this->Pdv->find(
				'list',
				array(
					'contain' => false,
					'joins' => array(
						$this->Pdv->join( 'Typeorient', array( 'type' => 'INNER' ) ),
					),
					'conditions' => (array)Configure::read( 'Tableausuivipdv93.conditionsPdv' ),
					'order' => array( 'Pdv.lib_struc' )
				)
			);
		}

		/**
		 * Retourne la liste des photographes des tableaux PDV.
		 *
		 * @return array
		 */
		public function listePhotographes() {
			$sq = $this->sq( array( 'fields' => array( 'DISTINCT(user_id)' ) ) );

			$list = $this->Photographe->find(
				'list',
				array(
					'fields' => array( 'Photographe.id', 'Photographe.nom_complet' ),
					'contain' => false,
					'order' => array( 'Photographe.nom_complet' ),
					'conditions' => array(
						"Photographe.id IN ( {$sq} )"
					)
				)
			);

			$list = Hash::merge( array( 'NULL' => 'Photographie automatique' ), $list );

			return $list;
		}
	}
?>