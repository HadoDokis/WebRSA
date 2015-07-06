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

--------------------------------------------------------------------------------
-- Tables de corespondance entre personne_id
--------------------------------------------------------------------------------

DROP TABLE IF EXISTS correspondancespersonnes CASCADE;
CREATE TABLE correspondancespersonnes (
    id                          SERIAL NOT NULL PRIMARY KEY,
	personne1_id				INTEGER NOT NULL REFERENCES personnes(id),
	personne2_id				INTEGER NOT NULL REFERENCES personnes(id),
	anomalie					BOOLEAN DEFAULT FALSE
);
COMMENT ON TABLE correspondancespersonnes IS 'correspondancespersonnes';

-- *****************************************************************************
COMMIT;
-- *****************************************************************************