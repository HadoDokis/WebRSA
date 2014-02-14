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

-- 20140214: Suite à la correction du ticket #4195, il faut supprimer les courriers
-- d'orientation suite à déménagement stockés dans la table pdfs
DELETE FROM pdfs
	WHERE pdfs.modele = 'Orientstruct'
	AND fk_value IN (
		SELECT orientsstructs.id
			FROM orientsstructs
			WHERE orientsstructs.origine = 'demenagement'
	);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
