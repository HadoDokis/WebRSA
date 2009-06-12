ALTER TABLE servicesinstructeurs ADD COLUMN type_voie VARCHAR(6);

/* mise à jour table referents */
ALTER TABLE referents ADD COLUMN qual VARCHAR(3);

/* création de la table de relation orientsstructs_servicesinstructeurs */
CREATE TABLE orientsstructs_servicesinstructeurs (
    orientstruct_id             INT NOT NULL REFERENCES orientsstructs (id),
    serviceinstructeur_id INT NOT NULL REFERENCES servicesinstructeurs (id),
    PRIMARY KEY( orientstruct_id, serviceinstructeur_id )
);

/* mise à jour table referents */
ALTER TABLE contratsinsertion ADD COLUMN date_saisie_ci DATE;
ALTER TABLE contratsinsertion ADD COLUMN lieu_saisie_ci VARCHAR(30);