SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
--------------- Ajout du 20/08/2009 à 15h20 ------------------
ALTER TABLE structuresreferentes ALTER COLUMN num_voie TYPE VARCHAR(15);
ALTER TABLE structuresreferentes ALTER COLUMN nom_voie TYPE VARCHAR(50);

ALTER TABLE servicesinstructeurs ALTER COLUMN num_rue TYPE VARCHAR(15);
