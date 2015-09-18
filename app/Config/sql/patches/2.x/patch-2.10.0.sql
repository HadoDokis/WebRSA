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


--------------------------------------------------------------------------------
-- Nouveaux champs du dossierpcg66
--------------------------------------------------------------------------------

SELECT alter_table_drop_column_if_exists('public', 'traitementspcgs66', 'imprimer');
SELECT alter_table_drop_column_if_exists('public', 'traitementspcgs66', 'etattraitementpcg');
ALTER TABLE traitementspcgs66 ADD COLUMN imprimer SMALLINT DEFAULT 0;
ALTER TABLE traitementspcgs66 ADD COLUMN etattraitementpcg VARCHAR(9);

SELECT alter_table_drop_constraint_if_exists ( 'public', 'traitementspcgs66', 'traitementspcgs66_imprimer_in_list_chk' );
SELECT alter_table_drop_constraint_if_exists ( 'public', 'traitementspcgs66', 'traitementspcgs66_etattraitementpcg_in_list_chk' );
ALTER TABLE traitementspcgs66 ADD CONSTRAINT traitementspcgs66_imprimer_in_list_chk CHECK ( cakephp_validate_in_list( imprimer, ARRAY[0,1] ) );
ALTER TABLE traitementspcgs66 ADD CONSTRAINT traitementspcgs66_etattraitementpcg_in_list_chk CHECK ( cakephp_validate_in_list( etattraitementpcg, ARRAY['contrôler','imprimer','attente','envoyé'] ) );

UPDATE traitementspcgs66 SET etattraitementpcg = 'envoyé' WHERE typetraitement = 'courrier' AND dateenvoicourrier IS NOT NULL;
UPDATE traitementspcgs66 SET etattraitementpcg = 'contrôler' WHERE typetraitement = 'courrier' AND dateenvoicourrier IS NULL;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************