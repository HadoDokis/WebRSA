<?php
	/**
	 * Code source de la classe Criteredossierpcg66Test.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Criteredossierpcg66Test réalise les tests unitaires du modèle
	 * Criteredossierpcg66.
	 *
	 * @package app.Test.Case.Model
	 */
	class Criteredossierpcg66Test extends CakeTestCase
	{
		/**
		 * Fixtures associated with this test case
		 *
		 * @var array
		 */
		public $fixtures = array(
			'app.Adresse',
			'app.Adressefoyer',
			'app.Decdospcg66Orgdospcg66',
			'app.Decisiondossierpcg66',
			'app.Decisionpdo',
			'app.Detailcalculdroitrsa',
			'app.Detaildroitrsa',
			'app.Dossier',
			'app.Dossierpcg66',
			'app.Fichiermodule',
			'app.Foyer',
			'app.Orgtransmisdossierpcg66',
			'app.Personne',
			'app.Personnepcg66',
			'app.Personnepcg66Situationpdo',
			'app.Personnepcg66Statutpdo',
			'app.PersonneReferent',
			'app.Poledossierpcg66',
			'app.Prestation',
			'app.Referent',
			'app.Situationpdo',
			'app.Statutpdo',
			'app.Structurereferente',
			'app.Traitementpcg66',
			'app.User',
		);

		protected $_querydatas = array(
			'searchDossier' => array(
				'fields' => array(
					0 => 'Dossierpcg66.id',
					1 => 'Dossierpcg66.foyer_id',
					2 => 'Dossierpcg66.datereceptionpdo',
					3 => 'Dossierpcg66.typepdo_id',
					4 => 'Dossierpcg66.etatdossierpcg',
					5 => 'Dossierpcg66.datetransmission',
					6 => 'Dossierpcg66.poledossierpcg66_id',
					7 => 'Decisiondossierpcg66.datetransmissionop',
					8 => 'Decisiondossierpcg66.datevalidation',
					9 => 'Decisiondossierpcg66.useravistechnique_id',
					10 => 'Decisiondossierpcg66.userproposition_id',
					11 => 'Decisiondossierpcg66.etatop',
					12 => 'Dossierpcg66.originepdo_id',
					13 => 'Dossierpcg66.user_id',
					14 => 'Dossier.id',
					15 => 'Dossier.numdemrsa',
					16 => 'Dossier.dtdemrsa',
					17 => 'Dossier.matricule',
					18 => 'Personne.id',
					19 => 'Personne.nom',
					20 => 'Personne.prenom',
					21 => 'Personne.dtnai',
					22 => 'Personne.nir',
					23 => 'Personne.qual',
					24 => 'Personne.nomcomnai',
					25 => 'Adresse.locaadr',
					26 => 'Adresse.codepos',
					27 => 'Adresse.numcomptt',
					28 => 'Situationdossierrsa.etatdosrsa',
					29 => '( ( SELECT COUNT(*) FROM decisionsdossierspcgs66 WHERE decisionsdossierspcgs66.dossierpcg66_id = "Dossierpcg66"."id" ) ) AS "Dossierpcg66__nbpropositions"',
					30 => '( SELECT COUNT("fichiermodule"."id") FROM "fichiersmodules" AS "fichiermodule" WHERE "fichiermodule"."modele" = \'Foyer\' AND "fichiermodule"."fk_value" = "Foyer"."id" ) AS "Fichiermodule__nb_fichiers_lies"',
					31 => 'Decisionpdo.libelle',
					32 => '( ( "User"."nom" || \' \' || "User"."prenom" ) ) AS "User__nom_complet"',
					33 => 'Poledossierpcg66.name',
					34 => 'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Situationpdo"."libelle" || \'\' AS "Situationpdo__libelle" FROM "personnespcgs66" AS "Personnepcg66" LEFT OUTER JOIN "public"."personnespcgs66_situationspdos" AS "Personnepcg66Situationpdo" ON ("Personnepcg66Situationpdo"."personnepcg66_id" = "Personnepcg66"."id") LEFT OUTER JOIN "public"."situationspdos" AS "Situationpdo" ON ("Personnepcg66Situationpdo"."situationpdo_id" = "Situationpdo"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Personnepcg66__listemotifs"',
					35 => 'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Statutpdo"."libelle" || \'\' AS "Statutpdo__libelle" FROM "personnespcgs66" AS "Personnepcg66" LEFT OUTER JOIN "public"."personnespcgs66_statutspdos" AS "Personnepcg66Statutpdo" ON ("Personnepcg66Statutpdo"."personnepcg66_id" = "Personnepcg66"."id") LEFT OUTER JOIN "public"."statutspdos" AS "Statutpdo" ON ("Personnepcg66Statutpdo"."statutpdo_id" = "Statutpdo"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Personnepcg66__listestatuts"',
					36 => 'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Traitementpcg66"."typetraitement" || \'\' AS "Traitementpcg66__typetraitement" FROM "traitementspcgs66" AS "Traitementpcg66" INNER JOIN "public"."personnespcgs66" AS "Personnepcg66" ON ("Traitementpcg66"."personnepcg66_id" = "Personnepcg66"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Dossierpcg66__listetraitements"',
					37 => 'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Traitementpcg66"."dateecheance" || \'\' AS "Traitementpcg66__dateecheance" FROM "traitementspcgs66" AS "Traitementpcg66" INNER JOIN "public"."personnespcgs66" AS "Personnepcg66" ON ("Traitementpcg66"."personnepcg66_id" = "Personnepcg66"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Dossierpcg66__dateecheance"',
					38 => 'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Orgtransmisdossierpcg66"."name" || \'\' AS "Orgtransmisdossierpcg66__name" FROM "decisionsdossierspcgs66" AS "Decisiondossierpcg66" LEFT JOIN "public"."decsdospcgs66_orgsdospcgs66" AS "Decdospcg66Orgdospcg66" ON ("Decdospcg66Orgdospcg66"."decisiondossierpcg66_id" = "Decisiondossierpcg66"."id") LEFT JOIN "public"."orgstransmisdossierspcgs66" AS "Orgtransmisdossierpcg66" ON ("Decdospcg66Orgdospcg66"."orgtransmisdossierpcg66_id" = "Orgtransmisdossierpcg66"."id") WHERE "Decisiondossierpcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ORDER BY "Decisiondossierpcg66"."created" DESC ), \'\' ) ) ) AS "Orgtransmisdossierpcg66__listorgs"',
					39 => 'PersonneReferent.referent_id',
					40 => '( ( "Referentparcours"."qual" || \' \' || "Referentparcours"."nom" || \' \' || "Referentparcours"."prenom" ) ) AS "Referentparcours__nom_complet"',
					41 => 'Structurereferenteparcours.lib_struc',
				),
				'recursive' => '-1',
				'joins' => array(
					0 => array(
						'table' => '"foyers"',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'conditions' => '"Dossierpcg66"."foyer_id" = "Foyer"."id"',
					),
					1 => array(
						'table' => '"users"',
						'alias' => 'User',
						'type' => 'LEFT OUTER',
						'conditions' => '"Dossierpcg66"."user_id" = "User"."id"',
					),
					2 => array(
						'table' => '"personnes"',
						'alias' => 'Personne',
						'type' => 'LEFT OUTER',
						'conditions' => '"Personne"."foyer_id" = "Foyer"."id" AND "Personne"."id" IN ( SELECT "personnes"."id" AS personnes__id FROM personnes AS personnes LEFT JOIN "public"."prestations" AS prestations ON ("prestations"."personne_id" = "personnes"."id" AND "prestations"."natprest" = \'RSA\') WHERE "personnes"."foyer_id" = "Foyer"."id" AND "prestations"."rolepers" = \'DEM\' LIMIT 1 )',
					),
					3 => array(
						'table' => '"personnespcgs66"',
						'alias' => 'Personnepcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" AND "Personnepcg66"."personne_id" = "Personne"."id"',
					),
					4 => array(
						'table' => '"adressesfoyers"',
						'alias' => 'Adressefoyer',
						'type' => 'LEFT OUTER',
						'conditions' => '"Adressefoyer"."foyer_id" = "Foyer"."id"',
					),
					5 => array(
						'table' => '"adresses"',
						'alias' => 'Adresse',
						'type' => 'LEFT OUTER',
						'conditions' => '"Adressefoyer"."adresse_id" = "Adresse"."id"',
					),
					6 => array(
						'table' => '"dossiers"',
						'alias' => 'Dossier',
						'type' => 'INNER',
						'conditions' => '"Foyer"."dossier_id" = "Dossier"."id"',
					),
					7 => array(
						'table' => '"situationsdossiersrsa"',
						'alias' => 'Situationdossierrsa',
						'type' => 'INNER',
						'conditions' => '"Situationdossierrsa"."dossier_id" = "Dossier"."id"',
					),
					8 => array(
						'table' => '"decisionsdossierspcgs66"',
						'alias' => 'Decisiondossierpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Decisiondossierpcg66"."dossierpcg66_id" = "Dossierpcg66"."id"',
					),
					9 => array(
						'table' => '"decisionspdos"',
						'alias' => 'Decisionpdo',
						'type' => 'LEFT OUTER',
						'conditions' => '"Decisiondossierpcg66"."decisionpdo_id" = "Decisionpdo"."id"',
					),
					10 => array(
						'table' => '"detailsdroitsrsa"',
						'alias' => 'Detaildroitrsa',
						'type' => 'LEFT OUTER',
						'conditions' => '"Detaildroitrsa"."dossier_id" = "Dossier"."id"',
					),
					11 => array(
						'table' => '"detailscalculsdroitsrsa"',
						'alias' => 'Detailcalculdroitrsa',
						'type' => 'LEFT OUTER',
						'conditions' => '"Detailcalculdroitrsa"."detaildroitrsa_id" = "Detaildroitrsa"."id"',
					),
					12 => array(
						'table' => '"polesdossierspcgs66"',
						'alias' => 'Poledossierpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Dossierpcg66"."poledossierpcg66_id" = "Poledossierpcg66"."id"',
					),
					13 => array(
						'table' => '"personnes_referents"',
						'alias' => 'PersonneReferent',
						'type' => 'LEFT OUTER',
						'conditions' => '"PersonneReferent"."personne_id" = "Personne"."id" AND (("PersonneReferent"."id" IS NULL) OR ("PersonneReferent"."id" IN ( SELECT "personnes_referents"."id" FROM personnes_referents WHERE "personnes_referents"."personne_id" = "Personne"."id" AND "personnes_referents"."dfdesignation" IS NULL ORDER BY "personnes_referents"."dddesignation" DESC LIMIT 1 )))'
					),
					14 => array(
						'table' => '"referents"',
						'alias' => 'Referentparcours',
						'type' => 'LEFT OUTER',
						'conditions' => '"PersonneReferent"."referent_id" = "Referentparcours"."id"',
					),
					15 => array(
						'table' => '"structuresreferentes"',
						'alias' => 'Structurereferenteparcours',
						'type' => 'LEFT OUTER',
						'conditions' => '"Referentparcours"."structurereferente_id" = "Structurereferenteparcours"."id"',
					),
				),
				'limit' => '10',
				'conditions' => array(
					0 => array(
						0 => array(
							'OR' => array(
								0 => 'Adressefoyer.id IS NULL',
								1 => 'Adressefoyer.id IN ( SELECT adressesfoyers.id FROM adressesfoyers WHERE adressesfoyers.foyer_id = Foyer.id AND adressesfoyers.rgadr = \'01\' ORDER BY adressesfoyers.dtemm DESC LIMIT 1 )',
							),
						),
						1 => array(
							'OR' => array(
								0 => 'Detailcalculdroitrsa.id IS NULL',
								1 => 'Detailcalculdroitrsa.id IN ( SELECT "detailscalculsdroitsrsa"."id" AS "detailscalculsdroitsrsa__id" FROM "detailscalculsdroitsrsa" AS "detailscalculsdroitsrsa" WHERE "detailscalculsdroitsrsa"."detaildroitrsa_id" = "Detaildroitrsa"."id" ORDER BY "detailscalculsdroitsrsa"."ddnatdro" DESC LIMIT 1 )',
							),
						),
						2 => array(
							'OR' => array(
								0 => 'Decisiondossierpcg66.id IS NULL',
								1 => 'Decisiondossierpcg66.id IN ( SELECT decisionsdossierspcgs66.id FROM decisionsdossierspcgs66 WHERE decisionsdossierspcgs66.dossierpcg66_id = Dossierpcg66.id ORDER BY decisionsdossierspcgs66.created DESC LIMIT 1 )',
							),
						),
					),
					1 => ''
				),
			),
			'searchGestionnaire' => array(
				'fields' => array(
					0 => 'Dossierpcg66.id',
					1 => 'Dossierpcg66.foyer_id',
					2 => 'Dossierpcg66.datereceptionpdo',
					3 => 'Dossierpcg66.typepdo_id',
					4 => 'Dossierpcg66.etatdossierpcg',
					5 => 'Dossierpcg66.originepdo_id',
					6 => 'Dossierpcg66.user_id',
					7 => 'Dossierpcg66.datetransmission',
					8 => 'Dossierpcg66.poledossierpcg66_id',
					9 => 'Decisiondossierpcg66.datetransmissionop',
					10 => 'Traitementpcg66.dateecheance',
					11 => 'Traitementpcg66.dateecheance',
					12 => 'Decisionpdo.libelle',
					13 => 'Dossier.id',
					14 => 'Dossier.numdemrsa',
					15 => 'Dossier.dtdemrsa',
					16 => 'Dossier.matricule',
					17 => 'Personne.id',
					18 => 'Personne.nom',
					19 => 'Personne.prenom',
					20 => 'Personne.dtnai',
					21 => 'Personne.nir',
					22 => 'Personne.qual',
					23 => 'Personne.nomcomnai',
					24 => 'Adresse.locaadr',
					25 => 'Adresse.codepos',
					26 => 'Adresse.numcomptt',
					27 => 'Situationdossierrsa.etatdosrsa',
					28 => '( ( SELECT COUNT(*) FROM decisionsdossierspcgs66 WHERE decisionsdossierspcgs66.dossierpcg66_id = "Dossierpcg66"."id" ) ) AS "Dossierpcg66__nbpropositions"',
					29 => '( ( SELECT COUNT(*) FROM traitementspcgs66 WHERE traitementspcgs66.personnepcg66_id = "Personnepcg66"."id" ) ) AS "Personnepcg66__nbtraitements"',
					30 => '( SELECT COUNT("fichiermodule"."id") FROM "fichiersmodules" AS "fichiermodule" WHERE "fichiermodule"."modele" = \'Foyer\' AND "fichiermodule"."fk_value" = "Foyer"."id" ) AS "Fichiermodule__nb_fichiers_lies"',
					31 => '( ( "User"."nom" || \' \' || "User"."prenom" ) ) AS "User__nom_complet"',
					32 => 'Poledossierpcg66.name',
					33 => 'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Situationpdo"."libelle" || \'\' AS "Situationpdo__libelle" FROM "personnespcgs66" AS "Personnepcg66" LEFT OUTER JOIN "public"."personnespcgs66_situationspdos" AS "Personnepcg66Situationpdo" ON ("Personnepcg66Situationpdo"."personnepcg66_id" = "Personnepcg66"."id") LEFT OUTER JOIN "public"."situationspdos" AS "Situationpdo" ON ("Personnepcg66Situationpdo"."situationpdo_id" = "Situationpdo"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Personnepcg66__listemotifs"',
					34 => 'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Statutpdo"."libelle" || \'\' AS "Statutpdo__libelle" FROM "personnespcgs66" AS "Personnepcg66" LEFT OUTER JOIN "public"."personnespcgs66_statutspdos" AS "Personnepcg66Statutpdo" ON ("Personnepcg66Statutpdo"."personnepcg66_id" = "Personnepcg66"."id") LEFT OUTER JOIN "public"."statutspdos" AS "Statutpdo" ON ("Personnepcg66Statutpdo"."statutpdo_id" = "Statutpdo"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Personnepcg66__listestatuts"',
					35 => 'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Traitementpcg66"."typetraitement" || \'\' AS "Traitementpcg66__typetraitement" FROM "traitementspcgs66" AS "Traitementpcg66" INNER JOIN "public"."personnespcgs66" AS "Personnepcg66" ON ("Traitementpcg66"."personnepcg66_id" = "Personnepcg66"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Dossierpcg66__listetraitements"',
					36 => 'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Traitementpcg66"."dateecheance" || \'\' AS "Traitementpcg66__dateecheance" FROM "traitementspcgs66" AS "Traitementpcg66" INNER JOIN "public"."personnespcgs66" AS "Personnepcg66" ON ("Traitementpcg66"."personnepcg66_id" = "Personnepcg66"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Dossierpcg66__dateecheance"',
					37 => 'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Orgtransmisdossierpcg66"."name" || \'\' AS "Orgtransmisdossierpcg66__name" FROM "decisionsdossierspcgs66" AS "Decisiondossierpcg66" LEFT JOIN "public"."decsdospcgs66_orgsdospcgs66" AS "Decdospcg66Orgdospcg66" ON ("Decdospcg66Orgdospcg66"."decisiondossierpcg66_id" = "Decisiondossierpcg66"."id") LEFT JOIN "public"."orgstransmisdossierspcgs66" AS "Orgtransmisdossierpcg66" ON ("Decdospcg66Orgdospcg66"."orgtransmisdossierpcg66_id" = "Orgtransmisdossierpcg66"."id") WHERE "Decisiondossierpcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ORDER BY "Decisiondossierpcg66"."created" DESC ), \'\' ) ) ) AS "Orgtransmisdossierpcg66__listorgs"',
					38 => 'PersonneReferent.referent_id',
					39 => '( ( "Referentparcours"."qual" || \' \' || "Referentparcours"."nom" || \' \' || "Referentparcours"."prenom" ) ) AS "Referentparcours__nom_complet"',
					40 => 'Structurereferenteparcours.lib_struc',
				),
				'recursive' => '-1',
				'joins' => array(
					0 => array(
						'table' => '"users"',
						'alias' => 'User',
						'type' => 'LEFT OUTER',
						'conditions' => '"Dossierpcg66"."user_id" = "User"."id"',
					),
					1 => array(
						'table' => '"foyers"',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'conditions' => '"Dossierpcg66"."foyer_id" = "Foyer"."id"',
					),
					2 => array(
						'table' => '"personnes"',
						'alias' => 'Personne',
						'type' => 'INNER',
						'conditions' => '"Personne"."foyer_id" = "Foyer"."id"',
					),
					3 => array(
						'table' => '"personnespcgs66"',
						'alias' => 'Personnepcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" AND "Personnepcg66"."personne_id" = "Personne"."id"',
					),
					4 => array(
						'table' => '"traitementspcgs66"',
						'alias' => 'Traitementpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Traitementpcg66"."personnepcg66_id" = "Personnepcg66"."id"',
					),
					5 => array(
						'table' => '"adressesfoyers"',
						'alias' => 'Adressefoyer',
						'type' => 'LEFT OUTER',
						'conditions' => '"Adressefoyer"."foyer_id" = "Foyer"."id"',
					),
					6 => array(
						'table' => '"adresses"',
						'alias' => 'Adresse',
						'type' => 'LEFT OUTER',
						'conditions' => '"Adressefoyer"."adresse_id" = "Adresse"."id"',
					),
					7 => array(
						'table' => '"dossiers"',
						'alias' => 'Dossier',
						'type' => 'INNER',
						'conditions' => '"Foyer"."dossier_id" = "Dossier"."id"',
					),
					8 => array(
						'table' => '"situationsdossiersrsa"',
						'alias' => 'Situationdossierrsa',
						'type' => 'INNER',
						'conditions' => '"Situationdossierrsa"."dossier_id" = "Dossier"."id"',
					),
					9 => array(
						'table' => '"decisionsdossierspcgs66"',
						'alias' => 'Decisiondossierpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Decisiondossierpcg66"."dossierpcg66_id" = "Dossierpcg66"."id"',
					),
					10 => array(
						'table' => '"decisionspdos"',
						'alias' => 'Decisionpdo',
						'type' => 'LEFT OUTER',
						'conditions' => '"Decisiondossierpcg66"."decisionpdo_id" = "Decisionpdo"."id"',
					),
					11 => array(
						'table' => '"detailsdroitsrsa"',
						'alias' => 'Detaildroitrsa',
						'type' => 'LEFT OUTER',
						'conditions' => '"Detaildroitrsa"."dossier_id" = "Dossier"."id"',
					),
					12 => array(
						'table' => '"detailscalculsdroitsrsa"',
						'alias' => 'Detailcalculdroitrsa',
						'type' => 'LEFT OUTER',
						'conditions' => '"Detailcalculdroitrsa"."detaildroitrsa_id" = "Detaildroitrsa"."id"',
					),
					13 => array(
						'table' => '"polesdossierspcgs66"',
						'alias' => 'Poledossierpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Dossierpcg66"."poledossierpcg66_id" = "Poledossierpcg66"."id"',
					),
					14 => array(
						'table' => '"personnes_referents"',
						'alias' => 'PersonneReferent',
						'type' => 'LEFT OUTER',
						'conditions' => '"PersonneReferent"."personne_id" = "Personne"."id" AND (("PersonneReferent"."id" IS NULL) OR ("PersonneReferent"."id" IN ( SELECT "personnes_referents"."id" FROM personnes_referents WHERE "personnes_referents"."personne_id" = "Personne"."id" AND "personnes_referents"."dfdesignation" IS NULL ORDER BY "personnes_referents"."dddesignation" DESC LIMIT 1 )))',
					),
					15 => array(
						'table' => '"referents"',
						'alias' => 'Referentparcours',
						'type' => 'LEFT OUTER',
						'conditions' => '"PersonneReferent"."referent_id" = "Referentparcours"."id"',
					),
					16 => array(
						'table' => '"structuresreferentes"',
						'alias' => 'Structurereferenteparcours',
						'type' => 'LEFT OUTER',
						'conditions' => '"Referentparcours"."structurereferente_id" = "Structurereferenteparcours"."id"',
					),
				),
				'limit' => '10',
				'contain' => '',
				'conditions' => array(
					0 => array(
						0 => array(
							0 => array(
								'OR' => array(
									0 => 'Adressefoyer.id IS NULL',
									1 => 'Adressefoyer.id IN ( SELECT adressesfoyers.id FROM adressesfoyers WHERE adressesfoyers.foyer_id = Foyer.id AND adressesfoyers.rgadr = \'01\' ORDER BY adressesfoyers.dtemm DESC LIMIT 1 )',
								),
							),
							1 => array(
								'OR' => array(
									0 => 'Detailcalculdroitrsa.id IS NULL',
									1 => 'Detailcalculdroitrsa.id IN ( SELECT "detailscalculsdroitsrsa"."id" AS "detailscalculsdroitsrsa__id" FROM "detailscalculsdroitsrsa" AS "detailscalculsdroitsrsa" WHERE "detailscalculsdroitsrsa"."detaildroitrsa_id" = "Detaildroitrsa"."id" ORDER BY "detailscalculsdroitsrsa"."ddnatdro" DESC LIMIT 1 )',
								),
							),
							2 => array(
								'OR' => array(
									0 => 'Decisiondossierpcg66.id IS NULL',
									1 => 'Decisiondossierpcg66.id IN ( SELECT decisionsdossierspcgs66.id FROM decisionsdossierspcgs66 WHERE decisionsdossierspcgs66.dossierpcg66_id = Dossierpcg66.id ORDER BY decisionsdossierspcgs66.created DESC LIMIT 1 )',
								),
							),
							3 => '( "Traitementpcg66"."id" IS NULL OR "Traitementpcg66"."id" IN ( SELECT "traitementspcgs66"."id" AS "traitementspcgs66__id" FROM "traitementspcgs66" AS "traitementspcgs66" WHERE "traitementspcgs66"."personnepcg66_id" = "Personnepcg66"."id" ORDER BY "traitementspcgs66"."created" DESC LIMIT 1 ) )',
						),
					),
					1 => 'Personne.id IN ( SELECT "personnes"."id" AS "personnes__id" FROM "personnes" AS "personnes" LEFT JOIN "public"."prestations" AS "prestations" ON ("prestations"."personne_id" = "personnes"."id" AND "prestations"."natprest" = \'RSA\') WHERE "personnes"."foyer_id" = "Foyer"."id" AND "prestations"."rolepers" = \'DEM\' LIMIT 1 )',
				),
			)
		);

		/**
		 * Méthode exécutée avant chaque test.
		 */
		public function setUp() {
			parent::setUp();
			Configure::write( 'Cg.departement', 66 );
			$this->Criteredossierpcg66 = ClassRegistry::init( 'Criteredossierpcg66' );
		}

		/**
		 * Méthode exécutée après chaque test.
		 */
		public function tearDown() {
			unset( $this->Criteredossierpcg66 );
		}

		/**
		 * Test de la méthode Criteredossierpcg66::searchDossier().
		 *
		 * @medium
		 */
		public function testSearchDossier() {
			$result = $this->Criteredossierpcg66->searchDossier(
				array(),
				array(),
				false
			);

			$regexes = array( '/[[:space:]]+/' => ' ' );

			$result = recursive_key_value_preg_replace( $result, $regexes );
			$expected = recursive_key_value_preg_replace( $this->_querydatas['searchDossier'], $regexes );

			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode Criteredossierpcg66::searchGestionnaire().
		 */
		public function testSearchGestionnaire() {
			$result = $this->Criteredossierpcg66->searchGestionnaire(
				array(),
				array(),
				false
			);

			$regexes = array( '/[[:space:]]+/' => ' ' );

			$result = recursive_key_value_preg_replace( $result, $regexes );
			$expected = recursive_key_value_preg_replace( $this->_querydatas['searchGestionnaire'], $regexes );

			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>