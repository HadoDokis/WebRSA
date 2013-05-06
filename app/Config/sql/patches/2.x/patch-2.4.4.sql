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

SELECT add_missing_table_field( 'public', 'users', 'email', 'VARCHAR(250)' );
ALTER TABLE users ALTER COLUMN email SET DEFAULT NULL;

ALTER TABLE expsproscers93 ALTER COLUMN nbduree TYPE FLOAT;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
