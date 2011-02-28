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
DROP TABLE IF EXISTS radiespoleemploieps93 CASCADE;
DROP TABLE IF EXISTS decisionsradiespoleemploieps93 CASCADE;
DROP TABLE IF EXISTS radiespoleemploieps58 CASCADE;
DROP TABLE IF EXISTS decisionsradiespoleemploieps58 CASCADE;

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
DROP INDEX IF EXISTS radiespoleemploieps93_historiqueetatpe_id_idx;
DROP INDEX IF EXISTS decisionsradiespoleemploieps93_radiepoleemploiep93_id_idx;
DROP INDEX IF EXISTS radiespoleemploieps58_historiqueetatpe_id_idx;
DROP INDEX IF EXISTS decisionsradiespoleemploieps58_radiepoleemploiep58_id_idx;

-- *****************************************************************************

ALTER TABLE dossierseps ALTER COLUMN themeep TYPE TEXT;
DROP TYPE IF EXISTS TYPE_THEMEEP;
CREATE TYPE TYPE_THEMEEP AS ENUM ( 'saisinesepsreorientsrs93', 'saisinesepsbilansparcours66', /*'suspensionsreductionsallocations93',*/ 'saisinesepdspdos66', 'nonrespectssanctionseps93', 'defautsinsertionseps66', 'nonorientationspros58', 'regressionsorientationseps58', 'radiespoleemploieps93', 'radiespoleemploieps58' );
ALTER TABLE dossierseps ALTER COLUMN themeep TYPE TYPE_THEMEEP USING CAST(themeep AS TYPE_THEMEEP);

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

