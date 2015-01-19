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

-- Suppression des dossiers d'EP pour lesquels il n'existe plus d'entrée dans la
-- table de la thématique. Corrige le ticket #7623.
DELETE FROM dossierseps WHERE id IN (
	SELECT
			dossierseps.id/*,
			dossierseps.personne_id,
			dossierseps.themeep*/
		FROM dossierseps
		WHERE
			-- Qui ne possèdent pas d'enregistrement dans leur thématique
			(
				-- 1. CG 93
				(
					dossierseps.themeep IN ( 'contratscomplexeseps93', 'nonorientationsproseps93', 'nonrespectssanctionseps93', 'reorientationseps93', 'signalementseps93' )
					AND NOT EXISTS( SELECT id FROM contratscomplexeseps93 WHERE contratscomplexeseps93.dossierep_id = dossierseps.id )
					AND NOT EXISTS( SELECT id FROM nonorientationsproseps93 WHERE nonorientationsproseps93.dossierep_id = dossierseps.id )
					AND NOT EXISTS( SELECT id FROM nonrespectssanctionseps93 WHERE nonrespectssanctionseps93.dossierep_id = dossierseps.id )
					AND NOT EXISTS( SELECT id FROM reorientationseps93 WHERE reorientationseps93.dossierep_id = dossierseps.id )
					AND NOT EXISTS( SELECT id FROM signalementseps93 WHERE signalementseps93.dossierep_id = dossierseps.id )
				)
				-- 2. CG 66
				OR (
					dossierseps.themeep IN ( 'saisinesbilansparcourseps66', 'defautsinsertionseps66', 'saisinespdoseps66', 'nonorientationsproseps66' )
					AND NOT EXISTS( SELECT id FROM saisinesbilansparcourseps66 WHERE saisinesbilansparcourseps66.dossierep_id = dossierseps.id )
					AND NOT EXISTS( SELECT id FROM defautsinsertionseps66 WHERE defautsinsertionseps66.dossierep_id = dossierseps.id )
					AND NOT EXISTS( SELECT id FROM saisinespdoseps66 WHERE saisinespdoseps66.dossierep_id = dossierseps.id )
					AND NOT EXISTS( SELECT id FROM nonorientationsproseps66 WHERE nonorientationsproseps66.dossierep_id = dossierseps.id )
				)
				-- 3. CG 58
				OR (
					dossierseps.themeep IN ( 'nonorientationsproseps58', 'regressionsorientationseps58', 'sanctionseps58', 'sanctionsrendezvouseps58' )
					AND NOT EXISTS( SELECT id FROM nonorientationsproseps58 WHERE nonorientationsproseps58.dossierep_id = dossierseps.id )
					AND NOT EXISTS( SELECT id FROM regressionsorientationseps58 WHERE regressionsorientationseps58.dossierep_id = dossierseps.id )
					AND NOT EXISTS( SELECT id FROM sanctionseps58 WHERE sanctionseps58.dossierep_id = dossierseps.id )
					AND NOT EXISTS( SELECT id FROM sanctionsrendezvouseps58 WHERE sanctionsrendezvouseps58.dossierep_id = dossierseps.id )
				)
			)
			-- Et qui ne sont pas encore attachés à une commission d'EP ou dont la commission n'a pas encore pris de décision ou dont le dernier passage en commission est un report ou une annulation
			AND NOT EXISTS( SELECT * FROM passagescommissionseps WHERE passagescommissionseps.dossierep_id = dossierseps.id AND etatdossierep IN ( 'decisionep', 'decisioncg', 'traite' ) )
);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************