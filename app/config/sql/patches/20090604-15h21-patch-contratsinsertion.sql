ALTER TABLE contratsinsertion DROP COLUMN nat_cont_trav;
ALTER TABLE contratsinsertion ADD COLUMN nat_cont_trav CHAR(4);

ALTER TABLE servicesinstructeurs DROP COLUMN lib_service;
ALTER TABLE servicesinstructeurs ADD COLUMN lib_service VARCHAR(100);