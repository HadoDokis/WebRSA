--------------- Ajout du 23 06 09 - 17h28 ------------------

ALTER TABLE users_zonesgeographiques DROP CONSTRAINT users_zonesgeographiques_pkey;
ALTER TABLE users_zonesgeographiques ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

--------------- Ajout du 25 06 09 - 17h28 ------------------
ALTER TABLE dspps ADD COLUMN diplomes TEXT;

--------------- Ajout du 29 06 09 - 14h28 ------------------
ALTER TABLE actionsinsertion ADD COLUMN lib_action CHAR(1);

--------------- Ajout du 01 07 09 - 08h55 ------------------
-- http://www.postgresql.org/docs/8.1/static/ddl-alter.html
-- grep -r -n "rolepers\|natprest\|rolepers\|topchapers\|toppersdrodevorsa" app/ | grep -v "\/\.svn\/" | grep -v "\/config\/sql\/" | grep -v "\/locale\/" | grep -v "\.bak"

-- 1
DROP TABLE IF EXISTS prestations;
CREATE TABLE prestations AS SELECT id AS personne_id, natprest, rolepers, topchapers, toppersdrodevorsa
    FROM personnes;
ALTER TABLE prestations ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;
UPDATE prestations SET natprest = 'RSA';

ALTER TABLE personnes DROP COLUMN natprest;
ALTER TABLE personnes DROP COLUMN rolepers;
ALTER TABLE personnes DROP COLUMN topchapers;
ALTER TABLE personnes DROP COLUMN toppersdrodevorsa;

-- 2
ALTER TABLE orientsstructs ADD COLUMN date_impression DATE;