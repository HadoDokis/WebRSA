
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
BEGIN;
-- *****************************************************************************
----------------------------------------------------------------------------------------
-- 20130927 : Création d'une table de paramétrage pour les programmes en lien
--          avec les fiches de candidature région
----------------------------------------------------------------------------------------

DROP TABLE IF EXISTS progsfichescandidatures66 CASCADE;
CREATE TABLE progsfichescandidatures66(
    id                          SERIAL NOT NULL PRIMARY KEY,
    name                        VARCHAR(20) NOT NULL,
    isactif                     VARCHAR(1) NOT NULL DEFAULT '1',
	----------------------------------------------------------------------------
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE progsfichescandidatures66 IS 'Table des différents programmes proposés par les fiches de candidature Région (CG66)';

DROP INDEX IF EXISTS progsfichescandidatures66_name_idx;
CREATE INDEX progsfichescandidatures66_name_idx ON progsfichescandidatures66( name );

DROP INDEX IF EXISTS progsfichescandidatures66_isactif_idx;
CREATE INDEX progsfichescandidatures66_isactif_idx ON progsfichescandidatures66( isactif );

SELECT alter_table_drop_constraint_if_exists( 'public', 'progsfichescandidatures66', 'progsfichescandidatures66_isactif_in_list_chk' );
ALTER TABLE progsfichescandidatures66 ADD CONSTRAINT progsfichescandidatures66_isactif_in_list_chk CHECK ( cakephp_validate_in_list( isactif, ARRAY['0', '1'] ) );

SELECT add_missing_table_field ( 'public', 'actionscandidats_personnes', 'formationregion', 'VARCHAR(250)' );

----------------------------------------------------------------------------------------
-- 20130927 : Création d'une table de liaison entre
--            les fiches de candidature et les programmes région
----------------------------------------------------------------------------------------
DROP TABLE IF EXISTS candidatures_progs66 CASCADE;
CREATE TABLE candidatures_progs66(
    id                              SERIAL NOT NULL PRIMARY KEY,
    actioncandidat_personne_id      INTEGER NOT NULL REFERENCES actionscandidats_personnes(id) ON DELETE CASCADE ON UPDATE CASCADE,
    progfichecandidature66_id       INTEGER NOT NULL REFERENCES progsfichescandidatures66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	----------------------------------------------------------------------------
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE candidatures_progs66 IS 'Table de liaison entre les fiches de candidature région et les programmes liés (CG66)';

DROP INDEX IF EXISTS candidatures_progs66_actioncandidat_personne_id_idx;
CREATE INDEX candidatures_progs66_actioncandidat_personne_id_idx ON candidatures_progs66( actioncandidat_personne_id );

DROP INDEX IF EXISTS candidatures_progs66_progfichecandidature66_id_idx;
CREATE INDEX candidatures_progs66_progfichecandidature66_id_idx ON candidatures_progs66( progfichecandidature66_id );

DROP INDEX IF EXISTS candidatures_progs66_actioncandidat_personne_id_progfichecandidature66_id_idx;
CREATE UNIQUE INDEX candidatures_progs66_actioncandidat_personne_id_progfichecandidature66_id_idx ON candidatures_progs66(actioncandidat_personne_id,progfichecandidature66_id);

-- *****************************************************************************
-- 201310022:  Module FSE, CG 93
-- *****************************************************************************

DROP TABLE IF EXISTS sortiesaccompagnementsd2pdvs93 CASCADE;
CREATE TABLE sortiesaccompagnementsd2pdvs93 (
	id			SERIAL NOT NULL PRIMARY KEY,
	name		VARCHAR(255) NOT NULL,
	parent_id	INTEGER DEFAULT NULL REFERENCES sortiesaccompagnementsd2pdvs93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	created		TIMESTAMP WITHOUT TIME ZONE,
	modified	TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE sortiesaccompagnementsd2pdvs93 IS 'Intitulés des motifs de sortie de l''accompagnement du formulaire/tableau D2';

CREATE UNIQUE INDEX sortiesaccompagnementsd2pdvs93_name_idx ON sortiesaccompagnementsd2pdvs93( name );
CREATE INDEX sortiesaccompagnementsd2pdvs93_parent_id_idx ON sortiesaccompagnementsd2pdvs93( parent_id );

--------------------------------------------------------------------------------

DROP TABLE IF EXISTS questionnairesd2pdvs93 CASCADE;
CREATE TABLE questionnairesd2pdvs93 (
    id								SERIAL NOT NULL PRIMARY KEY,
    personne_id						INTEGER NOT NULL REFERENCES personnes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	questionnaired1pdv93_id			INTEGER NOT NULL REFERENCES questionnairesd1pdvs93(id) ON DELETE CASCADE ON UPDATE CASCADE,
    structurereferente_id			INTEGER NOT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	situationaccompagnement			VARCHAR(25) NOT NULL,
	sortieaccompagnementd2pdv93_id	INTEGER DEFAULT NULL REFERENCES sortiesaccompagnementsd2pdvs93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	chgmentsituationadmin			VARCHAR(25) DEFAULT NULL,
    created							TIMESTAMP WITHOUT TIME ZONE,
    modified						TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE questionnairesd2pdvs93 IS 'Réponses au formulaire D2 pour les PDV du CG 93';

CREATE INDEX questionnairesd2pdvs93_personne_id_idx ON questionnairesd2pdvs93( personne_id );
CREATE INDEX questionnairesd2pdvs93_questionnaired1pdv93_id_idx ON questionnairesd2pdvs93( questionnaired1pdv93_id );
CREATE INDEX questionnairesd2pdvs93_structurereferente_id_idx ON questionnairesd2pdvs93( structurereferente_id );
CREATE INDEX questionnairesd2pdvs93_sortieaccompagnementd2pdv93_id_idx ON questionnairesd2pdvs93( sortieaccompagnementd2pdv93_id );

SELECT alter_table_drop_constraint_if_exists( 'public', 'questionnairesd2pdvs93', 'questionnairesd2pdvs93_situationaccompagnement_in_list_chk' );
ALTER TABLE questionnairesd2pdvs93 ADD CONSTRAINT questionnairesd2pdvs93_situationaccompagnement_in_list_chk CHECK ( cakephp_validate_in_list( situationaccompagnement, ARRAY['maintien', 'sortie_obligation', 'abandon', 'reorientation', 'changement_situation'] ) );

SELECT alter_table_drop_constraint_if_exists( 'public', 'questionnairesd2pdvs93', 'questionnairesd2pdvs93_chgmentsituationadmin_in_list_chk' );
ALTER TABLE questionnairesd2pdvs93 ADD CONSTRAINT questionnairesd2pdvs93_chgmentsituationadmin_in_list_chk CHECK ( cakephp_validate_in_list( chgmentsituationadmin, ARRAY['modif_sitfam', 'modif_situ_cjt', 'modif_departement', 'modif_commune', 'radiation', 'autres'] ) );

--------------------------------------------------------------------------------
-- Tables supplémentaires à créer pour D1/D2
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS populationsd1d2pdvs93 CASCADE;
CREATE TABLE populationsd1d2pdvs93 (
    id                          SERIAL NOT NULL PRIMARY KEY,
    questionnaired1pdv93_id		INTEGER DEFAULT NULL REFERENCES questionnairesd1pdvs93(id) ON DELETE CASCADE ON UPDATE CASCADE,
    questionnaired2pdv93_id		INTEGER DEFAULT NULL REFERENCES questionnairesd2pdvs93(id) ON DELETE CASCADE ON UPDATE CASCADE,
    tableausuivipdv93_id		INTEGER NOT NULL REFERENCES tableauxsuivispdvs93(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE populationsd1d2pdvs93 IS 'La population prise en compte pour les tableaux hisoricisés D1 ou D2';

-- On s'assure que soit questionnaired1pdv93_id, soit questionnaired2pdv93_id possède une valeur
SELECT alter_table_drop_constraint_if_exists( 'public', 'populationsd1d2pdvs93', 'populationsd1d2pdvs93_qd1_id_qd2_id_check' );
ALTER TABLE populationsd1d2pdvs93 ADD CONSTRAINT populationsd1d2pdvs93_qd1_id_qd2_id_check CHECK(
	( questionnaired1pdv93_id IS NOT NULL AND questionnaired2pdv93_id IS NULL )
	OR ( questionnaired1pdv93_id IS NULL AND questionnaired2pdv93_id IS NOT NULL )
);

--------------------------------------------------------------------------------
-- Modification des restrictions de la liste des tableaux de suivis PDV
--------------------------------------------------------------------------------

SELECT alter_table_drop_constraint_if_exists( 'public', 'tableauxsuivispdvs93', 'tableauxsuivispdvs93_name_in_list_chk' );
ALTER TABLE tableauxsuivispdvs93 ADD CONSTRAINT tableauxsuivispdvs93_name_in_list_chk CHECK ( cakephp_validate_in_list( name, ARRAY['tableaud1', 'tableaud2', 'tableau1b3', 'tableau1b4', 'tableau1b5', 'tableau1b6'] ) );

----------------------------------------------------------------------------------------
-- 20131028 : Ajout de la date d'impression de la tacite reconduction (CG66)
---------------------------------------------------------------------------------------
SELECT add_missing_table_field ( 'public', 'contratsinsertion', 'datetacitereconduction', 'DATE' );

--------------------------------------------------------------------------------
-- 20131030 : Ajout des sorties de l'accompagnement des enregistrements se
-- trouvant déjà en base pour le module FSE (CG93)
--------------------------------------------------------------------------------

INSERT INTO questionnairesd2pdvs93 ( personne_id, questionnaired1pdv93_id, structurereferente_id, situationaccompagnement, chgmentsituationadmin, created, modified )
	SELECT
			personnes.id AS personne_id,
			questionnairesd1pdvs93.id AS questionnaired1pdv93_id,
			rendezvous.structurereferente_id AS structurereferente_id,
			( CASE
				WHEN ( adresses.id IS NOT NULL OR transfertspdvs93.id IS NOT NULL ) THEN 'changement_situation'
				WHEN ( decisionsreorientationseps93.id IS NOT NULL OR decisionsnonorientationsproseps93.id IS NOT NULL ) THEN 'reorientation'
				WHEN ( decisionssignalementseps93.id IS NOT NULL ) THEN 'abandon'
				ELSE NULL
			END ) AS situationaccompagnement,
			( CASE
				WHEN ( adresses.id IS NOT NULL ) THEN 'modif_departement'
				WHEN ( transfertspdvs93.id IS NOT NULL ) THEN 'modif_commune'
				ELSE NULL
			END ) AS chgmentsituationadmin,
			( CASE
				WHEN ( adresses.id IS NOT NULL ) THEN adressesfoyers.dtemm
				WHEN ( transfertspdvs93.id IS NOT NULL ) THEN transfertspdvs93.created
				ELSE commissionseps.dateseance
			END ) AS created,
			( CASE
				WHEN ( adresses.id IS NOT NULL ) THEN adressesfoyers.dtemm
				WHEN ( transfertspdvs93.id IS NOT NULL ) THEN transfertspdvs93.created
				ELSE commissionseps.dateseance
			END ) AS modified
		FROM questionnairesd1pdvs93
			INNER JOIN rendezvous ON ( questionnairesd1pdvs93.rendezvous_id = rendezvous.id )
			INNER JOIN personnes ON ( questionnairesd1pdvs93.personne_id = personnes.id )
			INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
			LEFT OUTER JOIN adressesfoyers ON (
				adressesfoyers.foyer_id = foyers.id
				AND adressesfoyers.rgadr = '01'
				AND adressesfoyers.id IN ( SELECT "dernieresadressesfoyers"."id" FROM adressesfoyers AS "dernieresadressesfoyers" WHERE "dernieresadressesfoyers"."foyer_id" = foyers."id" AND "dernieresadressesfoyers"."rgadr" = '01' ORDER BY "dernieresadressesfoyers"."dtemm" DESC LIMIT 1 )
				AND adressesfoyers.dtemm > rendezvous.daterdv
			)
			LEFT OUTER JOIN adresses ON (
				adressesfoyers.adresse_id = adresses.id
				AND adresses.numcomptt NOT LIKE '93%'
			)
			LEFT OUTER JOIN dossierseps ON (
				dossierseps.personne_id = personnes.id
				AND dossierseps.themeep IN ( 'reorientationseps93', 'nonorientationsproseps93', 'nonrespectssanctionseps93' )
			)
			LEFT OUTER JOIN passagescommissionseps ON (
				passagescommissionseps.dossierep_id = dossierseps.id
				AND passagescommissionseps.id IN (
					SELECT dernierspassagescommissionseps.id
						FROM passagescommissionseps AS dernierspassagescommissionseps
							INNER JOIN commissionseps AS dernierescommissionseps ON ( dernierescommissionseps.id = dernierspassagescommissionseps.commissionep_id )
						WHERE dernierspassagescommissionseps.dossierep_id = passagescommissionseps.dossierep_id
						ORDER BY dernierescommissionseps.dateseance DESC
						LIMIT 1
				)
				AND passagescommissionseps.etatdossierep = 'traite'
			)
			-- Décision d'EP réorientation
			LEFT OUTER JOIN decisionsreorientationseps93 ON (
				decisionsreorientationseps93.passagecommissionep_id = passagescommissionseps.id
				AND decisionsreorientationseps93.etape = 'cg'
				AND decisionsreorientationseps93.decision = 'accepte'
			)
			-- Décision d'EP réorientation
			LEFT OUTER JOIN decisionsnonorientationsproseps93 ON (
				decisionsnonorientationsproseps93.passagecommissionep_id = passagescommissionseps.id
				AND decisionsnonorientationsproseps93.etape = 'cg'
				AND decisionsnonorientationsproseps93.decision = 'reorientation'
			)
			-- Décision d'EP sanction
			LEFT OUTER JOIN decisionssignalementseps93 ON (
				decisionssignalementseps93.passagecommissionep_id = passagescommissionseps.id
				AND decisionssignalementseps93.etape = 'cg'
				AND decisionssignalementseps93.decision IN ( '1reduction', '2suspensiontotale', '2suspensionpartielle' )
			)
			-- Décision d'EP sanction
			LEFT OUTER JOIN commissionseps ON (
				commissionseps.id = passagescommissionseps.commissionep_id
			)
			-- Transfert de PDV ?
			LEFT OUTER JOIN orientsstructs AS vxorientsstructs ON (
				vxorientsstructs.personne_id = personnes.id
				AND vxorientsstructs.statut_orient = 'Orienté'
			)
			LEFT OUTER JOIN orientsstructs AS nvorientsstructs ON (
				nvorientsstructs.personne_id = personnes.id
				AND nvorientsstructs.statut_orient = 'Orienté'
			)
			LEFT OUTER JOIN transfertspdvs93 ON (
				vxorientsstructs.id = transfertspdvs93.vx_orientstruct_id
				AND nvorientsstructs.id = transfertspdvs93.nv_orientstruct_id
			)
		WHERE
			-- Possédant un D1 sans D2
			questionnairesd1pdvs93.id NOT IN (
				SELECT questionnairesd2pdvs93.questionnaired1pdv93_id
					FROM questionnairesd2pdvs93
					WHERE
						questionnairesd2pdvs93.personne_id = questionnairesd1pdvs93.personne_id
						AND questionnairesd2pdvs93.structurereferente_id = rendezvous.structurereferente_id
						AND EXTRACT( 'YEAR' FROM rendezvous.daterdv ) = EXTRACT( 'YEAR' FROM questionnairesd2pdvs93.created )
			)
			AND (
				-- Changement de département après D1
				(
					adresses.numcomptt NOT LIKE '93%'
					AND adressesfoyers.dtemm > rendezvous.daterdv
				)
				OR
				-- Transfert PDV après D1
				(
					transfertspdvs93.id IS NOT NULL
					AND (
						vxorientsstructs.typeorient_id <> nvorientsstructs.typeorient_id
						OR vxorientsstructs.structurereferente_id <> nvorientsstructs.structurereferente_id
					)
					AND DATE_TRUNC( 'DAY', transfertspdvs93.created ) > rendezvous.daterdv
				)
				OR
				-- Décision d'EP réorientation
				(
					DATE_TRUNC( 'DAY', commissionseps.dateseance ) > rendezvous.daterdv
					AND (
						decisionsreorientationseps93.id IS NOT NULL
						OR decisionsnonorientationsproseps93.id IS NOT NULL
						OR decisionssignalementseps93.id IS NOT NULL
					)
				)
			);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
