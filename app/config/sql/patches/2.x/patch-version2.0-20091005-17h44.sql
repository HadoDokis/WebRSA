--------------- Ajout du 05/10/2009 à 17h44 ------------------
ALTER TABLE contratsinsertion ADD COLUMN raison_ci CHAR(1);
ALTER TABLE contratsinsertion ADD COLUMN aviseqpluri CHAR(1);

--------------- Ajout du 06/10/2009 à 15h40 ------------------
ALTER TABLE referents ADD COLUMN fonction TEXT;

--------------- Ajout du 07/10/2009 à 11h40 ------------------
ALTER TABLE contratsinsertion ADD COLUMN sitfam_ci TEXT;
ALTER TABLE contratsinsertion ADD COLUMN sitpro_ci TEXT;
ALTER TABLE contratsinsertion ADD COLUMN observ_benef TEXT;

--------------- Ajout du 07/10/2009 à 11h40 ------------------
CREATE TABLE contratsinsertion_referents(
    contratinsertion_id             INT NOT NULL REFERENCES contratsinsertion(id),
    referent_id          INT NOT NULL REFERENCES referents(id),
    PRIMARY KEY( contratinsertion_id, referent_id )
);
ALTER TABLE contratsinsertion_referents ADD COLUMN id SERIAL NOT NULL PRIMARY KEY;

CREATE INDEX contratsinsertion_referents_contratinsertion_id_idx ON contratsinsertion_referents (contratinsertion_id);
CREATE INDEX contratsinsertion_referents_referent_id_idx ON contratsinsertion_referents (referent_id);

ALTER TABLE contratsinsertion ADD COLUMN referent_id INT REFERENCES referents(id);
CREATE INDEX contratsinsertion_referent_id_idx ON contratsinsertion (referent_id);

--------------- Ajout du 12/10/2009 à 08h49 ------------------
ALTER TABLE rendezvous ADD COLUMN heurerdv TIME;