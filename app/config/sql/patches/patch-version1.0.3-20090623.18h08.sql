--------------- Ajout du 23 06 09 - 17h28 ------------------

ALTER TABLE users_zonesgeographiques DROP CONSTRAINT users_zonesgeographiques_pkey;
ALTER TABLE users_zonesgeographiques ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

--------------- Ajout du 25 06 09 - 17h28 ------------------
ALTER TABLE dspps ADD COLUMN diplomes TEXT;

--------------- Ajout du 29 06 09 - 14h28 ------------------
ALTER TABLE actionsinsertion ADD COLUMN lib_action CHAR(1);
