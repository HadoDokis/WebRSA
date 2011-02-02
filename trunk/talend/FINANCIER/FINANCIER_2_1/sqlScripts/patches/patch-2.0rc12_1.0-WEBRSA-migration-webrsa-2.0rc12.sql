-- patch-2.0rc12_1.0-WEBRSA-migration-webrsa-2.0rc12.sql
-- *** VERSIONS  ***
-- *** webrsa 2.0rc12
-- *** iRSA v. 3.3 min : INSTRUCTION 1.3-2.1
-- *** Cristal v. 29 min : BENEFICIAIRE/FINANCIER 2.1
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
	Scripts pour le renommage des tables correspondant à la migration vers webrsa_rc12
*******************************************************************************/

-- Renommage des colonnes des tables de statistiques d'intégration
ALTER TABLE statintegrationbeneficiaire RENAME COLUMN adresses_foyers TO adressesfoyers;
ALTER TABLE statintegrationbeneficiaire RENAME COLUMN avispcgdroitrsa TO avispcgdroitsrsa;
ALTER TABLE statintegrationbeneficiaire RENAME COLUMN dossiers_rsa TO dossiers;
ALTER TABLE statintegrationfinancier RENAME COLUMN dossiers_rsa TO dossiers;
ALTER TABLE statintegrationinstruction RENAME COLUMN adresses_foyers TO adressesfoyers;
ALTER TABLE statintegrationinstruction RENAME COLUMN dossiers_rsa TO dossiers;
ALTER TABLE statintegrationinstruction RENAME COLUMN titres_sejour TO titressejour;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************