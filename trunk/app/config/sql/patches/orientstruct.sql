ALTER TABLE personnes ADD COLUMN natprest CHAR(3);
ALTER TABLE personnes ADD COLUMN topchapers BOOLEAN;
ALTER TABLE personnes ADD COLUMN toppersdrodevorsa BOOLEAN;
ALTER TABLE personnes ADD COLUMN idassedic VARCHAR(8);

ALTER TABLE typesorients ADD COLUMN modele_notif VARCHAR(20);

ALTER TABLE orientsstructs ADD COLUMN personne_id INTEGER NOT NULL REFERENCES personnes(id);
ALTER TABLE orientsstructs ALTER COLUMN propo_algo INTEGER  REFERENCES typesorients(id);
ALTER TABLE orientsstructs DROP COLUMN propo_cg;
