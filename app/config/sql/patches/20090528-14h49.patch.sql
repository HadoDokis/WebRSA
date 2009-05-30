ALTER TABLE contratsinsertion DROP COLUMN expr_prof;
ALTER TABLE contratsinsertion ADD COLUMN expr_prof TEXT;

ALTER TABLE contratsinsertion DROP COLUMN diplomes;
ALTER TABLE contratsinsertion ADD COLUMN diplomes TEXT;

ALTER TABLE contratsinsertion DROP COLUMN objectifs_fixes;
ALTER TABLE contratsinsertion ADD COLUMN objectifs_fixes TEXT;

ALTER TABLE contratsinsertion DROP COLUMN engag_object;
ALTER TABLE contratsinsertion ADD COLUMN engag_object TEXT;

ALTER TABLE contratsinsertion DROP COLUMN nature_projet;
ALTER TABLE contratsinsertion ADD COLUMN nature_projet TEXT;

ALTER TABLE contratsinsertion DROP COLUMN observ_ci;
ALTER TABLE contratsinsertion ADD COLUMN observ_ci TEXT;

-- ALTER TABLE contratsinsertion DROP COLUMN type_ci;
ALTER TABLE orientsstructs ALTER COLUMN typeorient_id DROP NOT NULL;
ALTER TABLE orientsstructs ALTER COLUMN structurereferente_id DROP NOT NULL;

ALTER TABLE rattachements ALTER COLUMN typepar TYPE CHAR(3);
ALTER TABLE typesorients DROP COLUMN modele_notif;
ALTER TABLE typesorients ADD COLUMN modele_notif VARCHAR(40);
