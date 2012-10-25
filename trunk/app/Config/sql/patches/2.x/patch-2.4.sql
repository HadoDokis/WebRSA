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

-------------------------------------------------------------------------------------
-- 20121002: ajout du champ structurereferente_id à la table users
-------------------------------------------------------------------------------------

SELECT add_missing_table_field( 'public', 'users', 'structurereferente_id', 'INTEGER' );
SELECT add_missing_constraint ( 'public', 'users', 'users_structurereferente_id_fkey', 'structuresreferentes', 'structurereferente_id', false );
DROP INDEX IF EXISTS users_structurereferente_id_idx;
CREATE INDEX users_structurereferente_id_idx ON users( structurereferente_id );

-------------------------------------------------------------------------------------
-- 20121003: ajout du champ referent_id à la table users
-------------------------------------------------------------------------------------

SELECT add_missing_table_field( 'public', 'users', 'referent_id', 'INTEGER' );
SELECT add_missing_constraint ( 'public', 'users', 'users_referent_id_fkey', 'referents', 'referent_id', false );
DROP INDEX IF EXISTS users_referent_id_idx;
CREATE INDEX users_referent_id_idx ON users( referent_id );

-------------------------------------------------------------------------------------
-- Règles de validation CakePHP adaptées à PostgreSQL
-- @see Pgsqlcake.PgsqlAutovalidateBehavior.php
-- @see /2011/03/20110307
-------------------------------------------------------------------------------------

-- Règle de validation inList pour des entiers
CREATE OR REPLACE FUNCTION cakephp_validate_in_list( integer, integer[] ) RETURNS boolean AS
$$
	SELECT $1 IS NULL OR ( ARRAY[CAST($1 AS TEXT)] <@ CAST($2 AS TEXT[]) );
$$
LANGUAGE sql IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_in_list( integer, integer[] ) IS
	'@see http://api.cakephp.org/class/validation#method-ValidationinList';

