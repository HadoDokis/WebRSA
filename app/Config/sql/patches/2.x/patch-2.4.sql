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
-- 20121003: nouveau CER pour le CG 93
-------------------------------------------------------------------------------------

-- Tables devenues obsolètes au cours des développements
DROP TABLE IF EXISTS etatscivilscers93 CASCADE;

DROP TYPE IF EXISTS TYPE_CMU;
CREATE TYPE TYPE_CMU AS ENUM ( 'oui', 'non', 'encours' );

-- Données spécifiques au CG 93
DROP TABLE IF EXISTS cers93 CASCADE;
CREATE TABLE cers93 (
	id						SERIAL NOT NULL PRIMARY KEY,
	contratinsertion_id		INTEGER NOT NULL REFERENCES contratsinsertion(id) ON DELETE CASCADE ON UPDATE CASCADE,
	-- Bloc 2: état cvil
	-- FIXME: numdemrsa, rolepers, identifiantpe
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
	inscritpe				TYPE_BOOLEANNUMBER DEFAULT NULL,
	cmu						TYPE_CMU DEFAULT NULL,
	cmuc					TYPE_CMU DEFAULT NULL,
	-- Bloc 4: formation et expérience
	nivetu					TYPE_NIVETU DEFAULT NULL

);
COMMENT ON TABLE cers93 IS 'Données du CER spécifiques au CG 93';

DROP INDEX IF EXISTS cers93_contratinsertion_id_idx;
CREATE INDEX cers93_contratinsertion_id_idx ON cers93( contratinsertion_id );

-------------------------------------------------------------------------------------

DROP TABLE IF EXISTS composfoyerscers93 CASCADE;
CREATE TABLE composfoyerscers93 (
	id			SERIAL NOT NULL PRIMARY KEY,
	cer93_id	INTEGER NOT NULL REFERENCES cers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	qual		VARCHAR(3) DEFAULT NULL,
	nom			VARCHAR(50) NOT NULL,
	prenom		VARCHAR(50) NOT NULL,
	dtnai		DATE NOT NULL
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
	annee		INTEGER NOT NULL
);
COMMENT ON TABLE diplomescers93 IS 'Diplômes obtenus pour le CER 93 (bloc 4, formation et expérience)';

DROP INDEX IF EXISTS diplomescers93_cer93_id_idx;
CREATE INDEX diplomescers93_cer93_id_idx ON diplomescers93( cer93_id );


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
DROP TABLE IF EXISTS expsproscers93 CASCADE;
CREATE TABLE expsproscers93 (
	id					SERIAL NOT NULL PRIMARY KEY,
	cer93_id			INTEGER NOT NULL REFERENCES cers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	metierexerce_id		INTEGER NOT NULL REFERENCES metiersexerces(id) ON DELETE CASCADE ON UPDATE CASCADE,
	secteuracti_id		INTEGER NOT NULL REFERENCES secteursactis(id) ON DELETE CASCADE ON UPDATE CASCADE,
	anneedeb			INTEGER NOT NULL,
	duree				VARCHAR(25) NOT NULL
);
COMMENT ON TABLE expsproscers93 IS 'Expériences professionnelles significatives pour le CER 93 (bloc 4, formation et expérience)';

DROP INDEX IF EXISTS expsproscers93_cer93_id_idx;
CREATE INDEX expsproscers93_cer93_id_idx ON expsproscers93( cer93_id );


-- *****************************************************************************
COMMIT;
-- *****************************************************************************