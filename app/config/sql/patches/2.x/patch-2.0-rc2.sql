
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
-- DROP TABLE fraisdeplacements66;
CREATE TABLE fraisdeplacements66 (
    id                          SERIAL NOT NULL PRIMARY KEY,
    aideapre66_id               INTEGER NOT NULL REFERENCES aidesapres66(id),
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
    totalhebergt                DECIMAL(10,2),
    -- partie repas
    nbrepas                     DECIMAL(10,2),
    totalrepas                  DECIMAL(10,2)
);

CREATE INDEX fraisdeplacements66_aideapre66_id_idx ON fraisdeplacements66 (aideapre66_id);
COMMENT ON TABLE fraisdeplacements66 IS 'Table pour les frais de déplacements liés à l''APRE CG66';
-- -----------------------------------------------------------------------------

ALTER TABLE aidesapres66 ADD COLUMN motifrejet TEXT;
ALTER TABLE aidesapres66 ADD COLUMN montantpropose DECIMAL(10,2);
ALTER TABLE aidesapres66 ADD COLUMN datemontantpropose DATE;

CREATE TYPE type_decisionapre AS ENUM ( 'ACC', 'REF' );
ALTER TABLE aidesapres66 ADD COLUMN decisionapre type_decisionapre;
ALTER TABLE aidesapres66 ADD COLUMN montantaccorde DECIMAL(10,2);
ALTER TABLE aidesapres66 ADD COLUMN datemontantaccorde DATE;

ALTER TABLE aidesapres66 ADD COLUMN creancier VARCHAR (250);

DROP TABLE aidesapres66_piecesaides66;
COMMIT;