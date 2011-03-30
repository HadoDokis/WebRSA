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

-- *****************************************************************************

DROP TABLE IF EXISTS regressionsorientationseps93 CASCADE;
DROP TABLE IF EXISTS decisionsregressionsorientationseps93 CASCADE;

-- *****************************************************************************

DROP INDEX IF EXISTS regressionsorientationseps93_dossierep_id_idx;
DROP INDEX IF EXISTS regressionsorientationseps93_typeorient_id_idx;
DROP INDEX IF EXISTS regressionsorientationseps93_structurereferente_id_idx;
DROP INDEX IF EXISTS regressionsorientationseps93_referent_id_idx;
DROP INDEX IF EXISTS regressionsorientationseps93_user_id_idx;

DROP INDEX IF EXISTS decisionsregressionsorientationseps93_regressionorientationep93_id_idx;
DROP INDEX IF EXISTS decisionsregressionsorientationseps93_typeorient_id_idx;
DROP INDEX IF EXISTS decisionsregressionsorientationseps93_structurereferente_id_idx;
DROP INDEX IF EXISTS decisionsregressionsorientationseps93_referent_id_idx;

-- *****************************************************************************

SELECT add_missing_table_field ('public', 'contratsinsertion', 'current_action', 'TEXT');

CREATE TABLE regressionsorientationseps93 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	dossierep_id			INTEGER DEFAULT NULL REFERENCES dossierseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	typeorient_id			INTEGER NOT NULL REFERENCES typesorients(id) ON DELETE CASCADE ON UPDATE CASCADE,
	structurereferente_id	INTEGER NOT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	datedemande				DATE NOT NULL,
	referent_id				INTEGER DEFAULT NULL REFERENCES referents(id) ON DELETE SET NULL ON UPDATE CASCADE,
	user_id					INTEGER DEFAULT NULL REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
	commentaire				TEXT DEFAULT NULL,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE regressionsorientationseps93 IS 'Thématique pour la réorientation du professionel vers le social (CG93)';

CREATE INDEX regressionsorientationseps93_dossierep_id_idx ON regressionsorientationseps93 (dossierep_id);
CREATE INDEX regressionsorientationseps93_typeorient_id_idx ON regressionsorientationseps93 (typeorient_id);
CREATE INDEX regressionsorientationseps93_structurereferente_id_idx ON regressionsorientationseps93 (structurereferente_id);
CREATE INDEX regressionsorientationseps93_referent_id_idx ON regressionsorientationseps93 (referent_id);
CREATE INDEX regressionsorientationseps93_user_id_idx ON regressionsorientationseps93 (user_id);

SELECT add_missing_table_field ('public', 'eps', 'regressionorientationep93', 'TYPE_NIVEAUDECISIONEP');
ALTER TABLE eps ALTER COLUMN regressionorientationep93 SET DEFAULT 'nontraite';
UPDATE eps SET regressionorientationep93 = 'nontraite' WHERE regressionorientationep93 IS NULL;
ALTER TABLE eps ALTER COLUMN regressionorientationep93 SET NOT NULL;

