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

--------------------------------------------------------------------------------
-- Dossierspcgs66
--------------------------------------------------------------------------------

DELETE FROM acos WHERE alias = 'Module:Criteresdossierspcgs66';
DELETE FROM acos WHERE alias = 'Module:Cohortesdossierspcgs66';

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


--------------------------------------------------------------------------------
-- ActionscandidatsPersonnes
--------------------------------------------------------------------------------

DELETE FROM acos WHERE alias = 'Module:CriteresActionscandidatsPersonnes';
DELETE FROM acos WHERE alias = 'Module:Cohortesfichescandidature66';

CREATE OR REPLACE FUNCTION copy_permission_actionscandidats_personnes() RETURNS void AS
$$
DECLARE
	module_id integer;

BEGIN

	module_id := (SELECT id FROM acos WHERE alias =  'Module:ActionscandidatsPersonnes');


	UPDATE acos
		SET parent_id = module_id,
			alias = 'ActionscandidatsPersonnes:cohorte_enattente'
		WHERE alias = 'Cohortesfichescandidature66:fichesenattente';

	UPDATE acos
		SET parent_id = module_id,
			alias = 'ActionscandidatsPersonnes:cohorte_encours'
		WHERE alias = 'Cohortesfichescandidature66:fichesencours';

	UPDATE acos
		SET parent_id = module_id,
			alias = 'ActionscandidatsPersonnes:exportcsv'
		WHERE alias = 'Criteresfichescandidature:exportcsv';

	UPDATE acos
		SET parent_id = module_id,
			alias = 'ActionscandidatsPersonnes:search'
		WHERE alias = 'Criteresfichescandidature:index';

END;
$$
LANGUAGE 'plpgsql';

SELECT copy_permission_actionscandidats_personnes();
DROP FUNCTION copy_permission_actionscandidats_personnes();


--------------------------------------------------------------------------------
-- Cohortes (d'orientation)
--------------------------------------------------------------------------------

DELETE FROM acos WHERE alias = 'Module:Cohortes';

CREATE OR REPLACE FUNCTION copy_permission_cohortes() RETURNS void AS
$$
DECLARE
	module_id integer;

BEGIN

	module_id := (SELECT id FROM acos WHERE alias =  'Module:Orientsstructs');


	UPDATE acos
		SET parent_id = module_id,
			alias = 'Orientsstructs:cohorte_enattente'
		WHERE alias = 'Cohortes:enattente';

	UPDATE acos
		SET parent_id = module_id,
			alias = 'Orientsstructs:cohorte_impressions'
		WHERE alias = 'Cohortes:cohortegedooo';

	UPDATE acos
		SET parent_id = module_id,
			alias = 'Orientsstructs:cohorte_nouvelles'
		WHERE alias = 'Cohortes:nouvelles';

	UPDATE acos
		SET parent_id = module_id,
			alias = 'Orientsstructs:cohorte_orientees'
		WHERE alias = 'Cohortes:orientees';

END;
$$
LANGUAGE 'plpgsql';

SELECT copy_permission_cohortes();
DROP FUNCTION copy_permission_cohortes();

--------------------------------------------------------------------------------
-- Cohortesci (de CER)
--------------------------------------------------------------------------------

DELETE FROM acos WHERE alias = 'Module:Cohortesci';

CREATE OR REPLACE FUNCTION copy_permission_cohortesci() RETURNS void AS
$$
DECLARE
	v_row record;
	module_id integer;
	exportcsv_aco_id integer;

BEGIN

	module_id := (SELECT id FROM acos WHERE alias =  'Module:Contratsinsertion');

	UPDATE acos
		SET parent_id = module_id,
			alias = 'Contratsinsertion:cohorte_cerparticulieravalider'
		WHERE alias = 'Cohortesci:nouveauxparticulier';

	UPDATE acos
		SET parent_id = module_id,
			alias = 'Contratsinsertion:cohorte_cersimpleavalider'
		WHERE alias = 'Cohortesci:nouveauxsimple';

	UPDATE acos
		SET parent_id = module_id,
			alias = 'Contratsinsertion:cohorte_nouveaux'
		WHERE alias = 'Cohortesci:nouveaux';

	UPDATE acos
		SET parent_id = module_id,
			alias = 'Contratsinsertion:cohorte_valides'
		WHERE alias = 'Cohortesci:valides';

	UPDATE acos
		SET parent_id = module_id,
			alias = 'Contratsinsertion:search_valides'
		WHERE alias = 'Cohortesci:valides';

    IF NOT EXISTS(SELECT * FROM acos
		WHERE alias = 'Contratsinsertion:exportcsv_valides') THEN

        INSERT INTO acos (parent_id, model, foreign_key, alias, lft, rght)
			VALUES (module_id, '', 0, 'Contratsinsertion:exportcsv_valides', 0, 0);

		exportcsv_aco_id := (SELECT id FROM acos WHERE alias = 'Contratsinsertion:exportcsv_valides');

		FOR v_row IN
			SELECT * FROM aros_acos rc
			JOIN acos c ON rc.aco_id = c.id
			WHERE c.alias = 'Contratsinsertion:cohorte_valides'

		LOOP

			INSERT INTO aros_acos (aro_id, aco_id, _create, _read, _update, _delete)
				VALUES (v_row.aro_id, exportcsv_aco_id, v_row._create, v_row._read, v_row._update, v_row._delete);

		END LOOP;

    END IF;

END;
$$
LANGUAGE 'plpgsql';

SELECT copy_permission_cohortesci();
DROP FUNCTION copy_permission_cohortesci();


--------------------------------------------------------------------------------
-- Nonorientes66
--------------------------------------------------------------------------------

DELETE FROM acos WHERE alias = 'Module:Cohortesnonorientes66';

CREATE OR REPLACE FUNCTION copy_permission_nonorientes66() RETURNS void AS
$$
DECLARE
	module_id integer;

BEGIN

	module_id := (SELECT id FROM acos WHERE alias =  'Module:Nonorientes66');

	
	UPDATE acos
		SET parent_id = module_id,
			alias = 'Nonorientes66:cohorte_imprimeremploi'
		WHERE alias = 'Cohortesnonorientes66:notisemploiaimprimer';
	
	UPDATE acos
		SET parent_id = module_id,
			alias = 'Nonorientes66:cohorte_imprimernotifications'
		WHERE alias = 'Cohortesnonorientes66:notifaenvoyer';
	
	UPDATE acos
		SET parent_id = module_id,
			alias = 'Nonorientes66:cohorte_isemploi'
		WHERE alias = 'Cohortesnonorientes66:isemploi';
	
	UPDATE acos
		SET parent_id = module_id,
			alias = 'Nonorientes66:cohorte_reponse'
		WHERE alias = 'Cohortesnonorientes66:notisemploi';
	
	UPDATE acos
		SET parent_id = module_id,
			alias = 'Nonorientes66:recherche_notifie'
		WHERE alias = 'Cohortesnonorientes66:oriente';

END;
$$
LANGUAGE 'plpgsql';

SELECT copy_permission_nonorientes66();
DROP FUNCTION copy_permission_nonorientes66();


-- *****************************************************************************
COMMIT;
-- *****************************************************************************