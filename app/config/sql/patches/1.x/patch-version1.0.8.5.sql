SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
--------------- Ajout du 05/10/2009 à 17h44 ------------------
ALTER TABLE contratsinsertion ADD COLUMN raison_ci CHAR(1);
ALTER TABLE contratsinsertion ADD COLUMN aviseqpluri CHAR(1);

--------------- Ajout du 06/10/2009 à 15h40 ------------------
ALTER TABLE referents ADD COLUMN fonction VARCHAR(30);

--------------- Ajout du 07/10/2009 à 11h40 ------------------
ALTER TABLE contratsinsertion ADD COLUMN sitfam_ci TEXT;
ALTER TABLE contratsinsertion ADD COLUMN sitpro_ci TEXT;
ALTER TABLE contratsinsertion ADD COLUMN observ_benef TEXT;

--------------- Ajout du 07/10/2009 à 11h40 ------------------


ALTER TABLE contratsinsertion ADD COLUMN referent_id INT REFERENCES referents(id);
CREATE INDEX contratsinsertion_referent_id_idx ON contratsinsertion (referent_id);

--------------- Ajout du 12/10/2009 à 08h49 ------------------
ALTER TABLE rendezvous ADD COLUMN heurerdv TIME;

ALTER TABLE rendezvous ADD COLUMN referent_id INT REFERENCES referents(id);
CREATE INDEX rendezvous_referent_id_idx ON rendezvous (referent_id);

ALTER TABLE actionsinsertion ADD COLUMN commentaire_action TEXT;


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

ALTER TABLE rendezvous ADD COLUMN permanence_id INTEGER REFERENCES permanences(id);

--------------- Ajout du 20/10/2009 à 10h17 ------------------
CREATE TABLE typespdos (
    id            SERIAL NOT NULL PRIMARY KEY,
    libelle       VARCHAR(30)
);

CREATE TABLE decisionspdos (
    id            SERIAL NOT NULL PRIMARY KEY,
    libelle       VARCHAR(30)
);

CREATE TABLE typesnotifspdos (
    id              SERIAL NOT NULL PRIMARY KEY,
    libelle         VARCHAR(30),
    modelenotifpdo  VARCHAR(50)
);


ALTER TABLE propospdos DROP COLUMN decisionpdo;
ALTER TABLE propospdos DROP COLUMN typepdo;

ALTER TABLE propospdos ADD COLUMN typepdo_id INTEGER NOT NULL REFERENCES typespdos(id);
ALTER TABLE propospdos ADD COLUMN decisionpdo_id INTEGER REFERENCES decisionspdos(id);
ALTER TABLE propospdos ADD COLUMN typenotifpdo_id INTEGER REFERENCES typesnotifspdos(id);


CREATE TABLE piecespdos (
    id              SERIAL NOT NULL PRIMARY KEY,
    propopdo_id     INTEGER REFERENCES propospdos(id),
    libelle         VARCHAR(50),
    dateajout       DATE
);
CREATE INDEX piecespdos_propopdo_id_idx ON piecespdos (propopdo_id);

--------------- Ajout du 22/10/2009 à 10h17 ------------------
CREATE TABLE propospdos_typesnotifspdos (
    id                  SERIAL NOT NULL PRIMARY KEY,
    propopdo_id         INTEGER NOT NULL REFERENCES propospdos(id),
    typenotifpdo_id     INTEGER NOT NULL REFERENCES typesnotifspdos(id),
    datenotifpdo        DATE
);
