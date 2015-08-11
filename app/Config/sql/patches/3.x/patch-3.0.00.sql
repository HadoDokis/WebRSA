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

ALTER TABLE dossiers ADD CONSTRAINT dossiers_fonorg_in_list_chk CHECK ( cakephp_validate_in_list( fonorg, ARRAY[ 'CAF', 'MSA' ] ) );

-- TODO: supprimer la colonne nonrespectssanctionseps93.sortienvcontrat (cf. patch 2.9.02)

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
