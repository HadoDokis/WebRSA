SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

BEGIN;

-- *****************************************************************************
CREATE TYPE type_munir AS ENUM ( 'CER', 'NCA', 'CV', 'AUT' );
ALTER TABLE actionscandidats_personnes ADD COLUMN pieceallocataire type_munir;

ALTER TABLE actionscandidats_personnes ADD COLUMN autrepiece VARCHAR(50);
ALTER TABLE actionscandidats_personnes ADD COLUMN precisionmotif TEXT;

ALTER TABLE actionscandidats_personnes ADD COLUMN presencecontrat type_no;
ALTER TABLE actionscandidats_personnes ADD COLUMN integrationaction type_no;
-- *****************************************************************************
COMMIT;