-- FIXME: cakephp_validate_range
CREATE OR REPLACE FUNCTION cakephp_validate_range( p_check float, p_lower float, p_upper float ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL
			OR p_lower IS NULL
			OR p_upper IS NULL
			OR(
				p_check > p_lower
				AND p_check < p_upper
			);
	END;
$$
LANGUAGE plpgsql IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_range( p_check float, p_lower float, p_upper float ) IS
	'@see http://api.cakephp.org/class/validation#method-Validationrange';

CREATE OR REPLACE FUNCTION cakephp_validate_inclusive_range( p_check float, p_lower float, p_upper float ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL
			OR p_lower IS NULL
			OR p_upper IS NULL
			OR(
				p_check >= p_lower
				AND p_check <= p_upper
			);
	END;
$$
LANGUAGE plpgsql IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_inclusive_range( p_check float, p_lower float, p_upper float ) IS
	'Comme cakephp_validate_range(), mais avec les bornes incluses';

-------------------------------------------------------------------------------------
-- 20121003: nouveau CER pour le CG 93
-------------------------------------------------------------------------------------

-- tables liées au cers93
DROP TABLE IF EXISTS naturescontrats CASCADE;
CREATE TABLE naturescontrats (
	id				SERIAL NOT NULL PRIMARY KEY,
	name			VARCHAR(250) NOT NULL,
	isduree			VARCHAR(1) DEFAULT '0'
);
COMMENT ON TABLE naturescontrats IS 'Liste des natures de contrat paramétrable pour le CER93)';

DROP INDEX IF EXISTS naturescontrats_name_idx;
CREATE UNIQUE INDEX naturescontrats_name_idx ON naturescontrats( name );

ALTER TABLE naturescontrats ADD CONSTRAINT naturescontrats_isduree_in_list_chk CHECK ( cakephp_validate_in_list( isduree, ARRAY['0', '1'] ) );

-------------------------------------------------------------------------------------

DROP TABLE IF EXISTS metiersexerces CASCADE;
CREATE TABLE metiersexerces (
	id				SERIAL NOT NULL PRIMARY KEY,
	name			TEXT
);
COMMENT ON TABLE metiersexerces IS 'Métiers exercés en lien avec le CER 93 (bloc 4, formation et expérience)';

DROP INDEX IF EXISTS metiersexerces_name_idx;
CREATE UNIQUE INDEX metiersexerces_name_idx ON metiersexerces( name );

-------------------------------------------------------------------------------------
DROP TABLE IF EXISTS secteursactis CASCADE;
CREATE TABLE secteursactis (
	id				SERIAL NOT NULL PRIMARY KEY,
	name			TEXT
);
COMMENT ON TABLE secteursactis IS 'Secteurs d''activités exercés en lien avec le CER 93 (bloc 4, formation et expérience)';

DROP INDEX IF EXISTS secteursactis_name_idx;
CREATE UNIQUE INDEX secteursactis_name_idx ON secteursactis( name );

-------------------------------------------------------------------------------------

DROP TABLE IF EXISTS sujetscers93 CASCADE;
CREATE TABLE sujetscers93 (
	id					SERIAL NOT NULL PRIMARY KEY,
	name				VARCHAR(250) NOT NULL,
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE sujetscers93 IS 'Sujets sur lequel porte le CER CG93 (bloc 6)';
DROP INDEX IF EXISTS sujetscers93_name_idx;
CREATE UNIQUE INDEX sujetscers93_name_idx ON sujetscers93( name );

-------------------------------------------------------------------------------------

DROP TABLE IF EXISTS typessujetscers93 CASCADE;
CREATE TABLE typessujetscers93 (
	id					SERIAL NOT NULL PRIMARY KEY,
	name				VARCHAR(250) NOT NULL,
	sujetcer93_id		INTEGER NOT NULL REFERENCES sujetscers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE typessujetscers93 IS 'Types de sujet sur lequel porte le CER CG93 (bloc 6)';
DROP INDEX IF EXISTS typessujetscers93_name_idx;
CREATE UNIQUE INDEX typessujetscers93_name_idx ON typessujetscers93( name );

DROP INDEX IF EXISTS typessujetscers93_sujetcer93_id_idx;
CREATE INDEX typessujetscers93_sujetcer93_id_idx ON typessujetscers93(sujetcer93_id);

-- Tables devenues obsolètes au cours des développements
DROP TABLE IF EXISTS etatscivilscers93 CASCADE;

-- DROP TYPE IF EXISTS TYPE_CMU CASCADE;
-- CREATE TYPE TYPE_CMU AS ENUM ( 'oui', 'non', 'encours' );

-- DROP TYPE IF EXISTS TYPE_POSITIONCER93 CASCADE;
-- CREATE TYPE TYPE_POSITIONCER93 AS ENUM ( '00enregistre', '01signe', '02attdecisioncpdv', '03attdecisioncg', '04premierelecture', '05secondelecture', '06attaviscadre', '07attavisep', '99rejete', '99valide' );

-- Données spécifiques au CG 93
DROP TABLE IF EXISTS cers93 CASCADE;
CREATE TABLE cers93 (
	id						SERIAL NOT NULL PRIMARY KEY,
	contratinsertion_id		INTEGER NOT NULL REFERENCES contratsinsertion(id) ON DELETE CASCADE ON UPDATE CASCADE,
	user_id					INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
	-- Bloc 2: état cvil
	matricule				VARCHAR(15) DEFAULT NULL,
	dtdemrsa				DATE NOT NULL,
	qual					VARCHAR(3) DEFAULT NULL,
	nom						VARCHAR(50) NOT NULL,
	nomnai					VARCHAR(50) NOT NULL,
	prenom					VARCHAR(50) NOT NULL,
	dtnai					DATE NOT NULL,
	adresse					VARCHAR(250) DEFAULT NULL,
	codepos					VARCHAR(5) DEFAULT NULL,
	locaadr					VARCHAR(50) DEFAULT NULL,
	sitfam					VARCHAR(3) NOT NULL,
	natlog					VARCHAR(4) DEFAULT NULL,
	incoherencesetatcivil	TEXT DEFAULT NULL,
	-- Bloc 3: vérification des droits
	inscritpe				VARCHAR(1) DEFAULT NULL,
	cmu						VARCHAR(10) DEFAULT NULL,
	cmuc					VARCHAR(10) DEFAULT NULL,
	-- Bloc 4: formation et expérience
	nivetu					VARCHAR(4) DEFAULT NULL,
	numdemrsa				VARCHAR(11) DEFAULT NULL,
	rolepers				CHAR(3) DEFAULT NULL,
	identifiantpe			VARCHAR(11) DEFAULT NULL,
	positioncer				VARCHAR(20) NOT NULL DEFAULT '00enregistre',
	formeci					CHAR(1) DEFAULT NULL,
	datesignature			DATE DEFAULT NULL,
	autresexps				VARCHAR(250) DEFAULT NULL,
	isemploitrouv			VARCHAR(1) DEFAULT NULL,
	metierexerce_id			INTEGER REFERENCES metiersexerces(id) ON DELETE CASCADE ON UPDATE CASCADE,
	secteuracti_id			INTEGER REFERENCES secteursactis(id) ON DELETE CASCADE ON UPDATE CASCADE,
	naturecontrat_id		INTEGER REFERENCES naturescontrats(id) ON DELETE CASCADE ON UPDATE CASCADE,
	dureehebdo				INTEGER DEFAULT NULL,
	dureecdd				VARCHAR(3) DEFAULT NULL,
	prevu					TEXT DEFAULT NULL,
	bilancerpcd				TEXT DEFAULT NULL,
	sujetcer93_id			INTEGER REFERENCES sujetscers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	duree					INTEGER DEFAULT NULL,
	pointparcours			VARCHAR(25) DEFAULT NULL,
	datepointparcours		DATE DEFAULT NULL,
	pourlecomptede			VARCHAR(250)  DEFAULT NULL,
	observpro				TEXT,
	observbenef				TEXT,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE

);
COMMENT ON TABLE cers93 IS 'Données du CER spécifiques au CG 93';

DROP INDEX IF EXISTS cers93_contratinsertion_id_idx;
CREATE UNIQUE INDEX cers93_contratinsertion_id_idx ON cers93( contratinsertion_id );

ALTER TABLE cers93 ADD CONSTRAINT cers93_inscritpe_in_list_chk CHECK ( cakephp_validate_in_list( inscritpe, ARRAY['0', '1'] ) );
ALTER TABLE cers93 ADD CONSTRAINT cers93_cmu_in_list_chk CHECK ( cakephp_validate_in_list( cmu, ARRAY['oui', 'non', 'encours'] ) );
ALTER TABLE cers93 ADD CONSTRAINT cers93_cmuc_in_list_chk CHECK ( cakephp_validate_in_list( cmuc, ARRAY['oui', 'non', 'encours'] ) );
ALTER TABLE cers93 ADD CONSTRAINT cers93_nivetu_in_list_chk CHECK ( cakephp_validate_in_list( nivetu, ARRAY['1201', '1202', '1203', '1204', '1205', '1206', '1207'] ) );
ALTER TABLE cers93 ADD CONSTRAINT cers93_positioncer_in_list_chk CHECK ( cakephp_validate_in_list( positioncer, ARRAY['00enregistre', '01signe', '02attdecisioncpdv', '03attdecisioncg', '04premierelecture', '05secondelecture', '06attaviscadre', '07attavisep', '99rejete', '99valide'] ) );
ALTER TABLE cers93 ADD CONSTRAINT cers93_isemploitrouv_in_list_chk CHECK ( cakephp_validate_in_list( isemploitrouv, ARRAY['N', 'O'] ) );
ALTER TABLE cers93 ADD CONSTRAINT cers93_dureehebdo_inclusive_range_chk CHECK ( cakephp_validate_inclusive_range( dureehebdo, 0, 39 ) );
ALTER TABLE cers93 ADD CONSTRAINT cers93_duree_in_list_chk CHECK ( cakephp_validate_in_list( duree, ARRAY[3, 6, 9, 12] ) );
ALTER TABLE cers93 ADD CONSTRAINT cers93_pointparcours_in_list_chk CHECK ( cakephp_validate_in_list( pointparcours, ARRAY['aladate','alafin'] ) );

-------------------------------------------------------------------------------------

DROP TABLE IF EXISTS composfoyerscers93 CASCADE;
CREATE TABLE composfoyerscers93 (
	id			SERIAL NOT NULL PRIMARY KEY,
	cer93_id	INTEGER NOT NULL REFERENCES cers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	qual		VARCHAR(3) DEFAULT NULL,
	nom			VARCHAR(50) NOT NULL,
	prenom		VARCHAR(50) NOT NULL,
	dtnai		DATE NOT NULL,
	created		TIMESTAMP WITHOUT TIME ZONE,
	modified	TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE composfoyerscers93 IS 'Compositions du foyer pour le CER 93 (bloc 2, état civil)';

DROP INDEX IF EXISTS composfoyerscers93_cer93_id_idx;
CREATE INDEX composfoyerscers93_cer93_id_idx ON composfoyerscers93( cer93_id );

-------------------------------------------------------------------------------------

DROP TABLE IF EXISTS diplomescers93 CASCADE;
CREATE TABLE diplomescers93 (
	id			SERIAL NOT NULL PRIMARY KEY,
	cer93_id	INTEGER NOT NULL REFERENCES cers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	name		VARCHAR(250) NOT NULL,
	annee		INTEGER NOT NULL,
	created		TIMESTAMP WITHOUT TIME ZONE,
	modified	TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE diplomescers93 IS 'Diplômes obtenus pour le CER 93 (bloc 4, formation et expérience)';

DROP INDEX IF EXISTS diplomescers93_cer93_id_idx;
CREATE INDEX diplomescers93_cer93_id_idx ON diplomescers93( cer93_id );

-------------------------------------------------------------------------------------
DROP TABLE IF EXISTS expsproscers93 CASCADE;
CREATE TABLE expsproscers93 (
	id					SERIAL NOT NULL PRIMARY KEY,
	cer93_id			INTEGER NOT NULL REFERENCES cers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	metierexerce_id		INTEGER NOT NULL REFERENCES metiersexerces(id) ON DELETE CASCADE ON UPDATE CASCADE,
	secteuracti_id		INTEGER NOT NULL REFERENCES secteursactis(id) ON DELETE CASCADE ON UPDATE CASCADE,
	anneedeb			INTEGER NOT NULL,
	duree				VARCHAR(25) NOT NULL,
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE expsproscers93 IS 'Expériences professionnelles significatives pour le CER 93 (bloc 4, formation et expérience)';

DROP INDEX IF EXISTS expsproscers93_cer93_id_idx;
CREATE INDEX expsproscers93_cer93_id_idx ON expsproscers93( cer93_id );

-------------------------------------------------------------------------------------

DROP TABLE IF EXISTS histoschoixcers93 CASCADE;
CREATE TABLE histoschoixcers93 (
	id					SERIAL NOT NULL PRIMARY KEY,
	cer93_id			INTEGER NOT NULL REFERENCES cers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	user_id				INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
	commentaire			VARCHAR(250) DEFAULT NULL,
	formeci				CHAR(1) NOT NULL,
	etape				VARCHAR(20) NOT NULL,
	prevalide			VARCHAR(20) DEFAULT NULL,
	decisioncs			VARCHAR(20) DEFAULT NULL,
	decisioncadre		VARCHAR(20) DEFAULT NULL,
	datechoix			DATE DEFAULT NULL,
	isrejet				VARCHAR(1) DEFAULT NULL,
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE histoschoixcers93 IS 'Historiques des choix pris sur le CER 93 (signature, transfert cpdv, ...)';

DROP INDEX IF EXISTS histoschoixcers93_cer93_id_idx;
CREATE INDEX histoschoixcers93_cer93_id_idx ON histoschoixcers93( cer93_id );

DROP INDEX IF EXISTS histoschoixcers93_cer93_id_etape_idx;
CREATE UNIQUE INDEX histoschoixcers93_cer93_id_etape_idx ON histoschoixcers93( cer93_id, etape );

ALTER TABLE histoschoixcers93 ADD CONSTRAINT histoschoixcers93_etape_in_list_chk CHECK ( cakephp_validate_in_list( etape, ARRAY['00enregistre', '01signe', '02attdecisioncpdv', '03attdecisioncg', '04premierelecture', '05secondelecture', '06attaviscadre', '07attavisep', '99rejete', '99valide'] ) );
ALTER TABLE histoschoixcers93 ADD CONSTRAINT histoschoixcers93_prevalide_in_list_chk CHECK ( cakephp_validate_in_list( prevalide, ARRAY['arelire', 'prevalide'] ) );
ALTER TABLE histoschoixcers93 ADD CONSTRAINT histoschoixcers93_decisioncs_in_list_chk CHECK ( cakephp_validate_in_list( decisioncs, ARRAY['valide', 'aviscadre', 'passageep'] ) );
ALTER TABLE histoschoixcers93 ADD CONSTRAINT histoschoixcers93_decisioncadre_in_list_chk CHECK ( cakephp_validate_in_list( decisioncadre, ARRAY['valide', 'rejete', 'passageep'] ) );
ALTER TABLE histoschoixcers93 ADD CONSTRAINT histoschoixcers93_isrejet_in_list_chk CHECK ( cakephp_validate_in_list( isrejet, ARRAY['0', '1'] ) );

-------------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS cers93_sujetscers93;
CREATE TABLE cers93_sujetscers93 (
    id                 SERIAL NOT NULL PRIMARY KEY,
    cer93_id       		INTEGER NOT NULL REFERENCES cers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
    sujetcer93_id      INTEGER NOT NULL REFERENCES sujetscers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
    created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
DROP INDEX IF EXISTS cers93_sujetscers93_cer93_id_idx;
CREATE INDEX cers93_sujetscers93_cer93_id_idx ON cers93_sujetscers93(cer93_id);

DROP INDEX IF EXISTS cers93_sujetscers93_sujetcer93_id_idx;
CREATE INDEX cers93_sujetscers93_sujetcer93_id_idx ON cers93_sujetscers93(sujetcer93_id);


-- DROP TYPE IF EXISTS TYPE_POSITIONCER93;
-- CREATE TYPE TYPE_POSITIONCER93 AS ENUM ( 'enregistre', 'signe', 'attdecisioncpdv', 'attdecisioncg', 'relire', 'prevalide'  );
--
-- SELECT add_missing_table_field ('public', 'cers93', 'positioncer', 'TYPE_POSITIONCER93');
-- ALTER TABLE cers93 ALTER COLUMN positioncer SET DEFAULT 'enregistre'::TYPE_POSITIONCER93;
-- SELECT add_missing_table_field ('public', 'cers93', 'formeci', 'CHAR(1)');
-- SELECT add_missing_table_field ('public', 'cers93', 'datesignature', 'DATE');
-- SELECT add_missing_table_field ('public', 'cers93', 'isemploitrouv', 'TYPE_NO');
-- SELECT add_missing_table_field ('public', 'cers93', 'autresexps', 'VARCHAR(250)');


-- SELECT add_missing_table_field('public', 'cers93', 'metierexerce_id', 'INTEGER' );
-- SELECT add_missing_constraint ( 'public', 'cers93', 'cers93_metierexerce_id_fkey', 'metiersexerces', 'metierexerce_id', false );
-- SELECT add_missing_table_field('public', 'cers93', 'secteuracti_id', 'INTEGER' );
-- SELECT add_missing_constraint ( 'public', 'cers93', 'cers93_secteuracti_id_fkey', 'secteursactis', 'secteuracti_id', false );
-- SELECT add_missing_table_field('public', 'cers93', 'naturecontrat_id', 'INTEGER' );
-- SELECT add_missing_constraint ( 'public', 'cers93', 'cers93_naturecontrat_id_fkey', 'naturescontrats', 'naturecontrat_id', false );


-- SELECT add_missing_table_field ('public', 'cers93', 'dureehebdo', 'INTEGER');
-- ALTER TABLE cers93 ALTER COLUMN dureehebdo SET DEFAULT '0'::TYPE_DUREEHEBDO;
-- SELECT add_missing_table_field ('public', 'cers93', 'dureecdd', 'VARCHAR(3)');
-- SELECT add_missing_table_field ('public', 'cers93', 'bilancerpcd', 'TEXT');
-- SELECT add_missing_table_field ('public', 'cers93', 'duree', 'INTEGER');
-- SELECT add_missing_table_field ('public', 'cers93', 'pointparcours', 'VARCHAR(25)');

-- SELECT add_missing_table_field('public', 'cers93', 'user_id', 'INTEGER' );
-- SELECT add_missing_constraint ( 'public', 'cers93', 'cers93_user_id_fkey', 'users', 'user_id', false );

-- SELECT add_missing_table_field ('public', 'cers93', 'nomutilisateur', 'VARCHAR(50)');
-- SELECT add_missing_table_field ('public', 'cers93', 'structureutilisateur', 'VARCHAR(100)');

-- SELECT add_missing_table_field ('public', 'cers93', 'pourlecomptede', 'VARCHAR(250)');
-- SELECT add_missing_table_field ('public', 'cers93', 'observpro', 'TEXT');
-- SELECT add_missing_table_field ('public', 'cers93', 'observbenef', 'TEXT');

-- SELECT add_missing_table_field ('public', 'cers93', 'datepointparcours', 'DATE');

-- SELECT add_missing_table_field ('public', 'histoschoixcers93', 'prevalide', 'VARCHAR(20)');
-- SELECT add_missing_table_field ('public', 'histoschoixcers93', 'decisioncs', 'VARCHAR(20)');
-- SELECT add_missing_table_field ('public', 'histoschoixcers93', 'decisioncadre', 'VARCHAR(20)');

-- TODO aauzolat, remplacement des enums par des cakephp_validate_in_list
-- naturescontrats.isduree: TYPE_BOOLEANNUMBER -> VARCHAR(1)
-- cers.inscritpe TYPE_BOOLEANNUMBER -> VARCHAR(1)
-- cers.cmu TYPE_CMU -> VARCHAR(10)
-- cers.cmuc TYPE_CMU -> VARCHAR(10)
-- cers.nivetu TYPE_NIVETU -> VARCHAR(4)
-- cers.positioncer TYPE_POSITIONCER93 -> VARCHAR(20)
-- cers.isemploitrouv TYPE_NO -> VARCHAR(1)
-- histoschoixcers93.etape TYPE_POSITIONCER93 -> VARCHAR(20)

-- *****************************************************************************
COMMIT;
-- *****************************************************************************