-- patch-2.0rc9_1.2-STAGING_RSA-correction_types.sql
-- *** VERSIONS  ***
-- *** webrsa 2.0rc10
-- *** iRSA v. 6 : INSTRUCTION 2.0
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
	Corrections types
*******************************************************************************/

-- staging.rib
ALTER TABLE staging.rib ALTER COLUMN clerib TYPE character varying(2);
ALTER TABLE staging.rib ALTER COLUMN numdebiban TYPE character varying(4);
ALTER TABLE staging.rib ALTER COLUMN numfiniban TYPE character varying(7);
ALTER TABLE staging.rib ALTER COLUMN bic TYPE character varying(11);

-- elementaire.paiementsfoyers
ALTER TABLE elementaire.paiementsfoyers ALTER COLUMN clerib TYPE character varying(2);
ALTER TABLE elementaire.paiementsfoyers ALTER COLUMN numdebiban TYPE character varying(4);
ALTER TABLE elementaire.paiementsfoyers ALTER COLUMN numfiniban TYPE character varying(7);
ALTER TABLE elementaire.paiementsfoyers ALTER COLUMN bic TYPE character varying(11);


-- *****************************************************************************
COMMIT;
-- *****************************************************************************