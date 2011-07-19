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
ALTER TABLE apres ALTER COLUMN precisionsautrelogement TYPE VARCHAR(150);

SELECT add_missing_table_field ('public', 'apres', 'cessderact', 'VARCHAR(4)');
SELECT add_missing_table_field ('public', 'apres', 'nivetu', 'VARCHAR(4)');

SELECT add_missing_table_field ('public', 'relancesapres', 'listepiecemanquante', 'TEXT' );


SELECT add_missing_table_field ('public', 'commissionseps', 'chargesuivi', 'VARCHAR(100)' );
SELECT add_missing_table_field ('public', 'commissionseps', 'gestionnairebat', 'VARCHAR(100)' );
SELECT add_missing_table_field ('public', 'commissionseps', 'gestionnairebada', 'VARCHAR(100)' );


-- *****************************************************************************
COMMIT;
-- *****************************************************************************