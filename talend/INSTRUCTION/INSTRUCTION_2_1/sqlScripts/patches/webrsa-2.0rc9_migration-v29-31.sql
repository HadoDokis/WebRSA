-- Scripts de migrations iRSA v. 3.2 à 5 et Cristal v. 29 à 31 --

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

/*****************************************************************
	Scripts de migrations iRSA v. 3.2 à 5 et Cristal v. 29 à 31
******************************************************************/

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

--------------------------- INSTRUCTION V30-31

-- Table: personnes
CREATE OR REPLACE FUNCTION create_fields_personnes_numagenpoleemploi_dtinscpoleemploi() RETURNS VOID AS
$$
BEGIN
	IF NOT EXISTS(
		SELECT *
			FROM pg_namespace n, pg_class c, pg_attribute a
			WHERE
				nspname = 'public'
				AND c.relnamespace = n.oid
				AND a.attrelid = c.oid
				AND relname = 'personnes'
				AND attname = 'numagenpoleemploi'
	)
	THEN
		ALTER TABLE personnes ADD COLUMN numagenpoleemploi CHAR(3);
		ALTER TABLE personnes ADD COLUMN dtinscpoleemploi DATE;
	END IF;
END;
$$
LANGUAGE 'plpgsql';

SELECT create_fields_personnes_numagenpoleemploi_dtinscpoleemploi();
DROP FUNCTION create_fields_personnes_numagenpoleemploi_dtinscpoleemploi();


---------------------------- BENEFICIAIRE V29

-- Table: transmissionsflux
CREATE OR REPLACE FUNCTION create_field_transmissionsflux_nbtotdosrsatransm() RETURNS VOID AS
$$
BEGIN
	IF NOT EXISTS(
		SELECT *
			FROM pg_namespace n, pg_class c, pg_attribute a
			WHERE
				nspname = 'public'
				AND c.relnamespace = n.oid
				AND a.attrelid = c.oid
				AND relname = 'transmissionsflux'
				AND attname = 'nbtotdosrsatransm'
	)
	THEN
		ALTER TABLE transmissionsflux ADD COLUMN nbtotdosrsatransm INTEGER;
	END IF;
END;
$$
LANGUAGE 'plpgsql';

SELECT create_field_transmissionsflux_nbtotdosrsatransm();
DROP FUNCTION create_field_transmissionsflux_nbtotdosrsatransm();


---------------------------- BENEFICIAIRE V30-31

-- Table: controlesadministratifs
CREATE OR REPLACE FUNCTION create_table_controlesadministratifs() RETURNS VOID AS
$$
BEGIN
	IF NOT EXISTS(
		SELECT *
			FROM pg_tables
			WHERE schemaname = 'public'
				AND tablename = 'controlesadministratifs'
	)
	THEN
		CREATE TABLE controlesadministratifs
		(
		  id serial NOT NULL,
		  dteffcibcontro date,
		  cibcontro character(3),
		  cibcontromsa character(3),
		  dtdeteccontro date,
		  dtclocontro date,
		  libcibcontro character varying(45),
		  famcibcontro character(2),
		  natcibcontro character(3),
		  commacontro character(3),
		  typecontro character(2),
		  typeimpaccontro character(1),
		  mtindursacgcontro numeric(11,2),
		  mtraprsacgcontro numeric(11,2),
		  foyer_id integer NOT NULL,
		  CONSTRAINT controlesadministratifs_pkey PRIMARY KEY (id),
		  CONSTRAINT controlesadministratifs_foyer_id_fkey FOREIGN KEY (foyer_id)
		      REFERENCES foyers (id) MATCH SIMPLE
		      ON UPDATE NO ACTION ON DELETE NO ACTION
		)
		WITH (OIDS=FALSE);
		ALTER TABLE controlesadministratifs OWNER TO webrsa;

		CREATE INDEX controlesadministratifs_foyer_id_idx
		  ON controlesadministratifs
		  USING btree
		  (foyer_id);
	END IF;
END;
$$
LANGUAGE 'plpgsql';

SELECT create_table_controlesadministratifs();
DROP FUNCTION create_table_controlesadministratifs();

-----------------------------------------------------------------------------
-- Table:situationsdossiersrsa
CREATE OR REPLACE FUNCTION create_field_situationsdossiersrsa_motirefursa() RETURNS VOID AS
$$
BEGIN
	IF NOT EXISTS(
		SELECT *
			FROM pg_namespace n, pg_class c, pg_attribute a
			WHERE
				nspname = 'public'
				AND c.relnamespace = n.oid
				AND a.attrelid = c.oid
				AND relname = 'situationsdossiersrsa'
				AND attname = 'motirefursa'
	)
	THEN
		ALTER TABLE situationsdossiersrsa ADD COLUMN motirefursa CHAR(3);
	END IF;
END;
$$
LANGUAGE 'plpgsql';

SELECT create_field_situationsdossiersrsa_motirefursa();
DROP FUNCTION create_field_situationsdossiersrsa_motirefursa();

-----------------------------------------------------------------------------
--Table:suspensionsdroits
CREATE OR REPLACE FUNCTION create_field_suspensionsdroits_natgroupfsus() RETURNS VOID AS
$$
BEGIN
	IF NOT EXISTS(
		SELECT *
			FROM pg_namespace n, pg_class c, pg_attribute a
			WHERE
				nspname = 'public'
				AND c.relnamespace = n.oid
				AND a.attrelid = c.oid
				AND relname = 'suspensionsdroits'
				AND attname = 'natgroupfsus'
	)
	THEN
		ALTER TABLE suspensionsdroits ADD COLUMN natgroupfsus CHAR(3);
	END IF;
END;
$$
LANGUAGE 'plpgsql';

SELECT create_field_suspensionsdroits_natgroupfsus();
DROP FUNCTION create_field_suspensionsdroits_natgroupfsus();

-- *****************************************************************************
COMMIT;
-- *****************************************************************************