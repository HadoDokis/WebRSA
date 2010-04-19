
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

BEGIN;

-- -----------------------------------------------------------------------------
DROP TABLE fraisdeplacements66;
CREATE TABLE fraisdeplacements66 (
    id                          SERIAL NOT NULL PRIMARY KEY,
    apre_id                     INTEGER NOT NULL REFERENCES apres(id),
    lieuresidence               VARCHAR(100),
    destination                 VARCHAR(100),
    --partie véhicule personnel
    nbkmvoiture                 DECIMAL(10,2),
    nbtrajetvoiture             DECIMAL(10,2),
    nbtotalkm                   DECIMAL(10,2),
    totalvehicule               DECIMAL(10,2),
    --partie transport public
    nbtrajettranspub            DECIMAL(10,2),
    prixbillettranspub          DECIMAL(10,2),
    totaltranspub               DECIMAL(10,2),
    --partie hébergement
    nbnuithebergt               DECIMAL(10,2),
    totalhebergt                DECIMAL(10,2),.
    -- partie repas
    nbrepas                     DECIMAL(10,2),
    totalrepas                  DECIMAL(10,2)
);

CREATE INDEX fraisdeplacements66_apre_id_idx ON fraisdeplacements66 (apre_id);
COMMENT ON TABLE fraisdeplacements66 IS 'Table pour les frais de déplacements liés à l''APRE CG66';
-- -----------------------------------------------------------------------------

COMMIT;