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

--==============================================================================
-- 20131203: suppression de la contrainte pour la thématique des sanctions de
-- l'EP du CG 58
--==============================================================================

ALTER TABLE sanctionseps58 DROP CONSTRAINT sanctionseps58_orientstruct_id_origine_chk;

--------------------------------------------------------------------------------
-- Mise à jour de sanctionseps58.orientstruct_id car il faut une orientation en emploi
--------------------------------------------------------------------------------
UPDATE sanctionseps58
	SET orientstruct_id = (
		SELECT
				orientsstructs.id
			FROM sanctionseps58 AS s
				INNER JOIN dossierseps ON ( dossierseps.id = s.dossierep_id )
				INNER JOIN personnes ON ( dossierseps.personne_id = personnes.id )
				INNER JOIN orientsstructs ON ( orientsstructs.personne_id = personnes.id )
			WHERE
				s.id = sanctionseps58.id
				AND s.orientstruct_id IS NULL
				AND s.origine IN ( 'noninscritpe', 'radiepe' )
				-- la dernière, par-rapport à la date de création du dossier
				AND orientsstructs.id IN (
					SELECT
							o.id
						FROM orientsstructs AS o
						WHERE
							o.personne_id = personnes.id
							AND o.statut_orient = 'Orienté'
							AND o.date_valid <= DATE_TRUNC( 'day', s.created )
						ORDER BY o.date_valid DESC
						LIMIT 1
				)
				AND orientsstructs.structurereferente_id = 2
	)
	WHERE
		sanctionseps58.orientstruct_id IS NULL
		AND sanctionseps58.origine IN ( 'noninscritpe', 'radiepe' );

--------------------------------------------------------------------------------
-- Suppression des dossiers d'EP de la thématique des sanctions du CG 58
-- pour les allocataires non inscrits ou radiés de Pôle Emploi pour lesquels
-- le dossier d'EP n'aurait pas dû être créé.
--------------------------------------------------------------------------------
DELETE FROM dossierseps WHERE id IN (
		SELECT
				d.id
			FROM dossierseps AS d
				INNER JOIN sanctionseps58 AS s ON ( s.dossierep_id = d.id )
				LEFT OUTER JOIN orientsstructs ON ( s.orientstruct_id = orientsstructs.id )
			WHERE
				d.themeep = 'sanctionseps58'
				AND s.origine IN ( 'noninscritpe', 'radiepe' )
				AND (
					s.orientstruct_id IS NULL
					OR orientsstructs.structurereferente_id <> 2
				)
				AND d.id NOT IN (
					SELECT passagescommissionseps.dossierep_id
						FROM passagescommissionseps
							INNER JOIN commissionseps ON ( passagescommissionseps.commissionep_id = commissionseps.id )
						WHERE
							passagescommissionseps.dossierep_id = d.id
							AND commissionseps.etatcommissionep NOT IN ( 'cree', 'quorum', 'associe' )
				)
);

-- *****************************************************************************
COMMIT;
-- *****************************************************************************