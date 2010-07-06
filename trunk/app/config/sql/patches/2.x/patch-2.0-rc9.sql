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
-----------------------------  Ajout du 01/07/2010 ----------------------------
ALTER TABLE propospdos ADD COLUMN structurereferente_id INTEGER DEFAULT NULL REFERENCES structuresreferentes(id);
CREATE TYPE type_iscomplet AS ENUM ( 'COM', 'INC' );
ALTER TABLE propospdos ADD COLUMN iscomplet type_iscomplet DEFAULT NULL;
ALTER TABLE propospdos ADD COLUMN isvalidation type_booleannumber DEFAULT NULL;
ALTER TABLE propospdos ADD COLUMN validationdecision type_no DEFAULT NULL;
ALTER TABLE propospdos ADD COLUMN datevalidationdecision DATE;

ALTER TABLE propospdos ADD COLUMN isdecisionop type_booleannumber DEFAULT NULL;
ALTER TABLE propospdos ADD COLUMN decisionop type_decisioncomite DEFAULT NULL; -- FIXME: voir les champs à ajouter pr le moment ACC, REF, AJ
ALTER TABLE propospdos ADD COLUMN datedecisionop DATE;
ALTER TABLE propospdos ADD COLUMN observationoop TEXT;

ALTER TABLE cuis ALTER COLUMN compladremployeur DROP NOT NULL;

-----------------------------  Ajout du 05/07/2010 ----------------------------
CREATE TYPE type_etatdossierpdo AS ENUM ( '1', '2', '3', '4', '5', '6' );
ALTER TABLE propospdos ADD COLUMN etatdossierpdo type_etatdossierpdo DEFAULT NULL;



-- Champs manquants pour le passage en version v.32 de Cristal
ALTER TABLE personnes ADD COLUMN numagenpoleemploi CHAR(3) DEFAULT NULL;
ALTER TABLE personnes ADD COLUMN dtinscpoleemploi DATE DEFAULT NULL;
ALTER TABLE suspensionsdroits ADD COLUMN natgroupfsus CHAR(3) DEFAULT NULL;

-----------------------------  Ajout du 06/07/2010 ----------------------------
ALTER TABLE traitementspdos ADD COLUMN hasficheanalyse type_booleannumber DEFAULT NULL;
-- *****************************************************************************
COMMIT;
-- *****************************************************************************