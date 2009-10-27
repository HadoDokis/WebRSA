SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- --------------------------------------------------------------------------------------------------------
--------------- Ajout du 19/10/2009 à 11h40 ------------------
--------------------------------------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------------------------
-- Ajout de la table "transmissionsflux" liée à 'identificationsflux'
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE transmissionsflux(
    id                          SERIAL NOT NULL PRIMARY KEY,
    identificationflux_id       INTEGER NOT NULL REFERENCES identificationsflux(id),
    nbtotdemrsatransm           CHAR(8)
);
CREATE INDEX transmissionsflux_identificationflux_idx ON transmissionsflux (identificationflux_id);

-- --------------------------------------------------------------------------------------------------------
-- Ajout de la table "suivisappuisorientation" liée à 'personnes'
-- --------------------------------------------------------------------------------------------------------
CREATE TYPE type_sitperssocpro AS ENUM ( 'AF', 'EF', 'RE' );
CREATE TABLE suivisappuisorientation (
    id                          SERIAL NOT NULL PRIMARY KEY,
    personne_id                 INTEGER NOT NULL REFERENCES personnes(id),
    topoblsocpro                type_booleannumber DEFAULT NULL,
    topsouhsocpro               type_booleannumber DEFAULT NULL,
    sitperssocpro               type_sitperssocpro DEFAULT NULL,
    dtenrsocpro                 DATE,
    dtenrparco                  DATE,
    dtenrorie                   DATE
);
CREATE INDEX suivisappuisorientation_personne_id_idx ON suivisappuisorientation (personne_id);

-- --------------------------------------------------------------------------------------------------------
-- Ajout de la table "orientations" liée à 'personnes'
-- --------------------------------------------------------------------------------------------------------
CREATE  TABLE orientations (
    id                          SERIAL NOT NULL PRIMARY KEY,
    personne_id                 INTEGER NOT NULL REFERENCES personnes(id),
    raisocorgorie               VARCHAR(60),
    numvoie                     VARCHAR(6),
    typevoie                    CHAR(4),
    nomvoie                     VARCHAR(25),
    complideadr                 VARCHAR(38),
    compladr                    VARCHAR(26),
    lieudist                    VARCHAR(32),
    codepos                     CHAR(5),
    locaadr                     VARCHAR(26),
    numtelorgorie               VARCHAR(10),
    dtrvorgorie                 DATE,
    hrrvorgorie                 TIME,
    libadrrvorgorie             TEXT,
    numtelrvorgorie             VARCHAR(10)
);
CREATE INDEX orientations_personne_id_idx ON orientations (personne_id);

-- --------------------------------------------------------------------------------------------------------
-- Ajout de la table "parcours" liée à 'personnes'
-- --------------------------------------------------------------------------------------------------------
CREATE TYPE type_natparcocal AS ENUM ( 'AS', 'PP', 'PS' );
CREATE TYPE type_natparcomod AS ENUM ( 'AS', 'PP', 'PS' );
CREATE TYPE type_motimodparco AS ENUM ( 'CL', 'EA' );

CREATE TABLE parcours (
    id                          SERIAL NOT NULL PRIMARY KEY,
    personne_id                 INTEGER NOT NULL REFERENCES personnes(id),
    natparcocal                 type_natparcocal DEFAULT NULL,
    natparcomod                 type_natparcomod DEFAULT NULL,
    toprefuparco                type_booleannumber DEFAULT NULL,
    motimodparco                type_motimodparco DEFAULT NULL,
    raisocorgdeciorie           VARCHAR(60),
    numvoie                     VARCHAR(6),
    typevoie                    CHAR(4),
    nomvoie                     VARCHAR(25),
    complideadr                 VARCHAR(38),
    compladr                    VARCHAR(26),
    lieudist                    VARCHAR(32),
    codepos                     CHAR(5),
    locaadr                     VARCHAR(26),
    numtelorgdeciorie           VARCHAR(10),
    dtrvorgdeciorie             DATE,
    hrrvorgdeciorie             TIME,
    libadrrvorgdeciorie         TEXT,
    numtelrvorgdeciorie         VARCHAR(10)
);
CREATE INDEX parcours_personne_id_idx ON parcours (personne_id);

--------------- Ajout du 26/10/2009 à 16h57 ------------------
CREATE TABLE permanences(
    id                              SERIAL NOT NULL PRIMARY KEY,
    structurereferente_id           INTEGER NOT NULL REFERENCES structuresreferentes(id),
    libpermanence                   VARCHAR(100),
    numvoie                         VARCHAR(15),
    typevoie                        VARCHAR(6),
    nomvoie                         VARCHAR(50),
    codepos                         CHAR(5),
    ville                           VARCHAR(45),
    canton                          VARCHAR(50),
    numtel                          VARCHAR(15)
);
CREATE INDEX permanences_structurereferente_id_idx ON permanences(structurereferente_id);