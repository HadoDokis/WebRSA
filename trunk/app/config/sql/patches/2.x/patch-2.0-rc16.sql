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

DROP TABLE IF EXISTS regressionsorientationseps58 CASCADE;
DROP TABLE IF EXISTS decisionsregressionsorientationseps58 CASCADE;

DROP INDEX IF EXISTS dsps_personne_id_idx;
CREATE INDEX dsps_personne_id_idx ON dsps(personne_id);

DROP INDEX IF EXISTS regressionsorientationseps58_dossierep_id_idx;
DROP INDEX IF EXISTS regressionsorientationseps58_typeorient_id_idx;
DROP INDEX IF EXISTS regressionsorientationseps58_structurereferente_id_idx;
DROP INDEX IF EXISTS regressionsorientationseps58_referent_id_idx;
DROP INDEX IF EXISTS decisionsregressionsorientationseps58_regressionorientationep58_id_idx;
DROP INDEX IF EXISTS decisionsregressionsorientationseps58_typeorient_id_idx;
DROP INDEX IF EXISTS decisionsregressionsorientationseps58_structurereferente_id_idx;
DROP INDEX IF EXISTS decisionsregressionsorientationseps58_referent_id_idx;

-- *****************************************************************************

CREATE TABLE regressionsorientationseps58 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	dossierep_id			INTEGER DEFAULT NULL REFERENCES dossierseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	typeorient_id			INTEGER NOT NULL REFERENCES typesorients(id) ON DELETE CASCADE ON UPDATE CASCADE,
	structurereferente_id	INTEGER NOT NULL REFERENCES structuresreferentes(id) ON DELETE CASCADE ON UPDATE CASCADE,
	datedemande				DATE NOT NULL,
	referent_id				INTEGER DEFAULT NULL REFERENCES referents(id) ON DELETE SET NULL ON UPDATE CASCADE,
	commentaire				TEXT DEFAULT NULL,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE regressionsorientationseps58 IS 'Thématique pour la réorientation du professionel vers le social (CG58)';

CREATE INDEX regressionsorientationseps58_dossierep_id_idx ON regressionsorientationseps58 (dossierep_id);
CREATE INDEX regressionsorientationseps58_typeorient_id_idx ON regressionsorientationseps58 (typeorient_id);
CREATE INDEX regressionsorientationseps58_structurereferente_id_idx ON regressionsorientationseps58 (structurereferente_id);
CREATE INDEX regressionsorientationseps58_referent_id_idx ON regressionsorientationseps58 (referent_id);

SELECT add_missing_table_field ('public', 'eps', 'regressionorientationep58', 'TYPE_NIVEAUDECISIONEP');
ALTER TABLE eps ALTER COLUMN regressionorientationep58 SET DEFAULT 'nontraite';
UPDATE eps SET regressionorientationep58 = 'nontraite' WHERE regressionorientationep58 IS NULL;
ALTER TABLE eps ALTER COLUMN regressionorientationep58 SET NOT NULL;

ALTER TABLE dossierseps ALTER COLUMN themeep TYPE TEXT;
DROP TYPE IF EXISTS TYPE_THEMEEP;
CREATE TYPE TYPE_THEMEEP AS ENUM ( 'saisinesepsreorientsrs93', 'saisinesepsbilansparcours66', /*'suspensionsreductionsallocations93',*/ 'saisinesepdspdos66', 'nonrespectssanctionseps93', 'defautsinsertionseps66', 'nonorientationspros58', 'regressionsorientationseps58' );
ALTER TABLE dossierseps ALTER COLUMN themeep TYPE TYPE_THEMEEP USING CAST(themeep AS TYPE_THEMEEP);

CREATE TABLE decisionsregressionsorientationseps58 (
	id      						SERIAL NOT NULL PRIMARY KEY,
	regressionorientationep58_id	INTEGER NOT NULL REFERENCES regressionsorientationseps58(id) ON DELETE CASCADE ON UPDATE CASCADE,
	typeorient_id					INTEGER DEFAULT NULL REFERENCES typesorients(id) ON UPDATE CASCADE ON DELETE SET NULL,
	structurereferente_id			INTEGER DEFAULT NULL REFERENCES structuresreferentes(id) ON UPDATE CASCADE ON DELETE SET NULL,
	referent_id						INTEGER DEFAULT NULL REFERENCES referents(id) ON DELETE SET NULL ON UPDATE CASCADE,
	etape							TYPE_ETAPEDECISIONEP NOT NULL,
	decision						TYPE_DECISIONSANCTIONEP93 DEFAULT NULL,
	commentaire						TEXT DEFAULT NULL,
	created							TIMESTAMP WITHOUT TIME ZONE,
	modified						TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE decisionsregressionsorientationseps58 IS 'Décisions pour la thématique de la réorientation du professionel vers le social (CG58)';

CREATE INDEX decisionsregressionsorientationseps58_regressionorientationep58_id_idx ON decisionsregressionsorientationseps58 (regressionorientationep58_id);
CREATE INDEX decisionsregressionsorientationseps58_typeorient_id_idx ON decisionsregressionsorientationseps58 (typeorient_id);
CREATE INDEX decisionsregressionsorientationseps58_structurereferente_id_idx ON decisionsregressionsorientationseps58 (structurereferente_id);
CREATE INDEX decisionsregressionsorientationseps58_referent_id_idx ON decisionsregressionsorientationseps58 (referent_id);


-- -----------------------------------------------------------------------------
-- 20110221 
-- -----------------------------------------------------------------------------
ALTER TABLE contratsinsertion ADD COLUMN datesuspensionparticulier DATE DEFAULT NULL;
ALTER TABLE contratsinsertion ADD COLUMN dateradiationparticulier DATE DEFAULT NULL;
SELECT alter_table_drop_column_if_exists( 'public', 'contratsinsertion', 'datesuspensionparticulier' );
SELECT alter_table_drop_column_if_exists( 'public', 'contratsinsertion', 'dateradiationparticulier' );


-- *****************************************************************************
COMMIT;
-- *****************************************************************************