CREATE TABLE decisionsregressionsorientationseps58 (
	id      						SERIAL NOT NULL PRIMARY KEY,
	regressionorientationep58_id	INTEGER NOT NULL REFERENCES regressionsorientationseps58(id) ON DELETE CASCADE ON UPDATE CASCADE,
	typeorient_id					INTEGER DEFAULT NULL REFERENCES typesorients(id) ON UPDATE CASCADE ON DELETE SET NULL,
	structurereferente_id			INTEGER DEFAULT NULL REFERENCES structuresreferentes(id) ON UPDATE CASCADE ON DELETE SET NULL,
	referent_id						INTEGER DEFAULT NULL REFERENCES referents(id) ON DELETE SET NULL ON UPDATE CASCADE,
	etape							TYPE_ETAPEDECISIONEP NOT NULL,
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
SELECT alter_table_drop_column_if_exists( 'public', 'contratsinsertion', 'datesuspensionparticulier' );
SELECT alter_table_drop_column_if_exists( 'public', 'contratsinsertion', 'dateradiationparticulier' );
ALTER TABLE contratsinsertion ADD COLUMN datesuspensionparticulier DATE DEFAULT NULL;
ALTER TABLE contratsinsertion ADD COLUMN dateradiationparticulier DATE DEFAULT NULL;

-- -----------------------------------------------------------------------------
-- 20110222
-- -----------------------------------------------------------------------------
CREATE TABLE radiespoleemploieps93 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	dossierep_id			INTEGER DEFAULT NULL REFERENCES dossierseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	historiqueetatpe_id		INTEGER DEFAULT NULL REFERENCES historiqueetatspe(id) ON DELETE CASCADE ON UPDATE CASCADE,
	commentaire				TEXT DEFAULT NULL,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE radiespoleemploieps93 IS 'Thématique de détection des radiés de Pôle Emploi (CG93)';

CREATE INDEX radiespoleemploieps93_historiqueetatpe_id_idx ON radiespoleemploieps93 (historiqueetatpe_id);

SELECT add_missing_table_field ('public', 'eps', 'radiepoleemploiep93', 'TYPE_NIVEAUDECISIONEP');
ALTER TABLE eps ALTER COLUMN radiepoleemploiep93 SET DEFAULT 'nontraite';
UPDATE eps SET radiepoleemploiep93 = 'nontraite' WHERE radiepoleemploiep93 IS NULL;
ALTER TABLE eps ALTER COLUMN radiepoleemploiep93 SET NOT NULL;

CREATE TABLE decisionsradiespoleemploieps93 (
	id      						SERIAL NOT NULL PRIMARY KEY,
	radiepoleemploiep93_id			INTEGER NOT NULL REFERENCES radiespoleemploieps93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etape							TYPE_ETAPEDECISIONEP NOT NULL,
	decision						TYPE_DECISIONSANCTIONEP93 DEFAULT NULL,
	commentaire						TEXT DEFAULT NULL,
	created							TIMESTAMP WITHOUT TIME ZONE,
	modified						TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE decisionsradiespoleemploieps93 IS 'Décisions pour la thématique de détection des radiés de Pôle Emploi (CG93)';

CREATE INDEX decisionsradiespoleemploieps93_radiepoleemploiep93_id_idx ON decisionsradiespoleemploieps93 (radiepoleemploiep93_id);

-- -----------------------------------------------------------------------------
-- 20110222
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION public.calcul_cle_nir( TEXT ) RETURNS TEXT AS
$body$
	DECLARE
		p_nir text;
		cle text;
		correction BIGINT;

	BEGIN
		correction:=0;
		p_nir:=$1;

		IF NOT p_nir ~ '^[0-9]{6}(A|B|[0-9])[0-9]{6}$' THEN
			RETURN NULL;
		END IF;

		IF p_nir ~ '^.{6}(A|B)' THEN
			IF p_nir ~ '^.{6}A' THEN
				correction:=1000000;
			ELSE
				correction:=2000000;
			END IF;
			p_nir:=regexp_replace( p_nir, '(A|B)', '0' );
		END IF;

		cle:=LPAD( CAST( 97 - ( ( CAST( p_nir AS BIGINT ) - correction ) % 97 ) AS VARCHAR(13)), 2, '0' );
		RETURN cle;
	END;
$body$ LANGUAGE plpgsql;

COMMENT ON FUNCTION public.calcul_cle_nir( TEXT ) IS
	'Calcul de la clé d''un NIR. Retourne NULL si le NIR n''est pas sur 13 caractères (6 chiffres - A, B ou un chiffre - 6 chiffres) ou une chaîne de 2 caractères correspondant à la clé.';

-- -----------------------------------------------------------------------------
-- 20110228
-- -----------------------------------------------------------------------------
CREATE TABLE radiespoleemploieps58 (
	id      				SERIAL NOT NULL PRIMARY KEY,
	dossierep_id			INTEGER DEFAULT NULL REFERENCES dossierseps(id) ON DELETE CASCADE ON UPDATE CASCADE,
	historiqueetatpe_id		INTEGER DEFAULT NULL REFERENCES historiqueetatspe(id) ON DELETE CASCADE ON UPDATE CASCADE,
	commentaire				TEXT DEFAULT NULL,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE radiespoleemploieps58 IS 'Thématique de détection des radiés de Pôle Emploi (CG58)';

CREATE INDEX radiespoleemploieps58_historiqueetatpe_id_idx ON radiespoleemploieps58 (historiqueetatpe_id);

SELECT add_missing_table_field ('public', 'eps', 'radiepoleemploiep58', 'TYPE_NIVEAUDECISIONEP');
ALTER TABLE eps ALTER COLUMN radiepoleemploiep58 SET DEFAULT 'nontraite';
UPDATE eps SET radiepoleemploiep58 = 'nontraite' WHERE radiepoleemploiep58 IS NULL;
ALTER TABLE eps ALTER COLUMN radiepoleemploiep58 SET NOT NULL;

CREATE TABLE decisionsradiespoleemploieps58 (
	id      						SERIAL NOT NULL PRIMARY KEY,
	radiepoleemploiep58_id			INTEGER NOT NULL REFERENCES radiespoleemploieps58(id) ON DELETE CASCADE ON UPDATE CASCADE,
	etape							TYPE_ETAPEDECISIONEP NOT NULL,
	decision						TYPE_DECISIONSANCTIONEP93 DEFAULT NULL,
	commentaire						TEXT DEFAULT NULL,
	created							TIMESTAMP WITHOUT TIME ZONE,
	modified						TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE decisionsradiespoleemploieps58 IS 'Décisions pour la thématique de détection des radiés de Pôle Emploi (CG58)';

CREATE INDEX decisionsradiespoleemploieps58_radiepoleemploiep58_id_idx ON decisionsradiespoleemploieps58 (radiepoleemploiep58_id);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************