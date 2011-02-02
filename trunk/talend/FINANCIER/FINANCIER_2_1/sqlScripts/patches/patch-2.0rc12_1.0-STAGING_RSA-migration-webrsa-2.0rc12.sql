-- patch-2.0rc12_1.0-STAGING_RSA-migration-webrsa-2.0rc12.sql
-- *** VERSIONS  ***
-- *** webrsa 2.0rc12
-- *** iRSA v. 3.3  min : INSTRUCTION 1.3-2.1
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

-- Renommage de la table dossiers_rsa en dossiers
ALTER TABLE elementaire.dossiers_rsa RENAME TO dossiers;
ALTER TABLE elementaire.b_dossiers_rsa RENAME TO b_dossiers;
ALTER TABLE elementaire.f_dossiers_rsa RENAME TO f_dossiers;

-- Renommage de la table adresses_foyers en adressesfoyers
ALTER TABLE elementaire.adresses_foyers RENAME TO adressesfoyers;
ALTER TABLE elementaire.b_adresses_foyers RENAME TO b_adressesfoyers;
ALTER TABLE elementaire.f_adresses_foyers RENAME TO f_adressesfoyers;

-- Renommage de la table titres_sejour en titressejour
ALTER TABLE elementaire.titres_sejour RENAME TO titressejour;

-- Renommage des tables qui ne sont pas au pluriel
ALTER TABLE elementaire.b_avispcgdroitrsa RENAME TO b_avispcgdroitsrsa;

-- Renommage des clés étrangères
ALTER TABLE elementaire.dossiers RENAME COLUMN details_droits_rsa_id TO detaildroitrsa_id;
ALTER TABLE elementaire.dossiers RENAME COLUMN avis_pcg_id TO avispcgdroitrsa_id;

-- Renommage des colonnes des tables de statistiques d'intégration
ALTER TABLE administration.statintegrationbeneficiaire RENAME COLUMN elem_adresses_foyers TO elem_adressesfoyers;
ALTER TABLE administration.statintegrationbeneficiaire RENAME COLUMN elem_avispcgdroitrsa TO elem_avispcgdroitsrsa;
ALTER TABLE administration.statintegrationbeneficiaire RENAME COLUMN elem_dossiers_rsa TO elem_dossiers;
ALTER TABLE administration.statintegrationbeneficiaire RENAME COLUMN webav_adresses_foyers TO webav_adressesfoyers;
ALTER TABLE administration.statintegrationbeneficiaire RENAME COLUMN webav_avispcgdroitrsa TO webav_avispcgdroitsrsa;
ALTER TABLE administration.statintegrationbeneficiaire RENAME COLUMN webav_dossiers_rsa TO webav_dossiers;
ALTER TABLE administration.statintegrationbeneficiaire RENAME COLUMN webap_adresses_foyers TO webap_adressesfoyers;
ALTER TABLE administration.statintegrationbeneficiaire RENAME COLUMN webap_avispcgdroitrsa TO webap_avispcgdroitsrsa;
ALTER TABLE administration.statintegrationbeneficiaire RENAME COLUMN webap_dossiers_rsa TO webap_dossiers;
ALTER TABLE administration.statintegrationbeneficiaire RENAME COLUMN flux_adresses_foyers TO flux_adressesfoyers;
ALTER TABLE administration.statintegrationbeneficiaire RENAME COLUMN flux_avispcgdroitrsa TO flux_avispcgdroitsrsa;
ALTER TABLE administration.statintegrationbeneficiaire RENAME COLUMN flux_dossiers_rsa TO flux_dossiers;
ALTER TABLE administration.statintegrationfinancier RENAME COLUMN elem_dossiers_rsa TO elem_dossiers;
ALTER TABLE administration.statintegrationfinancier RENAME COLUMN webav_dossiers_rsa TO webav_dossiers;
ALTER TABLE administration.statintegrationfinancier RENAME COLUMN webap_dossiers_rsa TO webap_dossiers;
ALTER TABLE administration.statintegrationfinancier RENAME COLUMN flux_dossiers_rsa TO flux_dossiers;
ALTER TABLE administration.statintegrationinstruction RENAME COLUMN elem_adresses_foyers TO elem_adressesfoyers;
ALTER TABLE administration.statintegrationinstruction RENAME COLUMN elem_dossiers_rsa TO elem_dossiers;
ALTER TABLE administration.statintegrationinstruction RENAME COLUMN elem_titres_sejour TO elem_titressejour;
ALTER TABLE administration.statintegrationinstruction RENAME COLUMN webav_adresses_foyers TO webav_adressesfoyers;
ALTER TABLE administration.statintegrationinstruction RENAME COLUMN webav_dossiers_rsa TO webav_dossiers;
ALTER TABLE administration.statintegrationinstruction RENAME COLUMN webav_titres_sejour TO webav_titressejour;
ALTER TABLE administration.statintegrationinstruction RENAME COLUMN webap_adresses_foyers TO webap_adressesfoyers;
ALTER TABLE administration.statintegrationinstruction RENAME COLUMN webap_dossiers_rsa TO webap_dossiers;
ALTER TABLE administration.statintegrationinstruction RENAME COLUMN webap_titres_sejour TO webap_titressejour;
ALTER TABLE administration.statintegrationinstruction RENAME COLUMN flux_adresses_foyers TO flux_adressesfoyers;
ALTER TABLE administration.statintegrationinstruction RENAME COLUMN flux_dossiers_rsa TO flux_dossiers;
ALTER TABLE administration.statintegrationinstruction RENAME COLUMN flux_titres_sejour TO flux_titressejour;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************