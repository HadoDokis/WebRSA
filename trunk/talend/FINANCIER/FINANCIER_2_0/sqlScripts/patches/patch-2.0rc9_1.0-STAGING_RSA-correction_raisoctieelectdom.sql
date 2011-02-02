-- patch-2.0rc9_1.0-STAGING_RSA-correction_raisoctieelectdom.sql
-- *** VERSIONS  ***
-- *** webrsa 2.0rc10
-- *** iRSA v. 3.3 à 5 : INSTRUCTION 1.2
-- *** Cristal v. 29 min : BENEFICIAIRE/FINANCIER 2.0
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

/*******************************************************************************
	Correction du type de la colonne 'raisoctieelectdom' de 30 à 32 caractères
*******************************************************************************/

ALTER TABLE staging.electiondomicile ALTER COLUMN raisoctieelectdom TYPE character varying(32);
ALTER TABLE elementaire.foyers ALTER COLUMN raisoctieelectdom TYPE character varying(32);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************