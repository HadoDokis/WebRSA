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
-- ***** Modifications pour les équipes pluridisciplinaires (17/05/2010)   *****
-- *****************************************************************************

ALTER TABLE demandesreorient ADD COLUMN dtprementretien DATE NOT NULL;

ALTER TABLE precosreorients ALTER COLUMN referent_id DROP NOT NULL;
ALTER TABLE precosreorients ALTER COLUMN referent_id SET DEFAULT NULL;
ALTER TABLE precosreorients ADD COLUMN dtconcertation DATE DEFAULT NULL;

-- *****************************************************************************

COMMIT;