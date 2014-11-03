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
-- TODO: créer les bases de données en v280
-- grep -lri "romev3" app | grep -v "\.svn" => on en a un peu partout
/*
	FIXME: unicité sur les codes (et les clés étrangères) dans:
		ROME V2:
			- codesromesecteursdsps66 -> Non (code + name)
			- codesromemetiersdsps66 -> Non (code + name)
		ROME V3:
			- codesfamillesromev3 -> Non
			- codesdomainesprosromev3 -> Non
			- codesmetiersromev3 -> Non
			- codesappellationsromev3 -> Non

		=> A-t'on des soucis actuellement ?
			SELECT code, COUNT(*) FROM codesromesecteursdsps66 GROUP BY code HAVING COUNT(*) > 1; -> OK
			SELECT coderomesecteurdsp66_id, code, COUNT(*) FROM codesromemetiersdsps66 GROUP BY coderomesecteurdsp66_id, code HAVING COUNT(*) > 1; -> KO 4/12121

		=> TODO: mettre ces indexes uniques sur les nouvelles et corriger le souci! (+ revoir le nom des tables + inflections)
			- codesfamillesromev3		-> famillesromesv3 / familleromev3
			- codesdomainesprosromev3	-> domainesromesv3 / domaineromev3
			- codesmetiersromev3		-> metiersromesv3 / metierromev3
			- codesappellationsromev3	-> appellationsromesv3 / appellationromev3
										-> correspondancesromesv2v3 / correspondanceromev2v3
*/

-- TODO: nettoyage de l'ancien code
-- grep -lri "\(coderomev3\|codesromev3\|codedomaineproromev3\|codesdomainesprosromev3\|codefamilleromev3\|codesfamillesromev3\|codemetierromev3\|codesmetiersromev3\|codeappellationromev3\|codesappellationsromev3\)" app | grep -v "\.svn"
-- Suppression des anciennes tables ROME V3
/*DROP TABLE IF EXISTS codesfamillesromev3;
DROP TABLE IF EXISTS codesdomainesprosromev3;
DROP TABLE IF EXISTS codesmetiersromev3;
DROP TABLE IF EXISTS codesappellationsromev3;*/

/*
-- FIXME: gentiment, ça casse tous
TRUNCATE famillesromesv3 CASCADE;
TRUNCATE domainesromesv3 CASCADE;
TRUNCATE metiersromesv3 CASCADE;
TRUNCATE appellationsromesv3 CASCADE;
*/

