ALTER TABLE personnes DROP COLUMN natprest;
ALTER TABLE personnes DROP COLUMN rolepers;
ALTER TABLE personnes DROP COLUMN topchapers;
ALTER TABLE personnes DROP COLUMN toppersdrodevorsa;

ALTER TABLE contratsinsertion ADD COLUMN date_saisie_ci DATE;

CREATE TABLE prestations (
    id      		SERIAL NOT NULL PRIMARY KEY,
    personne_id		INT NOT NULL REFERENCES personnes(id),
    natprest    	CHAR(3),
    rolepers   		CHAR(3),
    topchapers		BOOLEAN,
    toppersdrodevorsa	BOOLEAN
);