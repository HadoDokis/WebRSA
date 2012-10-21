<?php
	/**
	 * Code source de la classe Cohortereferent93Test.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cohortereferent93Test réalise les tests unitaires du modèle Cohortereferent93.
	 *
	 * @package app.Test.Case.Model
	 */
	class Cohortereferent93Test extends CakeTestCase
	{
		/**
		 * Fixtures associated with this test case
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Adresse',
			'app.Adressefoyer',
			'app.Calculdroitrsa',
			'app.Contratinsertion',
			'app.Dossier',
			'app.Foyer',
			'app.Orientstruct',
			'app.Personne',
			'app.PersonneReferent',
			'app.Prestation',
			'app.Situationdossierrsa',
		);

		protected $_querydatas = array(
			'affecter' => array(
				'fields' => array(
					'Personne.id',
					'Personne.foyer_id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.nomnai',
					'Personne.prenom2',
					'Personne.prenom3',
					'Personne.nomcomnai',
					'Personne.dtnai',
					'Personne.rgnai',
					'Personne.typedtnai',
					'Personne.nir',
					'Personne.topvalec',
					'Personne.sexe',
					'Personne.nati',
					'Personne.dtnati',
					'Personne.pieecpres',
					'Personne.idassedic',
					'Personne.numagenpoleemploi',
					'Personne.dtinscpoleemploi',
					'Personne.numfixe',
					'Personne.numport',
					'Personne.haspiecejointe',
					'Personne.email',
					'Calculdroitrsa.id',
					'Calculdroitrsa.personne_id',
					'Calculdroitrsa.mtpersressmenrsa',
					'Calculdroitrsa.mtpersabaneursa',
					'Calculdroitrsa.toppersdrodevorsa',
					'Contratinsertion.id',
					'Contratinsertion.personne_id',
					'Contratinsertion.structurereferente_id',
					'Contratinsertion.typocontrat_id',
					'Contratinsertion.dd_ci',
					'Contratinsertion.df_ci',
					'Contratinsertion.diplomes',
					'Contratinsertion.form_compl',
					'Contratinsertion.expr_prof',
					'Contratinsertion.aut_expr_prof',
					'Contratinsertion.rg_ci',
					'Contratinsertion.actions_prev',
					'Contratinsertion.obsta_renc',
					'Contratinsertion.service_soutien',
					'Contratinsertion.pers_charg_suivi',
					'Contratinsertion.objectifs_fixes',
					'Contratinsertion.engag_object',
					'Contratinsertion.sect_acti_emp',
					'Contratinsertion.emp_occupe',
					'Contratinsertion.duree_hebdo_emp',
					'Contratinsertion.nat_cont_trav',
					'Contratinsertion.duree_cdd',
					'Contratinsertion.duree_engag',
					'Contratinsertion.nature_projet',
					'Contratinsertion.observ_ci',
					'Contratinsertion.decision_ci',
					'Contratinsertion.datevalidation_ci',
					'Contratinsertion.date_saisi_ci',
					'Contratinsertion.lieu_saisi_ci',
					'Contratinsertion.emp_trouv',
					'Contratinsertion.forme_ci',
					'Contratinsertion.commentaire_action',
					'Contratinsertion.raison_ci',
					'Contratinsertion.aviseqpluri',
					'Contratinsertion.sitfam_ci',
					'Contratinsertion.sitpro_ci',
					'Contratinsertion.observ_benef',
					'Contratinsertion.referent_id',
					'Contratinsertion.avisraison_ci',
					'Contratinsertion.type_demande',
					'Contratinsertion.num_contrat',
					'Contratinsertion.typeinsertion',
					'Contratinsertion.bilancontrat',
					'Contratinsertion.engag_object_referent',
					'Contratinsertion.outilsmobilises',
					'Contratinsertion.outilsamobiliser',
					'Contratinsertion.niveausalaire',
					'Contratinsertion.zonegeographique_id',
					'Contratinsertion.autreavisradiation',
					'Contratinsertion.autreavissuspension',
					'Contratinsertion.datesuspensionparticulier',
					'Contratinsertion.dateradiationparticulier',
					'Contratinsertion.faitsuitea',
					'Contratinsertion.positioncer',
					'Contratinsertion.created',
					'Contratinsertion.modified',
					'Contratinsertion.current_action',
					'Contratinsertion.haspiecejointe',
					'Contratinsertion.avenant_id',
					'Contratinsertion.sitfam',
					'Contratinsertion.typeocclog',
					'Contratinsertion.persacharge',
					'Contratinsertion.objetcerprecautre',
					'Contratinsertion.motifannulation',
					'Contratinsertion.datedecision',
					'Contratinsertion.datenotification',
					'Contratinsertion.actioncandidat_id',
					'Orientstruct.id',
					'Orientstruct.personne_id',
					'Orientstruct.typeorient_id',
					'Orientstruct.structurereferente_id',
					'Orientstruct.propo_algo',
					'Orientstruct.valid_cg',
					'Orientstruct.date_propo',
					'Orientstruct.date_valid',
					'Orientstruct.statut_orient',
					'Orientstruct.date_impression',
					'Orientstruct.daterelance',
					'Orientstruct.statutrelance',
					'Orientstruct.date_impression_relance',
					'Orientstruct.referent_id',
					'Orientstruct.etatorient',
					'Orientstruct.rgorient',
					'Orientstruct.structureorientante_id',
					'Orientstruct.referentorientant_id',
					'Orientstruct.user_id',
					'Orientstruct.haspiecejointe',
					'Orientstruct.origine',
					'Prestation.personne_id',
					'Prestation.natprest',
					'Prestation.rolepers',
					'Prestation.topchapers',
					'Prestation.id',
					'Dossier.id',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.dtdemrmi',
					'Dossier.numdepinsrmi',
					'Dossier.typeinsrmi',
					'Dossier.numcominsrmi',
					'Dossier.numagrinsrmi',
					'Dossier.numdosinsrmi',
					'Dossier.numcli',
					'Dossier.numorg',
					'Dossier.fonorg',
					'Dossier.matricule',
					'Dossier.statudemrsa',
					'Dossier.typeparte',
					'Dossier.ideparte',
					'Dossier.fonorgcedmut',
					'Dossier.numorgcedmut',
					'Dossier.matriculeorgcedmut',
					'Dossier.ddarrmut',
					'Dossier.codeposanchab',
					'Dossier.fonorgprenmut',
					'Dossier.numorgprenmut',
					'Dossier.dddepamut',
					'Dossier.detaildroitrsa_id',
					'Dossier.avispcgdroitrsa_id',
					'Dossier.organisme_id',
					'Adresse.id',
					'Adresse.numvoie',
					'Adresse.typevoie',
					'Adresse.nomvoie',
					'Adresse.complideadr',
					'Adresse.compladr',
					'Adresse.lieudist',
					'Adresse.numcomrat',
					'Adresse.numcomptt',
					'Adresse.codepos',
					'Adresse.locaadr',
					'Adresse.pays',
					'Adresse.canton',
					'Adresse.typeres',
					'Adresse.topresetr',
					'Adresse.foyerid',
					'( ( "Personne"."nom" || \' \' || "Personne"."prenom" ) ) AS "Personne__nom_complet_court"',
					'( ( SELECT dsps.id FROM dsps WHERE dsps.personne_id = "Personne"."id" LIMIT 1 ) IS NOT NULL ) AS "Dsp__exists"',
				),
				'contain' => false,
				'joins' => array(
					array(
						'table' => '"public"."calculsdroitsrsa"',
						'alias' => 'Calculdroitrsa',
						'type' => 'LEFT OUTER',
						'conditions' => '"Calculdroitrsa"."personne_id" = "Personne"."id"',
					),
					array(
						'table' => '"public"."contratsinsertion"',
						'alias' => 'Contratinsertion',
						'type' => 'LEFT OUTER',
						'conditions' => '"Contratinsertion"."personne_id" = "Personne"."id"',
					),
					array(
						'table' => '"public"."foyers"',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'conditions' => '"Personne"."foyer_id" = "Foyer"."id"',
					),
					array(
						'table' => '"public"."orientsstructs"',
						'alias' => 'Orientstruct',
						'type' => 'INNER',
						'conditions' => '"Orientstruct"."personne_id" = "Personne"."id"',
					),
					array(
						'table' => '"public"."personnes_referents"',
						'alias' => 'PersonneReferent',
						'type' => 'LEFT OUTER',
						'conditions' => '"PersonneReferent"."personne_id" = "Personne"."id"',
					),
					array(
						'table' => '"public"."prestations"',
						'alias' => 'Prestation',
						'type' => 'INNER',
						'conditions' => '"Prestation"."personne_id" = "Personne"."id" AND "Prestation"."natprest" = \'RSA\'',
					),
					array(
						'table' => '"public"."adressesfoyers"',
						'alias' => 'Adressefoyer',
						'type' => 'INNER',
						'conditions' => '"Adressefoyer"."foyer_id" = "Foyer"."id"',
					),
					array(
						'table' => '"public"."dossiers"',
						'alias' => 'Dossier',
						'type' => 'INNER',
						'conditions' => '"Foyer"."dossier_id" = "Dossier"."id"',
					),
					array(
						'table' => '"public"."adresses"',
						'alias' => 'Adresse',
						'type' => 'INNER',
						'conditions' => '"Adressefoyer"."adresse_id" = "Adresse"."id"',
					),
					array(
						'table' => '"public"."situationsdossiersrsa"',
						'alias' => 'Situationdossierrsa',
						'type' => 'INNER',
						'conditions' => '"Situationdossierrsa"."dossier_id" = "Dossier"."id"',
					),
				),
				'conditions' => array(
					'Prestation.rolepers' => array( 'DEM', 'CJT' ),
					'Adressefoyer.id IN (
				SELECT public.adressesfoyers.id
					FROM public.adressesfoyers
					WHERE
						public.adressesfoyers.foyer_id = Foyer.id
						AND public.adressesfoyers.rgadr = \'01\'
					ORDER BY public.adressesfoyers.dtemm DESC
					LIMIT 1
			 )',
					'Orientstruct.id IN ( SELECT "orientsstructs"."id" AS "orientsstructs__id" FROM "public"."orientsstructs" AS "orientsstructs"   WHERE "orientsstructs"."personne_id" = "Personne"."id" AND "orientsstructs"."statut_orient" = \'Orienté\' AND "orientsstructs"."date_valid" IS NOT NULL   ORDER BY "orientsstructs"."date_valid" DESC  LIMIT 1 )',
					'( "Contratinsertion"."id" IS NULL OR "Contratinsertion"."id" IN ( SELECT "contratsinsertion"."id" AS "contratsinsertion__id" FROM "public"."contratsinsertion" AS "contratsinsertion"   WHERE "contratsinsertion"."personne_id" = "Personne"."id" AND "contratsinsertion"."decision_ci" = \'V\'   ORDER BY "contratsinsertion"."rg_ci" DESC  LIMIT 1 ) )',

					array(
						'OR' => array(
							'PersonneReferent.id IS NULL',
							array(
								'PersonneReferent.id IN ( SELECT public.personnes_referents.id
					FROM public.personnes_referents
					WHERE
						public.personnes_referents.personne_id = Personne.id
					ORDER BY public.personnes_referents.dddesignation DESC
					LIMIT 1 )',
								'PersonneReferent.dfdesignation IS NOT NULL',
							),
						),
					),
				),
				'order' => array(
					'Orientstruct.date_valid ASC',
					'Personne.nom ASC',
					'Personne.prenom ASC',
				),
				'limit' => 10,
			)
		);

		/**
		 * Méthode exécutée avant chaque test.
		 */
		public function setUp() {
			parent::setUp();
			$this->Cohortereferent93 = ClassRegistry::init( 'Cohortereferent93' );
		}

		/**
		 * Méthode exécutée après chaque test.
		 */
		public function tearDown() {
			unset( $this->Cohortereferent93 );
		}

		/**
		 * Test de la méthode Cohortereferent93::search().
		 *
		 * @group medium
		 * @return void
		 */
		public function testSearch() {
			$result = $this->Cohortereferent93->search(
				'affecter',
				array(),
				false,
				array(),
				false
			);

			$regexes = array(
				'/[[:space:]]+/' => ' '
			);

			$result = recursive_key_value_preg_replace( $result, $regexes );
			$expected = recursive_key_value_preg_replace( $this->_querydatas['affecter'], $regexes );

			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>