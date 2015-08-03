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

DROP INDEX IF EXISTS questionnairesd1pdvs93_personne_id_idx;
CREATE INDEX questionnairesd1pdvs93_personne_id_idx ON questionnairesd1pdvs93(personne_id);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************

