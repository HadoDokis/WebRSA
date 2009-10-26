SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
--------------- Ajout du 24/07/2009 à 12h30 ------------------
ALTER TABLE prestations ADD CONSTRAINT personneidfk FOREIGN KEY (personne_id) REFERENCES personnes (id) MATCH FULL;