--------------------------------------------------------------------------------
-- Codes familles ROME V3
--------------------------------------------------------------------------------
DROP TABLE IF EXISTS famillesromesv3 CASCADE;
CREATE TABLE famillesromesv3 (
    id          SERIAL NOT NULL PRIMARY KEY,
    code        VARCHAR(1) NOT NULL,
    name        VARCHAR(255) NOT NULL,
    created     TIMESTAMP WITHOUT TIME ZONE,
    modified    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE famillesromesv3 IS 'Codes ROME V3 - Codes familles';

CREATE UNIQUE INDEX famillesromesv3_code_idx ON famillesromesv3 (code);
CREATE UNIQUE INDEX famillesromesv3_name_idx ON famillesromesv3 (name);

--------------------------------------------------------------------------------
-- Codes domaines ROME V3
--------------------------------------------------------------------------------
DROP TABLE IF EXISTS domainesromesv3 CASCADE;
CREATE TABLE domainesromesv3 (
    id					SERIAL NOT NULL PRIMARY KEY,
	familleromev3_id	INTEGER NOT NULL REFERENCES famillesromesv3(id) ON DELETE CASCADE ON UPDATE CASCADE,
    code				VARCHAR(2) NOT NULL,
    name				VARCHAR(255) NOT NULL,
    created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE domainesromesv3 IS 'Codes ROME V3 - Codes domaines';

CREATE INDEX domainesromesv3_familleromev3_id_idx ON domainesromesv3 (familleromev3_id);
CREATE UNIQUE INDEX domainesromesv3_familleromev3_id_code_idx ON domainesromesv3 (familleromev3_id, code);
CREATE UNIQUE INDEX domainesromesv3_familleromev3_id_name_idx ON domainesromesv3 (familleromev3_id, name);

--------------------------------------------------------------------------------
-- Codes métiers ROME V3
--------------------------------------------------------------------------------
DROP TABLE IF EXISTS metiersromesv3 CASCADE;
CREATE TABLE metiersromesv3 (
    id					SERIAL NOT NULL PRIMARY KEY,
	domaineromev3_id	INTEGER NOT NULL REFERENCES domainesromesv3(id) ON DELETE CASCADE ON UPDATE CASCADE,
    code				VARCHAR(2) NOT NULL,
    name				VARCHAR(255) NOT NULL,
    created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE metiersromesv3 IS 'Codes ROME V3 - Codes métiers';

CREATE INDEX metiersromesv3_domaineromev3_id_idx ON metiersromesv3 (domaineromev3_id);
CREATE UNIQUE INDEX metiersromesv3_domaineromev3_id_code_idx ON metiersromesv3 (domaineromev3_id, code);
CREATE UNIQUE INDEX metiersromesv3_domaineromev3_id_name_idx ON metiersromesv3 (domaineromev3_id, name);

--------------------------------------------------------------------------------
-- Appellations ROME V3
--------------------------------------------------------------------------------
DROP TABLE IF EXISTS appellationsromesv3 CASCADE;
CREATE TABLE appellationsromesv3 (
    id					SERIAL NOT NULL PRIMARY KEY,
	metierromev3_id		INTEGER NOT NULL REFERENCES metiersromesv3(id) ON DELETE CASCADE ON UPDATE CASCADE,
    name				VARCHAR(255) NOT NULL,
    created				TIMESTAMP WITHOUT TIME ZONE,
    modified			TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE appellationsromesv3 IS 'Codes ROME V3 - Appellations';

CREATE INDEX appellationsromesv3_metierromev3_id_idx ON appellationsromesv3 (metierromev3_id);
CREATE UNIQUE INDEX appellationsromesv3_metierromev3_id_name_idx ON appellationsromesv3 (metierromev3_id, name);

--------------------------------------------------------------------------------
-- Correspondances ROME V2 <-> ROME V3
--------------------------------------------------------------------------------
DROP TABLE IF EXISTS correspondancesromesv2v3 CASCADE;
CREATE TABLE correspondancesromesv2v3 (
    id						SERIAL NOT NULL PRIMARY KEY,
	coderomemetierdsp66_id	INTEGER NOT NULL REFERENCES codesromemetiersdsps66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	appellationromev2		VARCHAR(255) NOT NULL,
	metierromev3_id			INTEGER NOT NULL REFERENCES metiersromesv3(id) ON DELETE CASCADE ON UPDATE CASCADE,
	appellationromev3_id	INTEGER NOT NULL REFERENCES appellationsromesv3(id) ON DELETE CASCADE ON UPDATE CASCADE
);
COMMENT ON TABLE correspondancesromesv2v3 IS 'Codes ROME V3 - Correspondances entre les codes ROME V2 et V3';

-- TODO: mettre des indexes
-- codesromesecteursdsps66 (3 chiffres, ex. 111), codesromemetiersdsps66.coderomesecteurdsp66_id (5 chiffres, ex. 11111)

--------------------------------------------------------------------------------
-- Inclusion des codes ROME V3 dans les DSP
--------------------------------------------------------------------------------

-- 1. Dernière activité
-- libsecactderact66_secteur_id
-- libderact66_metier_id
-- 1.1 Famille dernière activité
SELECT add_missing_table_field ( 'public', 'dsps', 'deractfamilleromev3_id', 'INTEGER' );
ALTER TABLE dsps ALTER COLUMN deractfamilleromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps', 'dsps_deractfamilleromev3_id_fkey', 'famillesromesv3', 'deractfamilleromev3_id', false );
DROP INDEX IF EXISTS dsps_deractfamilleromev3_id_idx;
CREATE INDEX dsps_deractfamilleromev3_id_idx ON dsps(deractfamilleromev3_id);
-- 1.2 Domaine dernière activité
SELECT add_missing_table_field ( 'public', 'dsps', 'deractdomaineromev3_id', 'INTEGER' );
ALTER TABLE dsps ALTER COLUMN deractdomaineromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps', 'dsps_deractdomaineromev3_id_fkey', 'domainesromesv3', 'deractdomaineromev3_id', false );
DROP INDEX IF EXISTS dsps_deractdomaineromev3_id_idx;
CREATE INDEX dsps_deractdomaineromev3_id_idx ON dsps(deractdomaineromev3_id);
-- 1.3 Métier dernière activité
SELECT add_missing_table_field ( 'public', 'dsps', 'deractmetierromev3_id', 'INTEGER' );
ALTER TABLE dsps ALTER COLUMN deractmetierromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps', 'dsps_deractmetierromev3_id_fkey', 'metiersromesv3', 'deractmetierromev3_id', false );
DROP INDEX IF EXISTS dsps_deractmetierromev3_id_idx;
CREATE INDEX dsps_deractmetierromev3_id_idx ON dsps(deractmetierromev3_id);
-- 1.4 Appellation dernière activité
SELECT add_missing_table_field ( 'public', 'dsps', 'deractappellationromev3_id', 'INTEGER' );
ALTER TABLE dsps ALTER COLUMN deractappellationromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps', 'dsps_deractappellationromev3_id_fkey', 'appellationsromesv3', 'deractappellationromev3_id', false );
DROP INDEX IF EXISTS dsps_deractappellationromev3_id_idx;
CREATE INDEX dsps_deractappellationromev3_id_idx ON dsps(deractappellationromev3_id);

-- Dernière activité dominante
-- libsecactdomi66_secteur_id
-- libactdomi66_metier_id
-- 1.1 Famille dernière activité dominante
SELECT add_missing_table_field ( 'public', 'dsps', 'deractdomifamilleromev3_id', 'INTEGER' );
ALTER TABLE dsps ALTER COLUMN deractdomifamilleromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps', 'dsps_deractdomifamilleromev3_id_fkey', 'famillesromesv3', 'deractdomifamilleromev3_id', false );
DROP INDEX IF EXISTS dsps_deractdomifamilleromev3_id_idx;
CREATE INDEX dsps_deractdomifamilleromev3_id_idx ON dsps(deractdomifamilleromev3_id);
-- 1.2 Domaine dernière activité dominante
SELECT add_missing_table_field ( 'public', 'dsps', 'deractdomidomaineromev3_id', 'INTEGER' );
ALTER TABLE dsps ALTER COLUMN deractdomidomaineromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps', 'dsps_deractdomidomaineromev3_id_fkey', 'domainesromesv3', 'deractdomidomaineromev3_id', false );
DROP INDEX IF EXISTS dsps_deractdomidomaineromev3_id_idx;
CREATE INDEX dsps_deractdomidomaineromev3_id_idx ON dsps(deractdomidomaineromev3_id);
-- 1.3 Métier dernière activité dominante
SELECT add_missing_table_field ( 'public', 'dsps', 'deractdomimetierromev3_id', 'INTEGER' );
ALTER TABLE dsps ALTER COLUMN deractdomimetierromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps', 'dsps_deractdomimetierromev3_id_fkey', 'metiersromesv3', 'deractdomimetierromev3_id', false );
DROP INDEX IF EXISTS dsps_deractdomimetierromev3_id_idx;
CREATE INDEX dsps_deractdomimetierromev3_id_idx ON dsps(deractdomimetierromev3_id);
-- 1.4 Appellation dernière activité dominante
SELECT add_missing_table_field ( 'public', 'dsps', 'deractdomiappellationromev3_id', 'INTEGER' );
ALTER TABLE dsps ALTER COLUMN deractdomiappellationromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps', 'dsps_deractdomiappellationromev3_id_fkey', 'appellationsromesv3', 'deractdomiappellationromev3_id', false );
DROP INDEX IF EXISTS dsps_deractdomiappellationromev3_id_idx;
CREATE INDEX dsps_deractdomiappellationromev3_id_idx ON dsps(deractdomiappellationromev3_id);

-- Emploi recherché
-- libsecactrech66_secteur_id
-- libemploirech66_metier_id
-- 1.1 Famille emploi recherché
SELECT add_missing_table_field ( 'public', 'dsps', 'actrechfamilleromev3_id', 'INTEGER' );
ALTER TABLE dsps ALTER COLUMN actrechfamilleromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps', 'dsps_actrechfamilleromev3_id_fkey', 'famillesromesv3', 'actrechfamilleromev3_id', false );
DROP INDEX IF EXISTS dsps_actrechfamilleromev3_id_idx;
CREATE INDEX dsps_actrechfamilleromev3_id_idx ON dsps(actrechfamilleromev3_id);
-- 1.2 Domaine emploi recherché
SELECT add_missing_table_field ( 'public', 'dsps', 'actrechdomaineromev3_id', 'INTEGER' );
ALTER TABLE dsps ALTER COLUMN actrechdomaineromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps', 'dsps_actrechdomaineromev3_id_fkey', 'domainesromesv3', 'actrechdomaineromev3_id', false );
DROP INDEX IF EXISTS dsps_actrechdomaineromev3_id_idx;
CREATE INDEX dsps_actrechdomaineromev3_id_idx ON dsps(actrechdomaineromev3_id);
-- 1.3 Métier emploi recherché
SELECT add_missing_table_field ( 'public', 'dsps', 'actrechmetierromev3_id', 'INTEGER' );
ALTER TABLE dsps ALTER COLUMN actrechmetierromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps', 'dsps_actrechmetierromev3_id_fkey', 'metiersromesv3', 'actrechmetierromev3_id', false );
DROP INDEX IF EXISTS dsps_actrechmetierromev3_id_idx;
CREATE INDEX dsps_actrechmetierromev3_id_idx ON dsps(actrechmetierromev3_id);
-- 1.4 Appellation emploi recherché
SELECT add_missing_table_field ( 'public', 'dsps', 'actrechappellationromev3_id', 'INTEGER' );
ALTER TABLE dsps ALTER COLUMN actrechappellationromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps', 'dsps_actrechappellationromev3_id_fkey', 'appellationsromesv3', 'actrechappellationromev3_id', false );
DROP INDEX IF EXISTS dsps_actrechappellationromev3_id_idx;
CREATE INDEX dsps_actrechappellationromev3_id_idx ON dsps(actrechappellationromev3_id);

--------------------------------------------------------------------------------
-- Inclusion des codes ROME V3 dans les DSP CG
--------------------------------------------------------------------------------

-- 1. Dernière activité
-- 1.1 Famille dernière activité
SELECT add_missing_table_field ( 'public', 'dsps_revs', 'deractfamilleromev3_id', 'INTEGER' );
ALTER TABLE dsps_revs ALTER COLUMN deractfamilleromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps_revs', 'dsps_revs_deractfamilleromev3_id_fkey', 'famillesromesv3', 'deractfamilleromev3_id', false );
DROP INDEX IF EXISTS dsps_revs_deractfamilleromev3_id_idx;
CREATE INDEX dsps_revs_deractfamilleromev3_id_idx ON dsps_revs(deractfamilleromev3_id);
-- 1.2 Domaine dernière activité
SELECT add_missing_table_field ( 'public', 'dsps_revs', 'deractdomaineromev3_id', 'INTEGER' );
ALTER TABLE dsps_revs ALTER COLUMN deractdomaineromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps_revs', 'dsps_revs_deractdomaineromev3_id_fkey', 'domainesromesv3', 'deractdomaineromev3_id', false );
DROP INDEX IF EXISTS dsps_revs_deractdomaineromev3_id_idx;
CREATE INDEX dsps_revs_deractdomaineromev3_id_idx ON dsps_revs(deractdomaineromev3_id);
-- 1.3 Métier dernière activité
SELECT add_missing_table_field ( 'public', 'dsps_revs', 'deractmetierromev3_id', 'INTEGER' );
ALTER TABLE dsps_revs ALTER COLUMN deractmetierromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps_revs', 'dsps_revs_deractmetierromev3_id_fkey', 'metiersromesv3', 'deractmetierromev3_id', false );
DROP INDEX IF EXISTS dsps_revs_deractmetierromev3_id_idx;
CREATE INDEX dsps_revs_deractmetierromev3_id_idx ON dsps_revs(deractmetierromev3_id);
-- 1.4 Appellation dernière activité
SELECT add_missing_table_field ( 'public', 'dsps_revs', 'deractappellationromev3_id', 'INTEGER' );
ALTER TABLE dsps_revs ALTER COLUMN deractappellationromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps_revs', 'dsps_revs_deractappellationromev3_id_fkey', 'appellationsromesv3', 'deractappellationromev3_id', false );
DROP INDEX IF EXISTS dsps_revs_deractappellationromev3_id_idx;
CREATE INDEX dsps_revs_deractappellationromev3_id_idx ON dsps_revs(deractappellationromev3_id);

-- Dernière activité dominante
-- 1.1 Famille dernière activité dominante
SELECT add_missing_table_field ( 'public', 'dsps_revs', 'deractdomifamilleromev3_id', 'INTEGER' );
ALTER TABLE dsps_revs ALTER COLUMN deractdomifamilleromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps_revs', 'dsps_revs_deractdomifamilleromev3_id_fkey', 'famillesromesv3', 'deractdomifamilleromev3_id', false );
DROP INDEX IF EXISTS dsps_revs_deractdomifamilleromev3_id_idx;
CREATE INDEX dsps_revs_deractdomifamilleromev3_id_idx ON dsps_revs(deractdomifamilleromev3_id);
-- 1.2 Domaine dernière activité dominante
SELECT add_missing_table_field ( 'public', 'dsps_revs', 'deractdomidomaineromev3_id', 'INTEGER' );
ALTER TABLE dsps_revs ALTER COLUMN deractdomidomaineromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps_revs', 'dsps_revs_deractdomidomaineromev3_id_fkey', 'domainesromesv3', 'deractdomidomaineromev3_id', false );
DROP INDEX IF EXISTS dsps_revs_deractdomidomaineromev3_id_idx;
CREATE INDEX dsps_revs_deractdomidomaineromev3_id_idx ON dsps_revs(deractdomidomaineromev3_id);
-- 1.3 Métier dernière activité dominante
SELECT add_missing_table_field ( 'public', 'dsps_revs', 'deractdomimetierromev3_id', 'INTEGER' );
ALTER TABLE dsps_revs ALTER COLUMN deractdomimetierromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps_revs', 'dsps_revs_deractdomimetierromev3_id_fkey', 'metiersromesv3', 'deractdomimetierromev3_id', false );
DROP INDEX IF EXISTS dsps_revs_deractdomimetierromev3_id_idx;
CREATE INDEX dsps_revs_deractdomimetierromev3_id_idx ON dsps_revs(deractdomimetierromev3_id);
-- 1.4 Appellation dernière activité dominante
SELECT add_missing_table_field ( 'public', 'dsps_revs', 'deractdomiappellationromev3_id', 'INTEGER' );
ALTER TABLE dsps_revs ALTER COLUMN deractdomiappellationromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps_revs', 'dsps_revs_deractdomiappellationromev3_id_fkey', 'appellationsromesv3', 'deractdomiappellationromev3_id', false );
DROP INDEX IF EXISTS dsps_revs_deractdomiappellationromev3_id_idx;
CREATE INDEX dsps_revs_deractdomiappellationromev3_id_idx ON dsps_revs(deractdomiappellationromev3_id);

-- Emploi recherché
-- 1.1 Famille emploi recherché
SELECT add_missing_table_field ( 'public', 'dsps_revs', 'actrechfamilleromev3_id', 'INTEGER' );
ALTER TABLE dsps_revs ALTER COLUMN actrechfamilleromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps_revs', 'dsps_revs_actrechfamilleromev3_id_fkey', 'famillesromesv3', 'actrechfamilleromev3_id', false );
DROP INDEX IF EXISTS dsps_revs_actrechfamilleromev3_id_idx;
CREATE INDEX dsps_revs_actrechfamilleromev3_id_idx ON dsps_revs(actrechfamilleromev3_id);
-- 1.2 Domaine emploi recherché
SELECT add_missing_table_field ( 'public', 'dsps_revs', 'actrechdomaineromev3_id', 'INTEGER' );
ALTER TABLE dsps_revs ALTER COLUMN actrechdomaineromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps_revs', 'dsps_revs_actrechdomaineromev3_id_fkey', 'domainesromesv3', 'actrechdomaineromev3_id', false );
DROP INDEX IF EXISTS dsps_revs_actrechdomaineromev3_id_idx;
CREATE INDEX dsps_revs_actrechdomaineromev3_id_idx ON dsps_revs(actrechdomaineromev3_id);
-- 1.3 Métier emploi recherché
SELECT add_missing_table_field ( 'public', 'dsps_revs', 'actrechmetierromev3_id', 'INTEGER' );
ALTER TABLE dsps_revs ALTER COLUMN actrechmetierromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps_revs', 'dsps_revs_actrechmetierromev3_id_fkey', 'metiersromesv3', 'actrechmetierromev3_id', false );
DROP INDEX IF EXISTS dsps_revs_actrechmetierromev3_id_idx;
CREATE INDEX dsps_revs_actrechmetierromev3_id_idx ON dsps_revs(actrechmetierromev3_id);
-- 1.4 Appellation emploi recherché
SELECT add_missing_table_field ( 'public', 'dsps_revs', 'actrechappellationromev3_id', 'INTEGER' );
ALTER TABLE dsps_revs ALTER COLUMN actrechappellationromev3_id SET DEFAULT NULL;
SELECT add_missing_constraint ( 'public', 'dsps_revs', 'dsps_revs_actrechappellationromev3_id_fkey', 'appellationsromesv3', 'actrechappellationromev3_id', false );
DROP INDEX IF EXISTS dsps_revs_actrechappellationromev3_id_idx;
CREATE INDEX dsps_revs_actrechappellationromev3_id_idx ON dsps_revs(actrechappellationromev3_id);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
