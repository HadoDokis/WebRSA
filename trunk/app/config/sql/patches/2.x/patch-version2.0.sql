
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

--------------- Ajout du 22/10/2009 à 10h17 ------------------
CREATE TABLE propospdos_typesnotifspdos (
    id                  SERIAL NOT NULL PRIMARY KEY,
    propopdo_id         INTEGER NOT NULL REFERENCES propospdos(id),
    typenotifpdo_id     INTEGER NOT NULL REFERENCES typesnotifspdos(id),
    datenotifpdo        DATE
);
