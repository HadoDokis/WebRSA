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
----------------------------------------------------------------------------------------
-- 20131118 : Modification de la table avec ajout d'un champ permettant de 
--      distinguer les organismes auxquels les dossiers seront transmis pour une
--      génération auto d'un dossier PCG (FIXME)
----------------------------------------------------------------------------------------
-- SELECT add_missing_table_field ( 'public', 'orgstransmisdossierspcgs66', 'isinfotransmisdecision', 'VARCHAR(1)' );
-- SELECT alter_table_drop_constraint_if_exists( 'public', 'orgstransmisdossierspcgs66', 'orgstransmisdossierspcgs66_isinfotransmisdecision_in_list_chk' );
-- ALTER TABLE orgstransmisdossierspcgs66 ADD CONSTRAINT orgstransmisdossierspcgs66_isinfotransmisdecision_in_list_chk CHECK ( cakephp_validate_in_list( isinfotransmisdecision, ARRAY['0', '1'] ) );
-- UPDATE orgstransmisdossierspcgs66 SET isinfotransmisdecision = '0' WHERE isinfotransmisdecision IS NULL;

SELECT add_missing_table_field ( 'public', 'polesdossierspcgs66', 'originepdo_id', 'INTEGER' );
SELECT add_missing_constraint ( 'public', 'polesdossierspcgs66', 'polesdossierspcgs66_originepdo_id_fkey', 'originespdos', 'originepdo_id', false );

SELECT add_missing_table_field ( 'public', 'polesdossierspcgs66', 'typepdo_id', 'INTEGER' );
SELECT add_missing_constraint ( 'public', 'polesdossierspcgs66', 'polesdossierspcgs66_typepdo_id_fkey', 'typespdos', 'typepdo_id', false );

SELECT add_missing_table_field ( 'public', 'decisionsdossierspcgs66', 'infotransmise', 'VARCHAR(250)' );


-- Ajout du champ;poledossierpcg66_id dans la table orgs TODO
SELECT add_missing_table_field ( 'public', 'orgstransmisdossierspcgs66', 'poledossierpcg66_id', 'INTEGER' );
SELECT add_missing_constraint ( 'public', 'orgstransmisdossierspcgs66', 'orgstransmisdossierspcgs66_poledossierpcg66_id_fkey', 'orgstransmisdossierspcgs66', 'poledossierpcg66_id', false );

SELECT add_missing_table_field ( 'public', 'dossierspcgs66', 'dossierpcg66pcd_id', 'INTEGER' );
SELECT add_missing_constraint ( 'public', 'dossierspcgs66', 'dossierspcgs66_dossierpcg66pcd_id_fkey', 'dossierspcgs66', 'dossierpcg66pcd_id', false );
CREATE UNIQUE INDEX dossierspcgs66_dossierpcg66pcd_id_idx ON dossierspcgs66( dossierpcg66pcd_id );
-- *****************************************************************************
COMMIT;
-- *****************************************************************************