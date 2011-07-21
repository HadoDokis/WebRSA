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
ALTER TABLE apres ALTER COLUMN precisionsautrelogement TYPE VARCHAR(150);

SELECT add_missing_table_field ('public', 'apres', 'cessderact', 'VARCHAR(4)');
SELECT add_missing_table_field ('public', 'apres', 'nivetu', 'VARCHAR(4)');

SELECT add_missing_table_field ('public', 'relancesapres', 'listepiecemanquante', 'TEXT' );


SELECT add_missing_table_field ('public', 'commissionseps', 'chargesuivi', 'VARCHAR(100)' );
SELECT add_missing_table_field ('public', 'commissionseps', 'gestionnairebat', 'VARCHAR(100)' );
SELECT add_missing_table_field ('public', 'commissionseps', 'gestionnairebada', 'VARCHAR(100)' );

SELECT add_missing_table_field ('public', 'eps', 'adressemail', 'VARCHAR(100)' );

ALTER TABLE actionscandidats ALTER COLUMN contractualisation DROP NOT NULL;
ALTER TABLE actionscandidats ALTER COLUMN contractualisation SET DEFAULT NULL;

ALTER TABLE actionscandidats ALTER COLUMN lieuaction DROP NOT NULL;
ALTER TABLE actionscandidats ALTER COLUMN lieuaction SET DEFAULT NULL;

ALTER TABLE actionscandidats ALTER COLUMN cantonaction DROP NOT NULL;
ALTER TABLE actionscandidats ALTER COLUMN cantonaction SET DEFAULT NULL;

ALTER TABLE actionscandidats ALTER COLUMN ddaction DROP NOT NULL;
ALTER TABLE actionscandidats ALTER COLUMN ddaction SET DEFAULT NULL;

ALTER TABLE actionscandidats ALTER COLUMN dfaction DROP NOT NULL;
ALTER TABLE actionscandidats ALTER COLUMN dfaction SET DEFAULT NULL;

ALTER TABLE actionscandidats ALTER COLUMN contactpartenaire_id DROP NOT NULL;
ALTER TABLE actionscandidats ALTER COLUMN contactpartenaire_id SET DEFAULT NULL;

ALTER TABLE actionscandidats ALTER COLUMN chargeinsertion_id DROP NOT NULL;
ALTER TABLE actionscandidats ALTER COLUMN chargeinsertion_id SET DEFAULT NULL;

ALTER TABLE actionscandidats ALTER COLUMN secretaire_id DROP NOT NULL;
ALTER TABLE actionscandidats ALTER COLUMN secretaire_id SET DEFAULT NULL;

SELECT add_missing_table_field ('public', 'apres', 'isbeneficiaire', 'TYPE_BOOLEANNUMBER');
SELECT add_missing_table_field ('public', 'apres', 'hascer', 'TYPE_BOOLEANNUMBER');
SELECT add_missing_table_field ('public', 'apres', 'respectdelais', 'TYPE_BOOLEANNUMBER');
-- *****************************************************************************
COMMIT;
-- *****************************************************************************