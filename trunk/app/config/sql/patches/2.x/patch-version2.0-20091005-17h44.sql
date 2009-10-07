--------------- Ajout du 05/10/2009 à 17h44 ------------------
ALTER TABLE contratsinsertion ADD COLUMN avis_ci CHAR(1);
ALTER TABLE contratsinsertion ADD COLUMN raison_ci CHAR(1);
ALTER TABLE contratsinsertion ADD COLUMN aviseqpluri CHAR(1);

--------------- Ajout du 06/10/2009 à 15h40 ------------------
ALTER TABLE referents ADD COLUMN fonction TEXT;
ALTER TABLE contratsinsertion ADD COLUMN fonction_ref TEXT;