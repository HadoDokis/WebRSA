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

SELECT add_missing_table_field ( 'public', 'bilansparcours66', 'nvcontratinsertion_id', 'INTEGER' );
SELECT add_missing_constraint ( 'public', 'bilansparcours66', 'bilansparcours66_nvcontratinsertion_id_fkey', 'contratsinsertion', 'nvcontratinsertion_id', false );

CREATE UNIQUE INDEX bilansparcours66_nvcontratinsertion_id_idx ON bilansparcours66(nvcontratinsertion_id);

SELECT public.alter_enumtype ( 'TYPE_PROPOSITIONBILANPARCOURS', ARRAY['audition','parcours','traitement','auditionpe','parcourspe','aucun'] );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
