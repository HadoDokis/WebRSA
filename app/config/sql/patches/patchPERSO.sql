CREATE TABLE typoscontrats (
  id  SERIAL NOT NULL,
  lib_typo VARCHAR(20)
);

ALTER TABLE contratsinsertion ADD COLUMN typocontrat_id INT ;