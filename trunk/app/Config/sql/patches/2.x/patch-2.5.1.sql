SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
BEGIN;
-- *****************************************************************************

-------------------------------------------------------------------------------------
-- 20130619 : Ajout d'un champ isactif pour masquer les organismes n'étant plus actifs
-------------------------------------------------------------------------------------
SELECT add_missing_table_field( 'public', 'orgstransmisdossierspcgs66', 'isactif', 'VARCHAR(1)' );
SELECT alter_table_drop_constraint_if_exists( 'public', 'orgstransmisdossierspcgs66', 'orgstransmisdossierspcgs66_isactif_in_list_chk' );
ALTER TABLE orgstransmisdossierspcgs66 ADD CONSTRAINT orgstransmisdossierspcgs66_isactif_in_list_chk CHECK ( cakephp_validate_in_list( isactif, ARRAY['0','1'] ) );
UPDATE orgstransmisdossierspcgs66 SET isactif = '1' WHERE isactif IS NULL;
ALTER TABLE orgstransmisdossierspcgs66 ALTER COLUMN isactif SET DEFAULT '1'::VARCHAR(1);

-------------------------------------------------------------------------------------
-- 20130619 : Transformation du type enum en check in liste
-------------------------------------------------------------------------------------
ALTER TABLE proposdecisionscers66 ALTER COLUMN nonvalidationparticulier TYPE VARCHAR(9) USING CAST(nonvalidationparticulier AS VARCHAR(9));
SELECT alter_table_drop_constraint_if_exists( 'public', 'proposdecisionscers66', 'proposdecisionscers66_nonvalidationparticulier_in_list_chk' );
ALTER TABLE proposdecisionscers66 ADD CONSTRAINT proposdecisionscers66_nonvalidationparticulier_in_list_chk CHECK ( cakephp_validate_in_list( nonvalidationparticulier, ARRAY['reprise','radiation','etudiant'] ) );

-------------------------------------------------------------------------------------
-- 20130619 : Ajout d'une table rupture (0,1) en lien avec les CUIs
-------------------------------------------------------------------------------------
/*
DROP TABLE IF EXISTS rupturescuis66 CASCADE;
CREATE TABLE rupturescuis66(
	id			SERIAL NOT NULL PRIMARY KEY,
	name		VARCHAR(250) NOT NULL,
  cui_id		INTEGER NOT NULL REFERENCES cuis(id) ON DELETE CASCADE ON UPDATE CASCADE,
  motifrupturecui66_id		INTEGER NOT NULL REFERENCES cuis(id) ON DELETE CASCADE ON UPDATE CASCADE,
	created		TIMESTAMP WITHOUT TIME ZONE,
	modified	TIMESTAMP WITHOUT TIME ZONE
);

COMMENT ON TABLE secteurscuis IS 'Liste des secteurs du CUI paramétrable pour le CUI';

DROP INDEX IF EXISTS secteurscuis_name_idx;
CREATE UNIQUE INDEX secteurscuis_name_idx ON secteurscuis( name );

SELECT alter_table_drop_constraint_if_exists( 'public', 'secteurscuis', 'secteurscuis_isnonmarchand_in_list_chk' );
ALTER TABLE secteurscuis ADD CONSTRAINT secteurscuis_isnonmarchand_in_list_chk CHECK ( cakephp_validate_in_list( isnonmarchand, ARRAY['0','1'] ) );

SELECT add_missing_table_field( 'public', 'cuis', 'secteurcui_id', 'INTEGER' );
SELECT add_missing_constraint ( 'public', 'cuis', 'cuis_secteurcui_id_fkey', 'secteurscuis', 'secteurcui_id', false );
DROP INDEX IF EXISTS cuis_secteurcui_id_idx;
CREATE INDEX cuis_secteurcui_id_idx ON cuis( secteurcui_id );

SELECT add_missing_table_field ( 'public', 'cuis', 'actioncandidat_id', 'INTEGER' );
SELECT add_missing_constraint ( 'public', 'cuis', 'cuis_actioncandidat_id_fkey', 'actionscandidats', 'actioncandidat_id', false );

SELECT add_missing_table_field ( 'public', 'cuis', 'partenaire_id', 'INTEGER' );
SELECT add_missing_constraint ( 'public', 'cuis', 'cuis_partenaire_id_fkey', 'partenaires', 'partenaire_id', false );

SELECT add_missing_table_field ( 'public', 'cuis', 'newemployeur', 'VARCHAR(1)' );
SELECT alter_table_drop_constraint_if_exists( 'public', 'cuis', 'cuis_newemployeur_in_list_chk' );
ALTER TABLE cuis ADD CONSTRAINT cuis_newemployeur_in_list_chk CHECK ( cakephp_validate_in_list( newemployeur, ARRAY['0','1'] ) );

*/
-- *****************************************************************************
COMMIT;
-- *****************************************************************************