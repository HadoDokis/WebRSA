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
		 * Associations "Has many".
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Populationd1d2pdv93' => array(
				'className' => 'Populationd1d2pdv93',
				'foreignKey' => 'tableausuivipdv93_id',
				'conditions' => null,
				'order' => null,
				'limit' => null,
				'offset' => null,
				'dependent' => true,
				'exclusive' => null,
				'finderQuery' => null
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
			'tableaud2' => 'D 2',
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
		 * Liste des natures de prestation.
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
			'6_8' => 'De 6 ans à moins de 9 ans',
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
		 * Liste des colonnes du tableau D1.
		 */
		public $columns_d1 = array(
			'previsionnel',
			'reports_total',
			'reports_homme',
			'reports_femme',
			'entrees_total',
			'entrees_homme',
			'entrees_femme',
			'sorties_total',
			'sorties_homme',
			'sorties_femme',
			'participants_total',
			'participants_homme',
			'participants_femme',
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
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
			);

			$group = array( 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
		 * FIXME: les dates, voir avec D1/D2
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableaud2Total( array $search ) {
			$cer = 'EXISTS( SELECT contratsinsertion.id FROM contratsinsertion WHERE contratsinsertion.personne_id = "Rendezvous"."personne_id" AND contratsinsertion.decision_ci = \'V\' AND contratsinsertion.dd_ci <= DATE_TRUNC( \'day\', "Questionnaired1pdv93"."date_validation" ) AND contratsinsertion.df_ci >= \''.date( 'Y-m-d' ).'\' )';

			$fields = array(
				'"Situationallocataire"."sexe" AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'hommes\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femmes\'
					END
				) AS "sexe"',
				'(
					CASE
						WHEN '.$cer.' THEN 1
						ELSE 0
					END
				) AS "cer"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
			);

			$group = array( 'Situationallocataire.sexe', $cer );

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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
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
			$Situationallocataire = ClassRegistry::init( 'Situationallocataire' );

			$natpf = $Situationallocataire->virtualFields['natpf_d1'];
			$natpf = str_replace( 'ENUM::NATPF_D1::', '', $natpf );

			$fields = array(
				$natpf.' AS "categorie"',
				'(
					CASE
						WHEN "Situationallocataire"."sexe" = \'1\' THEN \'homme\'
						WHEN "Situationallocataire"."sexe" = \'2\' THEN \'femme\'
						ELSE \'NC\'
					END
				) AS "sexe"',
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
			);

			$group = array( $natpf, 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
			);

			$group = array( 'Questionnaired1pdv93.inscritpe', 'Situationallocataire.sexe' );

			return array( $fields, $group );
		}

		/**
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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
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
				'COUNT( DISTINCT( "Questionnaired1pdv93"."id" ) ) AS "count"'
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
				'nivetu' => array_reverse( $enums['Questionnaired1pdv93']['nivetu'], true ),
				'categorie_sociopro' => $enums['Questionnaired1pdv93']['categorie_sociopro'],
				'autre_caracteristique' => $enums['Questionnaired1pdv93']['autre_caracteristique'],
				'natpf' => $this->natpf + array( 'NC' => 'Non défini' ),
				'nati' => $this->nati,
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
		 * Retourne le querydata utilisé pour la tableau D1.
		 *
		 * @param array $search
		 * @return array
		 */
		public function qdTableaud1( array $search ) {
			$Questionnaired1pdv93 = ClassRegistry::init( 'Questionnaired1pdv93' );

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "Rendezvous.structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			/*// Soumis à droits et devoirs / au moins soumis une fois durant l'année
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
						)
					)
				);
				$conditiondd = array(
					'OR' => array(
						$conditiondd,
						"Questionnaired1pdv93.personne_id IN ( {$sq} )"
					)
				);
			}*/

			$conditiondd = $this->_conditionTableauxD1D2SoumisDD( $search );

			$querydata = array(
				'fields' => array(),
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
				'group' => array()
			);

			return $querydata;
		}

		/**
		 * Retourne le querydata nécessaire à l'export CSV du corpus pris en
		 * compte dans un historique de tableau D1.
		 *
		 * @param integer $id La clé primaire du tableau de suivi D1 historisé
		 * @return array
		 */
		public function qdExportcsvCorpusD1( $id ) {
			$querydata = array(
				'fields' => array_merge(
					$this->Populationd1d2pdv93->fields(),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->fields(),
					$this->Populationd1d2pdv93->Questionnaired2pdv93->fields(),
					$this->Populationd1d2pdv93->Questionnaired2pdv93->Sortieaccompagnementd2pdv93->fields(),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->Situationallocataire->fields(),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->Rendezvous->fields(),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->Rendezvous->Structurereferente->fields()
				),
				'conditions' => array(
					'Tableausuivipdv93.id' => $id,
					'Tableausuivipdv93.name' => 'tableaud1',
				),
				'joins' => array(
					$this->join( 'Populationd1d2pdv93', array( 'type' => 'INNER' ) ),
					$this->Populationd1d2pdv93->join( 'Questionnaired1pdv93', array( 'type' => 'INNER' ) ),
					$this->Populationd1d2pdv93->join( 'Questionnaired2pdv93', array( 'type' => 'LEFT OUTER' ) ),
					$this->Populationd1d2pdv93->Questionnaired2pdv93->join( 'Sortieaccompagnementd2pdv93', array( 'type' => 'LEFT OUTER' ) ),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->join( 'Situationallocataire', array( 'INNER' ) ),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->join( 'Rendezvous', array( 'INNER' ) ),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->Rendezvous->join( 'Structurereferente', array( 'INNER' ) ),
				),
				'order' => array(
					'Rendezvous.daterdv ASC',
					'Questionnaired2pdv93.date_validation ASC'
				)
			);

			return $querydata;
		}

		/**
		 * Retourne le querydata nécessaire à l'export CSV du corpus pris en
		 * compte dans un historique de tableau D2.
		 *
		 * @param integer $id La clé primaire du tableau de suivi D2 historisé
		 * @return array
		 */
		public function qdExportcsvCorpusD2( $id ) {
			$querydata = array(
				'fields' => array_merge(
					$this->Populationd1d2pdv93->fields(),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->fields(),
					$this->Populationd1d2pdv93->Questionnaired2pdv93->fields(),
					$this->Populationd1d2pdv93->Questionnaired2pdv93->Sortieaccompagnementd2pdv93->fields(),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->Situationallocataire->fields(),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->Rendezvous->fields(),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->Rendezvous->Structurereferente->fields()
				),
				'conditions' => array(
					'Tableausuivipdv93.id' => $id,
					'Tableausuivipdv93.name' => 'tableaud2',
				),
				'joins' => array(
					$this->join( 'Populationd1d2pdv93', array( 'type' => 'INNER' ) ),
					$this->Populationd1d2pdv93->join( 'Questionnaired2pdv93', array( 'type' => 'INNER' ) ),
					$this->Populationd1d2pdv93->Questionnaired2pdv93->join( 'Questionnaired1pdv93', array( 'type' => 'INNER' ) ),
					$this->Populationd1d2pdv93->Questionnaired2pdv93->join( 'Sortieaccompagnementd2pdv93', array( 'type' => 'LEFT OUTER' ) ),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->join( 'Situationallocataire', array( 'INNER' ) ),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->join( 'Rendezvous', array( 'INNER' ) ),
					$this->Populationd1d2pdv93->Questionnaired1pdv93->Rendezvous->join( 'Structurereferente', array( 'INNER' ) ),
				),
				'order' => array(
					'Rendezvous.daterdv ASC',
					'Questionnaired2pdv93.date_validation ASC'
				)
			);

			return $querydata;
		}

		protected function _conditionTableauxD1D2SoumisDD( array $search ) {
			$Questionnaired1pdv93 = ClassRegistry::init( 'Questionnaired1pdv93' );
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

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

			return $conditiondd;
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

			$conditiondd = $this->_conditionTableauxD1D2SoumisDD( $search );

			$results = array();
			$categories = array_keys( $this->tableaud1Categories() );

			$qdBase = $this->qdTableaud1( $search );

			foreach( $categories as $categorie ) {
				$method = '_tableaud1'.Inflector::camelize( $categorie );

				list( $fields, $group ) = $this->{$method}( $search );

				$querydata = $qdBase;
				$querydata['fields'] = $fields;
				$querydata['group'] = $group;

				$lines = $Questionnaired1pdv93->find( 'all', $querydata );
				if( !empty( $lines ) ) {
					foreach( $lines as $line ) {
						$results[$categorie][$line[0]['categorie']]['entrees'][$line[0]['sexe']] = $line[0]['count'];
					}
				}
				else {
					$results[$categorie]['NC']['entrees']['homme'] = 0;
					$results[$categorie]['NC']['entrees']['femme'] = 0;
				}
			}

			$empty = array(
				'previsionnel' => null,
				'reports_total' => null,
				'reports_homme' => null,
				'reports_femme' => null,
				'entrees_total' => 0,
				'entrees_homme' => 0,
				'entrees_femme' => 0,
				'sorties_total' => null,
				'sorties_homme' => null,
				'sorties_femme' => null,
				'participants_total' => 0,
				'participants_homme' => 0,
				'participants_femme' => 0,
			);

			$tmp = array_keys( Hash::flatten( $this->tableaud1Categories() ) );
			$return = Hash::expand( array_fill_keys( $tmp, null ) );
			foreach( $return as $categorie1 => $data1 ) {
				$return[$categorie1] = $empty;
				$return[$categorie1]['dont'] = array();

				foreach( $data1 as $categorie2 => $data2 ) {
					$return[$categorie1]['dont'][$categorie2] = $empty;
				}
			}

			foreach( $results as $categorie1 => $data ) { // $categorie1 = sexe
//				$return[$categorie1] = $empty;
//				$return[$categorie1]['dont'] = array();

				foreach( $data as $categorie2 => $data2 ) { // $categorie2 = 1
					if( !isset( $return[$categorie1]['dont'][$categorie2] ) ) {
						$return[$categorie1]['dont'][$categorie2] = $empty;
					}

					foreach( $data2['entrees'] as $sexe => $nombre ) {
						$return[$categorie1]['dont'][$categorie2]["entrees_{$sexe}"] = $nombre;
						$return[$categorie1]['dont'][$categorie2]["entrees_total"] = (int)$return[$categorie1]['dont'][$categorie2]["entrees_total"] + $nombre;

						$return[$categorie1]["entrees_{$sexe}"] = (int)$return[$categorie1]["entrees_{$sexe}"] + $nombre;
						$return[$categorie1]["entrees_total"] = (int)$return[$categorie1]["entrees_total"] + $nombre;
					}
				}
			}

			// Catégories spéciales
			$return['diplomes_etrangers'] = $return['diplomes_etrangers']['dont']['1'];
			unset( $return['diplomes_etrangers']['dont'] );

			// Non scolarisé, 1207
			// Il faut en plus les comptabiliser dans la ligne 5, sous l'intitulé 1206
			$return['non_scolarise'] = $return['non_scolarise']['dont']['1207'];
			foreach( $return['non_scolarise'] as $key => $value ) {
				if( $return['nivetu']['dont']['1206'][$key] !== null || $value !== null  ) {
					$return['nivetu']['dont']['1206'][$key] = (int)$return['nivetu']['dont']['1206'][$key] + $value;
				}
			}

			unset( $return['non_scolarise']['dont'] );
			unset( $return['nivetu']['dont']['1207'] );

			// Calcul des participants
			foreach( $return as $categorie => $data ) {
				foreach( array( 'total', 'homme', 'femme' ) as $column ) {
					$reports = $return[$categorie]["reports_{$column}"];
					$entrees = $return[$categorie]["entrees_{$column}"];
					$sorties = $return[$categorie]["sorties_{$column}"];

					if( !is_null( $reports ) || !is_null( $entrees ) || !is_null( $sorties ) ) {
						$participants = (int)$reports + (int)$entrees - (int)$sorties;
					}
					else {
						$participants = null;
					}

					$return[$categorie]["participants_{$column}"] = $participants;
				}

				if( isset( $data['dont'] ) ) {
					foreach( $data['dont'] as $categorie2 => $data2 ) {
						foreach( array( 'total', 'homme', 'femme' ) as $column ) {
							$reports = $return[$categorie]['dont'][$categorie2]["reports_{$column}"];
							$entrees = $return[$categorie]['dont'][$categorie2]["entrees_{$column}"];
							$sorties = $return[$categorie]['dont'][$categorie2]["sorties_{$column}"];

							if( !is_null( $reports ) || !is_null( $entrees ) || !is_null( $sorties ) ) {
								$participants = (int)$reports + (int)$entrees - (int)$sorties;
							}
							else {
								$participants = null;
							}

							$return[$categorie]['dont'][$categorie2]["participants_{$column}"] = $participants;
						}
					}
				}
			}

			// Suppression des NC
			foreach( $return as $categorie => $data ) {
				unset( $return[$categorie]['dont']['NC'] );
			}

			return $return;
		}

		/**
		 *
		 * @return array
		 */
		public function tableaud2Categories() {
			$Questionnaired2pdv93 = ClassRegistry::init( 'Questionnaired2pdv93' );

			// Liste des sorties de l'accompagnement
			$querydata = array(
				'fields' => array(
					'Sortieaccompagnementd2pdv93.id',
					'Sortieaccompagnementd2pdv93.name',
					'Parent.name',
				),
				'joins' => array(
					$Questionnaired2pdv93->Sortieaccompagnementd2pdv93->join( 'Parent' )
				),
				'conditions' => array(
					'Sortieaccompagnementd2pdv93.parent_id IS NOT NULL'
				),
				'order' => array(
					'Parent.id ASC',
					'Sortieaccompagnementd2pdv93.id ASC',
				)
			);
			$sortiesaccompagnement = $Questionnaired2pdv93->Sortieaccompagnementd2pdv93->find( 'list', $querydata );

			foreach( $sortiesaccompagnement as $group => $sortiesniveau2 ) {
				$sortiesaccompagnement[$group] = array();
				foreach( $sortiesniveau2 as $id => $sortieniveau2 ) {
					$sortiesaccompagnement[$group][$sortieniveau2] = null;
				}
			}

			$enums = $Questionnaired2pdv93->enums();

			$categories = Hash::normalize( array_keys( $enums['Questionnaired2pdv93']['situationaccompagnement'] ) );
			$categories['sortie_obligation'] = $sortiesaccompagnement;
			$categories['changement_situation'] = Hash::normalize( array_values( $enums['Questionnaired2pdv93']['chgmentsituationadmin'] ) );

			return $categories;
		}

		/**
		 * Retourne le querydata utilisé pour la tableau D2.
		 *
		 * @param array $search
		 * @return array
		 */
		public function qdTableaud2( array $search ) {
			$Questionnaired2pdv93 = ClassRegistry::init( 'Questionnaired2pdv93' );

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "Rendezvous.structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			$conditiondd = $this->_conditionTableauxD1D2SoumisDD( $search );

			$querydata = array(
				'fields' => array(
					'"Questionnaired2pdv93"."situationaccompagnement" AS "Tableaud2pdv93__categorie1"',
					'( CASE WHEN ( "Questionnaired2pdv93"."situationaccompagnement" = \'changement_situation\' ) THEN "Questionnaired2pdv93"."chgmentsituationadmin" WHEN ( "Questionnaired2pdv93"."situationaccompagnement" = \'sortie_obligation\' ) THEN ( SELECT sortiesaccompagnementsd2pdvs93.name FROM sortiesaccompagnementsd2pdvs93 WHERE sortiesaccompagnementsd2pdvs93.id = "Sortieaccompagnementd2pdv93"."parent_id" ) ELSE NULL END ) AS "Tableaud2pdv93__categorie2"',
					'"Sortieaccompagnementd2pdv93"."name" AS "Tableaud2pdv93__categorie3"',
					'COUNT("Questionnaired2pdv93"."id") AS "Tableaud2pdv93__nombre"',
					'COUNT(CASE WHEN ( "Personne"."sexe" = \'1\' ) THEN "Questionnaired2pdv93"."id" ELSE NULL END) AS "Tableaud2pdv93__hommes"',
					'COUNT(CASE WHEN ( "Personne"."sexe" = \'2\' ) THEN "Questionnaired2pdv93"."id" ELSE NULL END) AS "Tableaud2pdv93__femmes"',
					'COUNT(CASE WHEN ( EXISTS( SELECT contratsinsertion.id FROM contratsinsertion WHERE contratsinsertion.personne_id = "Personne"."id" AND contratsinsertion.decision_ci = \'V\' AND contratsinsertion.dd_ci <= DATE_TRUNC( \'day\', "Questionnaired2pdv93"."date_validation" ) AND contratsinsertion.df_ci >= DATE_TRUNC( \'day\', "Questionnaired2pdv93"."date_validation" ) ) ) THEN 1 ELSE NULL END ) AS "Tableaud2pdv93__cer"',
				),
				'conditions' => array(
					'EXTRACT( \'YEAR\' FROM Questionnaired2pdv93.date_validation )' => $annee,
					$conditionpdv,
					$conditiondd
				),
				'joins' => array(
					$Questionnaired2pdv93->join( 'Personne', array( 'type' => 'INNER' ) ),
					$Questionnaired2pdv93->join( 'Questionnaired1pdv93', array( 'type' => 'INNER' ) ),
					$Questionnaired2pdv93->join( 'Sortieaccompagnementd2pdv93', array( 'type' => 'LEFT OUTER' ) ),
					$Questionnaired2pdv93->Questionnaired1pdv93->join( 'Situationallocataire', array( 'type' => 'INNER' ) ),
					$Questionnaired2pdv93->Questionnaired1pdv93->join( 'Rendezvous', array( 'type' => 'INNER' ) ),
				),
				'contain' => false,
				'group' => array(
					'Questionnaired2pdv93.situationaccompagnement',
					'Questionnaired2pdv93.chgmentsituationadmin',
					'Sortieaccompagnementd2pdv93.parent_id',
					'Sortieaccompagnementd2pdv93.name',
				)
			);

			return $querydata;
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		public function tableaud2( array $search ) {
			$Questionnaired2pdv93 = ClassRegistry::init( 'Questionnaired2pdv93' );

			$querydata = $this->qdTableaud2( $search );

			// categorie1, categorie2, categorie3, nombre, hommes, femmes, couvertcer
			$results = $Questionnaired2pdv93->find( 'all', $querydata );

			$return = $this->tableaud2Categories();

			$dimensions = array();
			foreach( array_keys( $return ) as $key ) {
				$dimensions[$key] = Hash::dimensions( (array)$return[$key] ) + 1;
			}

			// Formattage du tableau de résultats
			$enums = $Questionnaired2pdv93->enums();

			foreach( $results as $result ) {
				$data = $result['Tableaud2pdv93'];
				unset( $data['categorie1'], $data['categorie2'], $data['categorie3'] );

				// Si on n'a que la catégorie 1
//				if( empty( $result['Tableaud2pdv93']['categorie2'] ) ) {
				if( $dimensions[$result['Tableaud2pdv93']['categorie1']] == 1 ) {
					$return[$result['Tableaud2pdv93']['categorie1']] = $data;
				}
				// Si on a les catégories 1 et 2
//				else if( empty( $result['Tableaud2pdv93']['categorie3'] ) ) {
				else if( $dimensions[$result['Tableaud2pdv93']['categorie1']] == 2 ) {
					if( isset( $enums['Questionnaired2pdv93']['chgmentsituationadmin'][$result['Tableaud2pdv93']['categorie2']] ) ) {
						$categorie2 = $enums['Questionnaired2pdv93']['chgmentsituationadmin'][$result['Tableaud2pdv93']['categorie2']];
					}
					else {
						$categorie2 = $result['Tableaud2pdv93']['categorie2'];
					}
					$return[$result['Tableaud2pdv93']['categorie1']][$categorie2] = $data;
				}
				// Si on a les catégories 1, 2 et 3
				else {
					if( isset( $enums['Questionnaired2pdv93']['chgmentsituationadmin'][$result['Tableaud2pdv93']['categorie2']] ) ) {
						$categorie2 = $enums['Questionnaired2pdv93']['chgmentsituationadmin'][$result['Tableaud2pdv93']['categorie2']];
					}
					else {
						$categorie2 = $result['Tableaud2pdv93']['categorie2'];
					}
					$return[$result['Tableaud2pdv93']['categorie1']][$categorie2][$result['Tableaud2pdv93']['categorie3']] = $data;
				}
			}

			// Total des participants, c'est à dire ceux pris en compte dans la tableau D1
			$Questionnaired1pdv93 = ClassRegistry::init( 'Questionnaired1pdv93' );
			$querydata = $this->qdTableaud1( $search );

			$conditiondd = $this->_conditionTableauxD1D2SoumisDD( $search );
			$querydata['conditions'][] = $conditiondd;

			list( $fields, $group ) = $this->_tableaud2Total( $search );
			$querydata['fields'] = $fields;
			$querydata['group'] = $group;
			$results = $Questionnaired1pdv93->find( 'all', $querydata );

			$totaux = array(
				'nombre' => 0,
				'hommes' => 0,
				'femmes' => 0,
				'cer' => 0
			);
			foreach( $results as $result ) {
				$totaux[$result[0]['sexe']] += $result[0]['count'];
				if( $result[0]['cer'] ) {
					$totaux['cer'] += $result[0]['count'];
				}
			}
			$totaux['nombre'] = $totaux['hommes'] + $totaux['femmes'];

			$nombre_total = max( array( $totaux['nombre'], 1 ) );

			// Ajout de la ligne de totaux au début du tableau de résultats
			$return = array( 'totaux' => $totaux ) + $return;

			// On complète le tableau pour les catégories vides
			$return = Hash::flatten( $return );
			foreach( $return as $key => $value ) {
				if( is_null( $value ) ) {
					$return[$key] = array(
						'nombre' => 0,
						'nombre_%' => 0,
						'hommes' => 0,
						'hommes_%' => 0,
						'femmes' => 0,
						'femmes_%' => 0,
						'cer' => 0,
						'cer_%' => 0,
					);
				}
			}
			$return = Hash::expand( $return );

			// Calcul des pourcentages
			foreach( $return as $categorie1 => $data1 ) {
				if( isset( $data1['nombre'] ) ) {
					foreach( array( 'nombre', 'hommes', 'femmes', 'cer' ) as $key ) {
						$return[$categorie1]["{$key}_%"] = $data1[$key] / $nombre_total * 100;
					}
				}
				else {
					foreach( $data1 as $categorie2 => $data2 ) {
						if( isset( $data2['nombre'] ) ) {
							foreach( array( 'nombre', 'hommes', 'femmes', 'cer' ) as $key ) {
								$return[$categorie1][$categorie2]["{$key}_%"] = $data2[$key] / $nombre_total * 100;
							}
						}
						else {
							foreach( $data2 as $categorie3 => $data3 ) {
								if( isset( $data3['nombre'] ) ) {
									foreach( array( 'nombre', 'hommes', 'femmes', 'cer' ) as $key ) {
										$return[$categorie1][$categorie2][$categorie3]["{$key}_%"] = $data3[$key] / $nombre_total * 100;
									}
								}
							}
						}
					}
				}
			}

			return $return;
		}

		/**
		 *
		 * @param string $field
		 * @return string
		 */
		protected function _conditionStatutRdv( $field = 'statutrdv_id' ) {
			$values = "'".implode( "', '", (array)Configure::read( 'Tableausuivipdv93.statutrdv_id' ) )."'";
			return "{$field} IN ( {$values} )";
		}

		/**
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

			$sql = "SELECT \"difficultes_exprimees\", COUNT(*) FROM (
	(
		SELECT 'sante'::text AS \"difficultes_exprimees\",
		-- liste ids
		dsps.id AS dsp, dsps_revs.id AS dsp_rev
		FROM personnes INNER JOIN dsps on (personnes.id=dsps.personne_id)
			INNER JOIN rendezvous ON (dsps.personne_id = rendezvous.personne_id)
			LEFT OUTER JOIN detailsdifsocs sante ON (dsps.id = sante.dsp_id AND sante.difsoc IN ('0402','0403') )
			LEFT OUTER JOIN dsps_revs ON (dsps.personne_id = dsps_revs.personne_id AND (dsps_revs.personne_id, dsps_revs.id) IN ( SELECT personne_id, MAX(dsps_revs.id) FROM dsps_revs GROUP BY personne_id))
			LEFT OUTER JOIN detailsdifsocs_revs sante_revs ON (dsps_revs.id = sante_revs.dsp_rev_id AND sante_revs.difsoc IN ('0402','0403') )
		WHERE 	-- si pas de DSP MAJ on prend la DSP CAF
			((dsps_revs.id IS NULL AND sante.difsoc IS NOT NULL) OR (dsps_revs.id IS NOT NULL AND sante_revs.difsoc IS NOT NULL))
			AND -- Dont le type de RDV est individuel
			rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
			-- avec un RDV honore durant l'annee N
			AND EXTRACT('YEAR' FROM daterdv) = '{$annee}' AND ".$this->_conditionStatutRdv()."
			-- pour la structure referente X (eventuellement)
			{$conditionpdv}
			{$conditionmaj}
			-- De plus, on restreint les structures referentes a celles qui apparaissent dans le select
			AND ".$this->_conditionStructurereferenteIsPdv()."

	)  UNION
	(
		SELECT 'logement'::text AS \"difficultes_exprimees\",
		-- liste ids
		dsps.id AS dsp, dsps_revs.id AS dsp_rev
		FROM personnes INNER JOIN dsps on (personnes.id=dsps.personne_id)
			INNER JOIN rendezvous ON (dsps.personne_id = rendezvous.personne_id)
			LEFT OUTER JOIN dsps_revs ON (dsps.personne_id = dsps_revs.personne_id AND (dsps_revs.personne_id, dsps_revs.id) IN ( SELECT personne_id, MAX(dsps_revs.id) FROM dsps_revs GROUP BY personne_id))
			LEFT OUTER JOIN detailsdiflogs ON (dsps.id = detailsdiflogs.dsp_id AND detailsdiflogs.diflog IN ('1004', '1005', '1006', '1007', '1008', '1009'))
			LEFT OUTER JOIN detailsdiflogs_revs ON (dsps_revs.id = detailsdiflogs_revs.dsp_rev_id AND detailsdiflogs_revs.diflog IN ('1004', '1005', '1006', '1007', '1008', '1009'))
		WHERE 	-- si pas de DSP MAJ on prend la DSP CAF
			((dsps_revs.id IS NULL AND detailsdiflogs.diflog IS NOT NULL) OR (dsps_revs.id IS NOT NULL AND detailsdiflogs_revs.diflog IS NOT NULL))
			AND -- Dont le type de RDV est individuel
			rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
			-- avec un RDV honore durant l'annee N
			AND EXTRACT('YEAR' FROM daterdv) = '{$annee}' AND ".$this->_conditionStatutRdv()."
			-- pour la structure referente X (eventuellement)
			{$conditionpdv}
			{$conditionmaj}
			-- De plus, on restreint les structures referentes a celles qui apparaissent dans le select
			AND ".$this->_conditionStructurereferenteIsPdv()."
	) UNION
	(
		SELECT 'familiales'::text AS \"difficultes_exprimees\",
		-- liste ids
		dsps.id AS dsp, dsps_revs.id AS dsp_rev
		FROM personnes INNER JOIN dsps on (personnes.id=dsps.personne_id)
			INNER JOIN rendezvous ON (dsps.personne_id = rendezvous.personne_id)
			LEFT OUTER JOIN dsps_revs ON (dsps.personne_id = dsps_revs.personne_id AND (dsps_revs.personne_id, dsps_revs.id) IN ( SELECT personne_id, MAX(dsps_revs.id) FROM dsps_revs GROUP BY personne_id))
			LEFT OUTER JOIN detailsaccosocfams ON (dsps.id = detailsaccosocfams.dsp_id AND detailsaccosocfams.nataccosocfam='0412')
			LEFT OUTER JOIN detailsaccosocfams_revs ON (dsps_revs.id = detailsaccosocfams_revs.dsp_rev_id AND detailsaccosocfams_revs.nataccosocfam='0412')
		WHERE 	-- si pas de DSP MAJ on prend la DSP CAF
			((dsps_revs.id IS NULL AND detailsaccosocfams.nataccosocfam IS NOT NULL) OR (dsps_revs.id IS NOT NULL AND detailsaccosocfams_revs.nataccosocfam IS NOT NULL))
			AND -- Dont le type de RDV est individuel
			rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
			-- avec un RDV honore durant l'annee N
			AND EXTRACT('YEAR' FROM daterdv) = '{$annee}' AND ".$this->_conditionStatutRdv()."
			-- pour la structure referente X (eventuellement)
			{$conditionpdv}
			{$conditionmaj}
			-- De plus, on restreint les structures referentes a celles qui apparaissent dans le select
			AND ".$this->_conditionStructurereferenteIsPdv()."
	) UNION
	(
		SELECT 'modes_gardes'::text AS \"difficultes_exprimees\",
		-- liste ids
		dsps.id AS dsp, dsps_revs.id AS dsp_rev
		FROM personnes INNER JOIN dsps on (personnes.id=dsps.personne_id)
			INNER JOIN rendezvous ON (dsps.personne_id = rendezvous.personne_id)
			LEFT OUTER JOIN dsps_revs ON (dsps.personne_id = dsps_revs.personne_id AND (dsps_revs.personne_id, dsps_revs.id) IN ( SELECT personne_id, MAX(dsps_revs.id) FROM dsps_revs GROUP BY personne_id))
			LEFT OUTER JOIN detailsdifdisps ON (dsps.id = detailsdifdisps.dsp_id AND detailsdifdisps.difdisp IN ('0502', '0503', '0504'))
			LEFT OUTER JOIN detailsdifdisps_revs ON (dsps_revs.id = detailsdifdisps_revs.dsp_rev_id AND detailsdifdisps_revs.difdisp IN ('0502', '0503', '0504'))
		WHERE 	-- si pas de DSP MAJ on prend la DSP CAF
			((dsps_revs.id IS NULL AND detailsdifdisps.difdisp IS NOT NULL) OR (dsps_revs.id IS NOT NULL AND detailsdifdisps_revs.difdisp IS NOT NULL))
			AND -- Dont le type de RDV est individuel
			rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
			-- avec un RDV honore durant l'annee N
			AND EXTRACT('YEAR' FROM daterdv) = '{$annee}' AND ".$this->_conditionStatutRdv()."
			-- pour la structure referente X (eventuellement)
			{$conditionpdv}
			{$conditionmaj}
			-- De plus, on restreint les structures referentes a celles qui apparaissent dans le select
			AND ".$this->_conditionStructurereferenteIsPdv()."
	) UNION
	(
		SELECT 'surendettement'::text AS \"difficultes_exprimees\",
		-- liste ids
		dsps.id AS dsp, dsps_revs.id AS dsp_rev
		FROM personnes INNER JOIN dsps on (personnes.id=dsps.personne_id)
			INNER JOIN rendezvous ON (dsps.personne_id = rendezvous.personne_id)
			LEFT OUTER JOIN dsps_revs ON (dsps.personne_id = dsps_revs.personne_id AND (dsps_revs.personne_id, dsps_revs.id) IN ( SELECT personne_id, MAX(dsps_revs.id) FROM dsps_revs GROUP BY personne_id))
			LEFT OUTER JOIN detailsdifsocs surendettement ON (dsps.id = surendettement.dsp_id AND surendettement.difsoc='0406')
			LEFT OUTER JOIN detailsdifsocs_revs surendettement_revs ON (dsps_revs.id = surendettement_revs.dsp_rev_id AND surendettement_revs.difsoc='0406')
		WHERE 	-- si pas de DSP MAJ on prend la DSP CAF
			((dsps_revs.id IS NULL AND surendettement.difsoc IS NOT NULL) OR (dsps_revs.id IS NOT NULL AND surendettement_revs.difsoc IS NOT NULL))
			AND -- Dont le type de RDV est individuel
			rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
			-- avec un RDV honore durant l'annee N
			AND EXTRACT('YEAR' FROM daterdv) = '{$annee}' AND ".$this->_conditionStatutRdv()."
			-- pour la structure referente X (eventuellement)
			{$conditionpdv}
			{$conditionmaj}
			-- De plus, on restreint les structures referentes a celles qui apparaissent dans le select
			AND ".$this->_conditionStructurereferenteIsPdv()."
	) UNION
	(
		SELECT 'administratives'::text AS \"difficultes_exprimees\",
		-- liste ids
		dsps.id AS dsp, dsps_revs.id AS dsp_rev
		FROM personnes INNER JOIN dsps on (personnes.id=dsps.personne_id)
			INNER JOIN rendezvous ON (dsps.personne_id = rendezvous.personne_id)
			LEFT OUTER JOIN dsps_revs ON (dsps.personne_id = dsps_revs.personne_id AND (dsps_revs.personne_id, dsps_revs.id) IN ( SELECT personne_id, MAX(dsps_revs.id) FROM dsps_revs GROUP BY personne_id))
			LEFT OUTER JOIN detailsdifsocs administratives ON (dsps.id = administratives.dsp_id AND administratives.difsoc='0405')
			LEFT OUTER JOIN detailsdifsocs_revs administratives_revs ON (dsps_revs.id = administratives_revs.dsp_rev_id AND administratives_revs.difsoc='0405')
		WHERE 	-- si pas de DSP MAJ on prend la DSP CAF
			((dsps_revs.id IS NULL AND administratives.difsoc IS NOT NULL) OR (dsps_revs.id IS NOT NULL AND administratives_revs.difsoc IS NOT NULL))
			AND -- Dont le type de RDV est individuel
			rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
			-- avec un RDV honore durant l'annee N
			AND EXTRACT('YEAR' FROM daterdv) = '{$annee}' AND ".$this->_conditionStatutRdv()."
			-- pour la structure referente X (eventuellement)
			{$conditionpdv}
			{$conditionmaj}
			-- De plus, on restreint les structures referentes a celles qui apparaissent dans le select
			AND ".$this->_conditionStructurereferenteIsPdv()."
	) UNION
	(
		SELECT 'linguistiques'::text AS \"difficultes_exprimees\",
		-- liste ids
		dsps.id AS dsp, dsps_revs.id AS dsp_rev
		FROM personnes INNER JOIN dsps on (personnes.id=dsps.personne_id)
			INNER JOIN rendezvous ON (dsps.personne_id = rendezvous.personne_id)
			LEFT OUTER JOIN dsps_revs ON (dsps.personne_id = dsps_revs.personne_id AND (dsps_revs.personne_id, dsps_revs.id) IN ( SELECT personne_id, MAX(dsps_revs.id) FROM dsps_revs GROUP BY personne_id))
			LEFT OUTER JOIN detailsdifsocs linguistiques ON (dsps.id = linguistiques.dsp_id AND linguistiques.difsoc='0404')
			LEFT OUTER JOIN detailsdifsocs_revs linguistiques_revs ON (dsps_revs.id = linguistiques_revs.dsp_rev_id AND linguistiques_revs.difsoc='0404')
		WHERE 	-- si pas de DSP MAJ on prend la DSP CAF
			((dsps_revs.id IS NULL AND linguistiques.difsoc IS NOT NULL) OR (dsps_revs.id IS NOT NULL AND linguistiques_revs.difsoc IS NOT NULL))
			AND -- Dont le type de RDV est individuel
			rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
			-- avec un RDV honore durant l'annee N
			AND EXTRACT('YEAR' FROM daterdv) = '{$annee}' AND ".$this->_conditionStatutRdv()."
			-- pour la structure referente X (eventuellement)
			{$conditionpdv}
			{$conditionmaj}
				-- De plus, on restreint les structures referentes a celles qui apparaissent dans le select
			AND ".$this->_conditionStructurereferenteIsPdv()."
	) UNION
	(
		SELECT 'qualification_professionnelle'::text AS \"difficultes_exprimees\",
		-- liste ids
		dsps.id AS dsp, dsps_revs.id AS dsp_rev
		FROM personnes INNER JOIN dsps on (personnes.id=dsps.personne_id)
			INNER JOIN rendezvous ON (dsps.personne_id = rendezvous.personne_id)
			LEFT OUTER JOIN dsps_revs ON (dsps.personne_id = dsps_revs.personne_id AND (dsps_revs.personne_id, dsps_revs.id) IN ( SELECT personne_id, MAX(dsps_revs.id) FROM dsps_revs GROUP BY personne_id))
			LEFT OUTER JOIN dsps nivetu ON (dsps.id=nivetu.id AND nivetu.nivetu IN ('1206','1207'))
			LEFT OUTER JOIN dsps_revs nivetu_revs ON (dsps_revs.id=nivetu_revs.id AND nivetu_revs.nivetu IN ('1206','1207'))
		WHERE 	-- si pas de DSP MAJ on prend la DSP CAF
			((dsps_revs.id IS NULL AND nivetu.nivetu IS NOT NULL) OR (dsps_revs.id IS NOT NULL AND nivetu_revs.nivetu IS NOT NULL))
			AND -- Dont le type de RDV est individuel
			rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
			-- avec un RDV honore durant l'annee N
			AND EXTRACT('YEAR' FROM daterdv) = '{$annee}' AND ".$this->_conditionStatutRdv()."
			-- pour la structure referente X (eventuellement)
			{$conditionpdv}
			{$conditionmaj}
			-- De plus, on restreint les structures referentes a celles qui apparaissent dans le select
			AND ".$this->_conditionStructurereferenteIsPdv()."
	) UNION
	(
		SELECT 'acces_emploi'::text AS \"difficultes_exprimees\",
		-- liste ids
		dsps.id AS dsp, dsps_revs.id AS dsp_rev
		FROM personnes INNER JOIN dsps on (personnes.id=dsps.personne_id)
			INNER JOIN rendezvous ON (dsps.personne_id = rendezvous.personne_id)
			LEFT OUTER JOIN dsps_revs ON (dsps.personne_id = dsps_revs.personne_id AND (dsps_revs.personne_id, dsps_revs.id) IN ( SELECT personne_id, MAX(dsps_revs.id) FROM dsps_revs GROUP BY personne_id))
			LEFT OUTER JOIN dsps topengdemarechemploi ON (dsps.id=topengdemarechemploi.id AND topengdemarechemploi.topengdemarechemploi='0')
			LEFT OUTER JOIN dsps_revs topengdemarechemploi_revs ON (dsps_revs.id=topengdemarechemploi_revs.id AND topengdemarechemploi_revs.topengdemarechemploi='0')
		WHERE 	-- si pas de DSP MAJ on prend la DSP CAF
			((dsps_revs.id IS NULL AND topengdemarechemploi.topengdemarechemploi IS NOT NULL) OR (dsps_revs.id IS NOT NULL AND topengdemarechemploi_revs.topengdemarechemploi IS NOT NULL))
			AND -- Dont le type de RDV est individuel
			rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
			-- avec un RDV honore durant l'annee N
			AND EXTRACT('YEAR' FROM daterdv) = '{$annee}' AND ".$this->_conditionStatutRdv()."
			-- pour la structure referente X (eventuellement)
			{$conditionpdv}
			{$conditionmaj}
			-- De plus, on restreint les structures referentes a celles qui apparaissent dans le select
			AND ".$this->_conditionStructurereferenteIsPdv()."
	) UNION
	(
		SELECT 'autres'::text AS \"difficultes_exprimees\",
		-- liste ids
		dsps.id AS dsp, dsps_revs.id AS dsp_rev
		FROM personnes INNER JOIN dsps on (personnes.id=dsps.personne_id)
			INNER JOIN rendezvous ON (dsps.personne_id = rendezvous.personne_id)
			LEFT OUTER JOIN dsps_revs ON (dsps.personne_id = dsps_revs.personne_id AND (dsps_revs.personne_id, dsps_revs.id) IN ( SELECT personne_id, MAX(dsps_revs.id) FROM dsps_revs GROUP BY personne_id))
			LEFT OUTER JOIN detailsaccosocindis ON (dsps.id = detailsaccosocindis.dsp_id AND detailsaccosocindis.nataccosocindi='0420' )
			LEFT OUTER JOIN detailsaccosocindis_revs ON (dsps_revs.id = detailsaccosocindis_revs.dsp_rev_id AND detailsaccosocindis_revs.nataccosocindi='0420' )
		WHERE 	-- si pas de DSP MAJ on prend la DSP CAF
			((dsps_revs.id IS NULL AND detailsaccosocindis.nataccosocindi IS NOT NULL) OR (dsps_revs.id IS NOT NULL AND  detailsaccosocindis_revs.nataccosocindi IS NOT NULL))
			AND -- Dont le type de RDV est individuel
			rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
			-- avec un RDV honore durant l'annee N
			AND EXTRACT('YEAR' FROM daterdv) = '{$annee}' AND ".$this->_conditionStatutRdv()."
			-- pour la structure referente X (eventuellement)
			{$conditionpdv}
			{$conditionmaj}
			-- De plus, on restreint les structures referentes a celles qui apparaissent dans le select
			AND ".$this->_conditionStructurereferenteIsPdv()."
	)
)  as liste_difficultes group by \"difficultes_exprimees\"; ";

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
						AND rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
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
		 * @deprecated
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
						{$conditionpdv}
						-- Dont la fiche de prescription n'a pas été annulée
						AND actionscandidats_personnes.positionfiche <> 'annule'
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
							{$conditionpdv}
							-- Dont la fiche de prescription n'a pas été annulée
							AND actionscandidats_personnes.positionfiche <> 'annule'
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
		 * Filtre sur un PDV ou sur l'ensemble du CG ?
		 * S'assure-ton qu'il existe au moins un RDV individuel ?
		 *
		 *
		 * @param array $search
		 * @param type $operand
		 * @return string
		 */
		protected function _conditionsFicheprescription93Rendezvous( array $search, $operand ) {
			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "AND Referent.structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// S'assure-ton qu'il existe au moins un RDV individuel ?
			$rdv_structurereferente = Hash::get( $search, 'Search.rdv_structurereferente' );
			if( $rdv_structurereferente ) {
				return "{$operand} Ficheprescription93.personne_id IN (
					SELECT DISTINCT personne_id FROM rendezvous
					WHERE
						-- avec un RDV honoré durant l'année N
						EXTRACT('YEAR' FROM daterdv) = '{$annee}'
						-- Dont le type de RDV est individuel
						AND rendezvous.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
						AND ".$this->_conditionStatutRdv()."
						-- dont la SR du référent de la fiche est la SR du RDV
						AND Referent.structurereferente_id = rendezvous.structurereferente_id
						{$conditionpdv}
				)";
			}

			return null;
		}

		/**
		 * Tableau 1-B-4: prescriptions vers les acteurs sociaux,
		 * culturels et de sante.
		 *
		 * @param array $search
		 * @return array
		 */
		public function tableau1b4new( array $search ) {
			$Ficheprescription93 = ClassRegistry::init( 'Ficheprescription93' );

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "Referent.structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// Filtre sur le type d'action
			$conditiontype = null;
			$typethematiquefp93_id = Hash::get( $search, 'Search.typethematiquefp93_id' );
			if( !empty( $typethematiquefp93_id ) ) {
				$conditiontype = "Thematiquefp93.type = '".Sanitize::clean( $typethematiquefp93_id, array( 'encode' => false ) )."'";
			}

			// Filtre sur le RDV individuel
			$conditionsrdv = $this->_conditionsFicheprescription93Rendezvous( $search, 'AND' );
			if( $conditionsrdv !== null ) {
				$conditionsrdv = preg_replace( '/^AND /', '', $conditionsrdv );
			}

			// Le query de base
			$base = array(
				'fields' => array(),
				'joins' => array(
					$Ficheprescription93->join( 'Actionfp93', array( 'type' => 'LEFT OUTER' ) ),
					$Ficheprescription93->join( 'Filierefp93', array( 'type' => 'INNER' ) ),
					$Ficheprescription93->join( 'Prestatairefp93', array( 'type' => 'LEFT OUTER' ) ),
					$Ficheprescription93->join( 'Referent', array( 'type' => 'INNER' ) ),
					$Ficheprescription93->Filierefp93->join( 'Categoriefp93', array( 'type' => 'INNER' ) ),
					$Ficheprescription93->Filierefp93->Categoriefp93->join( 'Thematiquefp93', array( 'type' => 'INNER' ) ),
				),
				'conditions' => array(
					'Ficheprescription93.statut <>' => '99annulee',
					"EXTRACT( 'YEAR' FROM Ficheprescription93.date_signature )" => $annee,
					$this->_conditionStructurereferenteIsPdv( 'Referent.structurereferente_id' ),
					$conditionpdv,
					$conditionsrdv,
					$conditiontype
				),
				'contain' => false
			);

			// Ajout des conditions de base définies dans le webrsa.inc pour l'ensemble du tableau
			$conditions = (array)Configure::read( 'Tableausuivi93.tableau1b4.conditions' );
			if( !empty( $conditions ) ) {
				$base['conditions'][] = $conditions;
			}

			// Ajout des libellés des catégories et des thématiques
			$Dbo = $Ficheprescription93->getDataSource();
			$categories = (array)Configure::read( 'Tableausuivi93.tableau1b4.categories' );

			$conditionsTotal = array( 'OR' => array() );
			$sqls = array();
			$counter = 0;
			foreach( $categories as $categorieName => $thematiques ) {
				$conditionsSousTotal = array( 'OR' => array() );

				foreach( $thematiques as $thematiqueName => $conditions ) {
					$categorieName = Sanitize::clean( $categorieName, array( 'encode' => false ) );
					$thematiqueName = Sanitize::clean( $thematiqueName, array( 'encode' => false ) );

					$conditionsSousTotal['OR'][] = $conditions;
					$conditionsTotal['OR'][] = $conditions;
					$conditions = $Dbo->conditions( $conditions, true, false );

					// 1 requête par ligne
					$query = $base;
					$query['fields'] = array(
						"'{$categorieName}' AS \"categorie\"",
						"'{$thematiqueName}' AS \"thematique\"",
						"{$counter} AS \"counter\"",
						'COUNT( Ficheprescription93.id ) AS "nombre"',
						'COUNT( DISTINCT Ficheprescription93.personne_id ) AS "nombre_unique"'
					);
					$query['conditions'][] = $conditions;

					$sqls[] = $Ficheprescription93->sq( $query );
					$counter++;
				}

				// requête pour le sous-total
				$query = $base;
				$query['fields'] = array(
					"'{$categorieName}' AS \"categorie\"",
					"'Sous-total' AS \"thematique\"",
					"{$counter} AS \"counter\"",
					'COUNT( Ficheprescription93.id ) AS "nombre"',
					'COUNT( DISTINCT Ficheprescription93.personne_id ) AS "nombre_unique"'
				);
				$query['conditions'][] = $conditionsSousTotal;

				$sqls[] = $Ficheprescription93->sq( $query );
				$counter++;
			}
			// requête pour le total
			$query = $base;
			$query['fields'] = array(
				"'Total' AS \"categorie\"",
				"NULL AS \"thematique\"",
				"{$counter} AS \"counter\"",
				'COUNT( Ficheprescription93.id ) AS "nombre"',
				'COUNT( DISTINCT Ficheprescription93.personne_id ) AS "nombre_unique"'
			);
			$query['conditions'][] = $conditionsTotal;

			$sqls[] = $Ficheprescription93->sq( $query );
			$counter++;

			// Requête complète
			$results = $Ficheprescription93->query( '( '.implode( $sqls, ' UNION ' ).' ) ORDER BY "counter" ASC;' );
			$results = Hash::remove( $results, '{n}.0.counter' );

			return $results;
		}

		/**
		 * Fournit la vérification des morceaux de querydata définis dans le
		 * webrsa.inc pour les clés Tableausuivi93.tableau1b4 et
		 * Tableausuivi93.tableau1b5.
		 *
		 * @return array
		 */
		public function querydataFragmentsErrors() {
			$return = array();

			// Tableausuivi93.tableau1b4 et Tableausuivi93.tableau1b5 (conditions et categories)
			foreach( array( 'tableau1b4', 'tableau1b5' ) as $name ) {
				$search = array(
					'Search' => array(
						'annee' => '2009',
						'structurereferente_id' => ''
					)
				);
				try {
					$method = "{$name}new"; //FIXME: tableau1b4new, tableau1b5new
					@$this->{$method}( $search );
					$message = null;
				} catch ( Exception $e ) {
					$message = $e->getMessage();
				}
				$return["Tableausuivi93.{$name}"] = array(
					'success' => is_null( $message ),
					'message' => $message
				);
			}

			return $return;
		}

		/**
		 * Requête de base pour le tableau 1B5.
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableau1b5newBase( array $search ) {
			$Ficheprescription93 = ClassRegistry::init( 'Ficheprescription93' );

			// Filtre sur l'année
			$annee = Sanitize::clean( Hash::get( $search, 'Search.annee' ), array( 'encode' => false ) );

			// Filtre sur un PDV ou sur l'ensemble du CG ?
			$conditionpdv = null;
			$pdv_id = Hash::get( $search, 'Search.structurereferente_id' );
			if( !empty( $pdv_id ) ) {
				$conditionpdv = "Referent.structurereferente_id = ".Sanitize::clean( $pdv_id, array( 'encode' => false ) );
			}

			// Filtre sur le type d'action
			$conditiontype = null;
			$typethematiquefp93_id = Hash::get( $search, 'Search.typethematiquefp93_id' );
			if( !empty( $typethematiquefp93_id ) ) {
				$conditiontype = "Thematiquefp93.type = '".Sanitize::clean( $typethematiquefp93_id, array( 'encode' => false ) )."'";
			}

			$conditionsrdv = $this->_conditionsFicheprescription93Rendezvous( $search, 'AND' );
			if( $conditionsrdv !== null ) {
				$conditionsrdv = preg_replace( '/^AND /', '', $conditionsrdv );
			}

			// Le query de base
			$query = array(
				'fields' => array(),
				'joins' => array(
					$Ficheprescription93->join( 'Actionfp93', array( 'type' => 'LEFT OUTER' ) ),
					$Ficheprescription93->join( 'Filierefp93', array( 'type' => 'INNER' ) ),
					$Ficheprescription93->join( 'Prestatairefp93', array( 'type' => 'LEFT OUTER' ) ),
					$Ficheprescription93->join( 'Referent', array( 'type' => 'INNER' ) ),
					$Ficheprescription93->Filierefp93->join( 'Categoriefp93', array( 'type' => 'INNER' ) ),
					$Ficheprescription93->Filierefp93->Categoriefp93->join( 'Thematiquefp93', array( 'type' => 'INNER' ) ),
				),
				'conditions' => array(
					'Ficheprescription93.statut <>' => '99annulee',
					"EXTRACT( 'YEAR' FROM Ficheprescription93.date_signature )" => $annee,
					$this->_conditionStructurereferenteIsPdv( 'Referent.structurereferente_id' ),
					$conditionpdv,
					$conditionsrdv,
					$conditiontype
				),
				'contain' => false
			);

			// Ajout des conditions de base définies dans le webrsa.inc pour l'ensemble du tableau
			$conditions = (array)Configure::read( 'Tableausuivi93.tableau1b5.conditions' );
			if( !empty( $conditions ) ) {
				$query['conditions'][] = $conditions;
			}

			return $query;
		}

		/**
		 * Tableau 1-B-5: prescription sur les actions à caractère socio-professionnel
		 * et professionnel.
		 *
		 * Partie "Tableaux périphériques".
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableau1b5newTotaux( array $search ) {
			$Ficheprescription93 = ClassRegistry::init( 'Ficheprescription93' );
			$query = $this->_tableau1b5newBase( $search );

			// Ajout des conditions des différentes catégories
			$categories = (array)Configure::read( 'Tableausuivi93.tableau1b5.categories' );
			$query['conditions'][] = array( 'OR' => Hash::extract( $categories, '{s}.{s}' ) );

			// Ajout des champs spécifiques à cette requête
			$query['fields'] = array(
				'COUNT( DISTINCT "Ficheprescription93"."personne_id" ) AS "distinct_personnes_prescription"',
				'COUNT( DISTINCT ( CASE WHEN "Ficheprescription93"."benef_retour_presente" = \'oui\' THEN "Ficheprescription93"."personne_id" ELSE NULL END ) ) AS "distinct_personnes_action"',
				// H. Cadre effectivité : La personne s'est présentée=non ou s'est excusée
				'COALESCE( SUM( CASE WHEN "Ficheprescription93"."benef_retour_presente" IN ( \'non\', \'excuse\' ) THEN 1 ELSE 0 END ), 0 ) AS "beneficiaires_pas_deplaces"',
				// I. Cadre effectivité : "Signé par le partenaire le"=vide
				'COALESCE( SUM( CASE WHEN "Ficheprescription93"."date_signature_partenaire" IS NULL THEN 1 ELSE 0 END ), 0 ) AS "nombre_fiches_attente"',
			);

			$results = $Ficheprescription93->find( 'all', $query );
			return $results;
		}

		/**
		 * Tableau 1-B-5: prescription sur les actions à caractère socio-professionnel
		 * et professionnel.
		 *
		 * Partie "Totaux" (les deux tableaux périphériques).
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _tableau1b5newResults( array $search ) {
			$Ficheprescription93 = ClassRegistry::init( 'Ficheprescription93' );
			$base = $this->_tableau1b5newBase( $search );

			// Ajout des libellés des catégories et des thématiques
			$Dbo = $Ficheprescription93->getDataSource();
			$categories = (array)Configure::read( 'Tableausuivi93.tableau1b5.categories' );

			$vFields = array(
				// A. nombre total de fiches quel que soit le statut renseigné
				'COUNT( Ficheprescription93.id ) AS "nombre"',
				// B. Cadre "Effectivité de la prescription": Nombre de fiches pour lesquelles l'allocataire s'est présenté ="oui" et date de signature du partenaire est renseignée
				'COALESCE( SUM( CASE WHEN "Ficheprescription93"."benef_retour_presente" = \'oui\' AND "Ficheprescription93"."date_signature_partenaire" IS NOT NULL THEN 1 ELSE 0 END ), 0 ) AS "nombre_effectives"',
				// C. Cadre "Suivi de l'action" : "La personne souhaite intégrer l'action=non"
				'COALESCE( SUM( CASE WHEN "Ficheprescription93"."personne_souhaite_integrer" = \'0\' THEN 1 ELSE 0 END ), 0 ) AS "nombre_refus_beneficiaire"',
				// D. Cadre "Suivi de l'action" : "La personne a été retenue par la structure=non"
				'COALESCE( SUM( CASE WHEN "Ficheprescription93"."personne_retenue" = \'0\' THEN 1 ELSE 0 END ), 0 ) AS "nombre_refus_organisme"',
				// E. Cadre "Suivi de l'action" : La personne a été reçue en entretien=oui La personne a été retenue par la structure:oui La personne souhaite intégrer l'action:oui L'allocataire a intégré l'action= vide Avec la date du jour antérieure à la date de début de l'action si elle existe
				'COALESCE( SUM(
					CASE
						WHEN (
							"Ficheprescription93"."personne_recue" = \'1\'
							AND "Ficheprescription93"."personne_retenue" = \'1\'
							AND "Ficheprescription93"."personne_souhaite_integrer" = \'1\'
							AND "Ficheprescription93"."personne_a_integre" IS NULL
							AND (
								"Ficheprescription93"."dd_action" IS NULL
								OR "Ficheprescription93"."dd_action" > NOW()
							)
						) THEN 1
						ELSE 0
					END
				), 0 ) AS "nombre_en_attente"',
				// F. Cadre "Suivi de l'action": L'allocataire a intégré l'action=oui
				'COUNT( DISTINCT ( CASE WHEN "Ficheprescription93"."personne_a_integre" = \'1\' THEN "Ficheprescription93"."personne_id" ELSE NULL END ) ) AS "nombre_participants"'
			);

			$conditionsTotal = array( 'OR' => array() );
			$sqls = array();
			$counter = 0;
			foreach( $categories as $categorieName => $thematiques ) {
				$conditionsSousTotal = array( 'OR' => array() );

				foreach( $thematiques as $thematiqueName => $conditions ) {
					$categorieName = Sanitize::clean( $categorieName, array( 'encode' => false ) );
					$thematiqueName = Sanitize::clean( $thematiqueName, array( 'encode' => false ) );

					$conditionsSousTotal['OR'][] = $conditions;
					$conditionsTotal['OR'][] = $conditions;
					$conditions = $Dbo->conditions( $conditions, true, false );

					// requête par ligne
					$query = $base;
					$query['fields'] = array_merge(
						array(
							"'{$categorieName}' AS \"categorie\"",
							"'{$thematiqueName}' AS \"thematique\"",
							"{$counter} AS \"counter\""
						),
						$vFields
					);
					$query['conditions'][] = $conditions;

					$sqls[] = $Ficheprescription93->sq( $query );
					$counter++;
				}

				// requête pour le sous-total
				$query = $base;
				$query['fields'] = array_merge(
					array(
						"'{$categorieName}' AS \"categorie\"",
						"'Sous-total' AS \"thematique\"",
						"{$counter} AS \"counter\"",
					),
					$vFields
				);
				$query['conditions'][] = $conditionsSousTotal;

				$sqls[] = $Ficheprescription93->sq( $query );
				$counter++;
			}
			// requête pour le total
			$query = $base;
			$query['fields'] = array_merge(
				array(
					"'Total' AS \"categorie\"",
					"NULL AS \"thematique\"",
					"{$counter} AS \"counter\"",
				),
				$vFields
			);
			$query['conditions'][] = $conditionsTotal;

			$sqls[] = $Ficheprescription93->sq( $query );
			$counter++;

			// Requête complète
			$results = $Ficheprescription93->query( '( '.implode( $sqls, ' UNION ' ).' ) ORDER BY "counter" ASC;' );
			$results = Hash::remove( $results, '{n}.0.counter' );

			return $results;
		}

		/**
		 * Tableau 1-B-5: prescription sur les actions à caractère socio-professionnel
		 * et professionnel.
		 *
		 * @param array $search
		 * @return array
		 */
		public function tableau1b5new( array $search ) {
			return array(
				'results' => $this->_tableau1b5newResults( $search ),
				'totaux' => $this->_tableau1b5newTotaux( $search )
			);
		}

		/**
		 * Tableau 1-B-5: prescription sur les actions à caractère socio-professionnel
		 * et professionnel.
		 *
		 * @deprecated
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
								{$conditionpdv}
								-- Qui ne se trouvent pas dans la tableau 1B4
								AND NOT ".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
								-- Dont la fiche de prescription n'a pas été annulée
								AND actionscandidats_personnes.positionfiche <> 'annule'

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
								{$conditionpdv}
								-- Qui ne se trouvent pas dans la tableau 1B4
								AND NOT ".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
								-- Dont la fiche de prescription n'a pas été annulée
								AND actionscandidats_personnes.positionfiche <> 'annule'

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
									-- dont la date de signature est dans l'année N
									EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
									-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
									AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
									".$this->_conditionRendezvousPdv( $search, 'AND' )."
									AND bilanretenu = 'RET'
									AND bilanvenu != 'VEN'
									{$conditionpdv}
									-- Qui ne se trouvent pas dans la tableau 1B4
									AND NOT ".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
									-- Dont la fiche de prescription n'a pas été annulée
									AND actionscandidats_personnes.positionfiche <> 'annule'
						) AS \"Tableau1b5__beneficiaires_pas_deplaces\",
						--nbre de fiches de prescription en attente d'un retour : venu + dfaction IS NULL
						(
							SELECT COUNT(*)
								FROM actionscandidats_personnes
									INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
									INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
								WHERE
									-- dont la date de signature est dans l'année N
									EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
									-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
									AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
									".$this->_conditionRendezvousPdv( $search, 'AND' )."
									AND bilanvenu = 'VEN'
									AND actionscandidats_personnes.dfaction IS NULL
									{$conditionpdv}
									-- Qui ne se trouvent pas dans la tableau 1B4
									AND NOT ".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
									-- Dont la fiche de prescription n'a pas été annulée
									AND actionscandidats_personnes.positionfiche <> 'annule'
						) AS \"Tableau1b5__nombre_fiches_attente\";";
			$results = Hash::merge( $results, $ActioncandidatPersonne->query( $sql ) );

			return $results[0];
		}

		/**
		 * TODO: nom de la fonction
		 *
		 * @deprecated
		 *
		 * @param array $results
		 * @param string $sql
		 * @param array $map
		 * @param string $nameKey
		 * @param string $valueKey
		 * @return array
		 */
		protected function _tableau1b5Foo( array $results, $sql, $map, $nameKey, $valueKey ) {
			$Actioncandidat = ClassRegistry::init( array( 'class' => 'Actioncandidat', 'alias' => 'Tableau1b5' ) );
			list( $modelName, $fieldName ) = model_field( $valueKey );

			$tmpresults = $Actioncandidat->query( $sql );
			$keysDiff = array_diff( array_keys( $map ), Hash::extract( $tmpresults, "{n}.{$nameKey}" ) );

			if( !empty( $tmpresults ) ) {
				foreach( $tmpresults as $tmpresult ) {
					$name = Hash::get( $tmpresult, $nameKey );
					$value = Hash::get( $tmpresult, $valueKey );
					$index = Hash::get( $map, $name );

					$results = Hash::insert( $results, "{$index}.Tableau1b5.{$fieldName}", $value );
				}
			}

			if( !empty( $keysDiff ) ) {
				foreach( $keysDiff as $name ) {
					$index = Hash::get( $map, $name );

					$results = Hash::insert( $results, "{$index}.Tableau1b5.{$fieldName}", 0 );
				}
			}

			return $results;
		}

		/**
		 * Tableau 1-B-5: prescription sur les actions à caractère socio-professionnel
		 * et professionnel.
		 *
		 * @deprecated
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
					'order' => array( 'Tableau1b5.name ASC' ),
                    'conditions' => array(
                        'NOT' => array( $this->_conditionNumcodefamille( 'Actioncandidat.numcodefamille' ) )
                    )
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
							-- dont la date de signature est dans l'année N
							EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
							-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
							AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
							".$this->_conditionRendezvousPdv( $search, 'AND' )."
							{$conditionpdv}
							-- Qui ne se trouvent pas dans la tableau 1B4
							AND NOT ".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
							-- Dont la fiche de prescription n'a pas été annulée
							AND actionscandidats_personnes.positionfiche <> 'annule'
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_tableau1b5Foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescription_count' );

			// Requête 2: nombre de prescription effectives : venu
			$sql = "SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescriptions_effectives_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
							INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
						WHERE
							bilanvenu = 'VEN'
							-- dont la date de signature est dans l'année N
							AND EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
							-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
							AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
							".$this->_conditionRendezvousPdv( $search, 'AND' )."
							{$conditionpdv}
							-- Qui ne se trouvent pas dans la tableau 1B4
							AND NOT ".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
							-- Dont la fiche de prescription n'a pas été annulée
							AND actionscandidats_personnes.positionfiche <> 'annule'
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_tableau1b5Foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_effectives_count' );

			// Requête 3: Raisons de la non participation,
			// Requête 3.1: Refus du bénéficiaire
			$sql = "SELECT
						NULL AS \"Tableausuivipdv93__prescription_name\",
						NULL AS \"Tableausuivipdv93__prescriptions_refus_beneficiaire_count\"
					FROM actionscandidats_personnes
					WHERE false;";
			$results = $this->_tableau1b5Foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_refus_beneficiaire_count' );

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
							-- dont la date de signature est dans l'année N
							AND EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
							-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
							AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
							".$this->_conditionRendezvousPdv( $search, 'AND' )."
							{$conditionpdv}
							-- Qui ne se trouvent pas dans la tableau 1B4
							AND NOT ".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
							-- Dont la fiche de prescription n'a pas été annulée
							AND actionscandidats_personnes.positionfiche <> 'annule'
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_tableau1b5Foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_refus_organisme_count' );

			// Requête 3.3: En attente : ddaction > now ?
			$sql = "SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescriptions_en_attente_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
							INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
						WHERE
							actionscandidats_personnes.ddaction > NOW()
							-- dont la date de signature est dans l'année N
							AND EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
							-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
							AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
							".$this->_conditionRendezvousPdv( $search, 'AND' )."
							{$conditionpdv}
							-- Qui ne se trouvent pas dans la tableau 1B4
							AND NOT ".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
							-- Dont la fiche de prescription n'a pas été annulée
							AND actionscandidats_personnes.positionfiche <> 'annule'
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_tableau1b5Foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_en_attente_count' );

			// Requête 3.3: En attente : ddaction > now ?
			$sql = "SELECT
							actionscandidats.name AS \"Tableausuivipdv93__prescription_name\",
							COUNT(*) AS \"Tableausuivipdv93__prescriptions_retenu_count\"
						FROM actionscandidats_personnes
							INNER JOIN actionscandidats ON (actionscandidats.id = actionscandidats_personnes.actioncandidat_id)
							INNER JOIN referents ON (referents.id = actionscandidats_personnes.referent_id)
						WHERE
							bilanretenu = 'RET'
							-- dont la date de signature est dans l'année N
							AND EXTRACT( 'YEAR' FROM actionscandidats_personnes.datesignature ) = '{$annee}'
							-- De plus, on restreint les structures référentes à celles qui apparaissent dans le select
							AND ".$this->_conditionStructurereferenteIsPdv( 'referents.structurereferente_id' )."
							".$this->_conditionRendezvousPdv( $search, 'AND' )."
							{$conditionpdv}
							-- Qui ne se trouvent pas dans la tableau 1B4
							AND NOT ".$this->_conditionNumcodefamille( 'actionscandidats.numcodefamille' )."
							-- Dont la fiche de prescription n'a pas été annulée
							AND actionscandidats_personnes.positionfiche <> 'annule'
						GROUP BY actionscandidats.name
						ORDER BY actionscandidats.name;";
			$results = $this->_tableau1b5Foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_retenu_count' );

			// Requête 4 : Abandon en cours d'action
			$sql = "SELECT
						NULL AS \"Tableausuivipdv93__prescription_name\",
						NULL AS \"Tableausuivipdv93__prescriptions_abandon_count\"
					FROM actionscandidats_personnes
					WHERE false;";
			$results = $this->_tableau1b5Foo( $results, $sql, $map, 'Tableausuivipdv93.prescription_name', 'Tableausuivipdv93.prescriptions_abandon_count' );

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

			// S'assure-ton qu'il existe au moins un RDV individuel ?
			$conditionrdv = null;
			$rdv_structurereferente = Hash::get( $search, 'Search.rdv_structurereferente' );
			if( $rdv_structurereferente ) {
				$conditionrdv = "AND rendezvous.personne_id IN (
					SELECT DISTINCT rdvindividuelhonore.personne_id
						FROM rendezvous AS rdvindividuelhonore
					WHERE
						-- avec un RDV honoré durant l'année N
						EXTRACT('YEAR' FROM rdvindividuelhonore.daterdv) = '{$annee}'
						-- Dont le type de RDV est individuel
						AND rdvindividuelhonore.typerdv_id IN ( ".implode( ',', (array)Configure::read( 'Tableausuivipdv93.typerdv_id' ) )." )
						AND rdvindividuelhonore.".$this->_conditionStatutRdv()."
						-- dont la SR du rendez-vous collectif est la même que celle du RDV individuel
						AND rendezvous.structurereferente_id = rdvindividuelhonore.structurereferente_id
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
							{$conditionpdv}
							{$conditionrdv}
						GROUP BY
							thematiquesrdvs.name,
							rendezvous_thematiquesrdvs.thematiquerdv_id";
			$results1 = $Thematiquerdv->query( $sql );

			$sql = "SELECT
							thematiquesrdvs.name AS \"Tableau1b6__name\",
							COUNT(DISTINCT rendezvous.daterdv) AS \"Tableau1b6__count_seances\",
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

			$success = true;

			// Si c'est le cas, on se contente de le réenregistrer pour que la date de modifcation soit mise à jour
			if( !empty( $found ) ) {
				$tableausuivipdv93 = $found;
				unset(
					$tableausuivipdv93['Tableausuivipdv93']['created'],
					$tableausuivipdv93['Tableausuivipdv93']['modified']
				);
			}

			$this->create( $tableausuivipdv93 );
			$success = $this->save() && $success;

			// TODO: mise à jour des modified ?
			if( empty( $found ) && in_array( $action, array( 'tableaud1', 'tableaud2' ) ) ) {
				$dn = ( $action == 'tableaud1' ? 'd1' : 'd2' );
				$Modelquestionnaire = ClassRegistry::init( "Questionnaire{$dn}pdv93" );

				$method = "qdTableau{$dn}"; // TODO: qd pour D1
				$querydata = $this->{$method}( $search );

				$querydata['fields'] = array(
					"\"Questionnaire{$dn}pdv93\".\"id\" AS \"Populationd1d2pdv93__questionnaire{$dn}pdv93_id\"",
					"'{$this->id}' AS \"Populationd1d2pdv93__tableausuivipdv93_id\"",
					"NOW() AS \"Populationd1d2pdv93__created\"",
					"NOW() AS \"Populationd1d2pdv93__modified\"",
				);
				unset( $querydata['group'] );
				$sq = $Modelquestionnaire->sq( $querydata );

				$Dbo = $this->Populationd1d2pdv93->getDataSource();
				$table = $Dbo->fullTableName( $this->Populationd1d2pdv93 );
				$sql = "INSERT INTO {$table} ( questionnaire{$dn}pdv93_id, tableausuivipdv93_id, created, modified ) ( {$sq} );";
				$success = ( $this->Populationd1d2pdv93->query( $sql ) !== false ) && $success;
			}

			return $success;
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