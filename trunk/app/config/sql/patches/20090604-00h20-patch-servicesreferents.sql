ALTER TABLE servicesinstructeurs DROP COLUMN nom_rue;
ALTER TABLE servicesinstructeurs ADD COLUMN nom_rue VARCHAR(100);

ALTER TABLE structuresreferentes DROP COLUMN lib_struc;
ALTER TABLE structuresreferentes ADD COLUMN lib_struc VARCHAR(100);
