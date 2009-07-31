--------------- Ajout du 24/07/2009 à 12h30 ------------------
ALTER TABLE prestations ADD CONSTRAINT personneidfk FOREIGN KEY (personne_id) REFERENCES personnes (id) MATCH FULL;

--------------- Ajout du 29/07/2009 à 16h41 ------------------
ALTER TABLE structuresreferentes_zonesgeographiques DROP CONSTRAINT structuresreferentes_zonesgeographiques_pkey;
ALTER TABLE structuresreferentes_zonesgeographiques ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;
ALTER TABLE structuresreferentes ADD COLUMN filtre_zone_geo BOOLEAN DEFAULT true;

--------------- Ajout du 29/07/2009 à 16h41 ------------------
ALTER TABLE contratsinsertion ADD COLUMN forme_ci CHAR(1);