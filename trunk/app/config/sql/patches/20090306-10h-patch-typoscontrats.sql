CREATE TYPE RANG_TYPECONTRAT AS ENUM( 'premier', 'autre' );
ALTER TABLE typoscontrats ADD COLUMN rang RANG_TYPECONTRAT DEFAULT 'autre';

ALTER TABLE referents DROP COLUMN numero_poste;
ALTER TABLE referents ADD COLUMN numero_poste VARCHAR(14);