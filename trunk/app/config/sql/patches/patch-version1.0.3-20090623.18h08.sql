--------------- Ajout du 23 06 09 - 17h28 ------------------

ALTER TABLE users_zonesgeographiques DROP CONSTRAINT users_zonesgeographiques_pkey;
ALTER TABLE users_zonesgeographiques ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

