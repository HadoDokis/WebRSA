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

/*DROP TABLE IF EXISTS regressionsorientationseps93 CASCADE;
DROP TABLE IF EXISTS decisionsregressionsorientationseps93 CASCADE;*/

-- *****************************************************************************

SELECT add_missing_table_field ('public', 'contratsinsertion', 'current_action', 'TEXT');

/*CREATE TABLE regressionsorientationseps93 (
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
CREATE INDEX decisionsregressionsorientationseps93_referent_id_idx ON decisionsregressionsorientationseps93 (referent_id);*/

ALTER TABLE membreseps_seanceseps RENAME TO commissionseps_membreseps;

ALTER TABLE dossierseps RENAME COLUMN seanceep_id TO commissionep_id;
ALTER TABLE commissionseps_membreseps RENAME COLUMN seanceep_id TO commissionep_id;
ALTER TABLE seanceseps RENAME TO commissionseps;

ALTER TABLE nvsrsepsreorientsrs93 RENAME TO decisionsreorientationseps93;

ALTER TABLE decisionsreorientationseps93 RENAME COLUMN saisineepreorientsr93_id TO reorientationep93_id;
ALTER TABLE saisinesepsreorientsrs93 RENAME TO reorientationseps93;

ALTER TABLE nvsepdspdos66 RENAME TO decisionssaisinespdoseps66;

ALTER TABLE decisionssaisinespdoseps66 RENAME COLUMN saisineepdpdo66_id TO saisinepdoep66_id;
ALTER TABLE saisinesepdspdos66 RENAME TO saisinespdoseps66;

ALTER TABLE nvsrsepsreorient66 RENAME TO decisionssaisinesbilansparcourseps66;

ALTER TABLE decisionssaisinesbilansparcourseps66 RENAME COLUMN saisineepbilanparcours66_id TO saisinebilanparcoursep66_id;
ALTER TABLE saisinesepsbilansparcours66 RENAME TO saisinesbilansparcourseps66;

ALTER TABLE reorientationseps93 RENAME COLUMN motifreorient_id TO motifreorientep93_id;
ALTER TABLE motifsreorients RENAME TO motifsreorientseps93;

ALTER TABLE decisionsnonorientationspros58 RENAME COLUMN nonorientationpro58_id TO nonorientationproep58_id;
ALTER TABLE nonorientationspros58 RENAME TO nonorientationsproseps58;
ALTER TABLE decisionsnonorientationspros66 RENAME COLUMN nonorientationpro66_id TO nonorientationproep66_id;
ALTER TABLE nonorientationspros66 RENAME TO nonorientationsproseps66;
ALTER TABLE decisionsnonorientationspros93 RENAME COLUMN nonorientationpro93_id TO nonorientationproep93_id;
ALTER TABLE nonorientationspros93 RENAME TO nonorientationsproseps93;

ALTER TABLE decisionsnonorientationspros58 RENAME TO decisionsnonorientationsproseps58;
ALTER TABLE decisionsnonorientationspros66 RENAME TO decisionsnonorientationsproseps66;
ALTER TABLE decisionsnonorientationspros93 RENAME TO decisionsnonorientationsproseps93;

ALTER TABLE eps RENAME COLUMN saisineepbilanparcours66 TO saisinebilanparcoursep66;
ALTER TABLE eps RENAME COLUMN saisineepdpdo66 TO saisinepdoep66;
ALTER TABLE eps RENAME COLUMN saisineepreorientsr93 TO reorientationep93;
ALTER TABLE eps RENAME COLUMN nonorientationpro58 TO nonorientationproep58;
ALTER TABLE eps RENAME COLUMN nonorientationpro93 TO nonorientationproep93;

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
