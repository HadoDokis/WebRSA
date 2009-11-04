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
-- Ajout de la table "referentsapre" liée à 'apres'
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE referentsapre (
    id                          SERIAL NOT NULL PRIMARY KEY,
    qual                        VARCHAR(3),
    nom                         VARCHAR(28),
    prenom                      VARCHAR(32),
    adresse                     TEXT,
    numtel                      VARCHAR(10),
    email                       VARCHAR(78),
    fonction                    VARCHAR(50),
    organismeref                VARCHAR(50)
);
-- CREATE INDEX referentsapre_apre_id_idx ON referentsapre (apre_id);

-- --------------------------------------------------------------------------------------------------------
-- Ajout de la table "apre" liée à 'personnes'
-- --------------------------------------------------------------------------------------------------------
CREATE TYPE type_typedemandeapre AS ENUM ( 'FO', 'AU' );
CREATE TYPE type_naturelogement AS ENUM ( 'P', 'L', 'H', 'S', 'A' );
CREATE TYPE type_activitebeneficiaire AS ENUM ( 'E', 'F', 'C' );
CREATE TYPE type_typecontrat AS ENUM ( 'CDI', 'CDD', 'CON', 'AUT' );

CREATE TABLE apres (
    id                              SERIAL NOT NULL PRIMARY KEY,
    personne_id                     INTEGER NOT NULL REFERENCES personnes(id),
    referentapre_id                 INTEGER NOT NULL REFERENCES referentsapre(id),
    numeroapre                      VARCHAR(16),
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

-- --------------------------------------------------------------------------------------------------------
--  ....
-- --------------------------------------------------------------------------------------------------------

CREATE TABLE piecesapre (
    id                          SERIAL NOT NULL PRIMARY KEY,
    libelle                     VARCHAR(250) NOT NULL
);

INSERT INTO piecesapre ( libelle ) VALUES
    ( 'Formulaire de demande d''APRE normalisé du département dûment complété' ),
    ( 'Justificatif d''entrée en formation ou de création d''entreprise' );


CREATE TABLE apres_piecesapre (
    id                          SERIAL NOT NULL PRIMARY KEY,
    apre_id                     INTEGER NOT NULL REFERENCES apres(id),
    pieceapre_id                INTEGER NOT NULL REFERENCES piecesapre(id)
);
CREATE INDEX apres_piecesapre_apre_id_idx ON apres_piecesapre (apre_id);
CREATE INDEX apres_piecesapre_pieceapre_id_idx ON apres_piecesapre (pieceapre_id);

-- --------------------------------------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------------------------
--  ....Données nécessaire pour la table Formqualif
-- --------------------------------------------------------------------------------------------------------

CREATE TABLE formsqualifs (
    id                          SERIAL NOT NULL PRIMARY KEY,
    apre_id                     INTEGER NOT NULL REFERENCES apres(id),
    intituleform                VARCHAR(100) NOT NULL,
    organismeform               VARCHAR(100) NOT NULL,
    ddform                      DATE,
    dfform                      DATE,
    dureeform                   INT4,
    modevalidation              VARCHAR(30),
    coutform                    DECIMAL(10,2),
    cofinanceurs                VARCHAR(30),
    montantaide                 DECIMAL(10,2)
);
CREATE INDEX formsqualifs_apre_id_idx ON formsqualifs (apre_id);
-- --------------------------------------------------------------------------------------------------------
--  ....Table des pièces liées à formqualif
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE piecesformsqualifs (
    id                          SERIAL NOT NULL PRIMARY KEY,
    libelle                     VARCHAR(250) NOT NULL
);

INSERT INTO piecesformsqualifs ( libelle ) VALUES
    ( 'Attestation d''entrée en formation' ),
    ( 'Facture ou devis' );

-- --------------------------------------------------------------------------------------------------------
--  ....Table liée Formqualif avec ses pièces
-- --------------------------------------------------------------------------------------------------------

CREATE TABLE formsqualifs_piecesformsqualifs (
    id                          SERIAL NOT NULL PRIMARY KEY,
    formqualif_id               INTEGER NOT NULL REFERENCES formsqualifs(id),
    pieceformqualif_id          INTEGER NOT NULL REFERENCES piecesformsqualifs(id)
);
CREATE INDEX formsqualifs_piecesformsqualifs_formqualif_id_idx ON formsqualifs_piecesformsqualifs (formqualif_id);
CREATE INDEX formsqualifs_piecesformsqualifs_pieceformqualif_id_idx ON formsqualifs_piecesformsqualifs (pieceformqualif_id);

-- --------------------------------------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------------------------
--  ....Données nécessaire pour la table Actprof
-- --------------------------------------------------------------------------------------------------------
CREATE TYPE type_typecontratact AS ENUM ( 'CI', 'CA', 'SA' );
CREATE TABLE actsprofs (
    id                          SERIAL NOT NULL PRIMARY KEY,
    apre_id                     INTEGER NOT NULL REFERENCES apres(id),
    nomemployeur                VARCHAR (50),
    adresseemployeur            VARCHAR (100),
    typecontratact              type_typecontratact DEFAULT NULL,
    ddconvention                DATE,
    dfconvention                DATE,
    intituleformation           VARCHAR (200),
    ddform                      DATE,
    dfform                      DATE,
    dureeform                   INT4,
    modevalidation              VARCHAR (30),
    coutform                    DECIMAL (10, 2),
    cofinanceurs                VARCHAR (30),
    montantaide                 DECIMAL (10, 2)
);
CREATE INDEX actsprofs_apre_id_idx ON actsprofs (apre_id);
-- --------------------------------------------------------------------------------------------------------
--  ....Table des pièces liées à formqualif
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE piecesactsprofs (
    id                          SERIAL NOT NULL PRIMARY KEY,
    libelle                     VARCHAR(250) NOT NULL
);

INSERT INTO piecesactsprofs ( libelle ) VALUES
    ( 'Convention individuelle (pour les contrats aidés)' ),
    ( 'Contrat de travail (pour les contrats SIAE)' ),
    ( 'Facture ou devis' );

-- --------------------------------------------------------------------------------------------------------
--  ....Table liée Formqualif avec ses pièces
-- --------------------------------------------------------------------------------------------------------

CREATE TABLE actsprofs_piecesactsprofs (
    id                          SERIAL NOT NULL PRIMARY KEY,
    actprof_id                  INTEGER NOT NULL REFERENCES actsprofs(id),
    pieceactprof_id             INTEGER NOT NULL REFERENCES piecesactsprofs(id)
);
CREATE INDEX actsprofs_piecesactsprofs_actprof_id_idx ON actsprofs_piecesactsprofs (actprof_id);
CREATE INDEX actsprofs_piecesactsprofs_pieceactprof_id_idx ON actsprofs_piecesactsprofs (pieceactprof_id);

-- --------------------------------------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------------------------
--  ....Données nécessaire pour la table Permisb
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE permisb (
    id                          SERIAL NOT NULL PRIMARY KEY,
    apre_id                     INTEGER NOT NULL REFERENCES apres(id),
    nomautoecole                VARCHAR (50),
    adresseautoecole            VARCHAR (100),
    code                        CHAR (1),
    conduite                    CHAR (1),
    dureeform                   INT4,
    coutform                    DECIMAL (10, 2)

);
CREATE INDEX permisb_apre_id_idx ON permisb (apre_id);
-- --------------------------------------------------------------------------------------------------------
--  ....Table des pièces liées à permisb
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE piecespermisb (
    id                          SERIAL NOT NULL PRIMARY KEY,
    libelle                     TEXT NOT NULL
);

INSERT INTO piecespermisb ( libelle ) VALUES
    ( 'Photocopie du permis de conduire' ),
    ( 'Devis nominatif détaillé précisant l''intitulé de la formation, son lieu, dates prévisionnelles de début et fin d''action, durée en heure jours et mois, contenu (heures et modules), l''organisation de la formation, le coût global ainsi que la participation éventuelle du stagiaire.' ),
    ( 'Evaluation des connaissances et compétences professionnelles (ECCP)' ),
    ( 'Facture ou devis' ),
    ( 'Attestation d''insdcription à l''auto-école' ),
    ( 'Obtention du code' ),
    ( 'Devis ou facture' );

-- --------------------------------------------------------------------------------------------------------
--  ....Table liée Permisb avec ses pièces
-- --------------------------------------------------------------------------------------------------------

CREATE TABLE permisb_piecespermisb (
    id                          SERIAL NOT NULL PRIMARY KEY,
    permisb_id                  INTEGER NOT NULL REFERENCES permisb(id),
    piecepermisb_id             INTEGER NOT NULL REFERENCES piecespermisb(id)
);
CREATE INDEX permisb_piecespermisb_permisb_id_idx ON permisb_piecespermisb (permisb_id);
CREATE INDEX permisb_piecespermisb_piecepermisb_id_idx ON permisb_piecespermisb (piecepermisb_id);


-- --------------------------------------------------------------------------------------------------------
-- --------------------------------------------------------------------------------------------------------
--  ....Données nécessaire pour la table Permisb
-- --------------------------------------------------------------------------------------------------------
CREATE TYPE type_typeaidelogement AS ENUM ( 'AEL', 'AML' );
CREATE TABLE amenagslogts (
    id                          SERIAL NOT NULL PRIMARY KEY,
    apre_id                     INTEGER NOT NULL REFERENCES apres(id),
    typeaidelogement            type_typeaidelogement DEFAULT NULL,
    besoins                     VARCHAR (250),
    montantaide                 DECIMAL (10, 2)
);
CREATE INDEX amenagslogts_apre_id_idx ON amenagslogts (apre_id);
-- --------------------------------------------------------------------------------------------------------
--  ....Table des pièces liées à permisb
-- --------------------------------------------------------------------------------------------------------
CREATE TABLE piecesamenagslogts (
    id                          SERIAL NOT NULL PRIMARY KEY,
    libelle                     TEXT NOT NULL
);

INSERT INTO piecesamenagslogts ( libelle ) VALUES
    ( 'Bail ou contrat de location' ),
    ( 'Document faisant état du montant de la dette pour le maintien au logement (compte locataire émis par le bialleur' ),
    ( 'Devis pour les frais d''agence' ),
    ( 'Devis ou facture frais de déménagement' ),
    ( 'Contrat ou devis assurance habitation' ),
    ( 'Facture ouverture compteurs EDF/GDF' ),
    ( 'Facture' );

-- --------------------------------------------------------------------------------------------------------
--  ....Table liée Permisb avec ses pièces
-- --------------------------------------------------------------------------------------------------------

CREATE TABLE amenagslogts_piecesamenagslogts (
    id                          SERIAL NOT NULL PRIMARY KEY,
    amenaglogt_id                  INTEGER NOT NULL REFERENCES amenagslogts(id),
    pieceamenaglogt_id             INTEGER NOT NULL REFERENCES piecesamenagslogts(id)
);
CREATE INDEX amenagslogts_piecesamenagslogts_permisb_id_idx ON amenagslogts_piecesamenagslogts (amenaglogt_id);
CREATE INDEX amenagslogts_piecesamenagslogts_piecepermisb_id_idx ON amenagslogts_piecesamenagslogts (pieceamenaglogt_id);