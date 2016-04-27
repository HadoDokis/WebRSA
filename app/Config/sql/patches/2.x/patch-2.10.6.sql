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
-- APRE -> ADRE
--------------------------------------------------------------------------------

SELECT add_missing_table_field ('public', 'apres', 'isapre', 'SMALLINT');
ALTER TABLE apres ALTER COLUMN isapre SET DEFAULT '1';
SELECT alter_table_drop_constraint_if_exists ('public', 'apres', 'apres_isapre_in_list_chk');
ALTER TABLE apres ADD CONSTRAINT apres_isapre_in_list_chk CHECK (cakephp_validate_in_list(isapre, ARRAY[0,1]));

DROP TABLE IF EXISTS namesapres66_typesaidesapres66, namesapres66;

CREATE TABLE namesapres66 (
	id                          SERIAL NOT NULL PRIMARY KEY,
	name						VARCHAR(4) NOT NULL
);
INSERT INTO namesapres66 (name) VALUES ('APRE'), ('ADRE');

CREATE TABLE namesapres66_typesaidesapres66 (
	id                          SERIAL NOT NULL PRIMARY KEY,
	nameapre66_id				INTEGER NOT NULL REFERENCES namesapres66(id),
	typeaideapre66_id			INTEGER NOT NULL REFERENCES typesaidesapres66(id)
);

CREATE OR REPLACE FUNCTION public.garnissage_namesapres66_typesaidesapres66() RETURNS bool as
$$
	DECLARE
		v_row		RECORD;
	BEGIN
		FOR v_row IN (SELECT typesaidesapres66.id FROM typesaidesapres66 ORDER BY typesaidesapres66.id)
			LOOP 
				INSERT INTO namesapres66_typesaidesapres66 (nameapre66_id, typeaideapre66_id) VALUES (1, v_row.id);
			END LOOP;
		RETURN false;
	END;
$$
LANGUAGE plpgsql;

SELECT garnissage_namesapres66_typesaidesapres66();
DROP FUNCTION public.garnissage_namesapres66_typesaidesapres66();

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
