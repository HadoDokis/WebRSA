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

-- TAGS --
DELETE FROM categorietags WHERE name = 'Doublons complexes';
INSERT INTO categorietags (name, created, modified) VALUES ('Doublons complexes', NOW(), NOW());
DELETE FROM valeurstags WHERE name = 'N''est pas un doublon';
INSERT INTO valeurstags (name, categorietag_id, created, modified) VALUES (
	'N''est pas un doublon', 
	(SELECT id FROM categorietags WHERE name = 'Doublons complexes' LIMIT 1), 
	NOW(), 
	NOW()
);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
