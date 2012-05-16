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

-- 20120514: suppression des dossiers d'EP n'ayant pas d'entrée dans les tables de thématiques
DELETE FROM dossierseps WHERE themeep = 'reorientationseps93' AND dossierseps.id NOT IN (SELECT reorientationseps93.dossierep_id FROM reorientationseps93);
DELETE FROM dossierseps WHERE themeep = 'saisinesbilansparcourseps66' AND dossierseps.id NOT IN (SELECT saisinesbilansparcourseps66.dossierep_id FROM saisinesbilansparcourseps66);
DELETE FROM dossierseps WHERE themeep = 'saisinespdoseps66' AND dossierseps.id NOT IN (SELECT saisinespdoseps66.dossierep_id FROM saisinespdoseps66);
DELETE FROM dossierseps WHERE themeep = 'nonrespectssanctionseps93' AND dossierseps.id NOT IN (SELECT nonrespectssanctionseps93.dossierep_id FROM nonrespectssanctionseps93);
DELETE FROM dossierseps WHERE themeep = 'defautsinsertionseps66' AND dossierseps.id NOT IN (SELECT defautsinsertionseps66.dossierep_id FROM defautsinsertionseps66);
DELETE FROM dossierseps WHERE themeep = 'nonorientationsproseps58' AND dossierseps.id NOT IN (SELECT nonorientationsproseps58.dossierep_id FROM nonorientationsproseps58);
DELETE FROM dossierseps WHERE themeep = 'nonorientationsproseps93' AND dossierseps.id NOT IN (SELECT nonorientationsproseps93.dossierep_id FROM nonorientationsproseps93);
DELETE FROM dossierseps WHERE themeep = 'regressionsorientationseps58' AND dossierseps.id NOT IN (SELECT regressionsorientationseps58.dossierep_id FROM regressionsorientationseps58);
DELETE FROM dossierseps WHERE themeep = 'sanctionseps58' AND dossierseps.id NOT IN (SELECT sanctionseps58.dossierep_id FROM sanctionseps58);
DELETE FROM dossierseps WHERE themeep = 'signalementseps93' AND dossierseps.id NOT IN (SELECT signalementseps93.dossierep_id FROM signalementseps93);
DELETE FROM dossierseps WHERE themeep = 'sanctionsrendezvouseps58' AND dossierseps.id NOT IN (SELECT sanctionsrendezvouseps58.dossierep_id FROM sanctionsrendezvouseps58);
DELETE FROM dossierseps WHERE themeep = 'contratscomplexeseps93' AND dossierseps.id NOT IN (SELECT contratscomplexeseps93.dossierep_id FROM contratscomplexeseps93);
DELETE FROM dossierseps WHERE themeep = 'nonorientationsproseps66' AND dossierseps.id NOT IN (SELECT nonorientationsproseps66.dossierep_id FROM nonorientationsproseps66);

-- 20120514: suppression des dossiers de COV n'ayant pas d'entrée dans les tables de thématiques
DELETE FROM dossierscovs58 WHERE themecov58 = 'proposorientationscovs58' AND dossierscovs58.id NOT IN (SELECT proposorientationscovs58.dossiercov58_id FROM proposorientationscovs58);
DELETE FROM dossierscovs58 WHERE themecov58 = 'proposcontratsinsertioncovs58' AND dossierscovs58.id NOT IN (SELECT proposcontratsinsertioncovs58.dossiercov58_id FROM proposcontratsinsertioncovs58);
DELETE FROM dossierscovs58 WHERE themecov58 = 'proposnonorientationsproscovs58' AND dossierscovs58.id NOT IN (SELECT proposnonorientationsproscovs58.dossiercov58_id FROM proposnonorientationsproscovs58);

-- 20120516: création d'indexes uniques pour les décisions COV

-- Il faut d'abord supprimer les vrais doublons éventuels.
DELETE FROM decisionsproposorientationscovs58
	WHERE decisionsproposorientationscovs58.id IN (
		SELECT
				d1.id
			FROM decisionsproposorientationscovs58 AS d1, decisionsproposorientationscovs58 AS d2
			WHERE
				d1.id <> d2.id
				AND d1.passagecov58_id = d2.passagecov58_id
				AND d1.etapecov = d2.etapecov
				AND d1.decisioncov = d2.decisioncov
				AND d1.typeorient_id = d2.typeorient_id
				AND d1.structurereferente_id = d2.structurereferente_id
				AND d1.referent_id = d2.referent_id
				AND d1.datevalidation IS NULL
				AND d1.commentaire IS NULL
				AND d1.modified < d2.modified
	);

DROP INDEX IF EXISTS decisionsproposcontratsinsertioncovs58_passagecov58_id_idx;
CREATE UNIQUE INDEX decisionsproposcontratsinsertioncovs58_passagecov58_id_idx ON decisionsproposcontratsinsertioncovs58 (passagecov58_id);

DROP INDEX IF EXISTS decisionsproposnonorientationsproscovs58_passagecov58_id_idx;
CREATE UNIQUE INDEX decisionsproposnonorientationsproscovs58_passagecov58_id_idx ON decisionsproposnonorientationsproscovs58 (passagecov58_id);

DROP INDEX IF EXISTS decisionsproposorientationscovs58_passagecov58_id_idx;
CREATE UNIQUE INDEX decisionsproposorientationscovs58_passagecov58_id_idx ON decisionsproposorientationscovs58 (passagecov58_id);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************