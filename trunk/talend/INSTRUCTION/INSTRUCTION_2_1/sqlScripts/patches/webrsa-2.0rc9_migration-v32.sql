-- Scripts de migrations iRSA v. 5 à 6 et Cristal v. 31 à 32 --

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

/***************************************************************
	Scripts de migrations iRSA v. 5 à 6 et Cristal v. 31 à 32
****************************************************************/

-- Création du langage plpgsl s'il n'existe pas
-- INFO: http://andreas.scherbaum.la/blog/archives/346-create-language-if-not-exist.html
CREATE OR REPLACE FUNCTION public.create_plpgsql_language ()
	RETURNS TEXT
	AS $$
		CREATE LANGUAGE plpgsql;
		SELECT 'language plpgsql created'::TEXT;
	$$
LANGUAGE 'sql';

SELECT CASE WHEN
	( SELECT true::BOOLEAN FROM pg_language WHERE lanname='plpgsql')
THEN
	(SELECT 'language already installed'::TEXT)
ELSE
	(SELECT public.create_plpgsql_language())
END;

DROP FUNCTION public.create_plpgsql_language ();

-- webrsa
ALTER USER webrsa SUPERUSER;

--------------------------- INSTRUCTION V32

--conditionsactivitesprealables
CREATE OR REPLACE FUNCTION create_table_conditionsactivitesprealables() RETURNS VOID AS
$$
BEGIN
	IF NOT EXISTS(
		SELECT *
			FROM pg_tables
			WHERE schemaname = 'public'
				AND tablename = 'conditionsactivitesprealables'
	)
	THEN
		CREATE TABLE conditionsactivitesprealables
		(
		  id serial NOT NULL,
		  ddcondactprea date NOT NULL,
		  dfcondactprea date NOT NULL,
		  topcondactprea type_booleannumber NOT NULL,
		  nbheuacttot integer NOT NULL,
		  personne_id integer NOT NULL,
		  CONSTRAINT conditionsactivitesprealables_pkey PRIMARY KEY (id),
		  CONSTRAINT conditionsactivitesprealables_personne_id_fkey FOREIGN KEY (personne_id)
		      REFERENCES personnes (id) MATCH SIMPLE
		      ON UPDATE CASCADE ON DELETE CASCADE
		)
		WITH (OIDS=FALSE);
		ALTER TABLE conditionsactivitesprealables OWNER TO webrsa;
	END IF;
END;
$$
LANGUAGE 'plpgsql';

SELECT create_table_conditionsactivitesprealables();
DROP FUNCTION create_table_conditionsactivitesprealables();

-----------------------------------------------------------------------------
--paiementsfoyers
CREATE OR REPLACE FUNCTION create_fields_paiementsfoyers_numdebiban_numfiniban_bic() RETURNS VOID AS
$$
BEGIN
	IF NOT EXISTS(
		SELECT *
			FROM pg_namespace n, pg_class c, pg_attribute a
			WHERE
				nspname = 'public'
				AND c.relnamespace = n.oid
				AND a.attrelid = c.oid
				AND relname = 'paiementsfoyers'
				AND attname = 'numdebiban'
	)
	THEN
		ALTER TABLE paiementsfoyers ADD COLUMN numdebiban VARCHAR(4) NOT NULL;
		ALTER TABLE paiementsfoyers ADD COLUMN numfiniban VARCHAR(7) NOT NULL;
		ALTER TABLE paiementsfoyers ADD COLUMN bic VARCHAR(11) NOT NULL;
	END IF;
END;
$$
LANGUAGE 'plpgsql';

SELECT create_fields_paiementsfoyers_numdebiban_numfiniban_bic();
DROP FUNCTION create_fields_paiementsfoyers_numdebiban_numfiniban_bic();

ALTER TABLE paiementsfoyers ALTER COLUMN clerib TYPE character varying(2);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************