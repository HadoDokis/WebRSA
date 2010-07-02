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
-- *****************************************************************************
COMMIT;
-- *****************************************************************************