CREATE TABLE decisionsregressionsorientationseps93 (
	id      						SERIAL NOT NULL PRIMARY KEY,
	regressionorientationep93_id	INTEGER NOT NULL REFERENCES regressionsorientationseps93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	typeorient_id					INTEGER DEFAULT NULL REFERENCES typesorients(id) ON UPDATE CASCADE ON DELETE SET NULL,
	structurereferente_id			INTEGER DEFAULT NULL REFERENCES structuresreferentes(id) ON UPDATE CASCADE ON DELETE SET NULL,
	referent_id						INTEGER DEFAULT NULL REFERENCES referents(id) ON DELETE SET NULL ON UPDATE CASCADE,
	etape							TYPE_ETAPEDECISIONEP NOT NULL,
	commentaire						TEXT DEFAULT NULL,
	created							TIMESTAMP WITHOUT TIME ZONE,
	modified						TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE decisionsregressionsorientationseps93 IS 'Décisions pour la thématique de la réorientation du professionel vers le social (CG93)';

CREATE INDEX decisionsregressionsorientationseps93_regressionorientationep93_id_idx ON decisionsregressionsorientationseps93 (regressionorientationep93_id);
CREATE INDEX decisionsregressionsorientationseps93_typeorient_id_idx ON decisionsregressionsorientationseps93 (typeorient_id);
CREATE INDEX decisionsregressionsorientationseps93_structurereferente_id_idx ON decisionsregressionsorientationseps93 (structurereferente_id);
CREATE INDEX decisionsregressionsorientationseps93_referent_id_idx ON decisionsregressionsorientationseps93 (referent_id);

-- *****************************************************************************

CREATE OR REPLACE FUNCTION public.alter_tablename_ifexists ( p_namespace text, p_namefrom text, p_nameto text ) RETURNS bool AS
$$
	DECLARE
		v_row       		record;
		v_query     		text;
	BEGIN
		select 1 into v_row
		from information_schema.tables ta
		where ta.table_name = p_namefrom;
		if found then
			raise notice 'Upgrade table %.% - rename to %', p_namespace, p_namefrom, p_nameto;
			v_query := 'alter table ' || p_namespace || '.' || p_namefrom || ' rename to ' || p_nameto || ';';
			execute v_query;
			return 't';
		else
			raise notice 'Table %.% not found', p_namespace, p_namefrom;
			return 'f';
		end if;
	END;
$$
LANGUAGE plpgsql;

COMMENT ON FUNCTION public.alter_tablename_ifexists ( p_namespace text, p_namefrom text, p_nameto text ) IS 'Renomage de table p_namefrom en p_nameto si elle existe';

-- *****************************************************************************

CREATE OR REPLACE FUNCTION public.alter_columnname_ifexists ( p_namespace text, p_tablename text, p_columnnamefrom text, p_columnnameto text ) RETURNS bool AS
$$
	DECLARE
		v_row       		record;
		v_query     		text;
	BEGIN
		select 1 into v_row
		from information_schema.columns tc
		where tc.table_name = p_tablename
			and tc.column_name = p_columnnamefrom;
		if found then
			raise notice 'Upgrade table %.% - rename colum % to %', p_namespace, p_tablename, p_columnnamefrom, p_columnnameto;
			v_query := 'alter table ' || p_namespace || '.' || p_tablename || ' rename column ' || p_columnnamefrom || ' to ' || p_columnnameto || ';';
			execute v_query;
			return 't';
		else
			raise notice 'Column % not found in table %.%', p_columnnamefrom, p_namespace, p_tablename;
			return 'f';
		end if;
	END;
$$
LANGUAGE plpgsql;

COMMENT ON FUNCTION public.alter_columnname_ifexists ( p_namespace text, p_tablename text, p_columnnamefrom text, p_columnnameto text ) IS 'Renomage de la colonne p_columnnamefrom en p_columnnameto de la table p_tablename si elle existe';

-- *****************************************************************************

SELECT public.alter_tablename_ifexists( 'public', 'membreseps_seanceseps', 'commissionseps_membreseps' );

SELECT public.alter_columnname_ifexists( 'public', 'dossierseps', 'seanceep_id', 'commissionep_id' );
SELECT public.alter_columnname_ifexists( 'public', 'commissionseps_membreseps', 'seanceep_id', 'commissionep_id' );
SELECT public.alter_tablename_ifexists( 'public', 'seanceseps', 'commissionseps' );

SELECT public.alter_tablename_ifexists( 'public', 'nvsrsepsreorientsrs93', 'decisionsreorientationseps93' );

SELECT public.alter_columnname_ifexists( 'public', 'decisionsreorientationseps93', 'saisineepreorientsr93_id', 'reorientationep93_id' );
SELECT public.alter_tablename_ifexists( 'public', 'saisinesepsreorientsrs93', 'reorientationseps93' );

SELECT public.alter_tablename_ifexists( 'public', 'nvsepdspdos66', 'decisionssaisinespdoseps66' );

SELECT public.alter_columnname_ifexists( 'public', 'decisionssaisinespdoseps66', 'saisineepdpdo66_id', 'saisinepdoep66_id' );
SELECT public.alter_tablename_ifexists( 'public', 'saisinesepdspdos66', 'saisinespdoseps66' );

SELECT public.alter_tablename_ifexists( 'public', 'nvsrsepsreorient66', 'decisionssaisinesbilansparcourseps66' );

SELECT public.alter_columnname_ifexists( 'public', 'decisionssaisinesbilansparcourseps66', 'saisineepbilanparcours66_id', 'saisinebilanparcoursep66_id' );
SELECT public.alter_tablename_ifexists( 'public', 'saisinesepsbilansparcours66', 'saisinesbilansparcourseps66' );

SELECT public.alter_columnname_ifexists( 'public', 'reorientationseps93', 'motifreorient_id', 'motifreorientep93_id' );
SELECT public.alter_tablename_ifexists( 'public', 'motifsreorients', 'motifsreorientseps93' );

SELECT public.alter_columnname_ifexists( 'public', 'decisionsnonorientationspros58', 'nonorientationpro58_id', 'nonorientationproep58_id' );
SELECT public.alter_tablename_ifexists( 'public', 'nonorientationspros58', 'nonorientationsproseps58' );
SELECT public.alter_columnname_ifexists( 'public', 'decisionsnonorientationspros66', 'nonorientationpro66_id', 'nonorientationproep66_id' );
SELECT public.alter_tablename_ifexists( 'public', 'nonorientationspros66', 'nonorientationsproseps66' );
SELECT public.alter_columnname_ifexists( 'public', 'decisionsnonorientationspros93', 'nonorientationpro93_id', 'nonorientationproep93_id' );
SELECT public.alter_tablename_ifexists( 'public', 'nonorientationspros93', 'nonorientationsproseps93' );

SELECT public.alter_tablename_ifexists( 'public', 'decisionsnonorientationspros58', 'decisionsnonorientationsproseps58' );
SELECT public.alter_tablename_ifexists( 'public', 'decisionsnonorientationspros66', 'decisionsnonorientationsproseps66' );
SELECT public.alter_tablename_ifexists( 'public', 'decisionsnonorientationspros93', 'decisionsnonorientationsproseps93' );

SELECT public.alter_columnname_ifexists( 'public', 'eps', 'saisineepbilanparcours66', 'saisinebilanparcoursep66' );
SELECT public.alter_columnname_ifexists( 'public', 'eps', 'saisineepdpdo66', 'saisinepdoep66' );
SELECT public.alter_columnname_ifexists( 'public', 'eps', 'saisineepreorientsr93', 'reorientationep93' );
SELECT public.alter_columnname_ifexists( 'public', 'eps', 'nonorientationpro58', 'nonorientationproep58' );
SELECT public.alter_columnname_ifexists( 'public', 'eps', 'nonorientationpro93', 'nonorientationproep93' );

ALTER TABLE dossierseps ALTER COLUMN themeep TYPE TEXT;
UPDATE dossierseps SET themeep = 'saisinesbilansparcourseps66' WHERE themeep = 'saisinesepsbilansparcours66';
UPDATE dossierseps SET themeep = 'saisinespdoseps66' WHERE themeep = 'saisinesepdspdos66';
UPDATE dossierseps SET themeep = 'reorientationseps93' WHERE themeep = 'saisinesepsreorientsrs93';
UPDATE dossierseps SET themeep = 'nonorientationsproseps58' WHERE themeep = 'nonorientationspros58';
UPDATE dossierseps SET themeep = 'nonorientationsproseps93' WHERE themeep = 'nonorientationspros93';
DROP TYPE IF EXISTS TYPE_THEMEEP;
CREATE TYPE TYPE_THEMEEP AS ENUM ( 'reorientationseps93', 'saisinesbilansparcourseps66', 'saisinespdoseps66', 'nonrespectssanctionseps93', 'defautsinsertionseps66', 'nonorientationsproseps58', 'nonorientationsproseps93', 'regressionsorientationseps58', 'sanctionseps58' );
ALTER TABLE dossierseps ALTER COLUMN themeep TYPE TYPE_THEMEEP USING CAST(themeep AS TYPE_THEMEEP);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
