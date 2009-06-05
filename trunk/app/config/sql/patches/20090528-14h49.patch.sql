ALTER TABLE contratsinsertion ALTER COLUMN expr_prof TYPE TEXT;

ALTER TABLE contratsinsertion ALTER COLUMN diplomes TYPE TEXT;

ALTER TABLE contratsinsertion ALTER COLUMN objectifs_fixes TYPE TEXT;

ALTER TABLE contratsinsertion ALTER COLUMN engag_object TYPE TEXT;

ALTER TABLE contratsinsertion ALTER COLUMN nature_projet TYPE TEXT;

ALTER TABLE contratsinsertion ALTER COLUMN observ_ci TYPE TEXT;

ALTER TABLE contratsinsertion DROP COLUMN type_ci;

-- ALTER TABLE contratsinsertion DROP COLUMN type_ci;
ALTER TABLE orientsstructs ALTER COLUMN typeorient_id DROP NOT NULL;

ALTER TABLE orientsstructs ALTER COLUMN structurereferente_id DROP NOT NULL;

ALTER TABLE rattachements ALTER COLUMN typepar TYPE CHAR(3);

ALTER TABLE typesorients ALTER COLUMN modele_notif TYPE VARCHAR(40);