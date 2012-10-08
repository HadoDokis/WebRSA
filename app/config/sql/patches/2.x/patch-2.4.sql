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

DROP TABLE IF EXISTS cers93 CASCADE;
CREATE TABLE cers93 (
	id 					SERIAL NOT NULL PRIMARY KEY,
	contratinsertion_id             	INTEGER NOT NULL REFERENCES contratsinsertion(id) ON DELETE CASCADE ON UPDATE CASCADE
);
COMMENT ON TABLE cers93 IS 'Données du CER spécifiques au CG 93';

SELECT add_missing_constraint ( 'public', 'cers93', 'cers93_contratinsertion_id_fkey', 'contratsinsertion', 'contratinsertion_id', false );
DROP INDEX IF EXISTS cers93_contratinsertion_id_idx;
CREATE INDEX cers93_contratinsertion_id_idx ON cers93( contratinsertion_id );

-------------------------------------------------------------------------------------

DROP TABLE IF EXISTS etatscivilscers93 CASCADE;
CREATE TABLE etatscivilscers93 (
	id 			SERIAL NOT NULL PRIMARY KEY,
	cer93_id             	INTEGER NOT NULL REFERENCES cers93(id) ON DELETE CASCADE ON UPDATE CASCADE,
	incoherences	TEXT DEFAULT NULL
);
COMMENT ON TABLE etatscivilscers93 IS 'Données du CER spécifiques au (CG 93)';

SELECT add_missing_constraint ( 'public', 'etatscivilscers93', 'etatscivilscers93_cer93_id_fkey', 'cers93', 'cer93_id', false );
DROP INDEX IF EXISTS etatscivilscers93_cer93_id_idx;
CREATE INDEX etatscivilscers93_cer93_id_idx ON etatscivilscers93( cer93_id );


-- *****************************************************************************
COMMIT;
-- *****************************************************************************