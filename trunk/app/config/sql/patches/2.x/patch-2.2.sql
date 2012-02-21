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
SELECT add_missing_table_field ('public', 'traitementspcgs66', 'reversedo', 'TYPE_BOOLEANNUMBER');
ALTER TABLE traitementspcgs66 ALTER COLUMN reversedo SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
UPDATE traitementspcgs66 SET reversedo = '0'::TYPE_BOOLEANNUMBER WHERE reversedo IS NULL;
ALTER TABLE traitementspcgs66 ALTER COLUMN reversedo SET NOT NULL;


SELECT add_missing_table_field ('public', 'bilansparcours66', 'personne_id', 'INTEGER');
SELECT add_missing_constraint ('public', 'bilansparcours66', 'bilansparcours66_personne_id_fkey', 'personnes', 'personne_id');
UPDATE bilansparcours66
	SET personne_id = (
		SELECT orientsstructs.personne_id
			FROM orientsstructs
			WHERE orientsstructs.id = orientstruct_id
	);
ALTER TABLE bilansparcours66 ALTER COLUMN personne_id SET NOT NULL;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************