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

-- ***********************************************************************************************************
-- 20111108 -- Ajout d'un champ pour annulation de la fiche de candidature 66
-- ***********************************************************************************************************
SELECT add_missing_table_field ( 'public', 'actionscandidats_personnes', 'motifannulation', 'TEXT' );

-- ***********************************************************************************************************
-- 20111109 -- Ajout de 2 champs pour le lieu et la personne du RDV prévu dans la fiche de candidature CG66
-- ***********************************************************************************************************
SELECT add_missing_table_field ( 'public', 'actionscandidats_personnes', 'lieurdvpartenaire', 'VARCHAR(255)' );
SELECT add_missing_table_field ( 'public', 'actionscandidats_personnes', 'personnerdvpartenaire', 'VARCHAR(100)' );


DROP SEQUENCE IF EXISTS apres_numeroapre_seq;
CREATE OR REPLACE FUNCTION public.init_apres_numeroapre_seq() RETURNS bool AS
$$
	DECLARE
		v_row       		record;
		v_query     		text;
	BEGIN
		SELECT ( CAST( regexp_replace( MAX(numeroapre), '^[0-9]{6}0+', '') AS INTEGER ) + 1 ) AS numeroapre FROM apres INTO v_row;

		IF FOUND THEN
			v_query := 'CREATE SEQUENCE apres_numeroapre_seq START ' || v_row.numeroapre || ';';
			EXECUTE v_query;
			RETURN 't';
		ELSE
			RETURN 'f';
		END IF;
	END;
$$
LANGUAGE plpgsql;
SELECT public.init_apres_numeroapre_seq();
DROP FUNCTION public.init_apres_numeroapre_seq();

-- *****************************************************************************
COMMIT;
-- *****************************************************************************