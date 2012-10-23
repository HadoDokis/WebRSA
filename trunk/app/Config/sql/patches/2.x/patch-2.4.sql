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

DROP TYPE IF EXISTS TYPE_CMU CASCADE;
CREATE TYPE TYPE_CMU AS ENUM ( 'oui', 'non', 'encours' );

DROP TYPE IF EXISTS TYPE_POSITIONCER93 CASCADE;
CREATE TYPE TYPE_POSITIONCER93 AS ENUM ( '00enregistre', '01signe', '02attdecisioncpdv', '03attdecisioncg', '04premierelecture', '05secondelecture', '06attaviscadre', '07attavisep', '99rejete', '99valide' );

-- Données spécifiques au CG 93
DROP TABLE IF EXISTS cers93 CASCADE;
CREATE TABLE cers93 (
	id						SERIAL NOT NULL PRIMARY KEY,
	contratinsertion_id		INTEGER NOT NULL REFERENCES contratsinsertion(id) ON DELETE CASCADE ON UPDATE CASCADE,
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
	inscritpe				TYPE_BOOLEANNUMBER DEFAULT NULL,
	cmu						TYPE_CMU DEFAULT NULL,
	cmuc					TYPE_CMU DEFAULT NULL,
	-- Bloc 4: formation et expérience
	nivetu					TYPE_NIVETU DEFAULT NULL,
	-- FIXME: numdemrsa, rolepers, identifiantpe
	numdemrsa				VARCHAR(11) DEFAULT NULL,
	rolepers				CHAR(3) DEFAULT NULL,
	identifiantpe			VARCHAR(11) DEFAULT NULL,
	positioncer				TYPE_POSITIONCER93 NOT NULL DEFAULT '00enregistre',
	formeci					CHAR(1) DEFAULT NULL,
	datesignature			DATE DEFAULT NULL,
	autresexps				VARCHAR(250) DEFAULT NULL,
	isemploitrouv			TYPE_NO DEFAULT NULL,
	metierexerce_id			INTEGER REFERENCES metiersexerces(id) ON DELETE CASCADE ON UPDATE CASCADE,
	secteuracti_id			INTEGER REFERENCES secteursactis(id) ON DELETE CASCADE ON UPDATE CASCADE,
	naturecontrat_id		INTEGER REFERENCES naturescontrats(id) ON DELETE CASCADE ON UPDATE CASCADE,
	dureehebdo				INTEGER DEFAULT NULL,
	dureecdd				VARCHAR(3) DEFAULT NULL,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE

);
COMMENT ON TABLE cers93 IS 'Données du CER spécifiques au CG 93';

DROP INDEX IF EXISTS cers93_contratinsertion_id_idx;
CREATE UNIQUE INDEX cers93_contratinsertion_id_idx ON cers93( contratinsertion_id );

-- ALTER TABLE modelestypescourrierspcgs66 ALTER COLUMN ismontant SET DEFAULT '0'::TYPE_BOOLEANNUMBER;
-- UPDATE modelestypescourrierspcgs66 SET ismontant = '0'::TYPE_BOOLEANNUMBER WHERE ismontant IS NULL;

-------------------------------------------------------------------------------------

DROP TABLE IF EXISTS composfoyerscers93 CASCADE;
CREATE TABLE composfoyerscers93 (
	id			SERIAL NOT NULL PRIMARY KEY,
	cer93_id	INTEGER NOT NULL REFERENCES cers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	qual		VARCHAR(3) DEFAULT NULL,
	nom			VARCHAR(50) NOT NULL,
	prenom		VARCHAR(50) NOT NULL,
	dtnai		DATE NOT NULL,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
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
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
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
	duree				VARCHAR(25) NOT NULL,
	created					TIMESTAMP WITHOUT TIME ZONE,
	modified				TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE expsproscers93 IS 'Expériences professionnelles significatives pour le CER 93 (bloc 4, formation et expérience)';

DROP INDEX IF EXISTS expsproscers93_cer93_id_idx;
CREATE INDEX expsproscers93_cer93_id_idx ON expsproscers93( cer93_id );

-------------------------------------------------------------------------------------

DROP TYPE IF EXISTS TYPE_DECISIONPREVALIDHISTOCER93 CASCADE;
CREATE TYPE TYPE_DECISIONPREVALIDHISTOCER93 AS ENUM ( 'arelire', 'prevalide' );

DROP TYPE IF EXISTS TYPE_DECISIONCSHISTOCER93 CASCADE;
CREATE TYPE TYPE_DECISIONCSHISTOCER93 AS ENUM ( 'valide', 'aviscadre', 'passageep' );

DROP TYPE IF EXISTS TYPE_DECISIONCADREHISTOCER93 CASCADE;
CREATE TYPE TYPE_DECISIONCADREHISTOCER93 AS ENUM ( 'valide', 'rejete', 'passageep' );

DROP TABLE IF EXISTS histoschoixcers93 CASCADE;
CREATE TABLE histoschoixcers93 (
	id					SERIAL NOT NULL PRIMARY KEY,
	cer93_id			INTEGER NOT NULL REFERENCES cers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	user_id				INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
	commentaire			VARCHAR(250) DEFAULT NULL,
	formeci				CHAR(1) NOT NULL,
	etape				TYPE_POSITIONCER93 NOT NULL,
	prevalide			TYPE_DECISIONPREVALIDHISTOCER93 DEFAULT NULL,
	decisioncs			TYPE_DECISIONCSHISTOCER93 DEFAULT NULL,
	decisioncadre		TYPE_DECISIONCADREHISTOCER93 DEFAULT NULL,
	datechoix			DATE DEFAULT NULL,
	created				TIMESTAMP WITHOUT TIME ZONE,
	modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE histoschoixcers93 IS 'Historiques des choix pris sur le CER 93 (signature, transfert cpdv, ...)';

DROP INDEX IF EXISTS histoschoixcers93_cer93_id_idx;
CREATE INDEX histoschoixcers93_cer93_id_idx ON histoschoixcers93( cer93_id );

DROP INDEX IF EXISTS histoschoixcers93_cer93_id_etape_idx;
CREATE UNIQUE INDEX histoschoixcers93_cer93_id_etape_idx ON histoschoixcers93( cer93_id, etape );


DROP TABLE IF EXISTS naturescontrats CASCADE;
CREATE TABLE naturescontrats (
	id				SERIAL NOT NULL PRIMARY KEY,
	name			VARCHAR(250) NOT NULL,
	isduree			TYPE_BOOLEANNUMBER DEFAULT '0'
);
COMMENT ON TABLE naturescontrats IS 'Liste des natures de contrat paramétrable pour le CER93)';

DROP INDEX IF EXISTS naturescontrats_name_idx;
CREATE UNIQUE INDEX naturescontrats_name_idx ON naturescontrats( name );
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

-- *****************************************************************************
COMMIT;
-- *****************************************************************************