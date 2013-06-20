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

DROP TABLE IF EXISTS rupturescuis66 CASCADE;
CREATE TABLE rupturescuis66(
	id			                      SERIAL NOT NULL PRIMARY KEY,
  cui_id		                    INTEGER NOT NULL REFERENCES cuis(id) ON DELETE CASCADE ON UPDATE CASCADE,
  user_id		                    INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  observation		                TEXT NULL,
  daterupturecui                DATE NOT NULL,
  dateenregistrementrupture     DATE NOT NULL,
	created		                    TIMESTAMP WITHOUT TIME ZONE,
  modified	                    TIMESTAMP WITHOUT TIME ZONE
);

COMMENT ON TABLE rupturescuis66 IS 'Saisie de la rupture du CUI (CG66)';

DROP INDEX IF EXISTS rupturescuis66_cui_id_idx;
CREATE INDEX rupturescuis66_cui_id_idx ON rupturescuis66( cui_id );

DROP INDEX IF EXISTS rupturescuis66_user_id_idx;
CREATE INDEX rupturescuis66_user_id_idx ON rupturescuis66( user_id );
-------------------------------------------------------------------------------------
-- 20130619 : Ajout d'une table de paramétrage pour les motifs de rupture CUI
-------------------------------------------------------------------------------------
DROP TABLE IF EXISTS motifssortiecuis66 CASCADE;
DROP TABLE IF EXISTS motifsrupturescuis66 CASCADE;
CREATE TABLE motifsrupturescuis66(
  id			SERIAL NOT NULL PRIMARY KEY,
  name		VARCHAR(250) NOT NULL,
  created		TIMESTAMP WITHOUT TIME ZONE,
  modified	TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE motifsrupturescuis66 IS 'Liste des motifs de rupture pour les CUIs (CG66)';

DROP INDEX IF EXISTS motifsrupturescuis66_name_idx;
CREATE UNIQUE INDEX motifsrupturescuis66_name_idx ON motifsrupturescuis66( name );


-------------------------------------------------------------------------------------
-- 20130619 : Ajout d'une table de liaison entre les ruptures de cuis et les motifs de ruptures
-------------------------------------------------------------------------------------
DROP TABLE IF EXISTS motifsrupturescuis66_rupturescuis66 CASCADE;
CREATE TABLE motifsrupturescuis66_rupturescuis66 (
  id                 					SERIAL NOT NULL PRIMARY KEY,
  motifrupturecui66_id			INTEGER NOT NULL REFERENCES motifsrupturescuis66(id) ON DELETE CASCADE ON UPDATE CASCADE,
  rupturecui66_id					INTEGER NOT NULL REFERENCES rupturescuis66(id) ON DELETE CASCADE ON UPDATE CASCADE,
  created								TIMESTAMP WITHOUT TIME ZONE,
  modified							TIMESTAMP WITHOUT TIME ZONE
);
COMMENT ON TABLE motifsrupturescuis66_rupturescuis66 IS 'Table de liaison entre les motifs de ruptures de cuis et les ruptures de cuis (CG 66)';
DROP INDEX IF EXISTS motifsrupturescuis66_rupturescuis66_motifrupturecui66_id_idx;
CREATE INDEX motifsrupturescuis66_rupturescuis66_motifrupturecui66_id_idx ON motifsrupturescuis66_rupturescuis66(motifrupturecui66_id);

DROP INDEX IF EXISTS motifsrupturescuis66_rupturescuis66_rupturecui66_id_idx;
CREATE INDEX motifsrupturescuis66_rupturescuis66_rupturecui66_id_idx ON motifsrupturescuis66_rupturescuis66(rupturecui66_id);

DROP INDEX IF EXISTS motifsrupturescuis66_rupturescuis66_motifrupturecui66_id_rupturecui66_id_idx;
CREATE UNIQUE INDEX motifsrupturescuis66_rupturescuis66_motifrupturecui66_id_rupturecui66_id_idx ON motifsrupturescuis66_rupturescuis66(motifrupturecui66_id,rupturecui66_id);


-------------------------------------------------------------------------------------
-- 20130620 : Modification de la contrainte (décision) sur les tables cuis et decisionscuis66
-------------------------------------------------------------------------------------
SELECT alter_table_drop_constraint_if_exists( 'public', 'decisionscuis66', 'decisioncui_decisioncui_in_list_chk' );

SELECT alter_table_drop_constraint_if_exists( 'public', 'decisionscuis66', 'decisionscuis66_decisioncui_in_list_chk' );
ALTER TABLE decisionscuis66 ADD CONSTRAINT decisionscuis66_decisioncui_in_list_chk CHECK ( cakephp_validate_in_list( decisioncui, ARRAY['accord','refus','enattente','annule','rupture'] ) );

SELECT alter_table_drop_constraint_if_exists( 'public', 'cuis', 'cuis_decisioncui_in_list_chk' );
ALTER TABLE cuis ADD CONSTRAINT cuis_decisioncui_in_list_chk CHECK ( cakephp_validate_in_list( decisioncui, ARRAY['accord','refus','enattente','annule','rupture'] ) );



ALTER TABLE cuis ALTER COLUMN positioncui66 TYPE VARCHAR(20) USING CAST(positioncui66 AS VARCHAR(20));
SELECT alter_table_drop_constraint_if_exists( 'public', 'cuis', 'cuis_positioncui66_in_list_chk' );
ALTER TABLE cuis ADD CONSTRAINT cuis_positioncui66_in_list_chk CHECK ( cakephp_validate_in_list( positioncui66, ARRAY['attavismne','attaviselu','attavisreferent','attdecision','encours','annule','fincontrat','attrenouv','perime','nonvalide','valid','validnotifie','nonvalidnotifie','rupture'] ) );
ALTER TABLE cuis ALTER COLUMN positioncui66 SET NOT NULL;


-- *****************************************************************************
COMMIT;
-- *****************************************************************************