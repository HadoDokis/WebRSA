CREATE TABLE structuresreferentes_zonesgeographiques (
    structurereferente_id               INT NOT NULL REFERENCES structuresreferentes (id),
    zonegeographique_id                 INT NOT NULL REFERENCES zonesgeographiques (id),
    PRIMARY KEY( structurereferente_id, zonegeographique_id )
);

-- ALTER TABLE structuresreferentes DROP COLUMN zonegeographique_id;

ALTER TABLE orientsstructs ADD COLUMN typeorient_id INT;