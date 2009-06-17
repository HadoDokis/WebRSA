
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
------------------------------------------------------------
