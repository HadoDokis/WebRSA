-- patch-1.0-WEBRSA-migration-IRSA-V6.sql
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

/*******************************************************************************
	Mise à Jour de la table de statistique Instruction en @RSA V6
*******************************************************************************/
-- StatIntegrationInstruction
ALTER TABLE StatIntegrationInstruction ADD COLUMN conditionsactivitesprealables INTEGER AFTER allocationssoutienfamilial;