<?php
	/**
	 * Code source de la classe Statistiqueministerielle2.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Statistiqueministerielle2 ...
	 *
	 * @package app.Model
	 */
	class Statistiqueministerielle2 extends AppModel
	{
		/**
		 * Nom.
		 *
		 * @var string
		 */
		public $name = 'Statistiqueministerielle2';

		/**
		 * Ce modèle n'est lié à aucune table.
		 *
		 * @var boolean
		 */
		public $useTable = false;

		/**
		 * Behaviors utilisés.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Conditionnable'
		);

		/**
		 * Les différentes tranches qui sont utilisées dans les tableaux
		 * "Indicateurs d'orientations" et "Indicateurs de réorientations".
		 *
		 * @var array
		 */
		public $tranches = array(
			'age' => array(
				'0 - 24',
				'25 - 29',
				'30 - 39',
				'40 - 49',
				'50 - 59',
				'>= 60',
				'NC'
			),
			'sitfam' => array(
				'01 - Homme seul sans enfant',
				'02 - Femme seule sans enfant',
				'03 - Homme seul avec enfant, RSA majoré',
				'04 - Homme seul avec enfant, RSA non majoré',
				'05 - Femme seule avec enfant, RSA majoré',
				'06 - Femme seule avec enfant, RSA non majoré',
				'07 - Homme en couple sans enfant',
				'08 - Femme en couple sans enfant',
				'09 - Homme en couple avec enfant',
				'10 - Femme en couple avec enfant',
				'11 - Non connue'
			),
			'nivetu' => array(
				'Vbis et VI',
				'V',
				'IV',
				'III, II, I',
				'NC'
			),
			'anciennete' => array(
				'moins de 6 mois',
				'6 mois et moins 1 an',
				'1 an et moins de 2 ans',
				'2 ans et moins de 5 ans',
				'5 ans et plus',
				'NC',
			)
		);

		/**
		 * Types de CER pour l'écran "Indicateurs de délais"
		 *
		 * @var array
		 */
		public $types_cers = array(
			'ppae' => array(
				'nbMoisTranche1' => 1,
				'nbMoisTranche2' => 3,
			),
			'cer_pro' => array(
				'nbMoisTranche1' => 1,
				'nbMoisTranche2' => 3,
			),
			'cer_pro_social' => array(
				'nbMoisTranche1' => 2,
				'nbMoisTranche2' => 4,
			),
		);

		/**
		 * Conditions concernant les durées du CER, par catégorie.
		 *
		 * @var array
		 */
		public $durees_cers = array(
			'duree_moins_6_mois' => array(
				"( Contratinsertion.df_ci - Contratinsertion.dd_ci || ' days' )::interval < '6 month'::interval"
			),
			'duree_6_mois_moins_1_an' => array(
				"( Contratinsertion.df_ci - Contratinsertion.dd_ci || ' days' )::interval >= '6 month'::interval",
				"( Contratinsertion.df_ci - Contratinsertion.dd_ci || ' days' )::interval < '1 year'::interval"
			),
			'duree_plus_1_an' => array(
				"( Contratinsertion.df_ci - Contratinsertion.dd_ci || ' days' )::interval > '1 year'::interval"
			),
		);


		/**
		 * Retourne la condition permettant de s'assurer qu'un Typeorient est en
		 * emploi, suivant le département, car ce sont différentes variables de
		 * configuration qui sont en jeu.
		 *
		 * TODO: dans Typeorient
		 * TODO: docs dépend du CG et de with_parentid
		 *
		 * @param string $alias
		 * @return array
		 * @throws InternalErrorException
		 */
		protected function _conditionsTypeorientEmploi( $alias = null ) {
			$departement = Configure::read( 'Cg.departement' );
			$alias = ( !is_null( $alias ) ? $alias : 'Typeorient' ); // TODO: à changer dans typeorient

			if( Configure::read( 'with_parentid' ) ) {
				$field = 'parentid';
			}
			else {
				$field = 'id';
			}

			switch( $departement ) {
				case 58:
					$typeorientIdEmploi = Configure::read( 'Typeorient.emploi_id' );
					break;
				case 66:
					$typeorientIdEmploi = Configure::read( 'Orientstruct.typeorientprincipale.Emploi' );
					break;
				case 93:
					$typeorientIdEmploi = Configure::read( 'Orientstruct.typeorientprincipale.Emploi' );
					break;
				default:
					throw new InternalErrorException( 'La configuration de Cg.departement n\'est pas correcte dans le webrsa.inc' );
			}

			return array(
				"{$alias}.{$field} IS NOT NULL",
				"{$alias}.{$field}" => $typeorientIdEmploi,
			);
		}

		/**
		 * Retourne la condition permettant de s'assurer qu'un Typeorient est en
		 * social ou préprofessionnel, suivant le département, car ce sont
		 * différentes variables de configuration qui sont en jeu.
		 *
		 * TODO: dans Typeorient
		 * TODO: docs dépend du CG et de with_parentid
		 *
		 * @param string $alias
		 * @return array
		 * @throws InternalErrorException
		 */
		protected function _conditionsTypeorientSocial( $alias = null ) {
			$departement = Configure::read( 'Cg.departement' );
			$alias = ( !is_null( $alias ) ? $alias : 'Typeorient' ); // TODO: à changer dans typeorient

			if( Configure::read( 'with_parentid' ) ) {
				$field = 'parentid';
			}
			else {
				$field = 'id';
			}

			switch( $departement ) {
				case 58:
					return array(
						"{$alias}.{$field} IS NOT NULL",
						"{$alias}.{$field} <>" => Configure::read( 'Typeorient.emploi_id' ),
					);
					break;
				case 66:
					return array(
						"{$alias}.{$field} IS NOT NULL",
						"{$alias}.{$field}" => Configure::read( 'Orientstruct.typeorientprincipale.SOCIAL' )
					);
					break;
				case 93:
					return array(
						"{$alias}.{$field} IS NOT NULL",
						'OR' => array(
							array( "{$alias}.{$field}" => Configure::read( 'Orientstruct.typeorientprincipale.Social' ) ),
							array( "{$alias}.{$field}" => Configure::read( 'Orientstruct.typeorientprincipale.Socioprofessionnelle' ) ),
						)
					);
					break;
				default:
					throw new InternalErrorException( 'La configuration de Cg.departement n\'est pas correcte dans le webrsa.inc' );
			}
		}

		/**
		 * Filtre par service instructeur.
		 * TODO: en fait (au moins au 66), il s'agirait de filtrer par un
		 * groupement de cantons.
		 *
		 * @param array $search
		 * @return string
		 */
		protected function _conditionServiceInstructeur( $search ) {
			$Dossier = ClassRegistry::init( 'Dossier' );

			$serviceinstructeur_id = trim( Hash::get( $search, 'Search.serviceinstructeur' ) );

			if( !empty( $serviceinstructeur_id ) ) {
				$sq = $Dossier->Suiviinstruction->sq(
					array(
						'alias' => 'suivisinstruction',
						'fields' => array( 'suivisinstruction.dossier_id' ),
						'contain' => false,
						'joins' => array(
							array_words_replace(
								$Dossier->Suiviinstruction->join( 'Serviceinstructeur', array( 'type' => 'INNER' ) ),
								array( 'Suiviinstruction' => 'suivisinstruction', 'Serviceinstructeur' => 'servicesinstructeurs' )
							)
						),
						'conditions' => array(
							'servicesinstructeurs.id' => $serviceinstructeur_id
						)
					)
				);

				return "Dossier.id IN ( {$sq} )";
			}

			return null;
		}

		/**
		 * Querydata de base qui sera utilisé dans tout le module de statistiques.
		 *
		 * Jointures (depuis Dossier) vers Detaildroitrsa, Foyer, Situationdossierrsa,
		 * Adressefoyer, Personne, Adresse, Prestation, Calculdroitrsa.
		 *
		 * Ajout des conditions venant du formulaire de recherche, pour les DEM
		 * ou CJT, dont la date d'ouverture du dossier a été fait avant la fin de
		 * l'année pour laquelle on fait des statistiques.
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _qdBase( array $search ) {
			$Dossier = ClassRegistry::init( 'Dossier' );
			$annee = Hash::get( $search, 'Search.annee' );

			$conditions = array(
				array(
					'OR' => array(
						'Adressefoyer.id IS NULL',
						'Adressefoyer.rgadr' => '01',
					)
				),
				'Prestation.rolepers' => array( 'DEM', 'CJT' ),
				'Dossier.dtdemrsa <=' => "{$annee}-12-31",
			);

			// Seulement les derniers dossiers des allocataires
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, array( 'Dossier' => array( 'dernier' => true ) ) );

			// Condition sur le service instructeur
			$conditions[] = $this->_conditionServiceInstructeur( $search );

			// Conditions sur l'adresse de l'allocataire
			$conditions = $this->conditionsAdresse( $conditions, $search, false, array() );

			// Conditions à ajouter éventuellement dans le webrsa.inc: même si
			// on s'assure que les allocataires sont soumis à DD, on ne se
			// préoccupe pas de l'état du dossier. On peut désormais le faire via
			// cette configuration.
			$conditions_base = (array)Configure::read( "Statistiqueministerielle2.conditions_base" );
			if( !empty( $conditions_base ) ) {
				$conditions = Hash::merge( $conditions_base, $conditions );
			}

			return array(
				'joins' => array(
					$Dossier->join( 'Detaildroitrsa', array( 'type' => 'INNER' ) ),
					$Dossier->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$Dossier->Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					$Dossier->Foyer->Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Dossier->Foyer->Personne->join( 'Calculdroitrsa', array( 'type' => 'INNER' ) ),
				),
				'contain' => false,
				'conditions' => $conditions,
			);
		}

		/**
		 * Querydata de base qui sera utilisé par les indicateurs d'orientation.
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _qdIndicateursOrientations( array $search ) {
			$Dossier = ClassRegistry::init( 'Dossier' );

			$querydata = $this->_qdBase( $search );

			$querydata['conditions']['Calculdroitrsa.toppersdrodevorsa'] = '1';

			$querydata['joins'][] = $Dossier->Foyer->Personne->join( 'Orientstruct', array( 'type' => 'LEFT OUTER' ) );
			$querydata['joins'][] = $Dossier->Foyer->Personne->Orientstruct->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) );

			return $querydata;
		}

		/**
		 * Querydata de base qui sera utilisé par les indicateurs de réorientation.
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _qdIndicateursReorientations( array $search ) {
			$Dossier = ClassRegistry::init( 'Dossier' );
			$annee = Hash::get( $search, 'Search.annee' );

			$querydata = $this->_qdIndicateursOrientations( $search );

			$querydata['joins'][] = array_words_replace(
				$Dossier->Foyer->Personne->join( 'Orientstruct', array( 'type' => 'LEFT OUTER' ) ),
				array( 'Orientstruct' => 'Orientstructpcd' )
			);

			$querydata['joins'][] = array_words_replace(
				$Dossier->Foyer->Personne->Orientstruct->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) ),
				array( 'Orientstruct' => 'Orientstructpcd', 'Typeorient' => 'Typeorientpcd' )
			);
			$querydata['conditions']['Orientstruct.statut_orient'] = 'Orienté';
			$querydata['conditions'][] = 'Orientstruct.date_valid IS NOT NULL';

			$querydata['conditions'][] = "Orientstruct.date_valid BETWEEN '{$annee}-01-01' AND '{$annee}-12-31'";

			$sqOrientstructpcd = $Dossier->Foyer->Personne->Orientstruct->sq(
				array(
					'alias' => 'orientsstructspcds',
					'fields' => array( 'orientsstructspcds.id' ),
					'contain' => false,
					'conditions' => array(
						'orientsstructspcds.personne_id = Personne.id',
						'orientsstructspcds.statut_orient' => 'Orienté',
						'orientsstructspcds.date_valid IS NOT NULL',
						'orientsstructspcds.date_valid < Orientstruct.date_valid',
					),
					'order' => array(
						'orientsstructspcds.date_valid DESC'
					),
					'limit' => 1
				)
			);
			$querydata['conditions'][] = "Orientstructpcd.id IN ( {$sqOrientstructpcd} )";

			return $querydata;
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _fieldsTrancheAge( array $search ) {
			$annee = Hash::get( $search, 'Search.annee' );

			return array(
				'(
					CASE
						WHEN EXTRACT( YEAR FROM AGE(TIMESTAMP\''.$annee.'-12-31\', "Personne"."dtnai" ) ) BETWEEN 0 AND 24 THEN \'0 - 24\'
						WHEN EXTRACT( YEAR FROM AGE(TIMESTAMP \''.$annee.'-12-31\', "Personne"."dtnai" ) ) BETWEEN 25 AND 29 THEN \'25 - 29\'
						WHEN EXTRACT( YEAR FROM AGE(TIMESTAMP \''.$annee.'-12-31\', "Personne"."dtnai" ) ) BETWEEN 30 AND 39 THEN \'30 - 39\'
						WHEN EXTRACT( YEAR FROM AGE(TIMESTAMP \''.$annee.'-12-31\', "Personne"."dtnai" ) ) BETWEEN 40 AND 49 THEN \'40 - 49\'
						WHEN EXTRACT( YEAR FROM AGE(TIMESTAMP \''.$annee.'-12-31\', "Personne"."dtnai" ) ) BETWEEN 50 AND 59 THEN \'50 - 59\'
						WHEN EXTRACT( YEAR FROM AGE(TIMESTAMP \''.$annee.'-12-31\', "Personne"."dtnai" ) ) >= 60 THEN \'>= 60\'
						ELSE \'NC\'
					END
				) AS "age_range"',
				'COUNT(DISTINCT(Personne.id)) AS "count"'
			);
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _fieldsSitfam( array $search ) {
			$parts = array(
				'femme' => '"Personne"."sexe" = \'2\'',
				'homme' => '"Personne"."sexe" = \'1\'',
				'en_couple' => '"Foyer"."sitfam" IN (\'MAR\', \'PAC\', \'RPA\', \'RVC\', \'RVM\', \'VIM\')',
				'seul' => '"Foyer"."sitfam" IN (\'CEL\', \'DIV\', \'ISO\', \'SEF\', \'SEL\', \'VEU\')',
				'avec_enfant' => 'EXISTS ( SELECT enfants.id FROM personnes AS enfants INNER JOIN prestations ON ( enfants.id = prestations.personne_id AND prestations.natprest = \'RSA\' ) WHERE enfants.foyer_id= "Foyer"."id" AND prestations.rolepers = \'ENF\' )',
				'rsa_majore' => 'EXISTS(
					SELECT * FROM detailsdroitsrsa
						INNER JOIN detailscalculsdroitsrsa ON ( detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id )
						WHERE
							detailsdroitsrsa.dossier_id = "Foyer"."dossier_id"
							AND detailscalculsdroitsrsa.natpf IN ( \'RCI\', \'RSI\' )
				)',
			);
			$parts['sans_enfant'] = "NOT {$parts['avec_enfant']}";
			$parts['rsa_non_majore'] = "NOT {$parts['rsa_majore']}";

			return array(
				'(
					CASE
						WHEN (
							'.$parts['homme'].'
							AND '.$parts['seul'].'
							AND '.$parts['sans_enfant'].'
						) THEN \'01 - Homme seul sans enfant\'
						WHEN (
							'.$parts['femme'].'
							AND '.$parts['seul'].'
							AND '.$parts['sans_enfant'].'
						) THEN \'02 - Femme seule sans enfant\'
						WHEN (
							'.$parts['homme'].'
							AND '.$parts['seul'].'
							AND '.$parts['avec_enfant'].'
							AND '.$parts['rsa_majore'].'
						) THEN \'03 - Homme seul avec enfant, RSA majoré\'
						WHEN (
							'.$parts['homme'].'
							AND '.$parts['seul'].'
							AND '.$parts['avec_enfant'].'
							AND '.$parts['rsa_non_majore'].'
						) THEN \'04 - Homme seul avec enfant, RSA non majoré\'
						WHEN (
							'.$parts['femme'].'
							AND '.$parts['seul'].'
							AND '.$parts['avec_enfant'].'
							AND '.$parts['rsa_majore'].'
						) THEN \'05 - Femme seule avec enfant, RSA majoré\'
						WHEN (
							'.$parts['femme'].'
							AND '.$parts['seul'].'
							AND '.$parts['avec_enfant'].'
							AND '.$parts['rsa_non_majore'].'
						) THEN \'06 - Femme seule avec enfant, RSA non majoré\'
						WHEN (
							'.$parts['homme'].'
							AND '.$parts['en_couple'].'
							AND '.$parts['sans_enfant'].'
						) THEN \'07 - Homme en couple sans enfant\'
						WHEN (
							'.$parts['femme'].'
							AND '.$parts['en_couple'].'
							AND '.$parts['sans_enfant'].'
						) THEN \'08 - Femme en couple sans enfant\'
						WHEN (
							'.$parts['homme'].'
							AND '.$parts['en_couple'].'
							AND '.$parts['avec_enfant'].'
						) THEN \'09 - Homme en couple avec enfant\'
						WHEN (
							'.$parts['femme'].'
							AND '.$parts['en_couple'].'
							AND '.$parts['avec_enfant'].'
						) THEN \'10 - Femme en couple avec enfant\'

						ELSE \'11 - Non connue\'
					END
				) AS "sitfam_range"',
				'COUNT(DISTINCT(Personne.id)) AS "count"'
			);
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _fieldsNivetu( array $search ) {
			return array(
				'(
					CASE
						WHEN "Dsp"."nivetu" IN ( \'1205\', \'1206\', \'1207\' ) THEN \'Vbis et VI\'
						WHEN "Dsp"."nivetu" IN ( \'1204\' ) THEN \'V\'
						WHEN "Dsp"."nivetu" IN ( \'1203\' ) THEN \'IV\'
						WHEN "Dsp"."nivetu" IN ( \'1201\', \'1202\') THEN \'III, II, I\'
						ELSE \'NC\'
					END
				) AS "nivetu_range"',
				'COUNT(DISTINCT(Personne.id)) AS "count"'
			);
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		protected function _fieldsAnciennete( array $search ) {
			$annee = Hash::get( $search, 'Search.annee' );

			return array(
				'(
					CASE
						WHEN "Dossier"."dtdemrsa" BETWEEN ( TIMESTAMP \''.$annee.'-12-31\' - INTERVAL \'6\' MONTH - INTERVAL \'1\' DAY ) AND ( TIMESTAMP \''.$annee.'-12-31\' ) THEN \'moins de 6 mois\'
						WHEN "Dossier"."dtdemrsa" BETWEEN ( TIMESTAMP \''.$annee.'-12-31\' - INTERVAL \'1\' YEAR - INTERVAL \'1\' DAY ) AND ( TIMESTAMP \''.$annee.'-12-31\' - INTERVAL \'6\' MONTH ) THEN \'6 mois et moins 1 an\'
						WHEN "Dossier"."dtdemrsa" BETWEEN ( TIMESTAMP \''.$annee.'-12-31\' - INTERVAL \'2\' YEAR - INTERVAL \'1\' DAY ) AND ( TIMESTAMP \''.$annee.'-12-31\' - INTERVAL \'1\' YEAR ) THEN \'1 an et moins de 2 ans\'
						WHEN "Dossier"."dtdemrsa" BETWEEN ( TIMESTAMP \''.$annee.'-12-31\' - INTERVAL \'5\' YEAR - INTERVAL \'1\' DAY ) AND ( TIMESTAMP \''.$annee.'-12-31\' - INTERVAL \'2\' YEAR ) THEN \'2 ans et moins de 5 ans\'
						WHEN "Dossier"."dtdemrsa" < ( TIMESTAMP \''.$annee.'-12-31\' - INTERVAL \'5\' YEAR ) THEN \'5 ans et plus\'
						ELSE \'NC\'
					END
				) AS "anciennete_range"',
				'COUNT(DISTINCT(Personne.id)) AS count'
			);
		}

		/**
		 * TODO: compléter Orientstruct::sqDerniere avec un second paramètre de conditions
		 * TODO: bouger dans Orientstruct
		 *
		 * @param type $personneIdFied
		 * @param type $annee
		 * @return type
		 */
		protected function _sqDerniereOrientation( $annee ) {
			$Orientstruct = ClassRegistry::init( 'Orientstruct' );
			$personneIdFied = 'Personne.id';

			return $Orientstruct->sq(
				array(
					'fields' => array(
						'orientsstructs.id'
					),
					'alias' => 'orientsstructs',
					'conditions' => array(
						"orientsstructs.personne_id = {$personneIdFied}",
						'orientsstructs.statut_orient = \'Orienté\'',
						'orientsstructs.date_valid IS NOT NULL',
						'orientsstructs.date_valid <=' => "{$annee}-12-31", // FIXME
					),
					'order' => array( 'orientsstructs.date_valid DESC' ),
					'limit' => 1
				)
			);
		}

		/**
		 *
		 * @param type $personneIdFied
		 * @param type $annee
		 * @return string
		 */
		protected function _sqPremiereOrientation() {
			$Orientstruct = ClassRegistry::init( 'Orientstruct' );
			$personneIdFied = 'Personne.id';

			return $Orientstruct->sq(
				array(
					'fields' => array(
						'orientsstructs.id'
					),
					'alias' => 'orientsstructs',
					'conditions' => array(
						"orientsstructs.personne_id = {$personneIdFied}",
						'orientsstructs.statut_orient = \'Orienté\'',
						'orientsstructs.date_valid IS NOT NULL',
					),
					'order' => array( 'orientsstructs.date_valid ASC' ),
					'limit' => 1
				)
			);
		}

		/**
		 *
		 * @param type $personneIdFied
		 * @param type $annee
		 * @return string
		 */
		protected function _sqPremierContratinsertion() {
			$Contratinsertion = ClassRegistry::init( 'Contratinsertion' );
			$personneIdFied = 'Personne.id';

			return $Contratinsertion->sq(
				array(
					'fields' => array(
						'contratsinsertion.id'
					),
					'alias' => 'contratsinsertion',
					'conditions' => array(
						"contratsinsertion.personne_id = {$personneIdFied}",
						'contratsinsertion.decision_ci = \'V\'',
						'contratsinsertion.datevalidation_ci IS NOT NULL',
					),
					'order' => array( 'contratsinsertion.datevalidation_ci ASC' ),
					'limit' => 1
				)
			);
		}

		/**
		 *
		 * @param string $name
		 * @param array $search
		 * @param array $fields
		 * @param string $group
		 * @param array $joins
		 */
		protected function _rowIndicateurOrientation( $name, array $search, array $fields, $group, array $joins = array() ) {
			$Dossier = ClassRegistry::init( 'Dossier' );
			$annee = Hash::get( $search, 'Search.annee' );
			$results = array();

			// Allocataires soumis à droits et devoirs
			$querydata = $this->_qdIndicateursOrientations( $search );
			$querydata['fields'] = $fields;
			if( !empty( $joins ) ) {
				foreach( $joins as $join ) {
					$querydata['joins'][] = $join;
				}
			}
			$querydata['group'] = $group;
			$querydata['order'] = $group;
			$tmp = $Dossier->find( 'all', $querydata );
			$results[$name]['sdd'] = Hash::combine( $tmp, "{n}.0.{$group}", '{n}.0.count' );

			// Orientation à dominante professionnelle
			$querydata = $this->_qdIndicateursOrientations( $search );
			$querydata['fields'] = $fields;
			if( !empty( $joins ) ) {
				foreach( $joins as $join ) {
					$querydata['joins'][] = $join;
				}
			}
			$querydata['group'] = $group;
			$querydata['order'] = $group;

			// On ne s'occupe que de la dernière orientation ? ?
			$sq = $this->_sqDerniereOrientation( $annee );
			$querydata['conditions'][] = array(
				"Orientstruct.id IN ( {$sq} )"
			);
			$querydata['conditions'][] = $this->_conditionsTypeorientEmploi();

			$tmp = $Dossier->find( 'all', $querydata );
			$results[$name]['orient_pro'] = Hash::combine( $tmp, "{n}.0.{$group}", '{n}.0.count' );

			// Orientation à dominante sociale
			$querydata = $this->_qdIndicateursOrientations( $search );
			$querydata['fields'] = $fields;
			if( !empty( $joins ) ) {
				foreach( $joins as $join ) {
					$querydata['joins'][] = $join;
				}
			}
			$querydata['group'] = $group;
			$querydata['order'] = $group;

			// FIXME: est-ce logique de ne s'occuper que de la dernière orientation ? ?
			$sq = $this->_sqDerniereOrientation( $annee );
			$querydata['conditions'][] = array(
				"Orientstruct.id IN ( {$sq} )"
			);

			$querydata['conditions'][] = $this->_conditionsTypeorientSocial();

			$tmp = $Dossier->find( 'all', $querydata );
			$results[$name]['orient_sociale'] = Hash::combine( $tmp, "{n}.0.{$group}", '{n}.0.count' );

			// En attente d'orientation
			$querydata = $this->_qdIndicateursOrientations( $search );
			$querydata['fields'] = $fields;
			if( !empty( $joins ) ) {
				foreach( $joins as $join ) {
					$querydata['joins'][] = $join;
				}
			}
			$querydata['group'] = $group;
			$querydata['order'] = $group;

			// TODO: utiliser la fonction à mettre dans orientstruct ?
			$sq = $Dossier->Foyer->Personne->Orientstruct->sq(
				array(
					'alias' => 'orientsstructs',
					'fields' => array( 'orientsstructs.id' ),
					'contain' => false,
					'conditions' => array(
						'Personne.id = orientsstructs.personne_id',
						'orientsstructs.statut_orient' => 'Orienté',
						'orientsstructs.date_valid <=' => "{$annee}-12-31", // TODO: between ?
					),
				)
			);
			$querydata['conditions'][] = "NOT EXISTS ( {$sq} )";

			$tmp = $Dossier->find( 'all', $querydata );
			$results[$name]['attente_orient'] = Hash::combine( $tmp, "{n}.0.{$group}", '{n}.0.count' );

			return $results;
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		public function indicateursOrientations( array $search ) {
			$Dossier = ClassRegistry::init( 'Dossier' );

			return Hash::merge(
				$this->_rowIndicateurOrientation(
					'Indicateurage',
					$search,
					$this->_fieldsTrancheAge( $search ),
					'age_range'
				),
				$this->_rowIndicateurOrientation(
					'Indicateursitfam',
					$search,
					$this->_fieldsSitfam( $search ),
					'sitfam_range'
				),
				$this->_rowIndicateurOrientation(
					'Indicateurnivetu',
					$search,
					$this->_fieldsNivetu( $search ),
					'nivetu_range',
					// TODO: function
					array(
						$Dossier->Foyer->Personne->join(
							'Dsp',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Dsp.id IN ( '.$Dossier->Foyer->Personne->Dsp->sqDerniereDsp().' )'
								)
							)
						)
					)
				),
				$this->_rowIndicateurOrientation(
					'Indicateuranciennete',
					$search,
					$this->_fieldsAnciennete( $search ),
					'anciennete_range'
				)
			);
		}

		/**
		 * TODO: $baseQuerydata
		 *
		 * @param string $name
		 * @param array $search
		 * @param array $fields
		 * @param string $group
		 * @param array $joins
		 */
		protected function _rowIndicateurReorientation( $name, array $search, array $fields, $group, array $joins = array() ) {
			$Dossier = ClassRegistry::init( 'Dossier' );
			$annee = Hash::get( $search, 'Search.annee' );
			$results = array();

			// Allocataires soumis à droits et devoirs
			$querydata = $this->_qdIndicateursReorientations( $search );
			$querydata['fields'] = $fields;
			if( !empty( $joins ) ) {
				foreach( $joins as $join ) {
					$querydata['joins'][] = $join;
				}
			}

			// Qui ont été réorienté(e)s
			$querydata['conditions'][] = array(
				'OR' => array(
					array(
						$this->_conditionsTypeorientSocial(),
						array_words_replace(
							$this->_conditionsTypeorientEmploi(),
							array( 'Typeorient' => 'Typeorientpcd' )
						),
					),
					array(
						$this->_conditionsTypeorientEmploi(),
						array_words_replace(
							$this->_conditionsTypeorientSocial(),
							array( 'Typeorient' => 'Typeorientpcd' )
						),
					),
				)
			);
			$querydata['group'] = $group;
			$querydata['order'] = $group;
			$tmp = $Dossier->find( 'all', $querydata );
			$results[$name]['sdd'] = Hash::combine( $tmp, "{n}.0.{$group}", '{n}.0.count' );

			// Orientation à dominante professionnelle vers orientation à dominante sociale
			$querydata = $this->_qdIndicateursReorientations( $search );
			$querydata['fields'] = $fields;
			if( !empty( $joins ) ) {
				foreach( $joins as $join ) {
					$querydata['joins'][] = $join;
				}
			}
			$querydata['group'] = $group;
			$querydata['order'] = $group;

			// On ne s'occupe que de la dernière orientation
			$sq = $this->_sqDerniereOrientation( $annee );
			$querydata['conditions'][] = array(
				"Orientstruct.id IN ( {$sq} )"
			);
			// TODO: factoriser
			$querydata['conditions'][] = array(
				array(
					$this->_conditionsTypeorientSocial(),
					array_words_replace(
						$this->_conditionsTypeorientEmploi(),
						array( 'Typeorient' => 'Typeorientpcd' )
					),
				)
			);

			$tmp = $Dossier->find( 'all', $querydata );
			$results[$name]['orient_pro'] = Hash::combine( $tmp, "{n}.0.{$group}", '{n}.0.count' );

			// Orientation à dominante sociale vers orientation à dominante professionnelle
			$querydata = $this->_qdIndicateursReorientations( $search );
			$querydata['fields'] = $fields;
			if( !empty( $joins ) ) {
				foreach( $joins as $join ) {
					$querydata['joins'][] = $join;
				}
			}
			$querydata['group'] = $group;
			$querydata['order'] = $group;

			// On ne s'occupe que de la dernière orientation ? ?
			$sq = $this->_sqDerniereOrientation( $annee );
			$querydata['conditions'][] = array(
				"Orientstruct.id IN ( {$sq} )"
			);

			// TODO: factoriser
			$querydata['conditions'][] = array(
				array(
					$this->_conditionsTypeorientEmploi(),
					array_words_replace(
						$this->_conditionsTypeorientSocial(),
						array( 'Typeorient' => 'Typeorientpcd' )
					),
				)
			);

			$tmp = $Dossier->find( 'all', $querydata );
			$results[$name]['orient_sociale'] = Hash::combine( $tmp, "{n}.0.{$group}", '{n}.0.count' );

			return $results;
		}

		/**
		 * @param array $search
		 * @return type
		 */
		public function indicateursReorientations( array $search ) {
			$Dossier = ClassRegistry::init( 'Dossier' );

			return Hash::merge(
				$this->_rowIndicateurReorientation(
					'Indicateurage',
					$search,
					$this->_fieldsTrancheAge( $search ),
					'age_range'
				),
				$this->_rowIndicateurReorientation(
					'Indicateursitfam',
					$search,
					$this->_fieldsSitfam( $search ),
					'sitfam_range'
				),
				$this->_rowIndicateurReorientation(
					'Indicateurnivetu',
					$search,
					$this->_fieldsNivetu( $search ),
					'nivetu_range',
					// TODO: function
					array(
						$Dossier->Foyer->Personne->join(
							'Dsp',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'Dsp.id IN ( '.$Dossier->Foyer->Personne->Dsp->sqDerniereDsp().' )'
								)
							)
						)
					)
				),
				$this->_rowIndicateurReorientation(
					'Indicateuranciennete',
					$search,
					$this->_fieldsAnciennete( $search ),
					'anciennete_range'
				)
			);
		}

		/**
		 * TODO: dans Dossierep ?
		 *
		 *	- au 58: nonorientationsproseps58
		 *	- FIXME: au 66, on passe par le bilan de parcours (voir cases / radios)
		 *	- au 93: nonorientationsproseps93
		 *
		 * @return string
		 * @throws InternalErrorException
		 */
		protected function _modeleNonOrientationProEp() {
			$departement = Configure::read( 'Cg.departement' );

			switch( $departement ) {
				case 58:
					return 'Nonorientationproep58';
					break;
				case 66:
					return 'Nonorientationproep66';
					break;
				case 93:
					return 'Nonorientationproep93';
					break;
				default:
					throw new InternalErrorException( 'La configuration de Cg.departement n\'est pas correcte dans le webrsa.inc' );
			}
		}

		protected function _qdIndicateursMotifsReorientationEp( array $search ) {
			$Dossier = ClassRegistry::init( 'Dossier' );
			$annee = Hash::get( $search, 'Search.annee' );
			$modeleNonorientationproep = $this->_modeleNonOrientationProEp();

			$querydata = $this->_qdBase( $search );

			$querydata['conditions'] = Hash::merge(
				$querydata['conditions'],
				array(
					'Dossierep.themeep' => Inflector::tableize( $modeleNonorientationproep ),
					'Passagecommissionep.etatdossierep' => 'traite',
					'Commissionep.etatcommissionep' => 'traite',
					"Commissionep.dateseance BETWEEN '{$annee}-01-01' AND '{$annee}-12-31'"
				)
			);

			$querydata['fields'] = array( 'COUNT(DISTINCT(Personne.id)) AS "count"' );

			$querydata['joins'][] = $Dossier->Foyer->Personne->join( 'Dossierep', array( 'type' => 'INNER' ) );
			$querydata['joins'][] = $Dossier->Foyer->Personne->Dossierep->join( 'Passagecommissionep', array( 'type' => 'INNER' ) );
			$querydata['joins'][] = $Dossier->Foyer->Personne->Dossierep->Passagecommissionep->join( 'Commissionep', array( 'type' => 'INNER' ) );

			return $querydata;
		}

		protected function _indicateursMotifsReorientationEp( array $search ) {
			$Dossier = ClassRegistry::init( 'Dossier' );
			$modeleNonorientationproep = $this->_modeleNonOrientationProEp();
			$results = array();

			// Réorientations en EP
			$querydataEp = $this->_qdIndicateursMotifsReorientationEp( $search );

			$results['Indicateurep']['total'] = Hash::get( $Dossier->find( 'all', $querydataEp ), '0.0.count' );

			// Passage en EP
			$querydataPassage = $querydataEp;

			$modeleDecision = Inflector::camelize( 'decision'.Inflector::underscore( $modeleNonorientationproep ) );
			$querydataPassage['joins'][] = $Dossier->Foyer->Personne->Dossierep->Passagecommissionep->join( $modeleDecision, array( 'type' => 'INNER' ) );
			$querydataPassage['joins'][] = $Dossier->Foyer->Personne->Dossierep->Passagecommissionep->{$modeleDecision}->join( 'Typeorient', array( 'type' => 'INNER' ) );

			// Seulement la dernière décision (ep/cg)
			$sq = $Dossier->Foyer->Personne->Dossierep->Passagecommissionep->{$modeleDecision}->sq(
				array(
					'alias' => 'decisions',
					'fields' => array( 'decisions.id' ),
					'contain' => false,
					'conditions' => array(
						'decisions.passagecommissionep_id = Passagecommissionep.id'
					),
					'order' => array( "{$modeleDecision}.etape ASC" ),
					'limit' => 1
				)
			);
			$querydataPassage['conditions'][] = "{$modeleDecision}.id IN ( {$sq} )";

			// Passage en EP, maintien en orientation à dominante sociale
			$querydataMaintien = $querydataPassage;
			$querydataMaintien['conditions'][] = $this->_conditionsTypeorientSocial();

			$results['Indicateurep']['maintien'] = Hash::get( $Dossier->find( 'all', $querydataMaintien ), '0.0.count' );

			// Passage en EP, réorientation vers une dominante professionnelle
			$querydataReorientation = $querydataPassage;
			$querydataReorientation['conditions'][] = $this->_conditionsTypeorientEmploi();

			$results['Indicateurep']['reorientation'] = Hash::get( $Dossier->find( 'all', $querydataReorientation ), '0.0.count' );

			return $results;
		}

		public function indicateursMotifsReorientation( array $search ) {
			$Dossier = ClassRegistry::init( 'Dossier' );
			$annee = Hash::get( $search, 'Search.annee' );
			$results = array();

			$querydata = $this->_qdIndicateursReorientations( $search );
			$querydata['fields'] = array( 'COUNT(DISTINCT(Personne.id)) AS "count"' );

			// On ne s'occupe que de la dernière orientation
			$sq = $this->_sqDerniereOrientation( $annee );
			$querydata['conditions'][] = array(
				"Orientstruct.id IN ( {$sq} )"
			);
			// TODO: factoriser
			$querydata['conditions'][] = array(
				array(
					$this->_conditionsTypeorientSocial(),
					array_words_replace(
						$this->_conditionsTypeorientEmploi(),
						array( 'Typeorient' => 'Typeorientpcd' )
					),
				)
			);

			$results['Indicateursocial']['total'] = Hash::get( $Dossier->find( 'all', $querydata ), '0.0.count' );

			// Total par catégorie de motifs
			$conditionsAutre = array( 'NOT' => array() );
			$motifs = array( 'orientation_initiale_inadaptee', 'changement_situation_allocataire' );
			foreach( $motifs as $motif ) {
				$conditions = (array)Configure::read( "Statistiqueministerielle2.conditions_indicateurs_motifs_reorientation.{$motif}" );
				if( !empty( $conditions ) ) {
					$conditionsAutre['NOT'][] = $conditions;
					$querydataMotif = $querydata;
					$querydataMotif['conditions'][] = $conditions;
					$results['Indicateursocial'][$motif] = Hash::get( $Dossier->find( 'all', $querydataMotif ), '0.0.count' );
				}
				else {
					$results['Indicateursocial'][$motif] = null;
				}
			}

			$querydataAutre = $querydata;
			$querydataAutre['conditions'][] = $conditions;
			$results['Indicateursocial']['autre'] = Hash::get( $Dossier->find( 'all', $querydataAutre ), '0.0.count' );

			return Hash::merge(
				$results,
				$this->_indicateursMotifsReorientationEp( $search )
			);
		}

		/**
		 * Retourne les indicateurs d'organismes suivant les critères envoyés en
		 * paramètre.
		 *
		 * @param array $search
		 * @return array
		 */
		public function indicateursOrganismes( array $search ) {
			$Dossier = ClassRegistry::init( 'Dossier' );
			$annee = Hash::get( $search, 'Search.annee' );
			$results = array();

			$querydata = $this->_qdIndicateursOrientations( $search );
			$querydata['fields'] = array( 'COUNT(DISTINCT(Personne.id)) AS "count"' );

			// Dont l'orientation est la dernière ou n'existe pas
			$sqDerniereOrientation = $this->_sqDerniereOrientation( $annee );
			$querydata['conditions'][] = array(
				'OR' => array(
					'Orientstruct.id IS NULL',
					"Orientstruct.id IN ( {$sqDerniereOrientation} )"
				)
			);

			$results['Indicateurorganisme']['total'] = Hash::get( $Dossier->find( 'all', $querydata ), '0.0.count' );

			$querydataAttente = $querydata;
			// TODO: utiliser la fonction à mettre dans orientstruct ?
			$sq = $Dossier->Foyer->Personne->Orientstruct->sq(
				array(
					'alias' => 'orientsstructs',
					'fields' => array( 'orientsstructs.id' ),
					'contain' => false,
					'conditions' => array(
						'Personne.id = orientsstructs.personne_id',
						'orientsstructs.statut_orient' => 'Orienté',
						'orientsstructs.date_valid <=' => "{$annee}-12-31", // TODO: between ?
					),
				)
			);
			$querydataAttente['conditions'][] = "NOT EXISTS ( {$sq} )";
			$results['Indicateurorganisme']['attente_orient'] = Hash::get( $Dossier->find( 'all', $querydataAttente ), '0.0.count' );

			// On ne s'occupe que du dernier référent de parcours
			$querydata['joins'][] = $Dossier->Foyer->Personne->join( 'PersonneReferent', array( 'type' => 'INNER' ) );
			$querydata['conditions'][] = array(
				'PersonneReferent.dddesignation <=' => "{$annee}-12-31",
				'OR' => array(
					'PersonneReferent.dfdesignation IS NULL',
					'PersonneReferent.dfdesignation >=' => "{$annee}-12-31",
				)
			);
			$querydata['joins'][] = $Dossier->Foyer->Personne->PersonneReferent->join( 'Referent', array( 'type' => 'INNER' ) );

			$querydata['joins'][] = array_words_replace(
					$Dossier->Foyer->Personne->PersonneReferent->Referent->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
					array( 'Structurereferente' => 'Structurereferentereferent' )
			);

			$querydata['joins'][] = array_words_replace(
					$Dossier->Foyer->Personne->PersonneReferent->Referent->Structurereferente->join( 'Typeorient', array( 'type' => 'INNER' ) ),
					array( 'Structurereferente' => 'Structurereferentereferent', 'Typeorient' => 'Typeorientreferent' )
			);

			$organismes = array(
				'pole_emploi',
				'oppp_autre_pole_emploi',
				'entreprise_travail_temporaire',
				'organisme_creation_developpement_entreprise',
				'iae',
				'autre_professionnel',
				'service_departement',
				'service_departement_professionnel',
				'service_departement_social',
				'caf_msa',
				'ccas_cias',
				'autres',
			);
			foreach( $organismes as $organisme ) {
				$conditions = (array)Configure::read( "Statistiqueministerielle2.conditions_indicateurs_organismes.{$organisme}" );
				if( !empty( $conditions ) ) {
					$querydataOrganisme = $querydata;
					$querydataOrganisme['conditions'][] = $conditions;
					$results['Indicateurorganisme'][$organisme] = Hash::get( $Dossier->find( 'all', $querydataOrganisme ), '0.0.count' );
				}
				else {
					$results['Indicateurorganisme'][$organisme] = null;
				}
			}

			return $results;
		}

		/**
		 *
		 * @param array $search
		 * @param string $type_cer
		 * @param array $querydataTypecerOriginal
		 * @param integer $nbMoisTranche1
		 * @param integer $nbMoisTranche2
		 * @return array
		 */
		protected function _indicateursDelaisParTypeCer( $search, $type_cer, $querydataTypecerOriginal, $nbMoisTranche1, $nbMoisTranche2 ) {
			$Dossier = ClassRegistry::init( 'Dossier' );
			$annee = Hash::get( $search, 'Search.annee' );
			$results = array();

			$conditions = (array)Configure::read( "Statistiqueministerielle2.conditions_types_cers.{$type_cer}" );

			if( $type_cer == 'cer_pro_social' ) {
				$conditions[] = $this->_conditionsTypeorientSocial( 'Typeorientcer' );
			}
			else if( $type_cer == 'cer_pro' ) {
				$conditions = $this->_conditionsTypeorientEmploi( 'Typeorientcer' );
				$conditionsPpae = (array)Configure::read( "Statistiqueministerielle2.conditions_types_cers.ppae" );
				if( !empty( $conditionsPpae ) ) {
					$conditions[] = $conditionsPpae;
				}
			}

			if( !empty( $conditions ) ) {
				$querydataTypecer = $querydataTypecerOriginal;

				$querydataTypecer['conditions'][] = $conditions;

				// Délai moyen
				$querydataDelaimoyen = $querydataTypecer;
				$querydataDelaimoyen['fields'] = array( 'AVG( "Contratinsertion"."date_saisi_ci" - "Orientstruct"."date_valid" ) AS "count"' );
				$results['Indicateurdelai']["{$type_cer}_delai_moyen"] = Hash::get( $Dossier->find( 'all', $querydataDelaimoyen ), '0.0.count' );

				// Nombre moyen au cours de l'année
				$querydataTypecer['fields'] = array( 'COUNT(Contratinsertion.id) AS "count"' );
				$results['Indicateurdelai']["{$type_cer}_nombre_moyen"] = Hash::get( $Dossier->find( 'all', $querydataTypecer ), '0.0.count' );

				// Dont contrats signés dans le mois après la décision d'orientation
				$querydataTypecerMois = $querydataTypecer;
				$querydataTypecerMois['conditions'][] = array(
					"AGE( Contratinsertion.date_saisi_ci, Orientstruct.date_valid ) <= INTERVAL '{$nbMoisTranche1} month'"
				);
				$results['Indicateurdelai']["{$type_cer}_delai_{$nbMoisTranche1}_mois"] = Hash::get( $Dossier->find( 'all', $querydataTypecerMois ), '0.0.count' );

				// Dont contrats signés entre un mois et trois mois après la décision d'orientation
				$querydataTypecerMois = $querydataTypecer;
				$querydataTypecerMois['conditions'][] = array(
					"AGE( Contratinsertion.date_saisi_ci, Orientstruct.date_valid ) > INTERVAL '{$nbMoisTranche1} month'",
					"AGE( Contratinsertion.date_saisi_ci, Orientstruct.date_valid ) <= INTERVAL '{$nbMoisTranche2} months'",
				);
				$results['Indicateurdelai']["{$type_cer}_delai_{$nbMoisTranche1}_{$nbMoisTranche2}_mois"] = Hash::get( $Dossier->find( 'all', $querydataTypecerMois ), '0.0.count' );

				// Dont contrats signés plus de trois mois après la décision d'orientation
				$querydataTypecerMois = $querydataTypecer;
				$querydataTypecerMois['conditions'][] = array(
					"AGE( Contratinsertion.date_saisi_ci, Orientstruct.date_valid ) > INTERVAL '{$nbMoisTranche2} months'",
				);
				$results['Indicateurdelai']["{$type_cer}_delai_plus_{$nbMoisTranche2}_mois"] = Hash::get( $Dossier->find( 'all', $querydataTypecerMois ), '0.0.count' );
			}
			else {
				$results['Indicateurdelai']["{$type_cer}_delai_moyen"] = null;
				$results['Indicateurdelai']["{$type_cer}_nombre_moyen"] = null;
				$results['Indicateurdelai']["{$type_cer}_delai_{$nbMoisTranche1}_mois"] = null;
				$results['Indicateurdelai']["{$type_cer}_delai_{$nbMoisTranche1}_{$nbMoisTranche2}_mois"] = null;
				$results['Indicateurdelai']["{$type_cer}_delai_plus_{$nbMoisTranche2}_mois"] = null;
			}

			return $results;
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		public function indicateursDelais( array $search ) {
			$Dossier = ClassRegistry::init( 'Dossier' );
			$annee = Hash::get( $search, 'Search.annee' );
			$results = array();

			$querydata = $this->_qdIndicateursOrientations( $search );

			// Délai moyen pour la première orientation
			$querydataOrientation = $querydata;
			$querydataOrientation['fields'] = array( 'AVG( DATE_PART( \'DAYS\', "Orientstruct"."date_valid" - DATE_TRUNC( \'MONTH\', "Dossier"."dtdemrsa" ) ) ) AS "count"' );

			$sqDerniereOrientation = $this->_sqPremiereOrientation( $annee );
			$querydataOrientation['conditions'][] = array(
				// Dont l'orientation est la première
				"Orientstruct.id IN ( {$sqDerniereOrientation} )",
				// Validation de l'orientation dans l'année
				'Orientstruct.date_valid >=' => date( "{$annee}-01-01" ),
				'Orientstruct.date_valid <=' => date( "{$annee}-12-31" ),
			);

			$results['Indicateurdelai']['delai_moyen_orientation'] = Hash::get( $Dossier->find( 'all', $querydataOrientation ), '0.0.count' );

			// Délai moyen pour la signature du premier CER
			$querydataSignature = $querydata;

			$querydataSignature['joins'][] = $Dossier->Foyer->Personne->join( 'Contratinsertion', array( 'type' => 'INNER' ) );

			$sqPremierContratinsertion = $this->_sqPremierContratinsertion();
			$querydataSignature['conditions'][] = array(
				// Dont le CER est le premier
				"Contratinsertion.id IN ( {$sqPremierContratinsertion} )",
				// Signature du CER dans l'année
				'Contratinsertion.date_saisi_ci >=' => date( "{$annee}-01-01" ),
				'Contratinsertion.date_saisi_ci <=' => date( "{$annee}-12-31" ),
			);

			$querydataSignature['fields'] = array( 'AVG("Contratinsertion"."datevalidation_ci" - "Orientstruct"."date_valid") AS "count"' );
			$results['Indicateurdelai']['delai_moyen_signature'] = Hash::get( $Dossier->find( 'all', $querydataSignature ), '0.0.count' );

			// Préparation du querydata par type de CER
			$querydataTypecerOriginal = $querydataSignature;
			$querydataTypecerOriginal['joins'][] = array_words_replace(
					$Dossier->Foyer->Personne->Contratinsertion->join( 'Structurereferente', array( 'type' => 'INNER' ) ),
					array( 'Structurereferente' => 'Structurereferentecer' )
			);

			$querydataTypecerOriginal['joins'][] = array_words_replace(
					$Dossier->Foyer->Personne->Contratinsertion->Structurereferente->join( 'Typeorient', array( 'type' => 'INNER' ) ),
					array( 'Structurereferente' => 'Structurereferentecer', 'Typeorient' => 'Typeorientcer' )
			);

			foreach( array_keys( $this->types_cers ) as $type_cer ) {
				$results = Hash::merge(
					$results,
					$this->_indicateursDelaisParTypeCer(
						$search,
						$type_cer,
						$querydataTypecerOriginal,
						$this->types_cers[$type_cer]['nbMoisTranche1'],
						$this->types_cers[$type_cer]['nbMoisTranche2']
					)
				);
			}

			return $results;
		}

		/**
		 *
		 * @param array $search
		 * @return array
		 */
		public function indicateursCaracteristiquesContrats( array $search ) {
			$Dossier = ClassRegistry::init( 'Dossier' );
			$annee = Hash::get( $search, 'Search.annee' );
			$results = array();

			$querydata = $this->_qdBase( $search );

			// Partie nombres

			// En cours de validité au 31 décembre
			$querydata['conditions']['Contratinsertion.decision_ci'] = 'V';
			$querydata['conditions'][] = array(
				'Contratinsertion.dd_ci <=' => "{$annee}-12-31",
				'Contratinsertion.df_ci >=' => "{$annee}-12-31",
			);

			$querydata['fields'] = array( 'COUNT( "Contratinsertion"."id" ) AS "Contratinsertion__count"' );

			$querydata['joins'][] = $Dossier->Foyer->Personne->join( 'Contratinsertion', array( 'type' => 'LEFT OUTER' ) );
			$querydata['joins'][] = $Dossier->Foyer->Personne->Contratinsertion->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) );
			$querydata['joins'][] = $Dossier->Foyer->Personne->Contratinsertion->Structurereferente->join( 'Typeorient', array( 'type' => 'LEFT OUTER' ) );

			// Pour chacune des lignes
			$categories_cers_conditionnees = array(
				'contrat_rmi',
				'cer_experimental',
				'ppae'
			);
			$categories_cers = array(
				'contrat_rmi' => array(),
				'cer_experimental' => array(),
				'cer' => array(),
				'ppae' => array(),
				'cer_pro' => $this->_conditionsTypeorientEmploi(),
				'cer_social_pro' => $this->_conditionsTypeorientSocial(),
			);

			// Pour chacune des colonnes
			foreach( Hash::normalize( $categories_cers ) as $categorie_cer => $conditionsCategorie ) {
				$conditions = (array)Configure::read( "Statistiqueministerielle2.conditions_caracteristiques_contrats.{$categorie_cer}" );

				if( !empty( $conditions ) || !in_array( $categorie_cer, $categories_cers_conditionnees ) ) {
					$querydataTotal = $querydata;
					$querydataTotal['conditions'] = Hash::merge(
						$querydataTotal['conditions'],
						$conditions,
						$conditionsCategorie
					);
					$results['Indicateurcaracteristique']["{$categorie_cer}_total"] = Hash::get( $Dossier->find( 'all', $querydataTotal ), '0.Contratinsertion.count' );

					$querydataChampDd = $querydata;
					$querydataChampDd['conditions'] = Hash::merge(
						$querydataChampDd['conditions'],
						$conditions,
						$conditionsCategorie
					);
					$querydataChampDd['conditions']['Calculdroitrsa.toppersdrodevorsa'] = '1';
					$results['Indicateurcaracteristique']["{$categorie_cer}_droitsdevoirs"] = Hash::get( $Dossier->find( 'all', $querydataChampDd ), '0.Contratinsertion.count' );

					$querydataHorsChampDd = $querydata;
					$querydataHorsChampDd['conditions'] = Hash::merge(
						$querydataHorsChampDd['conditions'],
						$conditions,
						$conditionsCategorie
					);
					$querydataHorsChampDd['conditions']['Calculdroitrsa.toppersdrodevorsa <>'] = '1';
					$results['Indicateurcaracteristique']["{$categorie_cer}_horsdroitsdevoirs"] = Hash::get( $Dossier->find( 'all', $querydataHorsChampDd ), '0.Contratinsertion.count' );

				}
			}

			// Partie durées, pour chacune des colonnes
			foreach( Hash::normalize( $categories_cers ) as $categorie_cer => $conditionsCategorie ) {
				if( in_array( $categorie_cer, array( 'cer_pro', 'cer_social_pro' ) ) ) {
					foreach( $this->durees_cers as $duree_cer => $conditionsDureescers ) {
						$querydataTotal = $querydata;
						$querydataTotal['conditions'] = Hash::merge(
							$querydataTotal['conditions'],
							$conditionsCategorie,
							$conditionsDureescers
						);
						$results['Indicateurcaracteristique']["{$categorie_cer}_{$duree_cer}_total"] = Hash::get( $Dossier->find( 'all', $querydataTotal ), '0.Contratinsertion.count' );

						$querydataChampDd = $querydata;
						$querydataChampDd['conditions'] = Hash::merge(
							$querydataChampDd['conditions'],
							$conditionsCategorie,
							$conditionsDureescers
						);
						$querydataChampDd['conditions']['Calculdroitrsa.toppersdrodevorsa'] = '1';
						$results['Indicateurcaracteristique']["{$categorie_cer}_{$duree_cer}_droitsdevoirs"] = Hash::get( $Dossier->find( 'all', $querydataChampDd ), '0.Contratinsertion.count' );

						$querydataHorsChampDd = $querydata;
						$querydataHorsChampDd['conditions'] = Hash::merge(
							$querydataHorsChampDd['conditions'],
							$conditionsCategorie,
							$conditionsDureescers
						);
						$querydataHorsChampDd['conditions']['Calculdroitrsa.toppersdrodevorsa <>'] = '1';
						$results['Indicateurcaracteristique']["{$categorie_cer}_{$duree_cer}_horsdroitsdevoirs"] = Hash::get( $Dossier->find( 'all', $querydataHorsChampDd ), '0.Contratinsertion.count' );
					}
				}
			}

			return $results;
		}
	}
?>