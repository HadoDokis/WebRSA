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
					'Dossierpcg66.id',
					'Dossierpcg66.foyer_id',
					'Dossierpcg66.datereceptionpdo',
					'Dossierpcg66.typepdo_id',
					'Dossierpcg66.etatdossierpcg',
					'Dossierpcg66.datetransmission',
					'Dossierpcg66.dateaffectation',
					'Dossierpcg66.poledossierpcg66_id',
					'Decisiondossierpcg66.datetransmissionop',
					'Decisiondossierpcg66.datevalidation',
					'Decisiondossierpcg66.useravistechnique_id',
					'Decisiondossierpcg66.userproposition_id',
					'Decisiondossierpcg66.etatop',
					'Dossierpcg66.originepdo_id',
					'Dossierpcg66.user_id',
					'Dossier.id',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Personne.id',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Personne.qual',
					'Personne.nomcomnai',
					'Adresse.locaadr',
					'Adresse.codepos',
					'Adresse.numcomptt',
					'Situationdossierrsa.etatdosrsa',
					'( ( SELECT COUNT(*) FROM decisionsdossierspcgs66 WHERE decisionsdossierspcgs66.dossierpcg66_id = "Dossierpcg66"."id" ) ) AS "Dossierpcg66__nbpropositions"',
					'( SELECT COUNT("fichiermodule"."id") FROM "fichiersmodules" AS "fichiermodule" WHERE "fichiermodule"."modele" = \'Foyer\' AND "fichiermodule"."fk_value" = "Foyer"."id" ) AS "Fichiermodule__nb_fichiers_lies"',
					'Decisionpdo.libelle',
					'( ( "User"."nom" || \' \' || "User"."prenom" ) ) AS "User__nom_complet"',
					'Poledossierpcg66.name',
					'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Situationpdo"."libelle" || \'\' AS "Situationpdo__libelle" FROM "personnespcgs66" AS "Personnepcg66" LEFT OUTER JOIN "public"."personnespcgs66_situationspdos" AS "Personnepcg66Situationpdo" ON ("Personnepcg66Situationpdo"."personnepcg66_id" = "Personnepcg66"."id") LEFT OUTER JOIN "public"."situationspdos" AS "Situationpdo" ON ("Personnepcg66Situationpdo"."situationpdo_id" = "Situationpdo"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Personnepcg66__listemotifs"',
					'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Statutpdo"."libelle" || \'\' AS "Statutpdo__libelle" FROM "personnespcgs66" AS "Personnepcg66" LEFT OUTER JOIN "public"."personnespcgs66_statutspdos" AS "Personnepcg66Statutpdo" ON ("Personnepcg66Statutpdo"."personnepcg66_id" = "Personnepcg66"."id") LEFT OUTER JOIN "public"."statutspdos" AS "Statutpdo" ON ("Personnepcg66Statutpdo"."statutpdo_id" = "Statutpdo"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Personnepcg66__listestatuts"',
					'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Traitementpcg66"."typetraitement" || \'\' AS "Traitementpcg66__typetraitement" FROM "traitementspcgs66" AS "Traitementpcg66" INNER JOIN "public"."personnespcgs66" AS "Personnepcg66" ON ("Traitementpcg66"."personnepcg66_id" = "Personnepcg66"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Dossierpcg66__listetraitements"',
					'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Traitementpcg66"."dateecheance" || \'\' AS "Traitementpcg66__dateecheance" FROM "traitementspcgs66" AS "Traitementpcg66" INNER JOIN "public"."personnespcgs66" AS "Personnepcg66" ON ("Traitementpcg66"."personnepcg66_id" = "Personnepcg66"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Dossierpcg66__dateecheance"',
					'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Orgtransmisdossierpcg66"."name" || \'\' AS "Orgtransmisdossierpcg66__name" FROM "decisionsdossierspcgs66" AS "Decisiondossierpcg66" LEFT JOIN "public"."decsdospcgs66_orgsdospcgs66" AS "Decdospcg66Orgdospcg66" ON ("Decdospcg66Orgdospcg66"."decisiondossierpcg66_id" = "Decisiondossierpcg66"."id") LEFT JOIN "public"."orgstransmisdossierspcgs66" AS "Orgtransmisdossierpcg66" ON ("Decdospcg66Orgdospcg66"."orgtransmisdossierpcg66_id" = "Orgtransmisdossierpcg66"."id") WHERE "Decisiondossierpcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ORDER BY "Decisiondossierpcg66"."created" DESC ), \'\' ) ) ) AS "Orgtransmisdossierpcg66__listorgs"',
					'PersonneReferent.referent_id',
					'( ( "Referentparcours"."qual" || \' \' || "Referentparcours"."nom" || \' \' || "Referentparcours"."prenom" ) ) AS "Referentparcours__nom_complet"',
					'Structurereferenteparcours.lib_struc',
				),
				'recursive' => '-1',
				'joins' => array(
					array(
						'table' => '"foyers"',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'conditions' => '"Dossierpcg66"."foyer_id" = "Foyer"."id"',
					),
					array(
						'table' => '"users"',
						'alias' => 'User',
						'type' => 'LEFT OUTER',
						'conditions' => '"Dossierpcg66"."user_id" = "User"."id"',
					),
					array(
						'table' => '"personnes"',
						'alias' => 'Personne',
						'type' => 'LEFT OUTER',
						'conditions' => '"Personne"."foyer_id" = "Foyer"."id" AND "Personne"."id" IN ( SELECT "personnes"."id" AS personnes__id FROM personnes AS personnes LEFT JOIN "public"."prestations" AS prestations ON ("prestations"."personne_id" = "personnes"."id" AND "prestations"."natprest" = \'RSA\') WHERE "personnes"."foyer_id" = "Foyer"."id" AND "prestations"."rolepers" = \'DEM\' LIMIT 1 )',
					),
					array(
						'table' => '"personnespcgs66"',
						'alias' => 'Personnepcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" AND "Personnepcg66"."personne_id" = "Personne"."id"',
					),
					array(
						'table' => '"adressesfoyers"',
						'alias' => 'Adressefoyer',
						'type' => 'LEFT OUTER',
						'conditions' => '"Adressefoyer"."foyer_id" = "Foyer"."id"',
					),
					array(
						'table' => '"adresses"',
						'alias' => 'Adresse',
						'type' => 'LEFT OUTER',
						'conditions' => '"Adressefoyer"."adresse_id" = "Adresse"."id"',
					),
					array(
						'table' => '"dossiers"',
						'alias' => 'Dossier',
						'type' => 'INNER',
						'conditions' => '"Foyer"."dossier_id" = "Dossier"."id"',
					),
					array(
						'table' => '"situationsdossiersrsa"',
						'alias' => 'Situationdossierrsa',
						'type' => 'INNER',
						'conditions' => '"Situationdossierrsa"."dossier_id" = "Dossier"."id"',
					),
					array(
						'table' => '"decisionsdossierspcgs66"',
						'alias' => 'Decisiondossierpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Decisiondossierpcg66"."dossierpcg66_id" = "Dossierpcg66"."id"',
					),
					array(
						'table' => '"decisionspdos"',
						'alias' => 'Decisionpdo',
						'type' => 'LEFT OUTER',
						'conditions' => '"Decisiondossierpcg66"."decisionpdo_id" = "Decisionpdo"."id"',
					),
					array(
						'table' => '"detailsdroitsrsa"',
						'alias' => 'Detaildroitrsa',
						'type' => 'LEFT OUTER',
						'conditions' => '"Detaildroitrsa"."dossier_id" = "Dossier"."id"',
					),
					array(
						'table' => '"detailscalculsdroitsrsa"',
						'alias' => 'Detailcalculdroitrsa',
						'type' => 'LEFT OUTER',
						'conditions' => '"Detailcalculdroitrsa"."detaildroitrsa_id" = "Detaildroitrsa"."id"',
					),
					array(
						'table' => '"polesdossierspcgs66"',
						'alias' => 'Poledossierpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Dossierpcg66"."poledossierpcg66_id" = "Poledossierpcg66"."id"',
					),
					array(
						'table' => '"personnes_referents"',
						'alias' => 'PersonneReferent',
						'type' => 'LEFT OUTER',
						'conditions' => '"PersonneReferent"."personne_id" = "Personne"."id" AND (("PersonneReferent"."id" IS NULL) OR ("PersonneReferent"."id" IN ( SELECT "personnes_referents"."id" FROM personnes_referents WHERE "personnes_referents"."personne_id" = "Personne"."id" AND "personnes_referents"."dfdesignation" IS NULL ORDER BY "personnes_referents"."dddesignation" DESC LIMIT 1 )))'
					),
					array(
						'table' => '"referents"',
						'alias' => 'Referentparcours',
						'type' => 'LEFT OUTER',
						'conditions' => '"PersonneReferent"."referent_id" = "Referentparcours"."id"',
					),
					array(
						'table' => '"structuresreferentes"',
						'alias' => 'Structurereferenteparcours',
						'type' => 'LEFT OUTER',
						'conditions' => '"Referentparcours"."structurereferente_id" = "Structurereferenteparcours"."id"',
					),
				),
				'limit' => '10',
				'conditions' => array(
					array(
						array(
							'OR' => array(
								'Adressefoyer.id IS NULL',
								'Adressefoyer.id IN ( SELECT "adressesfoyers"."id" AS "adressesfoyers__id" FROM "adressesfoyers" AS "adressesfoyers" WHERE "adressesfoyers"."foyer_id" = "Foyer"."id" AND "adressesfoyers"."rgadr" = \'01\' ORDER BY "adressesfoyers"."dtemm" DESC LIMIT 1 )'
							),
						),
						array(
							'OR' => array(
								'Detailcalculdroitrsa.id IS NULL',
								'Detailcalculdroitrsa.id IN ( SELECT "detailscalculsdroitsrsa"."id" AS "detailscalculsdroitsrsa__id" FROM "detailscalculsdroitsrsa" AS "detailscalculsdroitsrsa" WHERE "detailscalculsdroitsrsa"."detaildroitrsa_id" = "Detaildroitrsa"."id" ORDER BY "detailscalculsdroitsrsa"."ddnatdro" DESC LIMIT 1 )',
							),
						),
						array(
							'OR' => array(
								'Decisiondossierpcg66.id IS NULL',
								'Decisiondossierpcg66.id IN ( SELECT decisionsdossierspcgs66.id FROM decisionsdossierspcgs66 WHERE decisionsdossierspcgs66.dossierpcg66_id = Dossierpcg66.id ORDER BY decisionsdossierspcgs66.created DESC LIMIT 1 )',
							),
						),
					),
					''
				),
			),
			'searchGestionnaire' => array(
				'fields' => array(
					'Dossierpcg66.id',
					'Dossierpcg66.foyer_id',
					'Dossierpcg66.datereceptionpdo',
					'Dossierpcg66.typepdo_id',
					'Dossierpcg66.etatdossierpcg',
					'Dossierpcg66.originepdo_id',
					'Dossierpcg66.user_id',
					'Dossierpcg66.dateaffectation',
					'Dossierpcg66.datetransmission',
					'Dossierpcg66.poledossierpcg66_id',
					'Decisiondossierpcg66.datetransmissionop',
					'Traitementpcg66.dateecheance',
					'Traitementpcg66.dateecheance',
					'Decisionpdo.libelle',
					'Dossier.id',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Personne.id',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Personne.qual',
					'Personne.nomcomnai',
					'Adresse.locaadr',
					'Adresse.codepos',
					'Adresse.numcomptt',
					'Situationdossierrsa.etatdosrsa',
					'( ( SELECT COUNT(*) FROM decisionsdossierspcgs66 WHERE decisionsdossierspcgs66.dossierpcg66_id = "Dossierpcg66"."id" ) ) AS "Dossierpcg66__nbpropositions"',
					'( ( SELECT COUNT(*) FROM traitementspcgs66 WHERE traitementspcgs66.personnepcg66_id = "Personnepcg66"."id" ) ) AS "Personnepcg66__nbtraitements"',
					'( SELECT COUNT("fichiermodule"."id") FROM "fichiersmodules" AS "fichiermodule" WHERE "fichiermodule"."modele" = \'Foyer\' AND "fichiermodule"."fk_value" = "Foyer"."id" ) AS "Fichiermodule__nb_fichiers_lies"',
					'( ( "User"."nom" || \' \' || "User"."prenom" ) ) AS "User__nom_complet"',
					'Poledossierpcg66.name',
					'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Situationpdo"."libelle" || \'\' AS "Situationpdo__libelle" FROM "personnespcgs66" AS "Personnepcg66" LEFT OUTER JOIN "public"."personnespcgs66_situationspdos" AS "Personnepcg66Situationpdo" ON ("Personnepcg66Situationpdo"."personnepcg66_id" = "Personnepcg66"."id") LEFT OUTER JOIN "public"."situationspdos" AS "Situationpdo" ON ("Personnepcg66Situationpdo"."situationpdo_id" = "Situationpdo"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Personnepcg66__listemotifs"',
					'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Statutpdo"."libelle" || \'\' AS "Statutpdo__libelle" FROM "personnespcgs66" AS "Personnepcg66" LEFT OUTER JOIN "public"."personnespcgs66_statutspdos" AS "Personnepcg66Statutpdo" ON ("Personnepcg66Statutpdo"."personnepcg66_id" = "Personnepcg66"."id") LEFT OUTER JOIN "public"."statutspdos" AS "Statutpdo" ON ("Personnepcg66Statutpdo"."statutpdo_id" = "Statutpdo"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Personnepcg66__listestatuts"',
					'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Traitementpcg66"."typetraitement" || \'\' AS "Traitementpcg66__typetraitement" FROM "traitementspcgs66" AS "Traitementpcg66" INNER JOIN "public"."personnespcgs66" AS "Personnepcg66" ON ("Traitementpcg66"."personnepcg66_id" = "Personnepcg66"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Dossierpcg66__listetraitements"',
					'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Traitementpcg66"."dateecheance" || \'\' AS "Traitementpcg66__dateecheance" FROM "traitementspcgs66" AS "Traitementpcg66" INNER JOIN "public"."personnespcgs66" AS "Personnepcg66" ON ("Traitementpcg66"."personnepcg66_id" = "Personnepcg66"."id") WHERE "Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ), \'\' ) ) ) AS "Dossierpcg66__dateecheance"',
					'TRIM( BOTH \' \' FROM TRIM( TRAILING \'\' FROM ARRAY_TO_STRING( ARRAY( SELECT \'\\n\\r-\' || "Orgtransmisdossierpcg66"."name" || \'\' AS "Orgtransmisdossierpcg66__name" FROM "decisionsdossierspcgs66" AS "Decisiondossierpcg66" LEFT JOIN "public"."decsdospcgs66_orgsdospcgs66" AS "Decdospcg66Orgdospcg66" ON ("Decdospcg66Orgdospcg66"."decisiondossierpcg66_id" = "Decisiondossierpcg66"."id") LEFT JOIN "public"."orgstransmisdossierspcgs66" AS "Orgtransmisdossierpcg66" ON ("Decdospcg66Orgdospcg66"."orgtransmisdossierpcg66_id" = "Orgtransmisdossierpcg66"."id") WHERE "Decisiondossierpcg66"."dossierpcg66_id" = "Dossierpcg66"."id" ORDER BY "Decisiondossierpcg66"."created" DESC ), \'\' ) ) ) AS "Orgtransmisdossierpcg66__listorgs"',
					'PersonneReferent.referent_id',
					'( ( "Referentparcours"."qual" || \' \' || "Referentparcours"."nom" || \' \' || "Referentparcours"."prenom" ) ) AS "Referentparcours__nom_complet"',
					'Structurereferenteparcours.lib_struc',
				),
				'recursive' => '-1',
				'joins' => array(
					array(
						'table' => '"users"',
						'alias' => 'User',
						'type' => 'LEFT OUTER',
						'conditions' => '"Dossierpcg66"."user_id" = "User"."id"',
					),
					array(
						'table' => '"foyers"',
						'alias' => 'Foyer',
						'type' => 'INNER',
						'conditions' => '"Dossierpcg66"."foyer_id" = "Foyer"."id"',
					),
					array(
						'table' => '"personnes"',
						'alias' => 'Personne',
						'type' => 'INNER',
						'conditions' => '"Personne"."foyer_id" = "Foyer"."id"',
					),
					array(
						'table' => '"personnespcgs66"',
						'alias' => 'Personnepcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Personnepcg66"."dossierpcg66_id" = "Dossierpcg66"."id" AND "Personnepcg66"."personne_id" = "Personne"."id"',
					),
					array(
						'table' => '"traitementspcgs66"',
						'alias' => 'Traitementpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Traitementpcg66"."personnepcg66_id" = "Personnepcg66"."id"',
					),
					array(
						'table' => '"adressesfoyers"',
						'alias' => 'Adressefoyer',
						'type' => 'LEFT OUTER',
						'conditions' => '"Adressefoyer"."foyer_id" = "Foyer"."id"',
					),
					array(
						'table' => '"adresses"',
						'alias' => 'Adresse',
						'type' => 'LEFT OUTER',
						'conditions' => '"Adressefoyer"."adresse_id" = "Adresse"."id"',
					),
					array(
						'table' => '"dossiers"',
						'alias' => 'Dossier',
						'type' => 'INNER',
						'conditions' => '"Foyer"."dossier_id" = "Dossier"."id"',
					),
					array(
						'table' => '"situationsdossiersrsa"',
						'alias' => 'Situationdossierrsa',
						'type' => 'INNER',
						'conditions' => '"Situationdossierrsa"."dossier_id" = "Dossier"."id"',
					),
					array(
						'table' => '"decisionsdossierspcgs66"',
						'alias' => 'Decisiondossierpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Decisiondossierpcg66"."dossierpcg66_id" = "Dossierpcg66"."id"',
					),
					array(
						'table' => '"decisionspdos"',
						'alias' => 'Decisionpdo',
						'type' => 'LEFT OUTER',
						'conditions' => '"Decisiondossierpcg66"."decisionpdo_id" = "Decisionpdo"."id"',
					),
					array(
						'table' => '"detailsdroitsrsa"',
						'alias' => 'Detaildroitrsa',
						'type' => 'LEFT OUTER',
						'conditions' => '"Detaildroitrsa"."dossier_id" = "Dossier"."id"',
					),
					array(
						'table' => '"detailscalculsdroitsrsa"',
						'alias' => 'Detailcalculdroitrsa',
						'type' => 'LEFT OUTER',
						'conditions' => '"Detailcalculdroitrsa"."detaildroitrsa_id" = "Detaildroitrsa"."id"',
					),
					array(
						'table' => '"polesdossierspcgs66"',
						'alias' => 'Poledossierpcg66',
						'type' => 'LEFT OUTER',
						'conditions' => '"Dossierpcg66"."poledossierpcg66_id" = "Poledossierpcg66"."id"',
					),
					array(
						'table' => '"personnes_referents"',
						'alias' => 'PersonneReferent',
						'type' => 'LEFT OUTER',
						'conditions' => '"PersonneReferent"."personne_id" = "Personne"."id" AND (("PersonneReferent"."id" IS NULL) OR ("PersonneReferent"."id" IN ( SELECT "personnes_referents"."id" FROM personnes_referents WHERE "personnes_referents"."personne_id" = "Personne"."id" AND "personnes_referents"."dfdesignation" IS NULL ORDER BY "personnes_referents"."dddesignation" DESC LIMIT 1 )))',
					),
					array(
						'table' => '"referents"',
						'alias' => 'Referentparcours',
						'type' => 'LEFT OUTER',
						'conditions' => '"PersonneReferent"."referent_id" = "Referentparcours"."id"',
					),
					array(
						'table' => '"structuresreferentes"',
						'alias' => 'Structurereferenteparcours',
						'type' => 'LEFT OUTER',
						'conditions' => '"Referentparcours"."structurereferente_id" = "Structurereferenteparcours"."id"',
					),
				),
				'limit' => '10',
				'contain' => '',
				'conditions' => array(
					array(
						array(
							array(
								'OR' => array(
									'Adressefoyer.id IS NULL',
									'Adressefoyer.id IN ( SELECT "adressesfoyers"."id" AS "adressesfoyers__id" FROM "adressesfoyers" AS "adressesfoyers" WHERE "adressesfoyers"."foyer_id" = "Foyer"."id" AND "adressesfoyers"."rgadr" = \'01\' ORDER BY "adressesfoyers"."dtemm" DESC LIMIT 1 )',
								),
							),
							array(
								'OR' => array(
									'Detailcalculdroitrsa.id IS NULL',
									'Detailcalculdroitrsa.id IN ( SELECT "detailscalculsdroitsrsa"."id" AS "detailscalculsdroitsrsa__id" FROM "detailscalculsdroitsrsa" AS "detailscalculsdroitsrsa" WHERE "detailscalculsdroitsrsa"."detaildroitrsa_id" = "Detaildroitrsa"."id" ORDER BY "detailscalculsdroitsrsa"."ddnatdro" DESC LIMIT 1 )',
								),
							),
							array(
								'OR' => array(
									'Decisiondossierpcg66.id IS NULL',
									'Decisiondossierpcg66.id IN ( SELECT decisionsdossierspcgs66.id FROM decisionsdossierspcgs66 WHERE decisionsdossierspcgs66.dossierpcg66_id = Dossierpcg66.id ORDER BY decisionsdossierspcgs66.created DESC LIMIT 1 )',
								),
							),
							'( "Traitementpcg66"."id" IS NULL OR "Traitementpcg66"."id" IN ( SELECT "traitementspcgs66"."id" AS "traitementspcgs66__id" FROM "traitementspcgs66" AS "traitementspcgs66" WHERE "traitementspcgs66"."personnepcg66_id" = "Personnepcg66"."id" ORDER BY "traitementspcgs66"."created" DESC LIMIT 1 ) )',
						),
					),
					'Personne.id IN ( SELECT "personnes"."id" AS "personnes__id" FROM "personnes" AS "personnes" LEFT JOIN "public"."prestations" AS "prestations" ON ("prestations"."personne_id" = "personnes"."id" AND "prestations"."natprest" = \'RSA\') WHERE "personnes"."foyer_id" = "Foyer"."id" AND "prestations"."rolepers" = \'DEM\' LIMIT 1 )',
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