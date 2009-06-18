/* création de la table de relation orientsstructs_servicesinstructeurs */
CREATE TABLE orientsstructs_servicesinstructeurs (
    orientstruct_id             INT NOT NULL REFERENCES orientsstructs (id),
    serviceinstructeur_id INT NOT NULL REFERENCES servicesinstructeurs (id),
    PRIMARY KEY( orientstruct_id, serviceinstructeur_id )
);

/* mise à jour table referents */
ALTER TABLE contratsinsertion ADD COLUMN date_saisie_ci DATE;
ALTER TABLE contratsinsertion ADD COLUMN lieu_saisie_ci VARCHAR(30);


ALTER TABLE contratsinsertion RENAME COLUMN date_saisie_ci TO date_saisi_ci;
ALTER TABLE contratsinsertion RENAME COLUMN lieu_saisie_ci TO lieu_saisi_ci;

ALTER TABLE users ADD COLUMN numtel VARCHAR(15);

--------------- Ajout du 17 06 09 ------------------
ALTER TABLE contratsinsertion ADD COLUMN emp_trouv BOOLEAN;
ALTER TABLE contratsinsertion ALTER COLUMN actions_prev TYPE CHAR(1); ---FIXME: normalement BOOLEAN mais problème lors du patch
------------------------------------------------------------

/* création de la table regroupementszonesgeo */
--------------- Ajout du 18 06 09 - 10h00 ------------------

CREATE TABLE regroupementszonesgeo (
    id          SERIAL NOT NULL PRIMARY KEY,
    lib_rgpt    VARCHAR(50)
);

CREATE TABLE zonesgeographiques_regroupementszonesgeo (
    zonegeographique_id             	INT NOT NULL REFERENCES zonesgeographiques(id),
    regroupementzonegeo_id 		INT NOT NULL REFERENCES regroupementszonesgeo(id),
    PRIMARY KEY( zonegeographique_id, regroupementzonegeo_id )
);