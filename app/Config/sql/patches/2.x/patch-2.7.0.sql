SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
-- SET client_min_messages = notice;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
BEGIN;
-- *****************************************************************************

CREATE OR REPLACE FUNCTION public.enumtype_to_validate_in_list( p_schema TEXT, p_table TEXT, p_field TEXT ) RETURNS VOID AS
$$
	DECLARE
		v_row_field RECORD;
		v_select_query TEXT;
		v_count_query TEXT;
		v_row_count RECORD;
		v_enum_query TEXT;
		v_enum_row RECORD;
		v_enum_value TEXT;
		v_enum_i INTEGER;

		v_altercolumn_query TEXT;
		v_droptype_query TEXT;
		v_inlist_query TEXT;
		v_inlist_maxlength INTEGER;
		v_columndefault_query TEXT;
	BEGIN
		v_select_query := 'SELECT isc.table_schema, isc.table_name, isc.column_name, isc.udt_name, isc.column_default
			FROM information_schema.columns AS isc
			WHERE
				isc.data_type = ''USER-DEFINED''
				AND isc.udt_name IN (
					SELECT isc2.udt_name
						FROM information_schema.columns AS isc2
						WHERE
							isc2.data_type = ''USER-DEFINED''
							AND isc2.table_schema = ''' || p_schema || '''
							AND isc2.table_name = ''' || p_table || '''
							AND isc2.column_name = ''' || p_field || '''
			)';

		v_count_query := 'SELECT COUNT(tables.*) AS count FROM ( ' || v_select_query || ' ) AS tables';
		EXECUTE v_count_query INTO v_row_count;

		-- FIXME: pas d'erreur en-dehors des tests
		IF v_row_count.count = 0 THEN
			RAISE EXCEPTION 'Le champ %.%.% n''est pas de type ENUM', p_schema, p_table, p_field;
		END IF;

		-- Transformation de la requête pour ne concerner que le champ demandé
		v_select_query := v_select_query
			|| 'AND isc.table_schema = ''' || p_schema || '''
			AND isc.table_name = ''' || p_table || '''
			AND isc.column_name = ''' || p_field || ''';';

		FOR v_row_field IN EXECUTE v_select_query
		LOOP
			-- 1°) Récupération des valeurs
			v_enum_query := 'SELECT enum_range( null::' || v_row_field.udt_name || ' )::TEXT[] AS enum;';
			EXECUTE v_enum_query INTO v_enum_row;

			-- Recherche de la longueur maximale du champ
			v_inlist_maxlength := 0;
			FOR v_enum_value IN SELECT unnest( v_enum_row.enum )
			LOOP
				IF LENGTH( v_enum_value ) > v_inlist_maxlength THEN
					v_inlist_maxlength := LENGTH( v_enum_value );
				END IF;
			END LOOP;

			-- 2°) Transformation de la colonne
 			v_altercolumn_query := 'ALTER TABLE ' || p_schema || '.' || p_table || ' ALTER COLUMN ' || p_field || ' TYPE VARCHAR(' || v_inlist_maxlength || ') USING CAST(' || p_field || ' AS VARCHAR(' || v_inlist_maxlength || '));';
			EXECUTE v_altercolumn_query;

			-- 3°) Ajout de la contrainte cakephp_calidate_in_list()
			v_inlist_query := 'ALTER TABLE ' || p_schema || '.' || p_table || ' ADD CONSTRAINT ' || p_table || '_' || p_field || '_in_list_chk CHECK ( cakephp_validate_in_list( ' || p_field || ', ARRAY[\'' || ARRAY_TO_STRING( v_enum_row.enum, '\', \'' ) || '\'] ) );';
			EXECUTE v_inlist_query;

			-- 4°) Changement du type de la valeur par défaut
			IF v_row_field.column_default ~ '::[^'']+$' THEN
				v_columndefault_query := 'ALTER TABLE ' || p_schema || '.' || p_table || ' ALTER COLUMN ' || p_field || ' SET DEFAULT ' || REGEXP_REPLACE( v_row_field.column_default, '::[^'']+$', '' ) || ';';
				EXECUTE v_columndefault_query;
			END IF;

			-- 5°) Suppression de l'ENUM si on est le seul à l'utiliser
			IF v_row_count.count = 1 THEN
				v_droptype_query := 'DROP TYPE IF EXISTS ' || v_row_field.udt_name || ';';
				EXECUTE v_droptype_query;
			END IF;
		END LOOP;
	END;
$$
LANGUAGE plpgsql VOLATILE;

COMMENT ON FUNCTION public.enumtype_to_validate_in_list( p_schema TEXT, p_table TEXT, p_field TEXT ) IS
	'Permet de transformer un champ de type ENUM en un champ de type VARCHAR, avec ajout d''une contrainte cakephp_validate_in_list() et la suppression du type s''il n''est plus utilisé.';

--------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION public.table_enumtypes_to_validate_in_list( p_schema TEXT, p_table TEXT ) RETURNS VOID AS
$$
	DECLARE
		v_row_field RECORD;
		v_select_query TEXT;
		v_function_query TEXT;
	BEGIN
		v_select_query := 'SELECT isc.table_schema, isc.table_name, isc.column_name
			FROM information_schema.columns AS isc
			WHERE
				isc.data_type = ''USER-DEFINED''
				AND isc.table_schema = ''' || p_schema || '''
				AND isc.table_name = ''' || p_table || ''';';

		FOR v_row_field IN EXECUTE v_select_query
		LOOP
			EXECUTE public.enumtype_to_validate_in_list( v_row_field.table_schema, v_row_field.table_name, v_row_field.column_name );
		END LOOP;
	END;
$$
LANGUAGE plpgsql VOLATILE;

COMMENT ON FUNCTION public.table_enumtypes_to_validate_in_list( p_schema TEXT, p_table TEXT ) IS
	'Permet de transformer tous les champs de type ENUM d''une table en champs de type VARCHAR, avec ajout d''une contrainte cakephp_validate_in_list() et la suppression du type s''il n''est plus utilisé.';

--------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION public.table_defaultvalues_enumtypes_to_varchar( p_schema TEXT, p_table TEXT ) RETURNS VOID AS
$$
	DECLARE
		v_row_field RECORD;
		v_select_query TEXT;
		v_columndefault_query TEXT;
	BEGIN
		v_select_query := 'SELECT isc.table_schema, isc.table_name, isc.column_name, isc.udt_name, isc.column_default
			FROM information_schema.columns AS isc
			WHERE
				isc.data_type <> ''USER-DEFINED''
				AND isc.table_schema = ''' || p_schema || '''
				AND isc.table_name = ''' || p_table || '''
				AND isc.column_default ~ ''::type_[^'''']+$''
			;';

		FOR v_row_field IN EXECUTE v_select_query
		LOOP
			v_columndefault_query := 'ALTER TABLE ' || v_row_field.table_schema || '.' || v_row_field.table_name || ' ALTER COLUMN ' || v_row_field.column_name || ' SET DEFAULT ' || REGEXP_REPLACE( v_row_field.column_default, '::[^'']+$', '' ) || ';';
			EXECUTE v_columndefault_query;
		END LOOP;
	END;
$$
LANGUAGE plpgsql VOLATILE;

COMMENT ON FUNCTION public.table_defaultvalues_enumtypes_to_varchar( p_schema TEXT, p_table TEXT ) IS
	'Permet de transformer toutes les valeurs par défaut de type ENUM des champs de type non ENUM d''une table.';

--------------------------------------------------------------------------------

SELECT public.table_enumtypes_to_validate_in_list( 'public', 'cuis' );
SELECT public.table_defaultvalues_enumtypes_to_varchar( 'public', 'cuis' );

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
