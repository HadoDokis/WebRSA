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
			'app.Cer93',
			'app.Contratinsertion',
			'app.Dossier',
			'app.Foyer',
			'app.Orientstruct',
			'app.Personne',
			'app.PersonneReferent',
			'app.Prestation',
			'app.Situationdossierrsa',
			'app.Structurereferente',
		);

		protected $_querydatas = array(
			'affecter' => array(
				'fields' =>
				array(
					0 => 'Personne.id',
					1 => 'Personne.foyer_id',
					2 => 'Personne.qual',
					3 => 'Personne.nom',
					4 => 'Personne.prenom',
					5 => 'Personne.nomnai',
					6 => 'Personne.prenom2',
					7 => 'Personne.prenom3',
					8 => 'Personne.nomcomnai',
					9 => 'Personne.dtnai',
					10 => 'Personne.rgnai',
					11 => 'Personne.typedtnai',
					12 => 'Personne.nir',
					13 => 'Personne.topvalec',
					14 => 'Personne.sexe',
					15 => 'Personne.nati',
					16 => 'Personne.dtnati',
					17 => 'Personne.pieecpres',
					18 => 'Personne.idassedic',
					19 => 'Personne.numagenpoleemploi',
					20 => 'Personne.dtinscpoleemploi',
					21 => 'Personne.numfixe',
					22 => 'Personne.numport',
					23 => 'Personne.haspiecejointe',
					24 => 'Personne.email',
					25 => 'PersonneReferent.id',
					26 => 'PersonneReferent.personne_id',
					27 => 'PersonneReferent.referent_id',
					28 => 'PersonneReferent.dddesignation',
					29 => 'PersonneReferent.dfdesignation',
					30 => 'PersonneReferent.structurereferente_id',
					31 => 'PersonneReferent.haspiecejointe',
					32 => 'Calculdroitrsa.id',
					33 => 'Calculdroitrsa.personne_id',
					34 => 'Calculdroitrsa.mtpersressmenrsa',
					35 => 'Calculdroitrsa.mtpersabaneursa',
					36 => 'Calculdroitrsa.toppersdrodevorsa',
					37 => 'Calculdroitrsa.toppersentdrodevorsa',
					38 => 'Contratinsertion.id',
					39 => 'Contratinsertion.personne_id',
					40 => 'Contratinsertion.structurereferente_id',
					41 => 'Contratinsertion.typocontrat_id',
					42 => 'Contratinsertion.dd_ci',
					43 => 'Contratinsertion.df_ci',
					44 => 'Contratinsertion.diplomes',
					45 => 'Contratinsertion.form_compl',
					46 => 'Contratinsertion.expr_prof',
					47 => 'Contratinsertion.aut_expr_prof',
					48 => 'Contratinsertion.rg_ci',
					49 => 'Contratinsertion.actions_prev',
					50 => 'Contratinsertion.obsta_renc',
					51 => 'Contratinsertion.service_soutien',
					52 => 'Contratinsertion.pers_charg_suivi',
					53 => 'Contratinsertion.objectifs_fixes',
					54 => 'Contratinsertion.engag_object',
					55 => 'Contratinsertion.sect_acti_emp',
					56 => 'Contratinsertion.emp_occupe',
					57 => 'Contratinsertion.duree_hebdo_emp',
					58 => 'Contratinsertion.nat_cont_trav',
					59 => 'Contratinsertion.duree_cdd',
					60 => 'Contratinsertion.duree_engag',
					61 => 'Contratinsertion.nature_projet',
					62 => 'Contratinsertion.observ_ci',
					63 => 'Contratinsertion.decision_ci',
					64 => 'Contratinsertion.datevalidation_ci',
					65 => 'Contratinsertion.date_saisi_ci',
					66 => 'Contratinsertion.lieu_saisi_ci',
					67 => 'Contratinsertion.emp_trouv',
					68 => 'Contratinsertion.forme_ci',
					69 => 'Contratinsertion.commentaire_action',
					70 => 'Contratinsertion.raison_ci',
					71 => 'Contratinsertion.aviseqpluri',
					72 => 'Contratinsertion.sitfam_ci',
					73 => 'Contratinsertion.sitpro_ci',
					74 => 'Contratinsertion.observ_benef',
					75 => 'Contratinsertion.referent_id',
					76 => 'Contratinsertion.avisraison_ci',
					77 => 'Contratinsertion.type_demande',
					78 => 'Contratinsertion.num_contrat',
					79 => 'Contratinsertion.typeinsertion',
					80 => 'Contratinsertion.bilancontrat',
					81 => 'Contratinsertion.engag_object_referent',
					82 => 'Contratinsertion.outilsmobilises',
					83 => 'Contratinsertion.outilsamobiliser',
					84 => 'Contratinsertion.niveausalaire',
					85 => 'Contratinsertion.zonegeographique_id',
					86 => 'Contratinsertion.autreavisradiation',
					87 => 'Contratinsertion.autreavissuspension',
					88 => 'Contratinsertion.datesuspensionparticulier',
					89 => 'Contratinsertion.dateradiationparticulier',
					90 => 'Contratinsertion.faitsuitea',
					91 => 'Contratinsertion.positioncer',
					92 => 'Contratinsertion.created',
					93 => 'Contratinsertion.modified',
					94 => 'Contratinsertion.current_action',
					95 => 'Contratinsertion.haspiecejointe',
					96 => 'Contratinsertion.avenant_id',
					97 => 'Contratinsertion.sitfam',
					98 => 'Contratinsertion.typeocclog',
					99 => 'Contratinsertion.persacharge',
					100 => 'Contratinsertion.objetcerprecautre',
					101 => 'Contratinsertion.motifannulation',
					102 => 'Contratinsertion.datedecision',
					103 => 'Contratinsertion.datenotification',
					104 => 'Contratinsertion.actioncandidat_id',
					105 => 'Cer93.id',
					106 => 'Cer93.contratinsertion_id',
					107 => 'Cer93.user_id',
					108 => 'Cer93.matricule',
					109 => 'Cer93.dtdemrsa',
					110 => 'Cer93.qual',
					111 => 'Cer93.nom',
					112 => 'Cer93.nomnai',
					113 => 'Cer93.prenom',
					114 => 'Cer93.dtnai',
					115 => 'Cer93.adresse',
					116 => 'Cer93.codepos',
					117 => 'Cer93.locaadr',
					118 => 'Cer93.sitfam',
					119 => 'Cer93.natlog',
					120 => 'Cer93.incoherencesetatcivil',
					121 => 'Cer93.inscritpe',
					122 => 'Cer93.cmu',
					123 => 'Cer93.cmuc',
					124 => 'Cer93.nivetu',
					125 => 'Cer93.numdemrsa',
					126 => 'Cer93.rolepers',
					127 => 'Cer93.identifiantpe',
					128 => 'Cer93.positioncer',
					129 => 'Cer93.formeci',
					130 => 'Cer93.datesignature',
					131 => 'Cer93.autresexps',
					132 => 'Cer93.isemploitrouv',
					133 => 'Cer93.metierexerce_id',
					134 => 'Cer93.secteuracti_id',
					135 => 'Cer93.naturecontrat_id',
					136 => 'Cer93.dureehebdo',
					137 => 'Cer93.dureecdd',
					138 => 'Cer93.prevu',
					139 => 'Cer93.bilancerpcd',
					140 => 'Cer93.duree',
					141 => 'Cer93.pointparcours',
					142 => 'Cer93.datepointparcours',
					143 => 'Cer93.pourlecomptede',
					144 => 'Cer93.observpro',
					145 => 'Cer93.observbenef',
					146 => 'Cer93.structureutilisateur',
					147 => 'Cer93.nomutilisateur',
					148 => 'Cer93.prevupcd',
					149 => 'Cer93.sujetpcd',
					150 => 'Cer93.created',
					151 => 'Cer93.modified',
					152 => 'Cer93.dateimpressiondecision',
					153 => 'Cer93.observationdecision',
					154 => 'Orientstruct.id',
					155 => 'Orientstruct.personne_id',
					156 => 'Orientstruct.typeorient_id',
					157 => 'Orientstruct.structurereferente_id',
					158 => 'Orientstruct.propo_algo',
					159 => 'Orientstruct.valid_cg',
					160 => 'Orientstruct.date_propo',
					161 => 'Orientstruct.date_valid',
					162 => 'Orientstruct.statut_orient',
					163 => 'Orientstruct.date_impression',
					164 => 'Orientstruct.daterelance',
					165 => 'Orientstruct.statutrelance',
					166 => 'Orientstruct.date_impression_relance',
					167 => 'Orientstruct.referent_id',
					168 => 'Orientstruct.etatorient',
					169 => 'Orientstruct.rgorient',
					170 => 'Orientstruct.structureorientante_id',
					171 => 'Orientstruct.referentorientant_id',
					172 => 'Orientstruct.user_id',
					173 => 'Orientstruct.haspiecejointe',
					174 => 'Orientstruct.origine',
					175 => 'Orientstruct.typenotification',
					176 => 'Structurereferente.id',
					177 => 'Structurereferente.typeorient_id',
					178 => 'Structurereferente.lib_struc',
					179 => 'Structurereferente.num_voie',
					180 => 'Structurereferente.type_voie',
					181 => 'Structurereferente.nom_voie',
					182 => 'Structurereferente.code_postal',
					183 => 'Structurereferente.ville',
					184 => 'Structurereferente.code_insee',
					185 => 'Structurereferente.filtre_zone_geo',
					186 => 'Structurereferente.contratengagement',
					187 => 'Structurereferente.apre',
					188 => 'Structurereferente.orientation',
					189 => 'Structurereferente.pdo',
					190 => 'Structurereferente.numtel',
					191 => 'Structurereferente.actif',
					192 => 'Structurereferente.typestructure',
					193 => 'Structurereferente.cui',
					194 => 'Prestation.personne_id',
					195 => 'Prestation.natprest',
					196 => 'Prestation.rolepers',
					197 => 'Prestation.topchapers',
					198 => 'Prestation.id',
					199 => 'Dossier.id',
					200 => 'Dossier.numdemrsa',
					201 => 'Dossier.dtdemrsa',
					202 => 'Dossier.dtdemrmi',
					203 => 'Dossier.numdepinsrmi',
					204 => 'Dossier.typeinsrmi',
					205 => 'Dossier.numcominsrmi',
					206 => 'Dossier.numagrinsrmi',
					207 => 'Dossier.numdosinsrmi',
					208 => 'Dossier.numcli',
					209 => 'Dossier.numorg',
					210 => 'Dossier.fonorg',
					211 => 'Dossier.matricule',
					212 => 'Dossier.statudemrsa',
					213 => 'Dossier.typeparte',
					214 => 'Dossier.ideparte',
					215 => 'Dossier.fonorgcedmut',
					216 => 'Dossier.numorgcedmut',
					217 => 'Dossier.matriculeorgcedmut',
					218 => 'Dossier.ddarrmut',
					219 => 'Dossier.codeposanchab',
					220 => 'Dossier.fonorgprenmut',
					221 => 'Dossier.numorgprenmut',
					222 => 'Dossier.dddepamut',
					223 => 'Dossier.detaildroitrsa_id',
					224 => 'Dossier.avispcgdroitrsa_id',
					225 => 'Dossier.organisme_id',
					226 => 'Adresse.id',
					227 => 'Adresse.numvoie',
					228 => 'Adresse.typevoie',
					229 => 'Adresse.nomvoie',
					230 => 'Adresse.complideadr',
					231 => 'Adresse.compladr',
					232 => 'Adresse.lieudist',
					233 => 'Adresse.numcomrat',
					234 => 'Adresse.numcomptt',
					235 => 'Adresse.codepos',
					236 => 'Adresse.locaadr',
					237 => 'Adresse.pays',
					238 => 'Adresse.canton',
					239 => 'Adresse.typeres',
					240 => 'Adresse.topresetr',
					241 => 'Adresse.foyerid',
					242 => 'Situationdossierrsa.id',
					243 => 'Situationdossierrsa.dossier_id',
					244 => 'Situationdossierrsa.etatdosrsa',
					245 => 'Situationdossierrsa.dtrefursa',
					246 => 'Situationdossierrsa.moticlorsa',
					247 => 'Situationdossierrsa.dtclorsa',
					248 => 'Situationdossierrsa.motirefursa',
					249 => '( ( "Personne"."nom" || \' \' || "Personne"."prenom" ) ) AS "Personne__nom_complet_court"',
					250 => '( ( SELECT dsps.id FROM dsps WHERE dsps.personne_id = "Personne"."id" LIMIT 1 ) IS NOT NULL ) AS "Dsp__exists"',
					251 => '( "Contratinsertion"."structurereferente_id" = affecter ) AS "Contratinsertion__interne"',
					252 => '( ( CASE WHEN ( "PersonneReferent"."id" IS NULL AND "Contratinsertion"."id" IS NULL ) THEN 1 WHEN ( "PersonneReferent"."id" IS NULL AND "Contratinsertion"."id" IS NOT NULL AND "Contratinsertion"."decision_ci" = \'E\' AND "Cer93"."positioncer" = \'00enregistre\' ) THEN 2 WHEN ( "PersonneReferent"."id" IS NULL AND "Contratinsertion"."id" IS NOT NULL AND "Contratinsertion"."decision_ci" = \'E\' AND "Cer93"."positioncer" = \'01signe\' ) THEN 3 WHEN ( "PersonneReferent"."id" IS NOT NULL AND "Contratinsertion"."id" IS NULL ) THEN 4 WHEN ( "PersonneReferent"."id" IS NOT NULL AND "Contratinsertion"."id" IS NOT NULL AND "Contratinsertion"."decision_ci" = \'E\' AND "Cer93"."positioncer" = \'00enregistre\' ) THEN 5 WHEN ( "PersonneReferent"."id" IS NOT NULL AND "Contratinsertion"."id" IS NOT NULL AND "Contratinsertion"."decision_ci" = \'E\' AND "Cer93"."positioncer" = \'01signe\' ) THEN 6 WHEN ( "PersonneReferent"."id" IS NULL AND "Contratinsertion"."id" IS NOT NULL AND "Contratinsertion"."decision_ci" = \'V\' AND "Contratinsertion"."df_ci" <= NOW() ) THEN 7 WHEN ( "PersonneReferent"."id" IS NULL AND "Contratinsertion"."id" IS NOT NULL AND "Contratinsertion"."decision_ci" = \'V\' AND "Contratinsertion"."df_ci" > NOW() ) THEN 8 WHEN ( "PersonneReferent"."id" IS NOT NULL AND "Contratinsertion"."id" IS NOT NULL AND "Contratinsertion"."decision_ci" = \'V\' AND "Contratinsertion"."df_ci" <= NOW() ) THEN 9 WHEN ( "PersonneReferent"."id" IS NOT NULL AND "Contratinsertion"."id" IS NOT NULL AND "Contratinsertion"."decision_ci" = \'V\' AND "Contratinsertion"."df_ci" > NOW() ) THEN 10 WHEN ( "PersonneReferent"."id" IS NOT NULL AND "Contratinsertion"."id" IS NOT NULL AND "Contratinsertion"."decision_ci" = \'R\' AND "Cer93"."positioncer" = \'99rejete\' ) THEN 11 ELSE 12 END ) ) AS "Personne__situation"',
				),
				'contain' => '',
				'joins' =>
				array(
					0 =>
					array(
						'table' => '"calculsdroitsrsa"',
						'alias' => 'Calculdroitrsa',
						'type' => 'LEFT OUTER',
						'conditions' => '"Calculdroitrsa"."personne_id" = "Personne"."id"',
					),
					1 =>
					array(
						'table' => '"contratsinsertion"',
						'alias' => 'Contratinsertion',
						'type' => 'LEFT OUTER',
						'conditions' => '"Contratinsertion"."personne_id" = "Personne"."id"',
					),
					2 =>
					array(
						'table' => '"foyers"',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'conditions' => '"Personne"."foyer_id" = "Foyer"."id"',
					),
					3 =>
					array(
						'table' => '"orientsstructs"',
						'alias' => 'Orientstruct',
						'type' => 'INNER',
						'conditions' => '"Orientstruct"."personne_id" = "Personne"."id"',
					),
					4 =>
					array(
						'table' => '"personnes_referents"',
						'alias' => 'PersonneReferent',
						'type' => 'LEFT OUTER',
						'conditions' => '"PersonneReferent"."personne_id" = "Personne"."id" AND "PersonneReferent"."id" IN ( SELECT "personnes_referents"."id" FROM personnes_referents WHERE "personnes_referents"."personne_id" = "Personne"."id" AND "personnes_referents"."dfdesignation" IS NULL ORDER BY "personnes_referents"."dddesignation" DESC LIMIT 1 )',
					),
					5 =>
					array(
						'table' => '"prestations"',
						'alias' => 'Prestation',
						'type' => 'INNER',
						'conditions' => '"Prestation"."personne_id" = "Personne"."id" AND "Prestation"."natprest" = \'RSA\'',
					),
					6 =>
					array(
						'table' => '"cers93"',
						'alias' => 'Cer93',
						'type' => 'LEFT OUTER',
						'conditions' => '"Cer93"."contratinsertion_id" = "Contratinsertion"."id"',
					),
					7 =>
					array(
						'table' => '"adressesfoyers"',
						'alias' => 'Adressefoyer',
						'type' => 'INNER',
						'conditions' => '"Adressefoyer"."foyer_id" = "Foyer"."id"',
					),
					8 =>
					array(
						'table' => '"dossiers"',
						'alias' => 'Dossier',
						'type' => 'INNER',
						'conditions' => '"Foyer"."dossier_id" = "Dossier"."id"',
					),
					9 =>
					array(
						'table' => '"adresses"',
						'alias' => 'Adresse',
						'type' => 'INNER',
						'conditions' => '"Adressefoyer"."adresse_id" = "Adresse"."id"',
					),
					10 =>
					array(
						'table' => '"situationsdossiersrsa"',
						'alias' => 'Situationdossierrsa',
						'type' => 'INNER',
						'conditions' => '"Situationdossierrsa"."dossier_id" = "Dossier"."id"',
					),
					11 =>
					array(
						'table' => '"orientsstructs"',
						'alias' => 'Orientstructpcd',
						'type' => 'LEFT OUTER',
						'conditions' => '"Orientstructpcd"."personne_id" = "Personne"."id" AND (("Orientstructpcd"."id" IS NULL) OR ("Orientstructpcd"."id" IN ( SELECT "orientsstructs"."id" AS orientsstructs__id FROM orientsstructs AS orientsstructs WHERE "orientsstructs"."personne_id" = "Personne"."id" AND "orientsstructs"."statut_orient" = \'Orienté\' AND "orientsstructs"."id" NOT IN ( SELECT "orientsstructs"."id" AS orientsstructs__id FROM orientsstructs AS orientsstructs WHERE "orientsstructs"."personne_id" = "Personne"."id" AND "orientsstructs"."statut_orient" = \'Orienté\' AND "orientsstructs"."date_valid" IS NOT NULL ORDER BY "orientsstructs"."date_valid" DESC LIMIT 1 ) ORDER BY "orientsstructs"."date_valid" DESC LIMIT 1 )))',
					),
					12 =>
					array(
						'table' => '"structuresreferentes"',
						'alias' => 'Structurereferente',
						'type' => 'LEFT',
						'conditions' => '"Orientstructpcd"."structurereferente_id" = "Structurereferente"."id"',
					),
				),
				'conditions' =>
				array(
					'Prestation.rolepers' =>
					array(
						0 => 'DEM',
						1 => 'CJT',
					),
					0 => 'Adressefoyer.id IN ( SELECT adressesfoyers.id FROM adressesfoyers WHERE adressesfoyers.foyer_id = Foyer.id AND adressesfoyers.rgadr = \'01\' ORDER BY adressesfoyers.dtemm DESC LIMIT 1 )',
					1 => 'Orientstruct.id IN ( SELECT "orientsstructs"."id" AS "orientsstructs__id" FROM "orientsstructs" AS "orientsstructs" WHERE "orientsstructs"."personne_id" = "Personne"."id" AND "orientsstructs"."statut_orient" = \'Orienté\' AND "orientsstructs"."date_valid" IS NOT NULL ORDER BY "orientsstructs"."date_valid" DESC LIMIT 1 )',
					'Orientstruct.structurereferente_id' => 'affecter',
					2 => '( "Contratinsertion"."id" IS NULL OR "Contratinsertion"."id" IN ( SELECT "contratsinsertion"."id" AS "contratsinsertion__id" FROM "contratsinsertion" AS "contratsinsertion" WHERE "contratsinsertion"."personne_id" = "Personne"."id" ORDER BY "contratsinsertion"."created" DESC LIMIT 1 ) )',
					3 =>
					array(
						'OR' =>
						array(
							0 => 'Contratinsertion.id IS NULL',
							1 =>
							array(
								0 => 'Contratinsertion.id IS NOT NULL',
								1 => 'Orientstructpcd.id IS NOT NULL',
							),
							2 =>
							array(
								0 => 'Contratinsertion.id IS NOT NULL',
								'Contratinsertion.decision_ci' => 'V',
								'Contratinsertion.df_ci <=' => '2013-08-07',
							),
							3 =>
							array(
								0 => 'Contratinsertion.id IS NOT NULL',
								'Contratinsertion.decision_ci' => 'E',
								'Cer93.positioncer' =>
								array(
									0 => '00enregistre',
									1 => '01signe',
								),
							),
							4 =>
							array(
								0 => 'Contratinsertion.id IS NOT NULL',
								'Contratinsertion.decision_ci' => 'R',
								'Cer93.positioncer' => '99rejete',
							),
						),
					),
				),
				'order' =>
				array(
					'Personne.situation' => 'ASC',
					0 => 'Orientstruct.date_valid ASC',
					1 => 'Personne.nom ASC',
					2 => 'Personne.prenom ASC',
				),
				'limit' => '10',
			) );

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
			$expected['conditions'][3]['OR'][2]['Contratinsertion.df_ci <='] = date( 'Y-m-d', strtotime( Configure::read( 'Cohortescers93.saisie.periodeRenouvellement' ) ) );

			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>