
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
----------------------------------------------------------------------------------------
-- 20130927 : Création d'une table de paramétrage pour les programmes en lien 
--          avec les fiches de candidature région
----------------------------------------------------------------------------------------

DROP TABLE IF EXISTS progsfichescandidatures66 CASCADE;
CREATE TABLE progsfichescandidatures66(
    id                          SERIAL NOT NULL PRIMARY KEY,
    name                        VARCHAR(20) NOT NULL,
    isactif                     VARCHAR(1) NOT NULL DEFAULT '1',
	----------------------------------------------------------------------------
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE progsfichescandidatures66 IS 'Table des différents programmes proposés par les fiches de candidature Région (CG66)';

DROP INDEX IF EXISTS progsfichescandidatures66_name_idx;
CREATE INDEX progsfichescandidatures66_name_idx ON progsfichescandidatures66( name );

DROP INDEX IF EXISTS progsfichescandidatures66_isactif_idx;
CREATE INDEX progsfichescandidatures66_isactif_idx ON progsfichescandidatures66( isactif );

SELECT alter_table_drop_constraint_if_exists( 'public', 'progsfichescandidatures66', 'progsfichescandidatures66_isactif_in_list_chk' );
ALTER TABLE progsfichescandidatures66 ADD CONSTRAINT progsfichescandidatures66_isactif_in_list_chk CHECK ( cakephp_validate_in_list( isactif, ARRAY['0', '1'] ) );

SELECT add_missing_table_field ( 'public', 'actionscandidats_personnes', 'formationregion', 'VARCHAR(250)' );

----------------------------------------------------------------------------------------
-- 20130927 : Création d'une table de liaison entre 
--            les fiches de candidature et les programmes région
----------------------------------------------------------------------------------------
DROP TABLE IF EXISTS candidatures_progs66 CASCADE;
CREATE TABLE candidatures_progs66(
    id                              SERIAL NOT NULL PRIMARY KEY,
    actioncandidat_personne_id      INTEGER NOT NULL REFERENCES actionscandidats_personnes(id) ON DELETE CASCADE ON UPDATE CASCADE,
    progfichecandidature66_id       INTEGER NOT NULL REFERENCES progsfichescandidatures66(id) ON DELETE CASCADE ON UPDATE CASCADE,
	----------------------------------------------------------------------------
    created                     TIMESTAMP WITHOUT TIME ZONE,
    modified                    TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE candidatures_progs66 IS 'Table de liaison entre les fiches de candidature région et les programmes liés (CG66)';


-- *****************************************************************************
COMMIT;
-- *****************************************************************************
