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
-- TODO: supprimer la colonne nonrespectssanctionseps93.sortienvcontrat (cf. patch 2.9.02)
-- *****************************************************************************

SELECT alter_table_drop_constraint_if_exists( 'public', 'dossiers', 'dossiers_fonorg_in_list_chk' );
ALTER TABLE dossiers ADD CONSTRAINT dossiers_fonorg_in_list_chk CHECK ( cakephp_validate_in_list( fonorg, ARRAY[ 'CAF', 'MSA' ] ) );

--------------------------------------------------------------------------------
-- 20150710: Champs "enum" de la table orientsstructs
-- @todo: le reste des 119 tables -> SELECT DISTINCT table_name FROM information_schema.columns WHERE data_type = 'USER-DEFINED' ORDER BY table_name;
--------------------------------------------------------------------------------
ALTER TABLE orientsstructs DROP CONSTRAINT orientsstructs_origine_check;

-- FIXME: suppression du schéma administration (CASCADE) dans la BDD
-- cg93_20150608_v300 car il existe une règle prenant origine en compte dans la
-- vue allocatairestransferes, ce qui empêche la transformation.
SELECT public.table_enumtypes_to_validate_in_list( 'public', 'orientsstructs' );
SELECT public.table_defaultvalues_enumtypes_to_varchar( 'public', 'orientsstructs' );

ALTER TABLE orientsstructs ADD CONSTRAINT orientsstructs_origine_check CHECK(
	( origine IS NULL AND date_valid IS NULL )
	OR (
		( origine IS NOT NULL AND date_valid IS NOT NULL )
		AND (
			( rgorient = 1 AND origine IN ( 'manuelle', 'cohorte' ) )
			OR ( rgorient > 1 AND origine = 'reorientation' )
			OR ( rgorient > 1 AND origine = 'demenagement' )
		)
	)
);

SELECT alter_table_drop_constraint_if_exists( 'public', 'orientsstructs', 'orientsstructs_statutrelance_in_list_chk' );
ALTER TABLE orientsstructs ADD CONSTRAINT orientsstructs_statutrelance_in_list_chk CHECK ( cakephp_validate_in_list( statutrelance, ARRAY[ 'E', 'R' ] ) );

SELECT alter_table_drop_constraint_if_exists( 'public', 'orientsstructs', 'orientsstructs_statut_orient_in_list_chk' );
ALTER TABLE orientsstructs ADD CONSTRAINT orientsstructs_statut_orient_in_list_chk CHECK ( cakephp_validate_in_list( statut_orient, ARRAY[ 'Orienté', 'En attente', 'Non orienté' ] ) );

ALTER TABLE orientsstructs ALTER COLUMN etatorient SET DEFAULT NULL;

--------------------------------------------------------------------------------
-- 20150723: Champs "enum" de la table foyers
-- @todo >(sitfam|typeocclog)
--------------------------------------------------------------------------------

SELECT public.table_enumtypes_to_validate_in_list( 'public', 'foyers' );
SELECT public.table_defaultvalues_enumtypes_to_varchar( 'public', 'foyers' );

-- Certaines entrées avaient une chaîne de texte vide à la place de NULL
-- FIXME: le signaler pour les jobs talend et vérifier à la sauvegarde dans l'application
UPDATE foyers SET sitfam = NULL WHERE TRIM( BOTH ' ' FROM sitfam ) = '';
SELECT alter_table_drop_constraint_if_exists( 'public', 'foyers', 'foyers_sitfam_in_list_chk' );
ALTER TABLE foyers ADD CONSTRAINT foyers_sitfam_in_list_chk CHECK ( cakephp_validate_in_list( sitfam, ARRAY[ 'ABA', 'CEL', 'DIV', 'ISO', 'MAR', 'PAC', 'RPA', 'RVC', 'RVM', 'SEF', 'SEL', 'VEU', 'VIM' ] ) );
ALTER TABLE foyers ALTER COLUMN sitfam SET DEFAULT NULL;

-- Certaines entrées avaient une chaîne de texte vide à la place de NULL
-- FIXME: le signaler pour les jobs talend et vérifier à la sauvegarde dans l'application
UPDATE foyers SET typeocclog = NULL WHERE TRIM( BOTH ' ' FROM typeocclog ) = '';
SELECT alter_table_drop_constraint_if_exists( 'public', 'foyers', 'foyers_typeocclog_in_list_chk' );
ALTER TABLE foyers ADD CONSTRAINT foyers_typeocclog_in_list_chk CHECK ( cakephp_validate_in_list( typeocclog, ARRAY[ 'ACC', 'BAL', 'HCG', 'HCO', 'HGP', 'HOP', 'HOT', 'LOC', 'OLI', 'PRO', 'SRG', 'SRO' ] ) );
ALTER TABLE foyers ALTER COLUMN typeocclog SET DEFAULT NULL;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
