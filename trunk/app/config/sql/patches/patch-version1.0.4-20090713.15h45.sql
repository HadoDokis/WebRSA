--------------- Ajout du 13/07/2009 à 15h45 ------------------
ALTER TABLE dossierscaf ALTER COLUMN numdemrsaprece TYPE VARCHAR(11);
ALTER TABLE dossierscaf ALTER COLUMN numdemrsaprece SET DEFAULT NULL;

--------------- Ajout du 17/07/2009 à 17h50 ------------------
ALTER TABLE contratsinsertion ALTER COLUMN decision_ci SET DEFAULT 'E';