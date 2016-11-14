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
-- commeDroits des anciens moteurs en AroAco
--------------------------------------------------------------------------------

DELETE FROM acos WHERE alias = 'Module:Criteresdossierspcgs66';

CREATE OR REPLACE FUNCTION copy_permission_dossierspcgs66() RETURNS void AS
$$
DECLARE
	v_row record;
	module_id integer;
	exportcsv_aco_id integer;

BEGIN

	module_id := (SELECT id FROM acos WHERE alias =  'Module:Dossierspcgs66');

	
	UPDATE acos
		SET parent_id = module_id,
			alias = 'Dossierspcgs66:cohorte_atransmettre'
		WHERE alias = 'Cohortesdossierspcgs66:atransmettre';
	
	UPDATE acos
		SET parent_id = module_id,
			alias = 'Dossierspcgs66:cohorte_enattenteaffectation'
		WHERE alias = 'Cohortesdossierspcgs66:enattenteaffectation';
	
	UPDATE acos
		SET parent_id = module_id,
			alias = 'Dossierspcgs66:cohorte_imprimer'
		WHERE alias = 'Cohortesdossierspcgs66:aimprimer';
	
	UPDATE acos
		SET parent_id = module_id,
			alias = 'Dossierspcgs66:exportcsv'
		WHERE alias = 'Criteresdossierspcgs66:exportcsv';
	
	UPDATE acos
		SET parent_id = module_id,
			alias = 'Dossierspcgs66:search'
		WHERE alias = 'Criteresdossierspcgs66:dossier';
	
	UPDATE acos
		SET parent_id = module_id,
			alias = 'Dossierspcgs66:search_affectes'
		WHERE alias = 'Cohortesdossierspcgs66:affectes';
	
	UPDATE acos
		SET parent_id = module_id,
			alias = 'Dossierspcgs66:search_gestionnaire'
		WHERE alias = 'Criteresdossierspcgs66:gestionnaire';



    IF NOT EXISTS(SELECT * FROM acos 
		WHERE alias = 'Dossierspcgs66:exportcsv_gestionnaire') THEN

        INSERT INTO acos (parent_id, model, foreign_key, alias, lft, rght)
			VALUES (module_id, '', 0, 'Dossierspcgs66:exportcsv_gestionnaire', 0, 0);

		exportcsv_aco_id := (SELECT id FROM acos WHERE alias = 'Dossierspcgs66:exportcsv_gestionnaire');

		FOR v_row IN 
			SELECT * FROM aros_acos rc
			JOIN acos c ON rc.aco_id = c.id
			WHERE c.alias = 'Dossierspcgs66:exportcsv'

		LOOP

			INSERT INTO aros_acos (aro_id, aco_id, _create, _read, _update, _delete)
				VALUES (v_row.aro_id, exportcsv_aco_id, v_row._create, v_row._read, v_row._update, v_row._delete);

		END LOOP;

    END IF;

END;
$$
LANGUAGE 'plpgsql';

SELECT copy_permission_dossierspcgs66();
DROP FUNCTION copy_permission_dossierspcgs66();


-- *****************************************************************************
COMMIT;
-- *****************************************************************************