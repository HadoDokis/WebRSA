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

-- --------------------------------------------------------------------------------------------------------
-- Ajout de la table "apre" liée à 'personnes'
-- --------------------------------------------------------------------------------------------------------
CREATE TYPE type_typedemandeapre AS ENUM ( 'FO', 'AU' );
CREATE TYPE type_naturelogement AS ENUM ( 'P', 'L', 'H', 'S', 'A' );
CREATE TYPE type_activitebeneficiaire AS ENUM ( 'E', 'F', 'C' );
CREATE TYPE type_typecontrat AS ENUM ( 'CDI', 'CDD', 'CON', 'AUT' );
CREATE TYPE type_natureaide AS ENUM ( 'FQU', 'PCA', 'PCB', 'AAI', 'ACE', 'AMP', 'LVI' );

CREATE TABLE apres (
    id                              SERIAL NOT NULL PRIMARY KEY,
    personne_id                     INTEGER NOT NULL REFERENCES personnes(id),
    natureaide                      type_natureaide NOT NULL,
    numeroapre                      NUMERIC(10),
    typedemandeapre                 type_typedemandeapre DEFAULT NULL,
    datedemandeapre                 DATE,
    naturelogement                  type_naturelogement DEFAULT NULL,
    precisionsautrelogement         VARCHAR(20),
    anciennetepoleemploi            VARCHAR(20),
    projetprofessionnel             TEXT,
    secteurprofessionnel            TEXT,
    activitebeneficiaire            type_activitebeneficiaire DEFAULT NULL,
    dateentreeemploi                DATE,
    typecontrat                     type_typecontrat DEFAULT NULL,
    precisionsautrecontrat          VARCHAR(50),
    nbheurestravaillees             NUMERIC(4),
    nomemployeur                    VARCHAR(50),
    adresseemployeur                TEXT,
    quota                           NUMERIC(10,2),
    derogation                      NUMERIC(10,2),
    avistechreferent                TEXT
);
CREATE INDEX apres_personne_id_idx ON apres (personne_id);

-- --------------------------------------------------------------------------------------------------------
-- Ajout de la table "naturesaides" liée à 'apres'
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE naturesaides (
    id                          SERIAL NOT NULL PRIMARY KEY,
    apre_id                     INTEGER NOT NULL REFERENCES apres (id),
    natureaide                  type_natureaide NOT NULL
);
CREATE INDEX naturesaides_apre_id_idx ON naturesaides (apre_id);

-- --------------------------------------------------------------------------------------------------------
-- Ajout de la table "referentsapre" liée à 'apres'
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE referentsapre (
    id                          SERIAL NOT NULL PRIMARY KEY,
    apre_id                     INTEGER NOT NULL REFERENCES apres(id),
    qual                        CHAR(3),
    nom                         VARCHAR(28),
    prenom                      VARCHAR(32),
    adresse                     TEXT,
    numtel                      VARCHAR(10),
    email                       VARCHAR(78),
    fonction                    VARCHAR(50),
    organismeref                VARCHAR(50)
);
CREATE INDEX referentsapre_apre_id_idx ON referentsapre (apre_id);

-- --------------------------------------------------------------------------------------------------------
-- Ajout de la table "avisref" liée à 'referentsapre'
-- --------------------------------------------------------------------------------------------------------
-- CREATE TABLE avisref (
--     id                          SERIAL NOT NULL PRIMARY KEY,
--     referentapre_id             INTEGER NOT NULL REFERENCES referentsapre (id),
--     avistechreferent            TEXT
-- );
-- CREATE INDEX avisref_referentapre_id_idx ON avisref (referentapre_id);

-- --------------------------------------------------------------------------------------------------------
-- Ajout de la table "montantsconsommes" liée à 'apres'
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE montantsconsommes (
    id                          SERIAL NOT NULL PRIMARY KEY,
    apre_id                     INTEGER NOT NULL REFERENCES apres(id),
    montantconso                NUMERIC(10,2),
    dateconso                   DATE,
    justifconso                 VARCHAR(50)
);
CREATE INDEX montantsconsommes_apre_id_idx ON montantsconsommes (apre_id);

