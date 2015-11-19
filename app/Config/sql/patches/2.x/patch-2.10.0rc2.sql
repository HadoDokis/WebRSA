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

SELECT alter_table_drop_constraint_if_exists ( 'public', 'correspondancespersonnes', 'correspondancespersonnes_personne1_id_fkey' );
SELECT alter_table_drop_constraint_if_exists ( 'public', 'correspondancespersonnes', 'correspondancespersonnes_personne2_id_fkey' );
ALTER TABLE correspondancespersonnes ADD CONSTRAINT correspondancespersonnes_personne1_id_fkey FOREIGN KEY (personne1_id) REFERENCES personnes(id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE correspondancespersonnes ADD CONSTRAINT correspondancespersonnes_personne2_id_fkey FOREIGN KEY (personne2_id) REFERENCES personnes(id) ON DELETE CASCADE ON UPDATE CASCADE;


-- *****************************************************************************
COMMIT;
-- *****************************************************************************