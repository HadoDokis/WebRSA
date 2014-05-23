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

-- Il faut scinder le niveau 1201 des DSP pour les CER.
SELECT public.alter_table_drop_constraint_if_exists( 'public', 'cers93', 'cers93_nivetu_in_list_chk' );
ALTER TABLE cers93 ALTER COLUMN nivetu TYPE VARCHAR(5);
-- On corrige les CER existants avec la valeur exacte suivant l'intitulé qui était visible
UPDATE cers93 SET nivetu = '1201a' WHERE nivetu = '1201';
UPDATE cers93 SET nivetu = '1201b' WHERE nivetu = '1202';
UPDATE cers93 SET nivetu = '1202' WHERE nivetu = '1203';
UPDATE cers93 SET nivetu = '1203' WHERE nivetu = '1204';
UPDATE cers93 SET nivetu = '1204' WHERE nivetu = '1205';
UPDATE cers93 SET nivetu = '1205' WHERE nivetu = '1206';
UPDATE cers93 SET nivetu = '1206' WHERE nivetu = '1207';
UPDATE cers93 SET nivetu = '1207' WHERE nivetu = '1208';
ALTER TABLE cers93 ADD CONSTRAINT cers93_nivetu_in_list_chk CHECK ( cakephp_validate_in_list( nivetu, ARRAY['1201a', '1201b', '1202', '1203', '1204', '1205', '1206', '1207'] ) );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************