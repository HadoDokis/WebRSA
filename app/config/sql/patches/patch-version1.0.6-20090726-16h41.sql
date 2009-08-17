--------------- Ajout du 29/07/2009 à 16h41 ------------------
ALTER TABLE structuresreferentes_zonesgeographiques DROP CONSTRAINT structuresreferentes_zonesgeographiques_pkey;
ALTER TABLE structuresreferentes_zonesgeographiques ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;
ALTER TABLE structuresreferentes ADD COLUMN filtre_zone_geo BOOLEAN DEFAULT true;

--------------- Ajout du 29/07/2009 à 16h41 ------------------
ALTER TABLE contratsinsertion ADD COLUMN forme_ci CHAR(1);

--------------- Ajout du 11/08/2009 à 16h03 ------------------
ALTER TABLE dspps_nivetus ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

--------------- Ajout des id sur les tables liées : 14/08/2009 à 14h33 ------------------
ALTER TABLE ressources_ressourcesmensuelles ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;
ALTER TABLE ressourcesmensuelles_detailsressourcesmensuelles ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

ALTER TABLE orientsstructs_servicesinstructeurs DROP CONSTRAINT orientsstructs_servicesinstructeurs_pkey;
ALTER TABLE orientsstructs_servicesinstructeurs ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

ALTER TABLE zonesgeographiques_regroupementszonesgeo DROP CONSTRAINT zonesgeographiques_regroupementszonesgeo_pkey;
ALTER TABLE zonesgeographiques_regroupementszonesgeo ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

ALTER TABLE users_contratsinsertion DROP CONSTRAINT users_contratsinsertion_pkey;
ALTER TABLE users_contratsinsertion ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

ALTER TABLE foyers_evenements ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;
ALTER TABLE foyers_creances ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

ALTER TABLE dspps_natmobs ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;
ALTER TABLE dspps_nataccosocindis ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;
ALTER TABLE dspps_difsocs ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;
ALTER TABLE dspps_difdisps ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;
ALTER TABLE dspps_accoemplois ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

ALTER TABLE dspfs_nataccosocfams ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;
ALTER TABLE dspfs_diflogs ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

ALTER TABLE creancesalimentaires_personnes ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

--------------- Ajout du 14/08/2009 à 15h30 ------------------
ALTER TABLE ressourcesmensuelles DROP CONSTRAINT ressourcesmensuelles_ressource_id_fkey;
ALTER TABLE ressourcesmensuelles ADD CONSTRAINT ressourcesmensuelles_ressource_id_fkey FOREIGN KEY (ressource_id) REFERENCES ressources (id) ON DELETE CASCADE;

ALTER TABLE detailsressourcesmensuelles DROP CONSTRAINT detailsressourcesmensuelles_ressourcemensuelle_id_fkey;
ALTER TABLE detailsressourcesmensuelles ADD CONSTRAINT detailsressourcesmensuelles_ressourcemensuelle_id_fkey FOREIGN KEY (ressourcemensuelle_id) REFERENCES ressourcesmensuelles (id) ON DELETE CASCADE;


